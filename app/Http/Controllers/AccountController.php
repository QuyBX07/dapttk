<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        // Phân trang với 10 dòng mỗi trang
        $accounts = Account::orderBy('id', 'desc')->paginate(10);
        return view('layout.account.content', compact('accounts'));
    }
}
