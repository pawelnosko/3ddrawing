<?php

declare(strict_types=1);

namespace Drawing3D;

use Drawing3D\Canvas\SvgCanvas;
use Drawing3D\Dimension\ArrowDimension;
use Drawing3D\Math\Vector3;
use Drawing3D\Projection\IsometricProjector;
use Drawing3D\Projection\ProjectorInterface;
use Drawing3D\Style\StrokeStyle;

/**
 * Główna klasa rysunku — API do linii, prymitywów i eksportu SVG.
 *
 * @author Paweł Nosko
 */
final class Drawing3D
{
    private StrokeStyle $stroke;

    public function __construct(
        private SvgCanvas $canvas = new SvgCanvas(),
        private ProjectorInterface $projector = new IsometricProjector(),
    ) {
        $this->stroke = StrokeStyle::main();
    }

    public static function create(int $width = 800, int $height = 600): self
    {
        $canvas = new SvgCanvas($width, $height);
        $projector = new IsometricProjector(1.0, $width / 2, $height / 2);

        return new self($canvas, $projector);
    }

    public function canvas(): SvgCanvas
    {
        return $this->canvas;
    }

    public function projector(): ProjectorInterface
    {
        return $this->projector;
    }

    public function setProjector(ProjectorInterface $projector): self
    {
        $this->projector = $projector;

        return $this;
    }

    public function setStroke(StrokeStyle $stroke): self
    {
        $this->stroke = $stroke;

        return $this;
    }

    /**
     * @param array{0: float|int, 1: float|int, 2?: float|int} $from
     * @param array{0: float|int, 1: float|int, 2?: float|int} $to
     */
    public function line3D(array $from, array $to, ?StrokeStyle $style = null): self
    {
        $a = Vector3::from($from);
        $b = Vector3::from($to);

        [$x1, $y1] = $this->projector->project($a->x, $a->y, $a->z);
        [$x2, $y2] = $this->projector->project($b->x, $b->y, $b->z);

        $this->canvas->addLine($x1, $y1, $x2, $y2, $style ?? $this->stroke);

        return $this;
    }

    /**
     * Rysuje 12 krawędzi prostopadłościanu od punktu (x,y,z) o wymiarach (w,d,h).
     */
    public function box3D(
        float $x,
        float $y,
        float $z,
        float $width,
        float $depth,
        float $height,
        ?StrokeStyle $style = null,
    ): self {
        $stroke = $style ?? $this->stroke;
        $corners = $this->boxCorners($x, $y, $z, $width, $depth, $height);

        $edges = [
            [0, 1], [1, 2], [2, 3], [3, 0],
            [4, 5], [5, 6], [6, 7], [7, 4],
            [0, 4], [1, 5], [2, 6], [3, 7],
        ];

        foreach ($edges as [$i, $j]) {
            $this->line3D($corners[$i], $corners[$j], $stroke);
        }

        return $this;
    }

    /**
     * Ściana jako wypełniony wielokąt (4 narożniki w 3D).
     *
     * @param list<array{0: float, 1: float, 2: float}> $corners3D
     */
    public function face3D(array $corners3D, ?string $fill = 'rgba(200,210,220,0.25)', ?StrokeStyle $style = null): self
    {
        $points2D = [];
        foreach ($corners3D as $corner) {
            $v = Vector3::from($corner);
            $points2D[] = $this->projector->project($v->x, $v->y, $v->z);
        }

        $this->canvas->addPolygon($points2D, $style ?? $this->stroke, $fill);

        return $this;
    }

    /**
     * @param array{0: float|int, 1: float|int, 2?: float|int} $position
     */
    public function text3D(
        array $position,
        string $text,
        int $fontSize = 12,
        string $color = '#333333',
    ): self {
        $v = Vector3::from($position);
        [$px, $py] = $this->projector->project($v->x, $v->y, $v->z);

        $this->canvas->addText($px, $py - 4, $text, $fontSize, $color);

        return $this;
    }

    /**
     * Wymiar ze strzałkami i etykietą (najprostsze API).
     *
     * @param array{0: float|int, 1: float|int, 2?: float|int} $from
     * @param array{0: float|int, 1: float|int, 2?: float|int} $to
     */
    /**
     * @param array{0: float, 1: float, 2: float}|null $offsetDirection
     */
    public function arrowDimension(
        array $from,
        array $to,
        string $label,
        float $offset = 0.0,
        bool $extensions = false,
        ?array $offsetDirection = null,
    ): self {
        ArrowDimension::on($this)
            ->between3D($from, $to, $label, $offset, $extensions, $offsetDirection);

        return $this;
    }

    /** @return ArrowDimension konfigurator wymiarów (łańcuch: ->color()->between3D(...)) */
    public function arrows(): ArrowDimension
    {
        return ArrowDimension::on($this);
    }

    /**
     * @deprecated Użyj arrowDimension() — zachowane dla kompatybilności.
     *
     * @param array{0: float|int, 1: float|int, 2?: float|int} $from
     * @param array{0: float|int, 1: float|int, 2?: float|int} $to
     */
    public function dimension3D(
        array $from,
        array $to,
        ?string $label = null,
        float $offset = 40.0,
    ): self {
        $a = Vector3::from($from);
        $b = Vector3::from($to);

        $length = sqrt(
            ($b->x - $a->x) ** 2 +
            ($b->y - $a->y) ** 2 +
            ($b->z - $a->z) ** 2,
        );

        $this->arrowDimension($from, $to, $label ?? sprintf('%.0f', $length), $offset, true);

        return $this;
    }

    /**
     * Dopasowuje skalę izometrii do podanych punktów 3D.
     *
     * @param list<array{0: float, 1: float, 2: float}> $points
     */
    public function fitToModel(array $points, float $padding = 50.0): self
    {
        if (!$this->projector instanceof IsometricProjector) {
            return $this;
        }

        $fitted = $this->projector->fitToBounds(
            $points,
            (float) $this->canvas->getWidth(),
            (float) $this->canvas->getHeight(),
            $padding,
        );

        $this->projector = $fitted;

        return $this;
    }

    public function toSvg(): string
    {
        return $this->canvas->toSvg();
    }

    public function toHtml(string $title = 'Rysunek techniczny'): string
    {
        return $this->canvas->toHtml($title);
    }

    /**
     * @return list<array{0: float, 1: float, 2: float}>
     */
    public function boxCorners(
        float $x,
        float $y,
        float $z,
        float $width,
        float $depth,
        float $height,
    ): array {
        return [
            [$x, $y, $z],
            [$x + $width, $y, $z],
            [$x + $width, $y + $depth, $z],
            [$x, $y + $depth, $z],
            [$x, $y, $z + $height],
            [$x + $width, $y, $z + $height],
            [$x + $width, $y + $depth, $z + $height],
            [$x, $y + $depth, $z + $height],
        ];
    }

}
