<?php

namespace App\Http\Controllers;

use App\Models\PovertyStandard;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PovertyStandardController extends Controller
{
    public function index()
    {
        $povertyStandards = PovertyStandard::orderBy('name')->get();
        return Inertia::render('poverty-standards/index', [
            'povertyStandards' => $povertyStandards
        ]);
    }

    public function store(Request $request)
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

    public function update(Request $request, PovertyStandard $povertyStandard)
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

    public function destroy(PovertyStandard $povertyStandard)
    {
        $povertyStandard->delete();

        return redirect()->back()->with('success', 'Berhasil menghapus standar kemiskinan');
    }
}
