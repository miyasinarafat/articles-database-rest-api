<?php

namespace App\Domain\Objects;

use App\Domain\Validator;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

final class ArticleFilterItem extends BaseFilterItem
{
    use Validator;

    private function __construct(
        protected ?array $categories,
        protected ?array $sources,
        private ?Carbon $fromOrderTime,
        private ?Carbon $toOrderTime
    ) {
    }

    /**
     * @param array $filter
     * @return static
     * @throws ValidationException
     */
    public static function fromArray(array $filter): static
    {
        $rules = [
            'categories' => ['array', 'nullable'],
            'sources' => ['array', 'nullable'],
            'fromOrderTime' => ['date', 'nullable'],
            'toOrderTime' => ['date', 'after:fromOrderTime', 'nullable'],
        ];

        $filter = self::validateData($filter, $rules);

        return new static(
            $filter['categories'] ?? null,
            $filter['sources'] ?? null,
            $filter['fromOrderTime'] ?? null,
            $filter['toOrderTime'] ?? null
        );
    }

    /**
     * @return array|null
     */
    public function getCategories(): ?array
    {
        return $this->categories;
    }

    /**
     * @return array|null
     */
    public function getSources(): ?array
    {
        return $this->categories;
    }

    /**
     * @return Carbon|null
     */
    public function getFromOrderTime(): ?Carbon
    {
        return $this->fromOrderTime;
    }

    /**
     * @return Carbon|null
     */
    public function getToOrderTime(): ?Carbon
    {
        return $this->toOrderTime;
    }
}
