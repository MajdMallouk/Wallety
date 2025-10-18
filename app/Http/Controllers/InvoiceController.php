<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function index()
    {
        return Invoice::all();
    }

    public function create()
    {
        $user = auth()->user();
        return view('invoices.create', compact('user'));
    }

    public function store(InvoiceRequest $request)
    {
        return Invoice::create($request->validated());
    }

    public function show(Invoice $invoice)
    {
        return $invoice;
    }

    public function update(InvoiceRequest $request, Invoice $invoice)
    {
        $invoice->update($request->validated());

        return $invoice;
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return response()->json();
    }
}
