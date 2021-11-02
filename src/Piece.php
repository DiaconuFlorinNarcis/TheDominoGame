<?php

namespace Game;

class Piece
{
    private int $id;
    private int $x;
    private int $y;
    private bool $turn;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
        $this->id = $x . $y;
        $this->turn = false;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getX(): int|bool
    {
        return $this->turn ? $this->y : $this->x;
    }

    public function getY(): int|bool
    {
        return $this->turn ? $this->x : $this->y;
    }

    public function getPieceName(): string
    {
        $doubleMark = (($this->isDouble())? "\t ***": '');
        return '| ' . $this->getX() . ' -- ' . $this->getY() . ' |' . $doubleMark;
    }

    public function isDouble(): bool
    {
        return $this->x == $this->y;
    }

    public function turn(): void
    {
        $this->turn = true;
    }
}