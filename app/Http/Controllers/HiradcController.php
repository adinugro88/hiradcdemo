<?php

namespace App\Http\Controllers;

use App\Models\ControlRisk;
use App\Models\ProjectProcess;
use App\Models\Regulation;
use App\Models\Risk;
use Illuminate\Http\Request;

class HiradcController extends Controller
{
    public function pdf($id)
    {
        $projectProcess = ProjectProcess::with(['works', 'risks', 'controlRisks', 'regulations'])->findOrFail($id);
        $risks = Risk::with('riskAssessment')->where('project_process_id', $id)->get();
        $controlRisks = ControlRisk::all();
        $regulations = Regulation::all();

        return view('hiradc.pdf', compact('projectProcess', 'risks', 'controlRisks', 'regulations'));
    }
}
