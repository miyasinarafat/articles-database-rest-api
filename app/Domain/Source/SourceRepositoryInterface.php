<?php

namespace App\Domain\Source;

use Illuminate\Database\Eloquent\Collection;

interface SourceRepositoryInterface
{
    /**
     * @return Collection
     */
    public function getList(): Collection;

    /**
     * @param Source $source
     * @return Source|null
     */
    public function create(Source $source): ?Source;

    /**
     * @param int $id
     * @return Source|null
     */
    public function getById(int $id): ?Source;
}
