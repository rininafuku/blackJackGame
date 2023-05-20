<?php

namespace blackJack;

//TODO: tryCatchをする

require_once('Deck.php');
require_once('HandEvaluator.php');
require_once('Player.php');
require_once('Displacer.php');

class BlackJack
{
    private object $deck;
    private object $handEvaluator;
    private object $displacer;

    private object $player;
    private object $dealer;

    private const GAME_OVER_SCORE = 22;

    /**
     * @var object[] $participant
     */
    private array $participant;

    /**
     * @var object[] $cpuPlayers
     */
    private array $cpuPlayers;


    public function __construct(private string $name)
    {
        $this->deck = new Deck();
        $this->handEvaluator = new HandEvaluator();
        $this->player = new Player('あなた', $this->handEvaluator);
        $this->dealer = new Player('ディーラー', $this->handEvaluator);

        $this->participant = [$this->player, $this->dealer];
        $this->cpuPlayers = [$this->dealer];

        $this->displacer = new Displacer($this->name);
    }


    public function start(): void
    {
        $this->displacer->announceGameStart();
        $this->drawTwoCards($this->participant);
        $this->performPlayerAction($this->player);
        $this->performCpuAction($this->cpuPlayers);
        $this->getWinner($this->participant);
        $this->displacer->announceGameClose();
    }

    /**
     * @param object[] $participant
     */
    private function drawTwoCards(array $participant): void
    {
        foreach ($participant as $person) {
            for ($i = 1; $i <= 2; $i++) {
                $person->drawCard($this->deck);
                $this->displacer->displayCard($person);
            }
        } //endforeach

        $this->displacer->displayScore($this->player);
    }

    private function performPlayerAction(object $player): void
    {
        while (true) {
            $this->displacer->letPlayerSelect();
            $input = trim(fgets(STDIN));

            if ($input === 'Y') {
                $player->drawCard($this->deck);
                $this->displacer->displayCard($player);
                $this->displacer->displayScore($player);
            } elseif ($input === 'N') {
                break;
            }

            if ($player->getScore() >= self::GAME_OVER_SCORE) {
                break;
            }
        } //endWhile
    }

    /**
     * @param object[] $cpuPlayers
     */
    private function performCpuAction(array $cpuPlayers): void
    {
        foreach ($cpuPlayers as $person) {

            $this->displacer->displayCpuSecondCard($person);

            while ($person->getScore() <= 17) {
                $person->drawCard($this->deck);
                $this->displacer->displayCard($person);
                $this->displacer->displayScore($person);
            }
        } //endForeach
    }

    /**
     * @param object[] $participant
     */
    private function getWinner(array $participant): void
    {
        $this->displacer->displayAllParticipantScore($participant);

        // 全員のスコアを$scores配列で持つ
        $maxScore = 0;
        $scores = array_map(fn ($person) => $person->getScore(), $participant);
        $maxScore = max($scores);

        // 全員が22以上だった場合のドロー
        $isOverScore = array_filter($scores, fn ($score) => $score >= self::GAME_OVER_SCORE);
        if (!empty($isOverScore)) {
            $this->displacer->announceTieGame();
            return;
        }

        // 複数の勝者がいる場合のドロー
        $isMultipleWinners = array_count_values($scores)[$maxScore] > 1;
        if ($isMultipleWinners) {
            $this->displacer->announceTieGame();
            return;
        }

        //勝者をアナウンス
        foreach ($participant as $person) {
            if ($person->getScore() === $maxScore) {
                $this->displacer->announceWinner($person);
            }
        }
    }
}


$blackJack = new BlackJack('田中');
$blackJack->start();
