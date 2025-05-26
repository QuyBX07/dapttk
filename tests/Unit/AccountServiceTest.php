<?php

namespace Tests\Unit;

use App\Http\Services\AccountService;
use App\Http\Repositories\Interfaces\AccountRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Mockery;

class AccountServiceTest extends TestCase
{
    protected $accountService;
    protected $accountRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accountRepository = Mockery::mock(AccountRepositoryInterface::class);
        $this->accountService = new AccountService($this->accountRepository);
    }

    /** @test */
    public function it_can_create_an_account()
    {
        $data = [
            'name' => 'Nguyen Van A',
            'email' => 'a@example.com',
            'password' => '123456',
            'phone' => '0123456789',
            'role' => 'admin',
        ];

        $this->accountRepository
            ->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($arg) use ($data) {
                return $arg['name'] === $data['name'] &&
                    $arg['email'] === $data['email'] &&
                    $arg['phone'] === $data['phone'] &&
                    $arg['role'] === $data['role'] &&
                    isset($arg['password']);
            }))
            ->andReturn(true);

        $result = $this->accountService->create($data);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_can_update_an_account()
    {
        $id = 'uuid-123';
        $existingAccount = (object)[
            'id' => $id,
            'name' => 'Old Name',
            'email' => 'old@example.com',
            'role' => 'user',
            'phone' => '0123456789',
        ];

        $newData = [
            'name' => 'New Name',
            'email' => 'new@example.com',
            'role' => 'admin',
            'phone' => '0999999999',
            'password' => 'newpass',
        ];

        $this->accountRepository
            ->shouldReceive('find')
            ->with($id)
            ->andReturn($existingAccount);

        $this->accountRepository
            ->shouldReceive('update')
            ->once()
            ->with($id, Mockery::on(function ($arg) use ($newData) {
                return $arg['name'] === $newData['name'] &&
                    $arg['email'] === $newData['email'] &&
                    $arg['role'] === $newData['role'] &&
                    $arg['phone'] === $newData['phone'] &&
                    isset($arg['password']);
            }))
            ->andReturn(true);

        $result = $this->accountService->update($id, $newData);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_returns_false_when_updating_non_existing_account()
    {
        $id = 'non-existent-id';

        $this->accountRepository
            ->shouldReceive('find')
            ->with($id)
            ->andReturn(null);

        $result = $this->accountService->update($id, []);

        $this->assertFalse($result);
    }

    /** @test */
    public function it_can_delete_an_account()
    {
        $id = 'uuid-456';

        $this->accountRepository
            ->shouldReceive('delete')
            ->with($id)
            ->andReturn(true);

        $result = $this->accountService->delete($id);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_can_paginate_accounts()
    {
        $perPage = 10;

        $items = new Collection([
            ['id' => 1, 'name' => 'User 1'],
            ['id' => 2, 'name' => 'User 2'],
        ]);

        $paginator = new LengthAwarePaginator(
            $items,
            $total = 2,
            $perPage,
            $currentPage = 1,
            ['path' => '/fake-url']
        );

        $this->accountRepository
            ->shouldReceive('paginate')
            ->with($perPage)
            ->andReturn($paginator);

        $result = $this->accountService->paginateAccounts($perPage);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertCount(2, $result->items());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
