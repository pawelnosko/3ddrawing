<?php

declare(strict_types=1);

namespace Drawing3D\Building;

/**
 * Prosty domek — ściany, dach dwuspadowy, drzwi i okna.
 */
final class HouseDrawer extends BuildingDrawer
{
    public function draw(
        float $x = 0,
        float $y = 0,
        float $z = 0,
        float $width = 500,
        float $depth = 400,
        float $wallHeight = 280,
        float $ridgeHeight = 100,
        ?array $door = ['x' => 200, 'z' => 0, 'width' => 100, 'height' => 200],
        array $windows = [
            ['x' => 80, 'z' => 120, 'width' => 80, 'height' => 80],
            ['x' => 340, 'z' => 120, 'width' => 80, 'height' => 80],
        ],
    ): void {
        $this->drawing->box3D($x, $y, $z, $width, $depth, $wallHeight);
        $this->drawDualSlopeRoof($x, $y, $z, $width, $depth, $wallHeight, $ridgeHeight);

        if ($door !== null) {
            $this->drawOpeningFront(
                $x + $door['x'],
                $y,
                $z + $door['z'],
                $door['width'],
                $door['height'],
                'Drzwi',
            );
        }

        foreach ($windows as $i => $win) {
            $this->drawOpeningFront(
                $x + $win['x'],
                $y,
                $z + $win['z'],
                $win['width'],
                $win['height'],
                'Okno',
            );
        }

        $this->drawing->text3D([$x + $width / 2, $y - 30, $z + $wallHeight / 2], 'Domek', 14);
    }
}
