<?php

declare(strict_types=1);

namespace Drawing3D\Projection;

/**
 * Rzut izometryczny: klasyczne współczynniki cos(30°) i sin(30°).
 */
final class IsometricProjector implements ProjectorInterface
{
    private const COS_30 = 0.8660254037844386;
    private const SIN_30 = 0.5;

    public function __construct(
        private float $scale = 1.0,
        private float $offsetX = 0.0,
        private float $offsetY = 0.0,
    ) {
    }

    public function withScale(float $scale): self
    {
        $clone = clone $this;
        $clone->scale = $scale;

        return $clone;
    }

    public function withOffset(float $offsetX, float $offsetY): self
    {
        $clone = clone $this;
        $clone->offsetX = $offsetX;
        $clone->offsetY = $offsetY;

        return $clone;
    }

    /**
     * @return array{0: float, 1: float}
     */
    public function project(float $x, float $y, float $z): array
    {
        $s = $this->scale;

        return [
            ($x - $y) * self::COS_30 * $s + $this->offsetX,
            ($x + $y) * self::SIN_30 * $s - $z * $s + $this->offsetY,
        ];
    }

    /**
     * Oblicza skalę i przesunięcie tak, aby model zmieścił się w prostokącie docelowym.
     *
     * @param list<array{0: float, 1: float, 2: float}> $points
     */
    public function fitToBounds(
        array $points,
        float $targetWidth,
        float $targetHeight,
        float $padding = 40.0,
    ): self {
        if ($points === []) {
            return $this;
        }

        $minX = $minY = INF;
        $maxX = $maxY = -INF;

        $temp = new self($this->scale, 0, 0);

        foreach ($points as [$x, $y, $z]) {
            [$px, $py] = $temp->project($x, $y, $z);
            $minX = min($minX, $px);
            $maxX = max($maxX, $px);
            $minY = min($minY, $py);
            $maxY = max($maxY, $py);
        }

        $modelWidth = max(1.0, $maxX - $minX);
        $modelHeight = max(1.0, $maxY - $minY);

        $availableW = max(1.0, $targetWidth - 2 * $padding);
        $availableH = max(1.0, $targetHeight - 2 * $padding);

        $scale = min($availableW / $modelWidth, $availableH / $modelHeight) * $this->scale;

        $centerModelX = ($minX + $maxX) / 2;
        $centerModelY = ($minY + $maxY) / 2;

        $offsetX = $targetWidth / 2 - $centerModelX * ($scale / max($this->scale, 1e-9));
        $offsetY = $targetHeight / 2 - $centerModelY * ($scale / max($this->scale, 1e-9));

        return new self($scale, $offsetX, $offsetY);
    }
}
