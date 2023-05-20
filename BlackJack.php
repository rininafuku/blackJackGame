<?php

namespace blackJack;

use Exception;

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
        $this->player = new Player($this->name, $this->handEvaluator);
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
        $this->announceResult($this->participant);
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
            try {
                $this->displacer->letPlayerSelect();
                $input = trim(fgets(STDIN));

                if ($input === 'Y') {
                    $player->drawCard($this->deck);
                    $this->displacer->displayCard($player);
                    $this->displacer->displayScore($player);
                } elseif ($input === 'N') {
                    break;
                } else {
                    throw new Exception("YまたはNを入力してください");
                }

                if ($player->getScore() >= self::GAME_OVER_SCORE) {
                    break;
                }
            } catch (Exception $e) {
                echo "エラー: " . $e->getMessage() . PHP_EOL;
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
            $this->displacer->displayScore($person);

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
    private function announceResult(array $participant): void
    {
        $this->displacer->displayAllParticipantScore($participant);

        $winner = $this->handEvaluator->getWinner($participant);

        if (count($winner) !== 1) {
            $this->displacer->announceTieGame();
            return;
        }

        $this->displacer->announceWinner($winner[0]);
    }
}


$blackJack = new BlackJack('田中');
$blackJack->start();
