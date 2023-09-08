<?php

namespace App\Http\Controllers;

use App\Dto\UserDto;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Services\AccountService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthenticationController extends Controller
{

    /**
     * @throws \Exception
     */
    public function register(RegisterUserRequest $request, UserService $userService, AccountService $accountService): JsonResponse
    {
        try {
            DB::beginTransaction();
            $userDto = UserDto::fromApiFormRequest($request);
            $user = $userService->createUser($userDto);
            $accountService->createAccount(UserDto::fromModel($user));
            DB::commit();
            return $this->respondSuccess(
                [
                    'user' => $user
                ],
                'Registration successful'
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            throw  $exception;
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return $this->respondError('Supplied credentials are invalid', 401);
        }
        /** @var User $user */
        $user = Auth::user();

        $token = $user->createToken('Auth Token')->plainTextToken;

        return $this->respondSuccess(['token' => $token, 'user' => $user], 'Logged in successfully');
    }

    public function logout(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $user->tokens()->delete();
        return $this->respondSuccess([], 'Successfully logged out');
    }

    public function user(){
        return $this->respondSuccess(['user' => \request()->user()], 'Auth User retrieved');
    }


}
