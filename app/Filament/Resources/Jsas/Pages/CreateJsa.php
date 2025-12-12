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
        $work = Work::findOrFail($data['work_id']);

        $jsa = Jsa::create([
            'project_name' => $data['project_name'], // ✅ WAJIB
            'project_id'   => $data['project_id'],
            'job_id'       => $data['work_id'],       // atau work_id sesuai tabel
            'job_name'     => $work->name,
            'created_date' => $data['created_date'],
        ]);

        $hazards = $data['selected_hiradc_items'] ?? [];

        foreach ($hazards as $i => $hazardId) {
            $haz = DB::table('hazards as h')
                ->leftJoin('risk_assessments as ra', 'h.id', '=', 'ra.hazard_id')
                ->leftJoin('control_measures as cm', 'h.id', '=', 'cm.hazard_id')
                ->where('h.id', $hazardId)
                ->select(
                    'h.name as hazard',
                    'ra.description as risk',
                    'cm.basic_measure as control'
                )
                ->first();

            JsaStep::create([
                'jsa_id'        => $jsa->id,
                'step_number'   => $i + 1,
                'work_sequence' => $haz->hazard,
                'risk_analysis' => $haz->risk ?? '-',
                'risk_control'  => $haz->control ?? '-',
            ]);
        }

        return $jsa;
    }
}
