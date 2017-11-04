<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function userList() {
        return view('admin.user-list');
    }

    public function currencyList() {
        return view('admin.currency-list');
    }

    public function settingsList() {
        return view('admin.settings-list');
    }
}
