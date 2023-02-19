<?php

namespace App\Domain;

interface SettingsRepositoryInterface
{
    /**
     * @param Settings $settings
     * @return Settings|null
     */
    public function update(Settings $settings): ?Settings;

    /**
     * @param int $userId
     * @return Settings|null
     */
    public function getByUserId(int $userId): ?Settings;
}
