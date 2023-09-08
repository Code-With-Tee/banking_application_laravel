<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class OnboardingController extends Controller
{

    /**
     * @throws ValidationException
     */
    public function setPin(Request $request, UserService $userService): JsonResponse
    {
        $this->validate($request, [
            'pin' => ['required', 'string', 'min:4', 'max:6']
        ]);
        /** @var User $user */
        $user = Auth::user();
        $userService->setPin($user, $request->input('pin'));
        return $this->respondSuccess([], 'Pin set successfully');
    }

}
