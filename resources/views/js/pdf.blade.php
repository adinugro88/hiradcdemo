<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Job Safety Analysis - {{ $jsa->project_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 20mm;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid black;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }
        .header {
            font-weight: bold;
            background-color: #f2f2f2;
        }
        .signature {
            text-align: center;
            padding-top: 30px;
        }
        .signature-line {
            border-bottom: 1px solid black;
            width: 150px;
            margin: 0 auto;
        }
        .logo {
            text-align: right;
            padding: 5px;
        }
        .logo img {
            height: 40px;
        }
        .section-title {
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>

<table>
    <tr>
        <td colspan="3" style="font-weight:bold; font-size:14pt;">JOB SAFETY ANALYSIS</td>
        <td rowspan="2" class="logo">
            <!-- Ganti dengan path logo Anda -->
            <img src="{{ public_path('images/tata-logo.png') }}" alt="TATA Logo">
        </td>
    </tr>
    <tr>
        <td>FM/HSE-1/16</td>
        <td>Rev. 0</td>
        <td></td>
    </tr>
    <tr>
        <td>Nama Proyek</td>
        <td colspan="2">: {{ $jsa->project_name ?? '-' }}</td>
        <td>Tanggal Pembuatan : {{ \Carbon\Carbon::parse($jsa->created_date)->format('d/m/Y') }}</td>
    </tr>
    <tr>
        <td>Nama Pekerjaan</td>
        <td colspan="3">: {{ $jsa->job_name ?? '-' }}</td>
    </tr>
    <tr>
        <td rowspan="2">Dibuat Oleh<br><br><div class="signature-line"></div><br>Supervisor</td>
        <td colspan="2" style="text-align:center;">Diperiksa oleh</td>
        <td rowspan="2">Disetujui oleh<br><br><div class="signature-line"></div><br>Project Manager</td>
    </tr>
    <tr>
        <td style="text-align:center;"><div class="signature-line"></div><br>Site Manager</td>
        <td style="text-align:center;"><div class="signature-line"></div><br>Leader HSE Proyek</td>
    </tr>
</table>

<div class="section-title">Detail Langkah Pekerjaan</div>

<table>
    <thead>
        <tr class="header">
            <th>No</th>
            <th>Urutan Pekerjaan</th>
            <th>Analisa Risiko dan Dampak Lingkungan</th>
            <th>Pengendalian Risiko dan Dampak Lingkungan</th>
            <th>PIC</th>
            <th>Target Waktu</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($jsa->steps as $step)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $step->work_sequence }}</td>
                <td>{{ $step->risk_analysis }}</td>
                <td>{{ $step->risk_control }}</td>
                <td>{{ $step->pic ?? '-' }}</td>
                <td>{{ $step->target_date ? \Carbon\Carbon::parse($step->target_date)->format('d/m/Y') : '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>