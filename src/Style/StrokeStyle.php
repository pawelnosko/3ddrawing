<?php

declare(strict_types=1);

namespace Drawing3D\Style;

/**
 * Styl linii SVG.
 */
final class StrokeStyle
{
    public function __construct(
        public readonly string $color = '#1a1a1a',
        public readonly float $width = 1.5,
        public readonly ?string $dashArray = null,
    ) {
    }

    public static function main(): self
    {
        return new self('#1a1a1a', 1.8);
    }

    public static function light(): self
    {
        return new self('#666666', 1.0);
    }

    public static function hidden(): self
    {
        return new self('#999999', 1.0, '4 3');
    }

    public static function accent(): self
    {
        return new self('#2563eb', 2.0);
    }

    public function toSvgAttributes(): string
    {
        $attrs = sprintf(
            'stroke="%s" stroke-width="%.2f" fill="none"',
            htmlspecialchars($this->color, ENT_QUOTES),
            $this->width,
        );

        if ($this->dashArray !== null) {
            $attrs .= sprintf(' stroke-dasharray="%s"', htmlspecialchars($this->dashArray, ENT_QUOTES));
        }

        return $attrs;
    }
}
