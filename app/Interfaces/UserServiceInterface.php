<?php

namespace App\Interfaces;

use App\Dto\UserDto;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

interface UserServiceInterface
{
    public function modelQuery(): Builder;
    public function createUser(UserDto $userDto): User;

    public function setPin(User $user, string $pin): void;

    public function hasSetPin(User $user): bool;

    public function pinIsValid($userId, $pin): bool;

}
