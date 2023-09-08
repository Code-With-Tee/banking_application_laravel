<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthenticationController::class, 'login'])->name('login');
    Route::post('register', [AuthenticationController::class, 'register']);
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/logout', [AuthenticationController::class, 'logout']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthenticationController::class, 'user']);
    Route::post('/set/pin', [OnboardingController::class, 'setPin']);
    Route::middleware(['hasSetPin'])->group(function () {
        Route::post('/deposit', [AccountController::class, 'deposit']);
        Route::post('/withdraw', [AccountController::class, 'withdraw']);
        Route::post('/transfer', [AccountController::class, 'transfer']);
        Route::get('/balance/{account}', [AccountController::class, 'balance']);
        Route::get('/transactions', [TransactionController::class, 'index']);

    });
});
Route::get('/download/account/statement', [TransactionController::class, 'download']);

Route::get('test', function () {
    $userDto = new \App\Dto\UserDto();
    $userDto->setEmail("kinik@gmail.com");
    $userDto->setId(1);
    $userDto->setName("kinikan");
    $userDto->setCreatedAt(null);
    $userDto->setUpdatedAt(\Carbon\Carbon::now());
    $userDto->setPhoneNumber("090876672929");
    $userDto->setPassword("osis");


    dd($userDto->make()->removeNulls()->removeKeys(['id', 'created_at', 'updated_at'])->toArray());
});
