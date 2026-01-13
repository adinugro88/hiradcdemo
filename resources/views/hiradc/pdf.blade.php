<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>HIRADC</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        h4 {
            text-align: center;
            margin-top: 0;
            font-weight: normal;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        .left {
            text-align: left;
        }
    </style>
</head>

<body>

    <h2>HIRADC SERTA PENGKAJIAN ASPEK DAN DAMPAK LINGKUNGAN</h2>
    <h4>{{ $projectProcess->project->name }} – Proses: {{ $projectProcess->process }}</h4>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kegiatan Pekerjaan</th>
                <th>Bahaya</th>
                <th>Risiko</th>
                <th>P</th>
                <th>S</th>
                <th>Nilai</th>
                <th>Kategori</th>
                <th>Peraturan</th>
                <th>Pengendalian</th>
                <th>P (setelah)</th>
                <th>S (setelah)</th>
                <th>Nilai</th>
                <th>Kategori</th>
            </tr>
        </thead>

        <tbody>
            {{-- @dd($projectProcess) --}}
            @foreach ($projectProcess->works as $work)
                {{-- @dd($work); --}}
                @php
                    $risksForWork = $projectProcess->risks();
                    // dd($risksForWork);
                @endphp
                @foreach ($risks as $risk)
                    @php
                        $controlRisk = $projectProcess->controlRisks->firstWhere('risk_id', $risk->id);
                        $regulation = $projectProcess->regulations->firstWhere('risk_id', $risk->id);
                    @endphp
                    <tr>
                        {{-- @if ($loop->first)
                            <td rowspan="{{ $risksForWork->count() }}">{{ $loop->parent->iteration }}</td>
                            <td rowspan="{{ $risksForWork->count() }}" class="left">{{ $work->name }}</td>
                        @endif --}}
                        <td>{{ $loop->parent->iteration }}</td>
                        <td class="left">{{ $work->name }}</td>
                        <td class="left">{{ $risk->riskAssessment->hazard->name }}</td>
                        <td class="left">{{ $risk->riskAssessment->description }}</td>
                        <td>{{ $risk->probability }}</td>
                        <td>{{ $risk->severity }}</td>
                        <td>{{ $risk->total_value }}</td>
                        <td>{{ $risk->category }}</td>
                        <td class="left">
                            {{ $risk->riskAssessment->hazard->regulations->first()->title ?? '-' }}
                        </td>
                        <td class="left">
                            {{ $risk->riskAssessment->hazard->controlMeasures->first()->basic_measure ?? '-' }}
                        </td>
                        <td>{{ $controlRisk ? $controlRisk->post_probability : '-' }}</td>
                        <td>{{ $controlRisk ? $controlRisk->post_severity : '-' }}</td>
                        <td>{{ $controlRisk ? $controlRisk->post_risk_value : '-' }}</td>
                        <td>{{ $controlRisk ? $controlRisk->post_risk_category : '-' }}</td>
                    </tr>
                @endforeach
            @endforeach

        </tbody>
    </table>

    <br><br>

    <table style="width:100%; border:none;">
        <tr>
            <td style="border:none; text-align:center;">Dibuat Oleh<br><br><b>HSE Officer</b></td>
            <td style="border:none; text-align:center;">Diperiksa Oleh<br><br><b>Koord HSE</b></td>
            <td style="border:none; text-align:center;">Disetujui Oleh<br><br><b>Project Manager</b></td>
            <td style="border:none; text-align:center;">Diketahui Oleh<br><br><b>Client ACS</b></td>
        </tr>
    </table>

</body>

</html>
