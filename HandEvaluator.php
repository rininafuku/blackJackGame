<?php

namespace blackJack;

class HandEvaluator
{
    private const CARD_POINT = [
        '2' => 2,
        '3' => 3,
        '4' => 4,
        '5' => 5,
        '6' => 6,
        '7' => 7,
        '8' => 8,
        '9' => 9,
        '10' => 10,
        'J' => 10,
        'Q' => 10,
        'K' => 10,
        'A' => 11,
    ];
    private const ACE = 11;
    private const MIN_POINT = 1;
    private const GAME_OVER_SCORE = 22;

    /**
     * @param Card[] $hand
     */
    public function getScore(array $hand): int
    {
        $scores = [];
        $scores = array_map(fn ($card) => self::CARD_POINT[$card->getNumber()], $hand);

        while (array_sum($scores) >= self::GAME_OVER_SCORE && in_array(self::ACE, $scores)) {
            $scores = $this->convertAceToMinPoint($scores);
        }

        return array_sum($scores);
    }

    /**
     * @param array<int,int> $scores
     * @return array<int,int>
     */
    private function convertAceToMinPoint(array $scores): array
    {
        $scores[array_search(self::ACE, $scores)] = self::MIN_POINT;
        return $scores;
    }

    /**
     * @param object[] $participant
     * @return object[]
     */
    public function getWinner(array $participant): array
    {
        $notGameOverPlayers = array_filter($participant, fn ($person) => $person->getScore() < self::GAME_OVER_SCORE);

        if (empty($notGameOverPlayers)) {
            return [];
        }

        $maxScore = max(array_map(fn ($person) => $person->getScore(), $notGameOverPlayers));

        $winner = [];
        foreach ($notGameOverPlayers as $person) {
            if ($person->getScore() >= $maxScore) {
                $winner[] = $person;
            }
        }

        return $winner;
    }
}
