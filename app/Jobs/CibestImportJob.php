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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CibestImportJob implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fileName;
    protected $fileId;
    protected $userId;

    public function __construct(string $fileId, string $fileName, int $userId)
    {
        $this->fileId = $fileId;
        $this->fileName = $fileName;
        $this->userId = $userId;
    }

    public function handle(CibestFormService $cibestFormService)
    {
        // Ambil job ID dari queue driver
        $queueJobId = method_exists($this->job, 'getJobId') ? $this->job->getJobId() : null;

        // Simpan import job awal
        $importJob = ImportJob::create([
            'job_id'       => $queueJobId,
            'type'         => FormType::BPRS->value,
            'filename'     => $this->fileName,
            'user_id'      => $this->userId,
            'status'       => 'processing',
            'started_at'   => now(),
        ]);

        try {
            /**
             * STEP 1 — DOWNLOAD FILE DARI API
             */
            $downloadUrl = env('API_URL') . "/files/{$this->fileId}";
            $response = Http::timeout(60)->get($downloadUrl);

            if ($response->failed()) {
                $msg = "Failed downloading file (ID: {$this->fileId})";

                $importJob->update([
                    'status' => 'failed',
                    'errors' => [$msg],
                    'completed_at' => now()
                ]);

                return;
            }

            // Simpan file sementara
            $tempPath = "temp-imports/" . uniqid() . '_' . $this->fileName;
            Storage::put($tempPath, $response->body());

            /**
             * STEP 2 — PROSES IMPORT
             */
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
            } else {
                // Process the imported data
                $cibestFormService->processFormData($import->data, FormType::BPRS->value, $this->userId);

                // Update the import job record with success status
                $importJob->update([
                    'status' => 'completed',
                    'processed_rows' => count($import->data),
                    'completed_at' => now()
                ]);
            }

             // Hapus file sementara
            Storage::delete($tempPath);
            Http::timeout(60)->delete($downloadUrl);
        } catch (\Exception $e) {
            // Update the import job record with failure status
            $importJob->update([
                'status' => 'failed',
                'errors' => [$e->getMessage()],
                'completed_at' => now()
            ]);

            throw $e; // Re-throw to trigger failed job handling
        }
    }
}