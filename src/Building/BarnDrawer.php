<?php

declare(strict_types=1);

namespace Drawing3D\Building;

/**
 * Stodoła / budynek gospodarczy — szeroki, wysoki, drzwi podwójne.
 */
final class BarnDrawer extends BuildingDrawer
{
    public function draw(
        float $x = 0,
        float $y = 0,
        float $z = 0,
        float $width = 800,
        float $depth = 500,
        float $wallHeight = 350,
        float $ridgeHeight = 120,
    ): void {
        $this->drawing->box3D($x, $y, $z, $width, $depth, $wallHeight);
        $this->drawDualSlopeRoof($x, $y, $z, $width, $depth, $wallHeight, $ridgeHeight);

        $doorW = 180;
        $this->drawOpeningFront(
            $x + ($width - $doorW) / 2,
            $y,
            $z,
            $doorW,
            $wallHeight - 20,
            'Brama',
        );

        $this->drawOpeningSideX($x, $y + $depth * 0.3, $z + 100, 60, 80, 'Okno');
        $this->drawing->text3D([$x + $width / 2, $y - 40, $z + $wallHeight], 'Stodoła', 14);
    }
}
