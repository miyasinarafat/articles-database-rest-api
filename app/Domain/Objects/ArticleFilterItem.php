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
        protected ?array $authors,
        private ?Carbon $fromArticleDate,
        private ?Carbon $toArticleDate
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
            'authors' => ['array', 'nullable'],
            'fromArticleDate' => ['date', 'nullable'],
            'toArticleDate' => ['date', 'after:fromArticleDate', 'nullable'],
        ];

        $filter = self::validateData($filter, $rules);

        $fromArticleDate = isset($filter['fromArticleDate'])
            ? Carbon::parse($filter['fromArticleDate'])
            : null;
        $toArticleDate = isset($filter['toArticleDate'])
            ? Carbon::parse($filter['toArticleDate'])
                ->addHours(23)
                ->addMinutes(59)
                ->addSeconds(59)
            : null;

        return new static(
            $filter['categories'] ?? null,
            $filter['sources'] ?? null,
            $filter['authors'] ?? null,
            $fromArticleDate,
            $toArticleDate,
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
        return $this->sources;
    }

    /**
     * @return array|null
     */
    public function getAuthors(): ?array
    {
        return $this->authors;
    }

    /**
     * @return Carbon|null
     */
    public function getFromArticleDate(): ?Carbon
    {
        return $this->fromArticleDate;
    }

    /**
     * @return Carbon|null
     */
    public function getToArticleDate(): ?Carbon
    {
        return $this->toArticleDate;
    }
}
