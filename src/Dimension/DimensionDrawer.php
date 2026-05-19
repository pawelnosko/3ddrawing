<?php

declare(strict_types=1);

namespace Drawing3D\Dimension;

use Drawing3D\Drawing3D;

/**
 * Wymiary na elewacji 2D (widok frontowy: oś X w prawo, Z w górę).
 */
final class DimensionDrawer
{
    public function __construct(
        private Drawing3D $drawing,
    ) {
    }

    /**
     * Wymiar poziomy na elewacji (y stałe w modelu 3D).
     */
    public function horizontal(
        float $x1,
        float $x2,
        float $y,
        float $z,
        ?string $label = null,
        float $offsetZ = -40.0,
    ): void {
        $this->drawing->arrowDimension(
            [$x1, $y, $z],
            [$x2, $y, $z],
            $label ?? sprintf('%.0f', abs($x2 - $x1)),
            abs($offsetZ),
            true,
            [0, 0, $offsetZ >= 0 ? 1 : -1],
        );
    }

    /**
     * Wymiar pionowy na elewacji.
     */
    public function vertical(
        float $x,
        float $y,
        float $z1,
        float $z2,
        ?string $label = null,
        float $offsetX = -40.0,
    ): void {
        $this->drawing->arrowDimension(
            [$x, $y, $z1],
            [$x, $y, $z2],
            $label ?? sprintf('%.0f', abs($z2 - $z1)),
            abs($offsetX),
            true,
            [$offsetX >= 0 ? 1 : -1, 0, 0],
        );
    }

    public function labelAt(float $x, float $y, float $z, string $text): void
    {
        $this->drawing->text3D([$x, $y, $z], $text, 13, '#111827');
    }
}
