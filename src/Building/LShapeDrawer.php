<?php

declare(strict_types=1);

namespace Drawing3D\Building;

/**
 * Budynek w kształcie litery L — dwa skrzydła.
 */
final class LShapeDrawer extends BuildingDrawer
{
    public function draw(
        float $x = 0,
        float $y = 0,
        float $z = 0,
        float $mainWidth = 500,
        float $mainDepth = 300,
        float $wingWidth = 250,
        float $wingDepth = 350,
        float $wallHeight = 260,
        float $roofRise = 60,
    ): void {
        $this->drawSingleSlopeRoof($x, $y, $z, $mainWidth, $mainDepth, $wallHeight, $roofRise);
        $this->drawSingleSlopeRoof($x, $y + $mainDepth, $z, $wingWidth, $wingDepth, $wallHeight, $roofRise * 0.8);

        $this->drawOpeningFront($x + 50, $y, $z, 100, 200, 'Wejście');
        $this->drawing->text3D([$x + $mainWidth / 2, $y - 30, $z + $wallHeight], 'Budynek L', 13);
    }
}
