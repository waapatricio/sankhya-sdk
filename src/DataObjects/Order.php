<?php

namespace Sankhya\DataObjects;

class Order
{
    public function __construct(
        public string $NUNOTA,
        public int $CODPARC,
    )
    {
    }
}
