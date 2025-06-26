<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Team;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function generatePdf($id)
    {
        $invoice = Invoice::findOrFail($id);
        /* $team = app(Team::class);
        dd($team);
        $settings = getSettings([
            'Team Name',
            'Address',
            'E-mail',
        ], $team->id); */
        //dd($invoice);
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        return $pdf->download("invoice_{$id}.pdf");
    }

    public function print($id)
    {
        $invoice = Invoice::findOrFail($id);
        return view('invoices.print', compact('invoice'));
    }

    public function sendByEmail(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'))->output();

        Mail::send('emails.invoice', compact('invoice'), function ($message) use ($request, $invoice, $pdf, $id) {
            $message->to($request->input('email'))
                ->subject("Invoice #{$id}")
                ->attachData($pdf, "invoice_{$id}.pdf");
        });

        return back()->with('success', 'Invoice sent by email.');
    }
}
