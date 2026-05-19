<?php

declare(strict_types=1);

namespace Drawing3D\Math;

/**
 * Punkt lub wektor w przestrzeni 3D (jednostki dowolne: mm, m, px modelu).
 */
final class Vector3
{
    public function __construct(
        public readonly float $x,
        public readonly float $y,
        public readonly float $z,
    ) {
    }

    /**
     * @param array{0: float|int, 1: float|int, 2?: float|int} $coords
     */
    public static function from(array $coords): self
    {
        return new self(
            (float) $coords[0],
            (float) $coords[1],
            (float) ($coords[2] ?? 0),
        );
    }

    public function add(self $other): self
    {
        return new self(
            $this->x + $other->x,
            $this->y + $other->y,
            $this->z + $other->z,
        );
    }

    public function toArray(): array
    {
        return [$this->x, $this->y, $this->z];
    }
}
