<?php

namespace Game;

class Player
{
    const USERS = ["One", "Two", "Three", "Four"];

    private int $id;

    private string $userName;

    /** @var Piece[] */
    private array $pieces = [];

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->userName = self::USERS[$id];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserName(): string
    {
        return 'Player ' . $this->userName;
    }

    public function getPieces(): array
    {
        return $this->pieces;
    }

    public function setPieces(array $pieces): void
    {
        foreach ($pieces as $piece) {
            $this->pieces[$piece->getId()] = $piece;
        }
    }

    public function drawPiece(Piece $piece): void
    {
        $this->pieces[$piece->getId()] = $piece;
    }

    public function playFirstPiece(): Piece
    {
        $piece = reset($this->pieces);

        unset($this->pieces[$piece->getId()]);

        return $piece;
    }

    public function playPiece(Piece $piece): void
    {
        unset($this->pieces[$piece->getId()]);
    }

    public function getBiggerDouble(): Piece|array|int
    {
        if (empty($this->pieces)) {
            return 0;
        }

        $doubles = array_filter($this->pieces,
            fn($piece) => $piece->isDouble()
        );

        if (empty($doubles)) {
            return [];
        }

        $maxDouble = reset($doubles);
        $max = $maxDouble->getX();

        foreach ($doubles as $doublePiece) {
            if ($doublePiece->getX() > $max) {
                $maxDouble = $doublePiece;
                $max = $doublePiece->getX();
            }
        }

        return $maxDouble;
    }

    public function getTotalDots(): int
    {
        $totalDots = 0;

        foreach ($this->pieces as $piece) {
            $totalDots += $piece->getX() + $piece->getY();
        }

        return $totalDots;
    }
}
