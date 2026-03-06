<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function orderReport(Order $order)
    {
        $order->load(['patient', 'items.test', 'items.result.enteredBy', 'items.result.verifiedBy']);
        $lab = auth()->user()->lab;

        $pdf = Pdf::loadView('reports.order', compact('order', 'lab'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("Report-{$order->order_number}.pdf");
    }
}
