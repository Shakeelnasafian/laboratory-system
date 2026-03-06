<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report — {{ $order->order_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; padding: 20px; }

        .header { text-align: center; border-bottom: 2px solid #1e40af; padding-bottom: 12px; margin-bottom: 15px; }
        .header h1 { font-size: 20px; color: #1e40af; margin-bottom: 2px; }
        .header p { font-size: 10px; color: #666; }

        .patient-info { display: table; width: 100%; margin-bottom: 15px; border: 1px solid #ddd; }
        .patient-row { display: table-row; }
        .patient-cell { display: table-cell; padding: 5px 10px; border-bottom: 1px solid #eee; }
        .patient-label { font-weight: bold; width: 25%; color: #555; font-size: 10px; }
        .patient-value { width: 25%; }

        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .info-table td { padding: 4px 8px; font-size: 10px; }
        .info-table .label { font-weight: bold; color: #555; width: 15%; }
        .info-table .value { width: 35%; }

        .results-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .results-table th { background: #1e40af; color: white; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; }
        .results-table td { padding: 7px 10px; border-bottom: 1px solid #ddd; }
        .results-table tr:nth-child(even) { background: #f8f9fa; }
        .abnormal { color: #dc2626; font-weight: bold; }
        .flag-high { color: #dc2626; }
        .flag-low { color: #2563eb; }
        .flag-critical { color: #dc2626; font-weight: bold; text-transform: uppercase; }

        .footer { position: fixed; bottom: 20px; left: 20px; right: 20px; text-align: center; border-top: 1px solid #ddd; padding-top: 8px; font-size: 9px; color: #888; }

        .order-meta { background: #f0f4ff; padding: 8px 12px; border-radius: 4px; margin-bottom: 15px; font-size: 10px; }
        .urgent-badge { background: #dc2626; color: white; padding: 2px 8px; border-radius: 3px; font-weight: bold; font-size: 9px; }
        .verified-badge { color: #16a34a; font-size: 9px; }

        .signatures { display: table; width: 100%; margin-top: 40px; }
        .sig-col { display: table-cell; width: 33%; text-align: center; padding-top: 30px; }
        .sig-line { border-top: 1px solid #333; display: inline-block; width: 120px; }
        .sig-label { font-size: 9px; color: #666; margin-top: 3px; }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <h1>{{ $lab->name ?? 'Laboratory' }}</h1>
        @if($lab->header_text) <p>{{ $lab->header_text }}</p> @endif
        <p>{{ $lab->address ?? '' }}{{ $lab->city ? ', '.$lab->city : '' }}{{ $lab->phone ? ' | Ph: '.$lab->phone : '' }}</p>
        @if($lab->license_number) <p>License: {{ $lab->license_number }}</p> @endif
    </div>

    {{-- Order Info --}}
    <div class="order-meta">
        <strong>Report #:</strong> {{ $order->order_number }}
        &nbsp;&bull;&nbsp;
        <strong>Date:</strong> {{ $order->created_at->format('d/m/Y h:i A') }}
        @if($order->is_urgent) &nbsp;<span class="urgent-badge">URGENT</span> @endif
    </div>

    {{-- Patient Info --}}
    <table class="info-table">
        <tr>
            <td class="label">Patient:</td>
            <td class="value"><strong>{{ $order->patient->name }}</strong></td>
            <td class="label">Patient ID:</td>
            <td class="value">{{ $order->patient->patient_id }}</td>
        </tr>
        <tr>
            <td class="label">Age / Gender:</td>
            <td class="value">{{ $order->patient->age ?? '—' }} {{ $order->patient->age_unit }} / {{ ucfirst($order->patient->gender) }}</td>
            <td class="label">CNIC:</td>
            <td class="value">{{ $order->patient->cnic ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Phone:</td>
            <td class="value">{{ $order->patient->phone ?? '—' }}</td>
            <td class="label">Referred By:</td>
            <td class="value">{{ $order->referred_by ?? '—' }}</td>
        </tr>
    </table>

    {{-- Results --}}
    <table class="results-table">
        <thead>
            <tr>
                <th style="width: 30%">Test Name</th>
                <th style="width: 20%">Result</th>
                <th style="width: 15%">Unit</th>
                <th style="width: 20%">Normal Range</th>
                <th style="width: 15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->test->name }}</td>
                <td class="{{ $item->result?->is_abnormal ? 'abnormal' : '' }}">
                    {{ $item->result?->value ?? 'Pending' }}
                </td>
                <td>{{ $item->result?->unit ?? $item->test->unit ?? '' }}</td>
                <td>{{ $item->result?->normal_range ?? $item->test->normal_range ?? '—' }}</td>
                <td>
                    @if($item->result)
                        @if($item->result->flag !== 'normal')
                            <span class="flag-{{ $item->result->flag }}">{{ strtoupper($item->result->flag) }}</span>
                        @else
                            Normal
                        @endif
                        @if($item->result->is_verified) <span class="verified-badge">&#10003;</span> @endif
                    @else
                        <span style="color: #999;">Pending</span>
                    @endif
                </td>
            </tr>
            @if($item->result?->remarks)
            <tr>
                <td colspan="5" style="font-size: 9px; color: #666; padding-left: 20px;">Note: {{ $item->result->remarks }}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>

    {{-- Signatures --}}
    <div class="signatures">
        <div class="sig-col">
            <div class="sig-line"></div>
            <div class="sig-label">Lab Technician</div>
        </div>
        <div class="sig-col">
            <div class="sig-line"></div>
            <div class="sig-label">Lab Incharge</div>
        </div>
        <div class="sig-col">
            <div class="sig-line"></div>
            <div class="sig-label">Pathologist</div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        {{ $lab->footer_text ?? 'This is a computer-generated report.' }}
        &bull; Printed: {{ now()->format('d/m/Y h:i A') }}
    </div>

</body>
</html>
