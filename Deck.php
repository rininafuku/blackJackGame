<?php

namespace blackJack;

require_once('Card.php');

class Deck
{
    /**
     * @var Card[] $deck
     */
    private array $deck;

    public function __construct()
    {
        foreach (['ハート', 'スペード', 'クローバー', 'ダイヤ'] as $suit) {
            foreach (['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'] as $number) {
                $this->deck[] = new Card($suit, $number);
            }
        }

        shuffle($this->deck);
    }

    /**
     * @return array<int,Card>
     */
    public function drawCard(): array
    {
        $card = array_slice($this->deck, 0, 1);
        array_splice($this->deck, 0, 1);
        return $card;
    }
}
