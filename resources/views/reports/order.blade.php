<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report - {{ $order->order_number }}</title>
    @php($reportTheme = config('ui.theme.report'))
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: {{ $reportTheme['body_text'] }}; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid {{ $reportTheme['primary'] }}; padding-bottom: 12px; margin-bottom: 15px; }
        .header h1 { font-size: 20px; color: {{ $reportTheme['primary'] }}; margin-bottom: 2px; }
        .header p { font-size: 10px; color: {{ $reportTheme['muted'] }}; }
        .info-table, .results-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .info-table td { padding: 4px 8px; font-size: 10px; }
        .info-table .label { font-weight: bold; color: {{ $reportTheme['label'] }}; width: 15%; }
        .info-table .value { width: 35%; }
        .results-table th { background: {{ $reportTheme['primary'] }}; color: white; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; }
        .results-table td { padding: 7px 10px; border-bottom: 1px solid {{ $reportTheme['border'] }}; }
        .results-table tr:nth-child(even) { background: {{ $reportTheme['row_alt'] }}; }
        .abnormal { color: {{ $reportTheme['danger'] }}; font-weight: bold; }
        .flag-high { color: {{ $reportTheme['danger'] }}; }
        .flag-low { color: {{ $reportTheme['info'] }}; }
        .flag-critical { color: {{ $reportTheme['danger'] }}; font-weight: bold; text-transform: uppercase; }
        .footer { position: fixed; bottom: 20px; left: 20px; right: 20px; text-align: center; border-top: 1px solid {{ $reportTheme['border'] }}; padding-top: 8px; font-size: 9px; color: {{ $reportTheme['footer'] }}; }
        .order-meta { background: {{ $reportTheme['meta_surface'] }}; padding: 8px 12px; border-radius: 4px; margin-bottom: 15px; font-size: 10px; }
        .signatures { display: table; width: 100%; margin-top: 40px; }
        .sig-col { display: table-cell; width: 33%; text-align: center; padding-top: 30px; }
        .sig-line { border-top: 1px solid {{ $reportTheme['body_text'] }}; display: inline-block; width: 120px; }
        .sig-label { font-size: 9px; color: {{ $reportTheme['muted'] }}; margin-top: 3px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $lab->name ?? 'Laboratory' }}</h1>
        @if($lab->header_text) <p>{{ $lab->header_text }}</p> @endif
        <p>{{ $lab->address ?? '' }}{{ $lab->city ? ', '.$lab->city : '' }}{{ $lab->phone ? ' | Ph: '.$lab->phone : '' }}</p>
        @if($lab->license_number) <p>License: {{ $lab->license_number }}</p> @endif
    </div>

    <div class="order-meta">
        <strong>Report #:</strong> {{ $order->order_number }}
        &nbsp;|&nbsp;
        <strong>Date:</strong> {{ $order->created_at->format('d/m/Y h:i A') }}
        @if($order->is_urgent) &nbsp;|&nbsp;<strong>URGENT</strong> @endif
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Patient:</td>
            <td class="value"><strong>{{ $order->patient->name }}</strong></td>
            <td class="label">Patient ID:</td>
            <td class="value">{{ $order->patient->patient_id }}</td>
        </tr>
        <tr>
            <td class="label">Age / Gender:</td>
            <td class="value">{{ $order->patient->age ?? 'N/A' }} {{ $order->patient->age_unit }} / {{ ucfirst($order->patient->gender) }}</td>
            <td class="label">CNIC:</td>
            <td class="value">{{ $order->patient->cnic ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Phone:</td>
            <td class="value">{{ $order->patient->phone ?? 'N/A' }}</td>
            <td class="label">Referred By:</td>
            <td class="value">{{ $order->referred_by ?? 'N/A' }}</td>
        </tr>
    </table>

    <table class="results-table">
        <thead>
            <tr>
                <th style="width: 24%">Test Name</th>
                <th style="width: 16%">Accession</th>
                <th style="width: 15%">Result</th>
                <th style="width: 12%">Unit</th>
                <th style="width: 18%">Normal Range</th>
                <th style="width: 15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->test->name }}</td>
                    <td>{{ $item->sample?->accession_number ?? 'N/A' }}</td>
                    <td class="{{ $item->result?->is_abnormal ? 'abnormal' : '' }}">{{ $item->result?->value ?? 'Pending' }}</td>
                    <td>{{ $item->result?->unit ?? $item->test->unit ?? '' }}</td>
                    <td>{{ $item->result?->normal_range ?? $item->test->normal_range ?? 'N/A' }}</td>
                    <td>
                        @if($item->result)
                            {{ strtoupper($item->result->status) }}
                            @if($item->result->flag !== 'normal')
                                <div class="flag-{{ $item->result->flag }}">{{ strtoupper($item->result->flag) }}</div>
                            @endif
                        @else
                            Pending
                        @endif
                    </td>
                </tr>
                @if($item->result?->remarks)
                    <tr>
                        <td colspan="6" style="font-size: 9px; color: {{ $reportTheme['muted'] }}; padding-left: 20px;">Note: {{ $item->result->remarks }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="signatures">
        <div class="sig-col">
            <div class="sig-line"></div>
            <div class="sig-label">Entered By</div>
        </div>
        <div class="sig-col">
            <div class="sig-line"></div>
            <div class="sig-label">Verified By</div>
        </div>
        <div class="sig-col">
            <div class="sig-line"></div>
            <div class="sig-label">Released By</div>
        </div>
    </div>

    <div class="footer">
        {{ $lab->footer_text ?? 'This is a computer-generated report.' }}
        | Printed: {{ now()->format('d/m/Y h:i A') }}
    </div>
</body>
</html>
