<?php

declare(strict_types=1);

namespace Drawing3D\Building;

use Drawing3D\Style\StrokeStyle;

/**
 * Wiata / carport — słupy i dach bez pełnych ścian.
 */
final class CarportDrawer extends BuildingDrawer
{
    public function draw(
        float $x = 0,
        float $y = 0,
        float $z = 0,
        float $width = 500,
        float $depth = 350,
        float $postHeight = 220,
        float $roofRise = 30,
    ): void {
        $zFront = $z + $postHeight;
        $zBack = $zFront + $roofRise;
        $yb = $y + $depth;
        $xr = $x + $width;

        // Każdy słupek do wysokości dachu w swoim narożniku (przód niżej, tył wyżej).
        $corners = [
            [[$x, $y, $z], [$x, $y, $zFront]],
            [[$xr, $y, $z], [$xr, $y, $zFront]],
            [[$xr, $yb, $z], [$xr, $yb, $zBack]],
            [[$x, $yb, $z], [$x, $yb, $zBack]],
        ];

        foreach ($corners as [$base, $top]) {
            $this->drawing->line3D($base, $top, StrokeStyle::main());
        }

        $this->drawing->line3D([$x, $y, $zFront], [$xr, $y, $zFront]);
        $this->drawing->line3D([$x, $yb, $zBack], [$xr, $yb, $zBack]);
        $this->drawing->line3D([$x, $y, $zFront], [$x, $yb, $zBack]);
        $this->drawing->line3D([$xr, $y, $zFront], [$xr, $yb, $zBack]);

        $this->drawing->line3D([$x, $y, $z], [$x + $width, $y, $z], StrokeStyle::hidden());
        $this->drawing->line3D([$x, $y + $depth, $z], [$x + $width, $y + $depth, $z], StrokeStyle::hidden());

        $this->drawing->text3D([$x + $width / 2, $y - 30, $z + $postHeight], 'Wiata samochodowa', 13);
    }
}
