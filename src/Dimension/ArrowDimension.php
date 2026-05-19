<?php

declare(strict_types=1);

namespace Drawing3D\Dimension;

use Drawing3D\Drawing3D;
use Drawing3D\Math\Vector3;
use Drawing3D\Style\StrokeStyle;

/**
 * Wymiar z podwójną strzałką i etykietą na środku.
 *
 * Użycie:
 *   ArrowDimension::on($drawing)->between3D([0,0,0], [600,0,0], 'Szerokość: 600 cm');
 *   $drawing->arrowDimension([0,0,0], [600,0,0], '600 cm');
 */
final class ArrowDimension
{
    private string $color = '#1d4ed8';
    private int $fontSize = 12;
    private float $arrowSize = 8.0;
    private float $strokeWidth = 1.4;

    public function __construct(
        private Drawing3D $drawing,
    ) {
    }

    public static function on(Drawing3D $drawing): self
    {
        return new self($drawing);
    }

    public function color(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function fontSize(int $size): self
    {
        $this->fontSize = $size;

        return $this;
    }

    /**
     * Wymiar między punktami 3D (po rzucie izometrycznym).
     *
     * @param array{0: float|int, 1: float|int, 2?: float|int} $from
     * @param array{0: float|int, 1: float|int, 2?: float|int} $to
     */
    /**
     * @param array{0: float, 1: float, 2: float}|null $offsetDirection np. [0,0,-1] = w dół (wymiar pod obiektem)
     */
    public function between3D(
        array $from,
        array $to,
        string $label,
        float $offset = 0.0,
        bool $extensions = false,
        ?array $offsetDirection = null,
    ): void {
        $a = Vector3::from($from);
        $b = Vector3::from($to);

        if ($offset !== 0.0) {
            $shift = $offsetDirection !== null
                ? $this->directionOffset($offsetDirection, $offset)
                : $this->offsetVector($a, $b, $offset);
            $dimA = $a->add($shift);
            $dimB = $b->add($shift);

            if ($extensions) {
                $this->drawing->line3D($a->toArray(), $dimA->toArray(), StrokeStyle::light());
                $this->drawing->line3D($b->toArray(), $dimB->toArray(), StrokeStyle::light());
            }

            $this->drawProjectedSegment($dimA, $dimB, $label);

            return;
        }

        $this->drawProjectedSegment($a, $b, $label);
    }

    /**
     * Wymiar w płaszczyźnie SVG (piksele ekranu).
     */
    public function between2D(
        float $x1,
        float $y1,
        float $x2,
        float $y2,
        string $label,
    ): void {
        $this->drawing->canvas()->addDimensionLine(
            $x1,
            $y1,
            $x2,
            $y2,
            $label,
            $this->color,
            $this->fontSize,
            $this->arrowSize,
            $this->strokeWidth,
        );
    }

    private function drawProjectedSegment(Vector3 $a, Vector3 $b, string $label): void
    {
        $projector = $this->drawing->projector();

        [$x1, $y1] = $projector->project($a->x, $a->y, $a->z);
        [$x2, $y2] = $projector->project($b->x, $b->y, $b->z);

        $this->between2D($x1, $y1, $x2, $y2, $label);
    }

    /**
     * @param array{0: float, 1: float, 2: float} $direction
     */
    private function directionOffset(array $direction, float $offset): Vector3
    {
        $vx = (float) $direction[0];
        $vy = (float) $direction[1];
        $vz = (float) $direction[2];
        $len = sqrt($vx ** 2 + $vy ** 2 + $vz ** 2) ?: 1.0;

        return new Vector3($vx / $len * $offset, $vy / $len * $offset, $vz / $len * $offset);
    }

    private function offsetVector(Vector3 $a, Vector3 $b, float $offset): Vector3
    {
        $dx = $b->x - $a->x;
        $dy = $b->y - $a->y;
        $dz = $b->z - $a->z;

        $ux = 0.0;
        $uy = 0.0;
        $uz = 1.0;

        $px = $dy * $uz - $dz * $uy;
        $py = $dz * $ux - $dx * $uz;
        $pz = $dx * $uy - $dy * $ux;

        $len = sqrt($px ** 2 + $py ** 2 + $pz ** 2);
        if ($len < 1e-6) {
            $px = -$dy;
            $py = $dx;
            $pz = 0.0;
            $len = sqrt($px ** 2 + $py ** 2 + $pz ** 2) ?: 1.0;
        }

        $scale = $offset / $len;

        return new Vector3($px * $scale, $py * $scale, $pz * $scale);
    }
}
