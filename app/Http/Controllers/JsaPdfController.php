<?php

namespace App\Http\Controllers;

use App\Models\Jsa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class JsaPdfController extends Controller
{
    /**
     * Render JSA as PDF (or HTML fallback if PDF library missing).
     */
    public function show(Request $request, Jsa $jsa)
    {
        $data = $this->buildViewData($jsa);

        // Try using barryvdh/laravel-dompdf if available
        if (class_exists('Barryvdh\\DomPDF\\Facade\\Pdf')) {
            try {
                /** @var \Barryvdh\DomPDF\Facade\Pdf $pdf */
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('jsa.pdf', $data)
                    ->setPaper('A4');
                $filename = 'JSA-' . $jsa->id . '.pdf';
                return $pdf->download($filename);
            } catch (\Throwable $e) {
                Log::error('JSA PDF generation failed: ' . $e->getMessage(), ['jsa_id' => $jsa->id]);
            }
        }

        // Fallback: return HTML view with a banner indicating PDF not installed
        return response()->view('jsa.pdf', $data + [
            '_pdf_missing' => true,
        ]);
    }

    private function buildViewData(Jsa $jsa): array
    {
        // Load only existing relations/attributes
        $jsa->loadMissing(['steps']);

        return [
            'jsa' => $jsa,
            'project_name' => $jsa->project_name ?? null,
            'job_name' => $jsa->job_name ?? null,
            'supervisor_id' => $jsa->supervisor_id ?? null,
            'site_manager_id' => $jsa->site_manager_id ?? null,
            'leader_hse_id' => $jsa->leader_hse_id ?? null,
            'project_manager_id' => $jsa->project_manager_id ?? null,
            'supervisor_name' => User::find($jsa->supervisor_id)?->name ?? null,
            'site_manager_name' => User::find($jsa->site_manager_id)?->name ?? null,
            'leader_hse_name' => User::find($jsa->leader_hse_id)?->name ?? null,
            'project_manager_name' => User::find($jsa->project_manager_id)?->name ?? null,
            'steps' => $jsa->steps ?? collect(),
        ];
    }
}
