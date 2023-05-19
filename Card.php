<?php

namespace blackJack;

class Card
{
    public function __construct(private string $suite, private string $number)
    {
    }

    public function getSuit(): string
    {
        return $this->suite;
    }

    public function getNumber(): string
    {
        return $this->number;
    }
}
