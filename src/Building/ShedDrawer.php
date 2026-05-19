<?php

declare(strict_types=1);

namespace Drawing3D\Building;

/**
 * Wychodek / mała szopa — wąski, niski, często jednospadowy dach.
 */
final class ShedDrawer extends BuildingDrawer
{
    public function draw(
        float $x = 0,
        float $y = 0,
        float $z = 0,
        float $width = 120,
        float $depth = 120,
        float $wallHeight = 200,
        float $roofRise = 40,
        bool $withDoor = true,
    ): void {
        $this->drawSingleSlopeRoof($x, $y, $z, $width, $depth, $wallHeight, $roofRise);

        if ($withDoor) {
            $doorW = min(70, $width * 0.55);
            $this->drawOpeningFront(
                $x + ($width - $doorW) / 2,
                $y,
                $z,
                $doorW,
                $wallHeight - 10,
                'Drzwi',
            );
        }

        $this->drawing->text3D([$x + $width / 2, $y - 25, $z + $wallHeight], 'Wychodek', 12);
    }
}
