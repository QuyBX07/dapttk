<?php

namespace App\Http\Controllers;

use App\Http\Services\AccountService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function index(Request $request)
    {
        $accounts = $this->accountService->paginateAccounts();
        return view('layout.account.content', compact('accounts'));
    }

    public function store(Request $request)
    {
        $data = $request->only(['name', 'email', 'password', 'phone', 'role']);

        $result = $this->accountService->create($data);

        if ($result) {
            return redirect()->route('accounts.index')->with('success', 'Tạo tài khoản thành công!');
        } else {
            return redirect()->back()->with('error', 'Lỗi khi tạo tài khoản.');
        }
    }


    public function update(Request $request, $id)
    {

        $this->accountService->update($id, $request->all());
        return redirect()->route('accounts.index')->with('success', 'Cập nhật nhân viên thành công!');
    }

    public function destroy(string $id)
    {
        $result = $this->accountService->delete($id);

        if ($result) {
            return redirect()->route('accounts.index')->with('success', 'Xóa nhân viên thành công!');
        }

        return redirect()->route('accounts.index')->with('error', 'Không tìm thấy nhân viên cần xóa.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $accounts = $this->accountService->search($query);

        return view('layout.account.content', [
            'accounts' => $accounts
        ]);
    }
}
