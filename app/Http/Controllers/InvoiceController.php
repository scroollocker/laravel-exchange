<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{

    public function invoiceList() {
        return view('invoices.invoices-list');
    }

    public function invoiceAdd() {
        return view('invoices.invoices-add');
    }

}
