<?php

namespace App\Jobs;

use App\Enums\FormType;
use App\Imports\CibestImport;
use App\Models\ImportJob;
use App\Services\CibestFormService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CibestImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $userId;
    protected $importJobId;

    public function __construct($filePath, $userId = null)
    {
        $this->filePath = $filePath;
        $this->userId = $userId;
    }

    public function handle(CibestFormService $cibestFormService)
    {
        // Ambil job id yang valid dari queue (UUID)
        $queueJobId = method_exists($this->job, 'getJobId')
            ? $this->job->getJobId()
            : null;

        // Simpan record ImportJob
        $importJob = ImportJob::create([
            'job_id'       => $queueJobId,
            'type'         => FormType::BPRS->value,
            'filename'     => basename($this->filePath),
            'user_id'      => $this->userId,
            'status'       => 'processing',
            'started_at'   => now(),
        ]);

        try {
            // Check if file exists
            if (!Storage::exists($this->filePath)) {
                Log::error('File not found for Cibest import: ' . $this->filePath);
                $importJob->update([
                    'status' => 'failed',
                    'errors' => ['File not found'],
                    'completed_at' => now()
                ]);
                return;
            }

            $tempPath = Storage::path($this->filePath);
            $import = new CibestImport();
            $import->import($tempPath);

            if ($import->failures()->isNotEmpty()) {
                // Handle failures
                $errors = [];
                foreach ($import->failures() as $failure) {
                    $errors[] = [
                        'row' => $failure->row(),
                        'attribute' => $import->mapping($failure->attribute()),
                        'error' => collect($failure->errors())->map(function ($err) {
                            $clean = preg_replace('/^\d+\s*/', '', $err);
                            return ucfirst($clean);
                        })->join(', '),
                        'value' => ($failure->values())[$failure->attribute()]
                    ];
                }

                // Update the import job record with failure status
                $importJob->update([
                    'status' => 'failed',
                    'errors' => $errors,
                    'completed_at' => now()
                ]);

                Log::error('Cibest import failed', [
                    'file_path' => $this->filePath,
                    'errors' => $errors
                ]);
            } else {
                // Process the imported data
                $cibestFormService->processFormData($import->data, FormType::BPRS->value, $this->userId);

                // Update the import job record with success status
                $importJob->update([
                    'status' => 'completed',
                    'processed_rows' => count($import->data),
                    'completed_at' => now()
                ]);

                Log::info('Cibest import completed successfully', [
                    'file_path' => $this->filePath
                ]);
            }

            // Clean up temporary file
            Storage::delete($this->filePath);
        } catch (\Exception $e) {
            Log::error('Error in CibestImportJob: ' . $e->getMessage(), [
                'exception' => $e,
                'file_path' => $this->filePath
            ]);

            // Update the import job record with failure status
            $importJob->update([
                'status' => 'failed',
                'errors' => [$e->getMessage()],
                'completed_at' => now()
            ]);

            // Clean up temporary file even if there's an error
            if (Storage::exists($this->filePath)) {
                Storage::delete($this->filePath);
            }

            throw $e; // Re-throw to trigger failed job handling
        }
    }
}