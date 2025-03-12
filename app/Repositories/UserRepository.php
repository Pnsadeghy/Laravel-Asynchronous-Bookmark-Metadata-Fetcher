<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;
use App\Utils\Repositories\ResourceRepository;

class UserRepository extends ResourceRepository implements IUserRepository
{
    protected string $modelClass = User::class;

    public function findByEmailAndPassword(string $email, string $password): ?User {
        $user = $this->model->where('email', $email)->first();
        if (!$user) return null;
        return password_verify($password, $user->password) ? $user : null;
    }
}
