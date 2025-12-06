<?php

namespace App\Http\Controllers;

use App\Enums\FormType;
use App\Imports\BaznasImport;
use App\Imports\CibestImport;
use App\Models\PovertyStandard;
use App\Services\CibestFormService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CibestFormController extends Controller
{
    public function cibestIndex()
    {
        return Inertia::render('cibest/index');
    }

    public function uploadCibest(Request $request, CibestFormService $cibestFormService)
    {
        $request->validate([
            'file' => [
                'required',
                'mimes:xlsx,xls,csv',
                'max:5120' // maksimal 5MB
            ]
        ]);

        $file = $request->file('file');
        $import = new CibestImport();
        $import->import($file);

        if ($import->failures()->isNotEmpty()) {
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

            return redirect()->back()->with([
                'importError' => $errors,
                'error' => "Gagal menambahkan data dari file {$file->getClientOriginalName()}"
            ]);
        }

        $cibestFormService->processFormData($import->data, FormType::BPRS->value);

        return redirect()->back()->with('success', "Berhasil menambahkan data dari file \"{$file->getClientOriginalName()}\"");
    }

    public function baznasIndex()
    {
        return Inertia::render('baznas/index');
    }

    public function uploadBaznas(Request $request, CibestFormService $cibestFormService)
    {
        $request->validate([
            'file' => [
                'required',
                'mimes:xlsx,xls,csv',
                'max:5120' // maksimal 5MB
            ]
        ]);

        $file = $request->file('file');
        $import = new BaznasImport();
        $import->import($file);

        if ($import->failures()->isNotEmpty()) {
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

            return redirect()->back()->with([
                'importError' => $errors,
                'error' => "Gagal menambahkan data dari file {$file->getClientOriginalName()}"
            ]);
        }

        $cibestFormService->processFormData($import->data, FormType::BAZNAS->value);
        return redirect()->back()->with('success', "Berhasil menambahkan data dari file \"{$file->getClientOriginalName()}\"");
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
