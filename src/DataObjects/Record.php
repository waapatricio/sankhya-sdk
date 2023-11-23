<?php

namespace Sankhya\DataObjects;

use Saloon\Contracts\DataObjects\WithResponse;
use Saloon\Traits\Responses\HasResponse;

class Record implements WithResponse
{
    use HasResponse;

    public function __construct($fields)
    {
        if (is_object($fields))
            $fields = get_object_vars($fields);

        foreach ($fields as $field => $value)
            $this->{$field} = strval($value);
    }

    public function toArray(): array
    {
        return (array) $this;
    }
}
