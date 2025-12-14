@php($missing = $_pdf_missing ?? false)
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Job Safety Analysis</title>
    <style>
        @page {
            size: A4;
            margin: 16mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #111;
        }

        .banner {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
            padding: 8px;
            margin-bottom: 12px;
        }

        .header {
            display: grid;
            grid-template-columns: 140px 1fr 180px;
            gap: 8px;
            align-items: center;
            margin-bottom: 10px;
        }

        .logo {
            height: 60px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
        }

        .meta {
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
            vertical-align: top;
        }

        th {
            background: #f1f1f1;
        }

        .section-title {
            background: #e9ecef;
            font-weight: bold;
            text-transform: uppercase;
        }

        .nowrap {
            white-space: nowrap;
        }

        .small {
            font-size: 11px;
        }
    </style>
    @if ($missing)
        <style>
            .banner {
                position: sticky;
                top: 0
            }
        </style>
    @endif
</head>

<body>
    @if ($missing)
        <div class="banner small">PDF engine not installed. Install barryvdh/laravel-dompdf to download as PDF.</div>
    @endif

    <div class="header">
        <img class="logo" src="{{ public_path('images/company-logo.png') }}" alt="Company Logo">
        <div class="title">FM_HSE-1_16 R0 – Job Safety Analysis</div>
        <div class="meta">
            <div><strong>Date:</strong> {{ optional($jsa)->created_at?->format('d/m/Y') }}</div>
            <div><strong>Project:</strong> {{ $project_name ?? '-' }}</div>
            <div><strong>Job:</strong> {{ $job_name ?? '-' }}</div>
        </div>
    </div>

    <table>
        <tr>
            <th class="section-title" colspan="5">Activity Details</th>
        </tr>
        <tr>
            <td class="nowrap"><strong>Work Title</strong></td>
            <td colspan="4">{{ $job_name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="nowrap"><strong>Supervisor Name</strong></td>
            <td>{{ $supervisor_name ?? '-' }}</td>
            <td class="nowrap"><strong>Site Manager Name</strong></td>
            <td colspan="2">{{ $site_manager_name ?? '-' }}</td>
        </tr>
        <tr>
            <td class="nowrap"><strong>Leader HSE Name</strong></td>
            <td>{{ $leader_hse_name ?? '-' }}</td>
            <td class="nowrap"><strong>Project Manager Name</strong></td>
            <td class="nowrap" colspan="2">{{ $project_manager_name ?? '-' }}</td>
        </tr>
    </table>

    <br>

    <table>
        <tr>
            <th class="section-title" colspan="5">JSA Steps – Hazards – Controls</th>
        </tr>
        <tr>
            <th style="width: 28%">Work Sequence</th>
            <th style="width: 24%">Risk Analysis</th>
            <th style="width: 24%">Risk Control</th>
            <th style="width: 12%">PIC</th>
            <th style="width: 12%">Target Date</th>
        </tr>
        @forelse($steps as $step)
            <tr>
                <td>{{ $step->work_sequence }}</td>
                <td class="small">{!! nl2br(e($step->risk_analysis ?? '')) !!}</td>
                <td class="small">{!! nl2br(e($step->risk_control ?? '')) !!}</td>
                <td class="small">{{ $step->pic ?? '-' }}</td>
                <td class="small">{{ $step->target_date ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="small">No steps defined.</td>
            </tr>
        @endforelse
    </table>

    <br>

    <table>
        <tr>
            <th class="section-title" colspan="3">Approval</th>
        </tr>
        <tr>
            <td>
                <div><strong>Prepared by</strong></div>
                <div class="small">Supervisor Name : {{ $supervisor_name ?? '-' }}</div>
                <div class="small">Sign: ________________________</div>
                <div class="small">Date: {{ now()->format('d/m/Y') }}</div>
            </td>
            <td>
                <div><strong>Reviewed by</strong></div>
                <div class="small">Name: {{ $jsa->reviewer_name ?? '-' }}</div>
                <div class="small">Sign: ________________________</div>
                <div class="small">Date: {{ now()->format('d/m/Y') }}</div>
            </td>
            <td>
                <div><strong>Approved by</strong></div>
                <div class="small">Name: {{ $jsa->approver_name ?? '-' }}</div>
                <div class="small">Sign: ________________________</div>
                <div class="small">Date: {{ now()->format('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

</body>

</html>
