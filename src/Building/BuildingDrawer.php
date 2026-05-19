<?php

declare(strict_types=1);

namespace Drawing3D\Building;

use Drawing3D\Drawing3D;
use Drawing3D\Style\StrokeStyle;

/**
 * Wspólne operacje rysowania budynków (otwory, dachy, podstawy).
 */
abstract class BuildingDrawer
{
    public function __construct(
        protected Drawing3D $drawing,
    ) {
    }

    /**
     * Otwór (drzwi/okno) na ścianie frontowej (y = 0).
     */
    protected function drawOpeningFront(
        float $x,
        float $y,
        float $z,
        float $width,
        float $height,
        string $label = '',
    ): void {
        $this->drawing->line3D([$x, $y, $z], [$x + $width, $y, $z], StrokeStyle::accent());
        $this->drawing->line3D([$x + $width, $y, $z], [$x + $width, $y, $z + $height], StrokeStyle::accent());
        $this->drawing->line3D([$x + $width, $y, $z + $height], [$x, $y, $z + $height], StrokeStyle::accent());
        $this->drawing->line3D([$x, $y, $z + $height], [$x, $y, $z], StrokeStyle::accent());

        if ($label !== '') {
            $this->drawing->text3D([$x + $width / 2, $y - 20, $z + $height / 2], $label, 10, '#2563eb');
        }
    }

    /**
     * Otwór na ścianie bocznej (x = const).
     */
    protected function drawOpeningSideX(
        float $x,
        float $y,
        float $z,
        float $depth,
        float $height,
        string $label = '',
    ): void {
        $this->drawing->line3D([$x, $y, $z], [$x, $y + $depth, $z], StrokeStyle::accent());
        $this->drawing->line3D([$x, $y + $depth, $z], [$x, $y + $depth, $z + $height], StrokeStyle::accent());
        $this->drawing->line3D([$x, $y + $depth, $z + $height], [$x, $y, $z + $height], StrokeStyle::accent());
        $this->drawing->line3D([$x, $y, $z + $height], [$x, $y, $z], StrokeStyle::accent());

        if ($label !== '') {
            $this->drawing->text3D([$x, $y + $depth / 2, $z + $height / 2], $label, 10, '#2563eb');
        }
    }

    /**
     * Obrys budynku jednospadowego: ściany i dach współdzielą tę samą płaszczyznę spadu (+Y).
     * Przód (y) — niżej (zFront), tył (y+depth) — wyżej (zBackLeft / zBackRight).
     */
    protected function drawMonopitchShell(
        float $x,
        float $y,
        float $z,
        float $width,
        float $depth,
        float $zFront,
        float $zBackLeft,
        float $zBackRight,
        bool $drawRoofSurface = true,
    ): void {
        $yb = $y + $depth;
        $xr = $x + $width;

        // Podstawa
        $this->drawing->line3D([$x, $y, $z], [$xr, $y, $z]);
        $this->drawing->line3D([$xr, $y, $z], [$xr, $yb, $z]);
        $this->drawing->line3D([$xr, $yb, $z], [$x, $yb, $z]);
        $this->drawing->line3D([$x, $yb, $z], [$x, $y, $z]);

        // Ściana frontowa (y)
        $this->drawing->line3D([$x, $y, $z], [$x, $y, $zFront]);
        $this->drawing->line3D([$xr, $y, $z], [$xr, $y, $zFront]);
        $this->drawing->line3D([$x, $y, $zFront], [$xr, $y, $zFront]);

        // Ściana tylna (y+depth) — góra podąża za spadem dachu
        $this->drawing->line3D([$x, $yb, $z], [$x, $yb, $zBackLeft]);
        $this->drawing->line3D([$xr, $yb, $z], [$xr, $yb, $zBackRight]);
        $this->drawing->line3D([$x, $yb, $zBackLeft], [$xr, $yb, $zBackRight]);

        // Ściana lewa (x) — górna krawędź pod skosem
        $this->drawing->line3D([$x, $y, $z], [$x, $yb, $z]);
        $this->drawing->line3D([$x, $y, $zFront], [$x, $yb, $zBackLeft]);

        // Ściana prawa (x+width)
        $this->drawing->line3D([$xr, $y, $z], [$xr, $yb, $z]);
        $this->drawing->line3D([$xr, $y, $zFront], [$xr, $yb, $zBackRight]);

        if ($drawRoofSurface) {
            $this->drawing->line3D([$x, $y, $zFront], [$xr, $y, $zFront]);
            $this->drawing->line3D([$x, $yb, $zBackLeft], [$xr, $yb, $zBackRight]);
        }
    }

    /**
     * Dach jednospadowy — spad w kierunku +Y (używa drawMonopitchShell).
     */
    protected function drawSingleSlopeRoof(
        float $x,
        float $y,
        float $z,
        float $width,
        float $depth,
        float $wallHeight,
        float $roofRise,
    ): void {
        $zFront = $z + $wallHeight;
        $zBack = $z + $wallHeight + $roofRise;

        $this->drawMonopitchShell($x, $y, $z, $width, $depth, $zFront, $zBack, $zBack);
    }

    /**
     * Dach dwuspadowy (kalenica wzdłuż osi X).
     */
    protected function drawDualSlopeRoof(
        float $x,
        float $y,
        float $z,
        float $width,
        float $depth,
        float $wallHeight,
        float $ridgeHeight,
    ): void {
        $ridgeZ = $z + $wallHeight + $ridgeHeight;
        $eaveZ = $z + $wallHeight;
        $ridgeY = $y + $depth / 2;

        $this->drawing->line3D([$x, $y, $eaveZ], [$x + $width, $y, $eaveZ]);
        $this->drawing->line3D([$x, $y + $depth, $eaveZ], [$x + $width, $y + $depth, $eaveZ]);

        foreach ([$x, $x + $width] as $ix) {
            $this->drawing->line3D([$ix, $y, $eaveZ], [$ix, $ridgeY, $ridgeZ]);
            $this->drawing->line3D([$ix, $ridgeY, $ridgeZ], [$ix, $y + $depth, $eaveZ]);
        }

        $this->drawing->line3D([$x, $ridgeY, $ridgeZ], [$x + $width, $ridgeY, $ridgeZ], StrokeStyle::accent());
    }

    /**
     * Dach czterospadowy (kopertowy) — uproszczony szczyt.
     */
    protected function drawHipRoof(
        float $x,
        float $y,
        float $z,
        float $width,
        float $depth,
        float $wallHeight,
        float $peakRise,
    ): void {
        $eaveZ = $z + $wallHeight;
        $peakZ = $eaveZ + $peakRise;
        $cx = $x + $width / 2;
        $cy = $y + $depth / 2;

        $corners = [
            [$x, $y, $eaveZ],
            [$x + $width, $y, $eaveZ],
            [$x + $width, $y + $depth, $eaveZ],
            [$x, $y + $depth, $eaveZ],
        ];

        foreach ($corners as $corner) {
            $this->drawing->line3D($corner, [$cx, $cy, $peakZ]);
        }

        $this->drawing->line3D($corners[0], $corners[1]);
        $this->drawing->line3D($corners[1], $corners[2]);
        $this->drawing->line3D($corners[2], $corners[3]);
        $this->drawing->line3D($corners[3], $corners[0]);
    }

    /**
     * @return list<array{0: float, 1: float, 2: float}>
     */
    protected function collectBoxPoints(
        float $x,
        float $y,
        float $z,
        float $width,
        float $depth,
        float $height,
    ): array {
        return $this->drawing->boxCorners($x, $y, $z, $width, $depth, $height);
    }
}
