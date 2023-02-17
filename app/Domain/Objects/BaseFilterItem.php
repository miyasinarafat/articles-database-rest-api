<?php

namespace App\Domain\Objects;

use Illuminate\Http\Request;

abstract class BaseFilterItem
{
    abstract public static function fromArray(array $filter): static;

    /**
     * @param Request $request
     * @return static|null
     */
    public static function fromRequest(Request $request): ?static
    {
        return (null !== $request->get('filter'))
            ? static::fromArray((array)$request->get('filter'))
            : null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $vars = get_object_vars($this);

        array_walk($vars, static function ($val, $field) use (&$data) {
            $val = is_array($val) ? implode(',', $val) : (string)$val;

            $data[] = $field . ':' . $val;
        });

        return implode(';', $data);
    }
}
