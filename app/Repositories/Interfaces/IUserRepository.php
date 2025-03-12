<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use App\Utils\Interfaces\IResourceRepository;

interface IUserRepository extends IResourceRepository
{
    public function findByEmailAndPassword(string $email, string $password): ?User;
}
