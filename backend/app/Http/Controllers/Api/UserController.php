<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ListUsersRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\DetailUserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }
    public function index(ListUsersRequest $request)
    {
        $filters = $request->only(['country', 'currency']);
        $sortBy = $request->query('sortBy');

        $users = $this->service->listUsers($filters, $sortBy);

        return DetailUserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->service->storeUser(
            $request->validated()
        );

        return new DetailUserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user = $this->service->updateUser($user, $request->validated());

        return new DetailUserResource($user);
    }

    public function destroy(User $user)
    {
        $this->service->deleteUser($user);

        return response()->json([
            'message' => 'User deleted successfully.'
        ]);
    }
}
