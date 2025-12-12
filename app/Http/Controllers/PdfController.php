<?php

namespace App\Http\Controllers;

use App\Models\Jsa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class JsaPdfController extends Controller
{
    public function generate($id)
    {
        $jsa = Jsa::with('steps')->findOrFail($id);

        $pdf = Pdf::loadView('jsa.pdf', compact('jsa'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'chroot' => public_path(),
            ]);

        return $pdf->download("JSA_{$jsa->project_name}_{$jsa->created_date}.pdf");
    }
}