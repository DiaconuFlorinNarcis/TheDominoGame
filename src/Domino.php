<?php

namespace Game;

class Domino
{
    const LEFT = 'left';
    const RIGHT = 'right';

    private int $numberOfPlayers;

    /** @var Player[] */
    private array $players;

    /** @var Piece[] */
    private array $pieces;

    private array $board = [];

    private bool $end = false;

    private Player $currentPlayer;

    private string $winner = '';

    public function __construct(int $numberOfPlayers)
    {
        $this->numberOfPlayers = $numberOfPlayers;
    }

    public function createGame(): void
    {
        $this->createPlayers();
        $this->createPieces();
        $this->pickPieces();
        $this->play();
    }

    private function createPlayers()
    {
        for ($id = 0; $id < $this->numberOfPlayers; $id++) {
            $this->players[$id] = new Player($id);
        }
    }

    private function play(): void
    {
        echo "Welcome to Dominos! \n
            The game is ready... \n\n";

        echo "Number of players: " .
            $this->numberOfPlayers . "\n\n";

        echo "Players hand: ";

        foreach ($this->players as $player) {
            echo("\n - " . $player->getUserName() . ": \n");
            foreach ($player->getPieces() as $piece) {
                echo " " . $piece->getPieceName() . " \n";
            }
        }

        echo "\n - Pieces left on the table: \n";

        foreach ($this->pieces as $piece) {
            echo " " . $piece->getPieceName() . " \n";
        }

        $this->firstMove();
        $this->log();
        $this->nextPlayer();

        while (!$this->end) {
            $this->move();
        }

        if (!$this->winner) {
            echo "\n No more pieces left on the table BUT all players still have non matching pieces in hand...";
            echo "\n The winner is the one with the least total dots...";

            $sum = [];
            foreach ($this->players as $id => $player) {
                $sum[$id] = $player->getTotalDots();
            }

            $this->winner = $this->players[(array_keys($sum, max($sum)))[0]]->getUsername();
        }

        echo "\n \nGAME OVER. " . $this->winner . " won the game!\n";
    }

    private function firstMove(): void
    {
        $this->currentPlayer = $this->getFirstPlayer();
        $this->board[] = $this->currentPlayer->playFirstPiece();

        echo "\n - " . $this->currentPlayer->getUserName() . " plays first. \n";
    }

    private function nextPlayer(): void
    {
        if ($id = $this->currentPlayer->getId() < ($this->numberOfPlayers - 1)) {
            $this->currentPlayer = $this->players[$id];
        } else {
            $this->currentPlayer = reset($this->players);
        }
    }

    private function move(): void
    {
        $played = false;

        foreach ($this->currentPlayer->getPieces() as $piece) {
            $result = $this->matches($piece);

            if ($result) {
                if ($result === 'right') {
                    array_push($this->board, $piece);
                } else {
                    array_unshift($this->board, $piece);
                }

                echo "\n - " . $this->currentPlayer->getUserName() . " plays " . $piece->getPieceName() . " at the " . $result . " board. \n";
                $this->currentPlayer->playPiece($piece);
                $played = true;
                $this->log();

                if (empty($this->currentPlayer->getPieces())) {
                    $this->end = true;
                    $this->winner = $this->currentPlayer->getUserName();
                } else {
                    $this->nextPlayer();
                }
                break;
            }
        }

        if (!$played) {
            if (empty($this->pieces)) {
                $this->end = true;
            } else {
                $draw = $this->pieces[0];
                $this->currentPlayer->drawPiece($draw);

                array_splice($this->pieces, 0, 1);
                echo "\n - " . $this->currentPlayer->getUserName() . " draws " . $draw->getPieceName() . "\n";
            }
        }
    }

    public function matches(Piece $piece): string|bool
    {
        $boardLimits = $this->boardLimits();
        $left = reset($boardLimits);
        $right = next($boardLimits);

        if ($piece->getX() === $right) {
            if ($piece->getY() === $right) {
                $piece->turn();
            }
            return self::RIGHT;
        }

        if ($piece->getY() === $left) {
            if ($piece->getX() == $left) {
                $piece->turn();
            }
            return self::LEFT;
        }

        return false;
    }

    public function createPieces()
    {
        for ($x = 0; $x <= 6; $x++) {
            for ($y = $x; $y <= 6; $y++) {
                $piece = new Piece($x, $y);
                $this->pieces[] = $piece;
            }
        }

        shuffle($this->pieces);
    }

    private function pickPieces(): void
    {
        foreach ($this->players as $player) {
            $player->setPieces(
                array_splice($this->pieces, 0, 7)
            );
        }
    }

    private function getFirstPlayer(): Player
    {
        $doubles = (array_map(fn($player) => $player->getBiggerDouble(),
            $this->players
        ));

        $biggerXOfDoubles = max($biggerDoubleValues = array_map(
            fn($piece) => (empty($piece)) ? 0 : $piece->getX(),
            $doubles
        ));

        $playerIndex = array_keys($biggerDoubleValues, $biggerXOfDoubles);

        return $this->players[reset($playerIndex)];
    }

    private function boardLimits(): array
    {
        return [reset($this->board)->getX(), end($this->board)->getY()];
    }

    private function log(): void
    {
        echo " - The board is now:";

        foreach ($this->board as $piece) {
            echo $piece->getPieceName();
        }

        echo "\n";
    }
}
