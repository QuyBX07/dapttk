<?php

namespace App\Http\Services;

use App\Http\Repositories\Interfaces\AccountRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountService
{
    protected $accountRepository;

    public function __construct(AccountRepositoryInterface $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function paginateAccounts($perPage = 10)
    {
        return $this->accountRepository->paginate($perPage);
    }

    public function create(array $data)
    {
        $createData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'phone' => $data['phone'] ?? null,  // <-- đảm bảo có phone
            'role' => $data['role'],
        ];

        return $this->accountRepository->create($createData);
    }


    public function update($id, array $data): bool
    {
        $account = $this->accountRepository->find($id);

        if (!$account) {
            return false;
        }

        $updateData = [
            'name' => $data['name'] ?? $account->name,
            'email' => $data['email'] ?? $account->email,
            'role' => $data['role'] ?? $account->role,
            'phone' => $data['phone'] ?? $account->phone, // <-- ĐẢM BẢO CÓ DÒNG NÀY
        ];

        // Nếu có password mới thì mã hóa và cập nhật
        if (!empty($data['password'])) {
            $updateData['password'] = bcrypt($data['password']);
        }

        return $this->accountRepository->update($id, $updateData);
    }

    public function delete(string $id): bool
    {
        return $this->accountRepository->delete($id);
    }
}
