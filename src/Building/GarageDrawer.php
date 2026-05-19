<?php

declare(strict_types=1);

namespace Drawing3D\Building;

/**
 * Garaż — konfigurowalny spad dachu, drzwi i okna.
 */
final class GarageDrawer extends BuildingDrawer
{
    /**
     * @param array{width: float, depth: float, height: float} $size
     * @param array{left: float, right: float}|array{rise: float}|null $roof
     * @param array{x: float, z: float, width: float, height: float}|null $door
     * @param list<array{x: float, z: float, width: float, height: float}> $windows
     */
    public function draw(
        float $x = 0,
        float $y = 0,
        float $z = 0,
        array $size = ['width' => 600, 'depth' => 400, 'height' => 250],
        ?array $roof = null,
        ?array $door = null,
        array $windows = [],
    ): void {
        $w = $size['width'];
        $d = $size['depth'];
        $h = $size['height'];

        if ($roof !== null) {
            $this->applyRoof($x, $y, $z, $w, $d, $h, $roof);
        } else {
            $this->drawing->box3D($x, $y, $z, $w, $d, $h);
        }

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
                'Okno ' . ($i + 1),
            );
        }
    }

    /**
     * Garaż dwuspadowy (kalenica w poprzek).
     */
    public function drawDualSlope(
        float $x = 0,
        float $y = 0,
        float $z = 0,
        float $width = 600,
        float $depth = 400,
        float $wallHeight = 250,
        float $ridgeHeight = 80,
        ?array $door = null,
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
                'Brama',
            );
        }
    }

    private function applyRoof(
        float $x,
        float $y,
        float $z,
        float $w,
        float $d,
        float $h,
        array $roof,
    ): void {
        $zFront = $z + $h;

        if (isset($roof['rise'])) {
            $rise = (float) $roof['rise'];
            $zBack = $zFront + $rise;
            $this->drawMonopitchShell($x, $y, $z, $w, $d, $zFront, $zBack, $zBack);

            return;
        }

        $zBackLeft = $zFront + (float) ($roof['left'] ?? 0);
        $zBackRight = $zFront + (float) ($roof['right'] ?? 0);

        $this->drawMonopitchShell($x, $y, $z, $w, $d, $zFront, $zBackLeft, $zBackRight);
    }
}
