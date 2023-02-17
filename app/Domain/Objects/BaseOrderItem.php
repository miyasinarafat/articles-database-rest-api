<?php

namespace App\Domain\Objects;

use App\Domain\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

abstract class BaseOrderItem
{
    use Validator;

    protected static array $fields = [];

    protected function __construct(
        private string $field,
        private string $direction,
    ) {
    }

    /**
     * @param Request $request
     * @return static|null
     * @throws ValidationException
     */
    public static function fromRequest(Request $request): ?static
    {
        return (null !== $request->get('order'))
            ? static::fromArray((array)$request->get('order'))
            : null;
    }

    /**
     * @param array $order
     * @return static
     * @throws ValidationException
     */
    public static function fromArray(array $order): static
    {
        $rules = [
            'by' => ['string', Rule::in(static::$fields)],
            'dir' => ['string', Rule::in(['asc', 'desc'])],
        ];

        $order = self::validateData($order, $rules);

        return new static(
            $order['by'],
            $order['dir']
        );
    }

    /** @return string */
    public function getField(): string
    {
        return $this->field;
    }

    /** @return string */
    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->field . ':' . $this->direction;
    }
}
