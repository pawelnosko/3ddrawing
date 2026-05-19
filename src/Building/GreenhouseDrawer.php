<?php

declare(strict_types=1);

namespace Drawing3D\Building;

use Drawing3D\Style\StrokeStyle;

/**
 * Szklarnia — szkielet + nachylone ściany szklane (linie).
 */
final class GreenhouseDrawer extends BuildingDrawer
{
    public function draw(
        float $x = 0,
        float $y = 0,
        float $z = 0,
        float $width = 400,
        float $depth = 250,
        float $wallHeight = 150,
        float $roofRise = 80,
    ): void {
        $zFront = $z + $wallHeight;
        $zBack = $zFront + $roofRise;

        $this->drawSingleSlopeRoof($x, $y, $z, $width, $depth, $wallHeight, $roofRise);

        $segments = 5;
        for ($i = 0; $i <= $segments; $i++) {
            $fx = $x + ($width / $segments) * $i;
            $this->drawing->line3D([$fx, $y, $z], [$fx, $y, $zFront], StrokeStyle::light());
            $this->drawing->line3D([$fx, $y + $depth, $z], [$fx, $y + $depth, $zBack], StrokeStyle::light());
        }

        $this->drawOpeningFront($x + $width / 2 - 40, $y, $z, 80, 140, 'Wejście');
        $this->drawing->text3D([$x + $width / 2, $y - 25, $z + $wallHeight + $roofRise], 'Szklarnia', 12);
    }
}
