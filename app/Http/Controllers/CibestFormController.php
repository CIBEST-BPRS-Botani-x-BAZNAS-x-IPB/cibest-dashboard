<?php

namespace App\Http\Controllers;

use App\Jobs\BaznasImportJob;
use App\Jobs\CibestImportJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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

        // Store the file temporarily in the api service
        $api_url = env('API_URL');
        $response = Http::attach(
            'file',
            file_get_contents($file->getRealPath()),
            $file->getClientOriginalName()
        )->post($api_url . '/upload');

        if ($response->failed()) {
            return redirect()->back()->with('error', "File {$file->getClientOriginalName()} gagal diupload. {$response['error']}.");
        }

        // Dispatch the import job to run in the background
        CibestImportJob::dispatch($response['file']['id'], $response['file']['originalName'], Auth::user()->id)->withoutDelay();
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

        // Store the file temporarily in the api service
        $api_url = env('API_URL');
        $response = Http::attach(
            'file',
            file_get_contents($file->getRealPath()),
            $file->getClientOriginalName()
        )->post($api_url . '/upload');

        if ($response->failed()) {
            return redirect()->back()->with('error', "File {$file->getClientOriginalName()} gagal diupload. {$response['error']}.");
        }

        // Dispatch the import job to run in the background
        BaznasImportJob::dispatch($response['file']['id'], $response['file']['originalName'], Auth::user()->id)->withoutDelay();
        return redirect()->back()->with('success', "File {$file->getClientOriginalName()} sedang diproses di latar belakang. Silakan periksa kembali nanti untuk hasilnya.");
    }
}
