<?php

namespace App\Http\Controllers;

use App\Imports\CibestImport;
use App\Models\BantuanKonsumtifSection;
use App\Models\BantuanProduktifSection;
use App\Models\BantuanZiswafSection;
use App\Models\CibestForm;
use App\Models\PembiayaanSyariahSection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CibestFormController extends Controller
{
    public function uploadCibest(Request $request)
    {
        // dd($request);
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

        foreach ($import->data as $row) {
            $bantuanZiswaf = null;
            if ($row['bantuan_ziswaf_section']) {
                // Section
                $bantuanKonsumtifSection = BantuanKonsumtifSection::create(
                    Arr::get($row, 'bantuan_ziswaf_section.bantuan_konsumtif_section')
                );
                $bantuanProduktifSection = BantuanProduktifSection::create(
                    Arr::get($row, 'bantuan_ziswaf_section.bantuan_konsumtif_section')
                );

                // Main
                $bantuanZiswaf = BantuanZiswafSection::create([
                    ...Arr::except($row['bantuan_ziswaf_section'], 'lembaga_ziswaf_checkbox', 'program_bantuan_checkbox', 'pembiayaan_lain_checkbox'),
                    'bantuan_konsumtif_section_id' => $bantuanKonsumtifSection->id,
                    'bantuan_produktif_section_id' => $bantuanProduktifSection->id,
                ]);

                // Checkbox
                $bantuanZiswaf->lembagaZiswafCheckboxes()->sync(Arr::get($row, 'bantuan_ziswaf_section.lembaga_ziswaf_checkbox'));
                $bantuanZiswaf->programBantuanCheckboxes()->sync(Arr::get($row, 'bantuan_ziswaf_section.program_bantuan_checkbox'));
                $bantuanZiswaf->pembiayaanLainCheckboxes()->sync(Arr::get($row, 'bantuan_ziswaf_section.pembiayaan_lain_checkbox'));
            }

            $pembiayaanSyariah = null;
            if ($row['pembiayaan_syariah_section']) {
                // Main
                $pembiayaanSyariah = PembiayaanSyariahSection::create([
                    ...Arr::except($row['pembiayaan_syariah_section'], 'akad_pembiayaan_checkbox', 'penggunaan_pembiayaan_checkbox', 'pembiayaan_lain_checkbox'),
                ]);

                // Checkbox
                $pembiayaanSyariah->akadPembiayaanCheckboxes()->sync(Arr::get($row, 'pembiayaan_syariah_section.akad_pembiayaan_checkbox'));
                $pembiayaanSyariah->penggunaanPembiayaanCheckboxes()->sync(Arr::get($row, 'pembiayaan_syariah_section.penggunaan_pembiayaan_checkbox'));
                $pembiayaanSyariah->pembiayaanLainCheckboxes()->sync(Arr::get($row, 'pembiayaan_syariah_section.pembiayaan_lain_checkbox'));
            }

            $cibestForm = CibestForm::create([
                ...Arr::except($row, 'bantuan_ziswaf_section', 'pembiayaan_syariah_section'),
                'bantuan_ziswaf_section_id' => $bantuanZiswaf->id ?? null,
                'user_id' => Auth::user()->id,
            ]);
        }

        return redirect()->back()->with('success', "Berhasil upload file {$file->getClientOriginalName()}");
    }
}
