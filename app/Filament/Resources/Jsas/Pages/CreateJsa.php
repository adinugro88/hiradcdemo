<?php

namespace App\Filament\Resources\Jsas\Pages;

use App\Filament\Resources\Jsas\JsaResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;   // <-- WAJIB, inilah penyebab error barusan!
use App\Models\Jsa;
use App\Models\JsaStep;
use App\Models\Work;
use Illuminate\Support\Facades\DB;

class CreateJsa extends CreateRecord
{
    protected static string $resource = JsaResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Ambil nama pekerjaan
        $work = Work::findOrFail($data['work_id']);

        // 1. Simpan JSA utama
        $jsa = Jsa::create([
            'project_name' => $data['project_name'],
            'project_id'   => $data['project_id'],
            'job_id'       => $data['work_id'],
            'job_name'     => $work->name,
            'created_date' => $data['created_date'],
        ]);

        // 2. Ambil data hazard_details dari form
        $hazards = $data['hazard_details'] ?? [];

        foreach ($hazards as $i => $h) {

            // -------------- AMBIL DATA --------------
            $hazardName = $h['hazard']['name'] ?? '-';

            // Risk Analysis (digabung)
            $riskText = collect($h['risks'] ?? [])
                ->pluck('description')
                ->filter()
                ->implode("; ");

            // Control Measures (digabung)
            $controlText = collect($h['controls'] ?? [])
                ->map(function ($c) {
                    $basic = $c['basic_measure'] ?? '';
                    $adv = $c['advanced_measure'] ?? '';

                    return $adv
                        ? "$basic (Advanced: $adv)"
                        : $basic;
                })
                ->filter()
                ->implode("; ");

            // -------------- SIMPAN STEP --------------
            JsaStep::create([
                'jsa_id'        => $jsa->id,
                'step_number'   => $i + 1,
                'work_sequence' => $hazardName,
                'risk_analysis' => $riskText ?: '-',
                'risk_control'  => $controlText ?: '-',
            ]);
        }

        return $jsa;
    }
}
