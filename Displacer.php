<?php

namespace blackJack;

class Displacer
{

    public function announceGameStart(): void
    {
        echo 'ブラックジャックを開始します。' . PHP_EOL;
    }

    public function displayCard(object $person): void
    {
        // TODO:ElseExpressionで検知されるためリファクタリングできないか確認
        if ($person instanceof Cpu && count($person->getHand()) === 2) {
            $this->hideCpuSecondCard($person);
        } else {
            echo $person->getName() . 'の引いたカードは' . $person->getCard()->getSuit() . 'の' . $person->getCard()->getNumber() . 'です。' . PHP_EOL;
        }
    }

    public function hideCpuSecondCard(object $person): void
    {
        echo $person->getName() . 'の引いた2枚目のカードは分かりません。' . PHP_EOL;
    }

    public function displayScore(object $person): void
    {
        echo $person->getName() . 'の現在の得点は' . $person->getScore() . 'です。' . PHP_EOL;
    }

    public function letPlayerSelect(): void
    {
        echo 'カードを引きますか？（Y/N）' . PHP_EOL;
    }

    public function displayCpuSecondCard(object $person): void
    {
        echo $person->getName() .
            'の引いた2枚目のカードは' . $person->getCard()->getSuit() . 'の' . $person->getCard()->getNumber() . 'でした。' . PHP_EOL;
    }

    /**
     * @param object[] $participant
     */
    public function displayAllParticipantScore(array $participant): void
    {
        foreach ($participant as $person) {
            echo $person->getName() . 'の得点は' . $person->getScore() . 'です。' . PHP_EOL;
        }
    }

    public function announceTieGame(): void
    {
        echo 'ドローです。' . PHP_EOL;
    }

    public function announceWinner(object $winner): void
    {
        echo '勝者は' . $winner->getName() . 'です。' . PHP_EOL;
    }

    public function announceGameClose(): void
    {
        echo 'ブラックジャックを終了します。' . PHP_EOL;
    }
}
