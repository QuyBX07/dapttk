<?php

namespace App\Http\Repositories\Eloquent;

use App\Http\Repositories\Interfaces\AccountRepositoryInterface;
use App\Models\Account;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AccountRepository implements AccountRepositoryInterface
{
    protected $model;

    public function __construct(Account $model)
    {
        $this->model = $model;
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->orderBy('name')->paginate($perPage);
    }

    public function create(array $data)
    {
        return Account::create($data);
    }
    public function find($id)
    {
        return Account::find($id);
    }

    public function update($id, array $data): bool
    {
        $account = Account::findOrFail($id);
        return $account->update($data); // trả về true/false
    }
    public function delete(string $id): bool
    {
        $account = Account::find($id);

        if (!$account) {
            return false;
        }

        return $account->delete();
    }

    public function search(string $query)
    {
        return Account::where('name', 'like', '%' . $query . '%')
            ->orWhere('email', 'like', '%' . $query . '%')
            ->orWhere('phone', 'like', '%' . $query . '%')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
    }
}
