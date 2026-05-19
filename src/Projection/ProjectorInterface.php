<?php

declare(strict_types=1);

namespace Drawing3D\Projection;

/**
 * Kontrakt rzutowania współrzędnych 3D na płaszczyznę 2D.
 */
interface ProjectorInterface
{
    public function project(float $x, float $y, float $z): array;
}
