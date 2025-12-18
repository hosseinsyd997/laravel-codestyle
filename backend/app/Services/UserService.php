<?php

namespace App\Services;

use App\Models\Country;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

class UserService
{
    protected UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function listUsers(array $filters = [], ?string $sortBy = null)
    {
        return $this->repository->all($filters, $sortBy);
    }

    public function storeUser(array $data)
    {
        return DB::transaction(function () use ($data) {

            $country = Country::where('name', $data['country'])
                ->with('currency')
                ->firstOrFail();

            return $this->repository->create([
                'name'       => $data['name'],
                'email'      => $data['email'],
                'country_id' => $country->id,
            ]);

        });
    }

    public function updateUser(User $user, array $data): User
    {
        if (isset($data['country'])) {
            $country = Country::where('name', $data['country'])->firstOrFail();
            $data['country_id'] = $country->id;
            unset($data['country']);
        }

        return $this->repository->update($user, $data);
    }

    public function deleteUser(User $user): bool
    {
        return $this->repository->delete($user);
    }
}
