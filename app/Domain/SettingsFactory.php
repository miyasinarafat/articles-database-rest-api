<?php

namespace App\Domain;

use Illuminate\Validation\ValidationException;

final class SettingsFactory
{
    use Validator;

    /**
     * @throws ValidationException
     */
    public static function fromArray(array $data): Settings
    {
        $rules = [
            'user_id' => 'required|integer|exists:App\Models\User,id',
            'sources' => 'nullable|array',
            'categories' => 'nullable|array',
            'authors' => 'nullable|array',
        ];

        $validData = self::validateData($data, $rules);

        return (new Settings())->fill([
            'user_id' => $validData['user_id'],
            'sources' => $validData['sources'] ?? [],
            'categories' => $validData['categories'] ?? [],
            'authors' => $validData['authors'] ?? [],
        ]);
    }
}
