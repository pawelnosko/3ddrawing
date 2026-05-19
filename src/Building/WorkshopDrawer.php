<?php

declare(strict_types=1);

namespace Drawing3D\Building;

/**
 * Warsztat — garaż z wieloma oknami i drzwiami bocznymi.
 */
final class WorkshopDrawer extends BuildingDrawer
{
    public function draw(
        float $x = 0,
        float $y = 0,
        float $z = 0,
        float $width = 700,
        float $depth = 450,
        float $wallHeight = 280,
        float $roofRise = 70,
    ): void {
        $this->drawSingleSlopeRoof($x, $y, $z, $width, $depth, $wallHeight, $roofRise);

        $this->drawOpeningFront($x + 80, $y, $z, 140, 210, 'Drzwi');
        $this->drawOpeningFront($x + 280, $y, $z, 300, 220, 'Brama');

        foreach ([120.0, 280.0, 440.0] as $wx) {
            $this->drawOpeningFront($x + $wx, $y, $z + 140, 70, 60, 'Okno');
        }

        $this->drawOpeningSideX($x + $width, $y + 150, $z + 80, 90, 120, 'Drzwi boczne');
        $this->drawing->text3D([$x + $width / 2, $y - 35, $z + $wallHeight], 'Warsztat', 14);
    }
}
