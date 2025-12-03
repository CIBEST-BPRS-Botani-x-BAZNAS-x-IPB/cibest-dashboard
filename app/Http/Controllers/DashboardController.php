<?php

namespace App\Http\Controllers;

use App\Models\CibestForm;
use App\Models\CibestQuadrant;
use App\Models\PovertyStandard;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Fortify\Features;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics for the welcome page
     */
    public function index()
    {
        // Get total number of respondents
        $respondentCount = CibestForm::count();

        // Get distribution of quadrants with poverty standard names
        $quadrantDistribution = [];

        // Get all poverty standards to create dynamic quadrants
        $povertyStandards = PovertyStandard::all();

        foreach ($povertyStandards as $standard) {
            // Get quadrant distribution for this poverty standard - before
            $quadrantDataBefore = CibestQuadrant::where('poverty_id', $standard->id)
                ->selectRaw('kuadran_sebelum, COUNT(*) as count')
                ->groupBy('kuadran_sebelum')
                ->orderBy('kuadran_sebelum')
                ->get();

            // Get quadrant distribution for this poverty standard - after
            $quadrantDataAfter = CibestQuadrant::where('poverty_id', $standard->id)
                ->selectRaw('kuadran_setelah, COUNT(*) as count')
                ->groupBy('kuadran_setelah')
                ->orderBy('kuadran_setelah')
                ->get();

            // Create distribution array for this standard with all possible quadrants (1-4)
            $distribution = [
                'id' => $standard->id,
                'name' => $standard->name,
                'before' => [
                    1 => 0, // Kuadran 1
                    2 => 0, // Kuadran 2
                    3 => 0, // Kuadran 3
                    4 => 0, // Kuadran 4
                ],
                'after' => [
                    1 => 0, // Kuadran 1
                    2 => 0, // Kuadran 2
                    3 => 0, // Kuadran 3
                    4 => 0, // Kuadran 4
                ]
            ];

            // Fill the distribution with actual data - before
            foreach ($quadrantDataBefore as $qd) {
                // Ensure we only add to existing quadrants (1-4)
                if (isset($distribution['before'][$qd->kuadran_sebelum])) {
                    $distribution['before'][$qd->kuadran_sebelum] = $qd->count;
                }
            }

            // Fill the distribution with actual data - after
            foreach ($quadrantDataAfter as $qd) {
                // Ensure we only add to existing quadrants (1-4)
                if (isset($distribution['after'][$qd->kuadran_setelah])) {
                    $distribution['after'][$qd->kuadran_setelah] = $qd->count;
                }
            }

            $quadrantDistribution[] = $distribution;
        }

        // dd($quadrantDistribution);

        return Inertia::render('welcome', [
            'canRegister' => Features::enabled(Features::registration()),
            'respondentCount' => $respondentCount,
            'quadrantDistribution' => $quadrantDistribution
        ]);
    }
}