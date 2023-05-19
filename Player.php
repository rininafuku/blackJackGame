<?php

namespace blackJack;

require_once('ScoreKeeper.php');

class Player
{
    /**
     * @var Card[] $hand
     */
    private array $hand = [];

    /**
     * @var array<int,Card>
     */
    // NOTE : $cardプロパティを用いてDisplacerクラスのdisplayCardメソッドを実現している
    private array $card = [];
    private object $scoreKeeper;

    public function __construct(private string $name)
    {
        $this->scoreKeeper = new ScoreKeePer();
    }

    public function getName(): string
    {
        return $this->name;
    }


    public function drawCard(Deck $deck): void
    {
        $card = $deck->drawCard();
        $this->addCardToHand($card);
        $this->addScore($card);
    }

    /**
     * @param array<int,Card> $card
     */
    public function addCardToHand(array $card): void
    {
        $this->card = $card;
        $this->hand = array_merge($this->hand, $this->card);
    }

    /**
     * @param array<int,Card> $card
     */
    public function addScore($card): void
    {
        $this->scoreKeeper->addScore($card);
    }

    public function getCard(): Card
    {
        return $this->card[0];
    }

    /**
     * @return Card[]
     */
    public function getHand(): array
    {
        return $this->hand;
    }

    public function getScore(): int
    {
        return $this->scoreKeeper->getScore();
    }
}
