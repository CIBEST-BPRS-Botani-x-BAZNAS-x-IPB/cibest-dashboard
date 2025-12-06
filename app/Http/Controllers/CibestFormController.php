<?php

namespace App\Http\Controllers;

use App\Enums\FormType;
use App\Imports\BaznasImport;
use App\Imports\CibestImport;
use App\Jobs\BaznasImportJob;
use App\Jobs\CibestImportJob;
use App\Models\ImportJob;
use App\Models\PovertyStandard;
use App\Services\CibestFormService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CibestFormController extends Controller
{
    public function cibestIndex()
    {
        return Inertia::render('cibest/index');
    }

    public function uploadCibest(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'mimes:xlsx,xls,csv',
                'max:5120' // maksimal 5MB
            ]
        ]);

        $file = $request->file('file');

        // Store the file temporarily in the storage
        $unique_name = uniqid() . '_' . $file->getClientOriginalName();
        $tempPath = Storage::putFileAs('temp-imports', $file, $unique_name);

        // Dispatch the import job to run in the background
        CibestImportJob::dispatch($tempPath, Auth::user()->id)->withoutDelay();

        return redirect()->back()->with('success', "File {$file->getClientOriginalName()} sedang diproses di latar belakang. Silakan periksa kembali nanti untuk hasilnya.");
    }

    public function baznasIndex()
    {
        return Inertia::render('baznas/index');
    }

    public function uploadBaznas(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'mimes:xlsx,xls,csv',
                'max:5120' // maksimal 5MB
            ]
        ]);

        $file = $request->file('file');

        // Store the file temporarily in the storage
        $unique_name = uniqid() . '_' . $file->getClientOriginalName();
        $tempPath = Storage::putFileAs('temp-imports', $file, $unique_name);

        // Dispatch the import job to run in the background
        BaznasImportJob::dispatch($tempPath, Auth::user()->id)->withoutDelay();

        return redirect()->back()->with('success', "File {$file->getClientOriginalName()} sedang diproses di latar belakang. Silakan periksa kembali nanti untuk hasilnya.");
    }

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

    public function povertyStandardsIndex()
    {
        $povertyStandards = PovertyStandard::orderBy('name')->get();
        return Inertia::render('poverty-standards/index', [
            'povertyStandards' => $povertyStandards
        ]);
    }

    public function povertyStandardsStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nilai_keluarga' => 'required|integer',
            'nilai_per_tahun' => 'required|integer',
            'log_natural' => 'required|numeric',
        ]);

        $povertyStandard = PovertyStandard::create([
            'name' => $request->name,
            'nilai_keluarga' => $request->nilai_keluarga,
            'nilai_per_tahun' => $request->nilai_per_tahun,
            'log_natural' => $request->log_natural,
        ]);

        return redirect()->back()->with('success', 'Berhasil menambahkan standar kemiskinan');
    }

    public function povertyStandardsUpdate(Request $request, PovertyStandard $povertyStandard)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nilai_keluarga' => 'required|integer',
            'nilai_per_tahun' => 'required|integer',
            'log_natural' => 'required|numeric',
        ]);

        $povertyStandard->update([
            'name' => $request->name,
            'nilai_keluarga' => $request->nilai_keluarga,
            'nilai_per_tahun' => $request->nilai_per_tahun,
            'log_natural' => $request->log_natural,
        ]);

        return redirect()->back()->with('success', 'Berhasil mengupdate standar kemiskinan');
    }

    public function povertyStandardsDestroy(PovertyStandard $povertyStandard)
    {
        $povertyStandard->delete();

        return redirect()->back()->with('success', 'Berhasil menghapus standar kemiskinan');
    }
}
