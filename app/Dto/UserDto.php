<?php

namespace App\Dto;

use App\Http\Requests\RegisterUserRequest;
use App\Interfaces\DtoInterface;
use App\Models\User;
use App\Traits\DataManipulatorTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class UserDto implements DtoInterface
{
    use  DataManipulatorTrait;

    private int $id;
    private string $name;

    private string $email;

    private string $phone_number;

    private string $password;

    private string $pin;

    private ?Carbon $created_at;

    private ?Carbon $updated_at;


    /**
     * @param RegisterUserRequest $request
     * @return UserDto
     */
    public static function fromApiFormRequest(FormRequest $request): UserDto
    {
        $userDto = new UserDto();
        $userDto->setName($request->input('name'));
        $userDto->setPhoneNumber($request->input('phone_number'));
        $userDto->setEmail($request->input('email'));
        $userDto->setPassword($request->input('password'));
        return $userDto;
    }

    public static function fromModel(User|Model $model): UserDto
    {
        $userDto = new UserDto();
        $userDto->setId($model->getKey());
        $userDto->setName($model->name);
        $userDto->setPhoneNumber($model->phone_number);
        $userDto->setEmail($model->email);
        return $userDto;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phone_number;
    }

    /**
     * @param string $phone_number
     */
    public function setPhoneNumber(string $phone_number): void
    {
        $this->phone_number = $phone_number;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPin(): string
    {
        return $this->pin;
    }

    /**
     * @param string $pin
     */
    public function setPin(string $pin): void
    {
        $this->pin = $pin;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }

    /**
     * @param Carbon|null $created_at
     * @return void
     */
    public function setCreatedAt(Carbon|null $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return Carbon
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
    }

    /**
     * @param Carbon $updated_at
     * @return void
     */
    public function setUpdatedAt(Carbon $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    public function get(): self
    {
        return $this;
    }

}
