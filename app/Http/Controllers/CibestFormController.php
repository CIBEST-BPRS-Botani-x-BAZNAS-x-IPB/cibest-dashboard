<?php

namespace App\Http\Controllers;

use App\Enums\FormType;
use App\Imports\BaznasImport;
use App\Imports\CibestImport;
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
}
