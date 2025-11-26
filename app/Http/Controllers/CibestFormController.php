<?php

namespace App\Http\Controllers;

use App\Imports\CibestImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CibestFormController extends Controller
{
    public function uploadCibest(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:5120' // maksimal 5MB
            ]
        ]);

        $file = $request->file('file');
        Excel::import(new CibestImport, $file);

        return redirect()->back()->with('success', "Berhasil upload file {$file->getClientOriginalName()}");
    }
}
