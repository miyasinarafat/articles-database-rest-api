<?php

namespace App\Domain;

use Illuminate\Support\Facades\Validator as LaravelValidator;
use Illuminate\Validation\ValidationException;

trait Validator
{
    /**
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @return array
     * @throws ValidationException
     */
    protected static function validateData(array $data, array $rules, array $messages = []): array
    {
        $validator = LaravelValidator::make($data, $rules, $messages);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        return $validator->validated();
    }
}
