<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    public function all(array $filters = [], ?string $sortBy = null): Collection
    {
        $query = User::with(['country.currency']);

        if (!empty($filters['country'])) {
            $query->whereHas('country', function ($q) use ($filters) {
                $q->where('name', $filters['country']);
            });
        }

        if (!empty($filters['currency'])) {
            $query->whereHas('country.currency', function ($q) use ($filters) {
                $q->where('code', $filters['currency']);
            });
        }

        if ($sortBy) {
            $query->orderBy($sortBy);
        }

        return $query->get();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user->refresh()->load('country');
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }
}
