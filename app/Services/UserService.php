<?php

namespace App\Services;

use App\Dto\UserDto;
use App\Exceptions\ANotFoundException;
use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UserService implements UserServiceInterface
{
    public function modelQuery(): Builder
    {
        return User::query();
    }


    public function createUser(UserDto $userDto): User
    {

        $data = $userDto->make()
            ->removeKeys(['id', 'created_at', 'updated_at'])
            ->toArray();
        /** @var User $user */
        $user = $this->modelQuery()->create($data);
        return $user;
    }

    /**
     * @throws ANotFoundException
     */
    public function getUserById(int $userId): Model|Builder
    {
        $user = User::query()->where('id', $userId)->first();
        if (!$user){
            throw new ANotFoundException("User not found");
        }
        return $user;
     }

    public function setPin(User $user, string $pin): void
    {
        if ($this->hasSetPin($user)) {
            throw new BadRequestException("User pin has already been set");
        }
        $user->pin = Hash::make($pin);
        $user->save();
    }

    /**
     * @throws ANotFoundException
     */
    public function pinIsValid($userId, $pin): bool
    {
        /** @var  User $user */
        $user = $this->getUserById($userId);
        if (!$this->hasSetPin($user)) {
            throw new BadRequestException("User pin has not been set");
        }
        return Hash::check($pin, $user->pin);
    }

    public function hasSetPin(User $user): bool
    {
        return $user->pin != null;
    }
}
