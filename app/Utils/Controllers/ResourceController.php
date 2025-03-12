<?php

namespace App\Utils\Controllers;

use App\Http\Controllers\Controller;
use App\Utils\Interfaces\IResourceRepository;

class ResourceController extends Controller
{
    protected ?IResourceRepository $repository;

    public function __construct(IResourceRepository $repository = null)
    {
        $this->repository = $repository;
    }
}
