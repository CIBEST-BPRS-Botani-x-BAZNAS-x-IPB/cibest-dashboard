<?php

namespace App\Http\Controllers;

use App\Models\CibestForm;
use App\Models\CibestQuadrant;
use App\Models\PovertyStandard;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        // Get all poverty standards data
        $povertyStandards = PovertyStandard::orderBy('id')->get();

        // Format the poverty standards data for frontend
        $formattedStandards = $povertyStandards->map(function ($standard) {
            return [
                'id' => $standard->id,
                'name' => $standard->name,
                'index_kesejahteraan_cibest' => $standard->index_kesejahteraan_cibest,
                'besaran_nilai_cibest_model' => $standard->besaran_nilai_cibest_model,
                'nilai_keluarga' => $standard->nilai_keluarga,
                'nilai_per_tahun' => $standard->nilai_per_tahun,
                'log_natural' => $standard->log_natural,
            ];
        });

        // Hardcoded poverty indicators data based on the seed data
        $povertyIndicators = [
            [
                'indicator' => 'Headcount Index (H)',
                'before' => 0.39,
                'after' => 0.33,
                'change' => -0.06
            ],
            [
                'indicator' => 'Income Gap (I)',
                'before' => 0.15,
                'after' => 0.11,
                'change' => -0.04
            ],
            [
                'indicator' => 'Poverty Gap (P1)',
                'before' => 0.08,
                'after' => 0.05,
                'change' => -0.03
            ],
            [
                'indicator' => 'Index Sen (P2)',
                'before' => 0.37,
                'after' => 0.19,
                'change' => -0.18
            ],
            [
                'indicator' => 'Index FGT (P3)',
                'before' => 0.12,
                'after' => 0.05,
                'change' => -0.07
            ]
        ];

        // Get province data with quadrant distribution for ALL poverty standards
        $allProvincesData = [];

        foreach ($povertyStandards as $standard) {
            // Get province data with quadrant distribution for the current poverty standard
            $provincesForStandard = DB::select("
                SELECT
                    p.id,
                    p.value as name,
                    COUNT(cf.id) as total,
                    COALESCE(SUM(CASE WHEN cq.kuadran_setelah = 1 THEN 1 ELSE 0 END), 0) as Q1,
                    COALESCE(SUM(CASE WHEN cq.kuadran_setelah = 2 THEN 1 ELSE 0 END), 0) as Q2,
                    COALESCE(SUM(CASE WHEN cq.kuadran_setelah = 3 THEN 1 ELSE 0 END), 0) as Q3,
                    COALESCE(SUM(CASE WHEN cq.kuadran_setelah = 4 THEN 1 ELSE 0 END), 0) as Q4
                FROM provinces p
                LEFT JOIN cibest_forms cf ON p.id = cf.province_id
                LEFT JOIN cibest_quadrants cq ON cf.id = cq.form_id AND cq.poverty_id = ?
                GROUP BY p.id, p.value
                ORDER BY p.value
            ", [$standard->id]);

            // Convert to array format and determine dominant quadrant
            $provincesForStandard = array_map(function($province) use ($standard) {
                $maxQ = max($province->Q1, $province->Q2, $province->Q3, $province->Q4);
                $dominant = 'Q1';
                if ($province->Q2 == $maxQ) $dominant = 'Q2';
                if ($province->Q3 == $maxQ) $dominant = 'Q3';
                if ($province->Q4 == $maxQ) $dominant = 'Q4';

                return [
                    'id' => $province->id,
                    'name' => $province->name,
                    'Q1' => $province->Q1,
                    'Q2' => $province->Q2,
                    'Q3' => $province->Q3,
                    'Q4' => $province->Q4,
                    'total' => $province->total,
                    'dominant' => $dominant,
                    'poverty_standard_id' => $standard->id
                ];
            }, $provincesForStandard);

            usort($provincesForStandard, fn($a, $b) => $a['id'] <=> $b['id']);

            $allProvincesData[] = $provincesForStandard;
        }        

        return Inertia::render('welcome', [
            'canRegister' => Features::enabled(Features::registration()),
            'respondentCount' => $respondentCount,
            'quadrantDistribution' => $quadrantDistribution,
            'povertyStandards' => $formattedStandards,
            'povertyIndicators' => $povertyIndicators,
            'allProvincesByStandard' => $allProvincesData,
        ]);
    }
}