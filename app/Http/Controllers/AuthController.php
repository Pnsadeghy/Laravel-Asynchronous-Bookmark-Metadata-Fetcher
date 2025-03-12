<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Repositories\Interfaces\IUserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Auth
 *
 * API endpoints for managing auth
 */
class AuthController extends Controller
{
    public function __construct(protected IUserRepository $repository)
    {}

    /**
     * Login
     *
     * @bodyParam email string required
     * @bodyParam password string required minimum character length is 6
     *
     * @responseFile 200 resources/responses/Auth/login.json
     */
    public function login(AuthLoginRequest $request): JsonResponse {

        $user = $this->repository->findByEmailAndPassword(
            $request->string("email"), $request->string("password")
        );

        abort_if($user === null, 401, __("auth.failed"));

        return response()->json(new LoginResource($user));
    }

    /**
     * Register
     *
     * @bodyParam name string required
     * @bodyParam email string required
     * @bodyParam password string required
     * @bodyParam password_confirmation string required
     *
     * @responseFile 201 resources/responses/Auth/register.json
     */
    public function register(AuthRegisterRequest $request): JsonResponse {
        $data = $request->validated();

        $data["password"] = bcrypt($data["password"]);

        $user = $this->repository->store($data);

        return response()->json(new LoginResource($user), 201);
    }

    /**
     * Logout
     *
     * @authenticated
     */
    public function logout(Request $request): JsonResponse {
        $request->user()->currentAccessToken()->delete();

        return response()->json();
    }
}
