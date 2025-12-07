<?php

namespace App\Http\Controllers;

use App\Models\ImportJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ImportJobController extends Controller
{
    public function getImportJobs(Request $request)
    {
        $type = $request->query('type'); // 'cibest' or 'baznas'
        $status = $request->query('status'); // 'pending', 'processing', 'completed', 'failed'

        $query = ImportJob::where('user_id', Auth::user()->id);

        if ($type) {
            $query->where('type', $type);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $importJobs = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($importJobs);
    }

    public function getImportJobDetail(ImportJob $importJob)
    {
        // Verify the import job belongs to the authenticated user
        if ($importJob->user_id !== Auth::user()->id) {
            abort(403, 'Unauthorized');
        }

        return Inertia::render('import-jobs/detail', [
            'importJob' => $importJob,
        ]);
    }

    public function deleteImportJob(ImportJob $importJob)
    {
        // Verify the import job belongs to the authenticated user
        if ($importJob->user_id !== Auth::user()->id) {
            abort(403, 'Unauthorized');
        }

        $filename = $importJob->filename;
        $importJob->delete();

        return redirect()->back()->with('success', "Berhasil menghapus import job untuk file {$filename}");
    }
}
