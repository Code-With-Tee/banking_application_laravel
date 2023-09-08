<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

interface DtoInterface
{
   public static function fromApiFormRequest(FormRequest $request);

    public static function fromModel(Model $model): self;
}
