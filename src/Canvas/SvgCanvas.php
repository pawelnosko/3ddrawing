<?php

declare(strict_types=1);

namespace Drawing3D\Canvas;

use Drawing3D\Style\StrokeStyle;

/**
 * Bufor elementów SVG (linie, tekst, prostokąty).
 */
final class SvgCanvas
{
    /** @var list<string> */
    private array $elements = [];

    /** @var array<string, true> */
    private array $markerIds = [];

    public function __construct(
        private int $width = 800,
        private int $height = 600,
        private string $background = '#ffffff',
    ) {
    }

    public function setSize(int $width, int $height): void
    {
        $this->width = $width;
        $this->height = $height;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function addLine(
        float $x1,
        float $y1,
        float $x2,
        float $y2,
        StrokeStyle $style,
    ): void {
        $this->elements[] = sprintf(
            '<line x1="%.2f" y1="%.2f" x2="%.2f" y2="%.2f" %s />',
            $x1,
            $y1,
            $x2,
            $y2,
            $style->toSvgAttributes(),
        );
    }

    public function addPolygon(array $points, StrokeStyle $style, ?string $fill = null): void
    {
        $pairs = [];
        foreach ($points as [$x, $y]) {
            $pairs[] = sprintf('%.2f,%.2f', $x, $y);
        }

        $fillAttr = $fill === null
            ? 'fill="none"'
            : sprintf('fill="%s"', htmlspecialchars($fill, ENT_QUOTES));

        $this->elements[] = sprintf(
            '<polygon points="%s" %s %s />',
            implode(' ', $pairs),
            $style->toSvgAttributes(),
            $fillAttr,
        );
    }

    public function addText(
        float $x,
        float $y,
        string $text,
        int $size = 12,
        string $color = '#333333',
        string $anchor = 'middle',
    ): void {
        $safe = htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        $this->elements[] = sprintf(
            '<text x="%.2f" y="%.2f" font-size="%d" fill="%s" text-anchor="%s" font-family="Arial, sans-serif">%s</text>',
            $x,
            $y,
            $size,
            htmlspecialchars($color, ENT_QUOTES),
            htmlspecialchars($anchor, ENT_QUOTES),
            $safe,
        );
    }

    public function addRaw(string $svgFragment): void
    {
        $this->elements[] = $svgFragment;
    }

    /**
     * Linia wymiarowa: strzałki na obu końcach + etykieta na środku.
     */
    public function addDimensionLine(
        float $x1,
        float $y1,
        float $x2,
        float $y2,
        string $label,
        string $color = '#1d4ed8',
        int $fontSize = 12,
        float $arrowSize = 8.0,
        float $strokeWidth = 1.4,
    ): void {
        $markerId = $this->registerArrowMarker($color, $arrowSize);

        $this->elements[] = sprintf(
            '<line x1="%.2f" y1="%.2f" x2="%.2f" y2="%.2f" stroke="%s" stroke-width="%.2f" fill="none" marker-start="url(#%s-start)" marker-end="url(#%s-end)" />',
            $x1,
            $y1,
            $x2,
            $y2,
            htmlspecialchars($color, ENT_QUOTES),
            $strokeWidth,
            $markerId,
            $markerId,
        );

        $mx = ($x1 + $x2) / 2;
        $my = ($y1 + $y2) / 2;
        $dx = $x2 - $x1;
        $dy = $y2 - $y1;
        $len = sqrt($dx ** 2 + $dy ** 2) ?: 1.0;
        $nx = -$dy / $len * 11;
        $ny = $dx / $len * 11;

        $safe = htmlspecialchars($label, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $pad = 3;

        $this->elements[] = sprintf(
            '<rect x="%.2f" y="%.2f" width="%.2f" height="%.2f" fill="#ffffff" opacity="0.92" rx="2" />',
            $mx - strlen($label) * $fontSize * 0.28 - $pad,
            $my + $ny - $fontSize * 0.75 - $pad,
            max(24.0, strlen($label) * $fontSize * 0.56 + $pad * 2),
            $fontSize + $pad * 2,
        );

        $this->addText($mx + $nx, $my + $ny, $label, $fontSize, $color, 'middle');
    }

    private function registerArrowMarker(string $color, float $size): string
    {
        $key = $color . ':' . $size;
        $id = 'dim-' . substr(md5($key), 0, 8);

        if (isset($this->markerIds[$key])) {
            return $id;
        }

        $this->markerIds[$key] = true;
        $safeColor = htmlspecialchars($color, ENT_QUOTES);

        // Trójkąt: podstawa x=0, czubek (w, h/2) — klasyczny grot wymiaru technicznego
        $w = max(10.0, $size * 1.3);
        $h = max(7.0, $size * 0.85);
        $mid = $h / 2;
        $path = sprintf('M 0 0 L %.2f %.2f L 0 %.2f Z', $w, $mid, $h);

        $this->elements[] = <<<SVG
<defs>
  <marker id="{$id}-end" viewBox="0 0 {$w} {$h}" refX="{$w}" refY="{$mid}" markerWidth="{$w}" markerHeight="{$h}" orient="auto" markerUnits="userSpaceOnUse" overflow="visible">
    <path d="{$path}" fill="{$safeColor}" stroke="none"/>
  </marker>
  <marker id="{$id}-start" viewBox="0 0 {$w} {$h}" refX="{$w}" refY="{$mid}" markerWidth="{$w}" markerHeight="{$h}" orient="auto-start-reverse" markerUnits="userSpaceOnUse" overflow="visible">
    <path d="{$path}" fill="{$safeColor}" stroke="none"/>
  </marker>
</defs>
SVG;

        return $id;
    }

    public function toSvg(): string
    {
        $body = implode("\n  ", $this->elements);
        $bg = htmlspecialchars($this->background, ENT_QUOTES);

        return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg"
     width="{$this->width}" height="{$this->height}"
     viewBox="0 0 {$this->width} {$this->height}">
  <rect width="100%" height="100%" fill="{$bg}"/>
  {$body}
</svg>
SVG;
    }

    public function toHtml(string $title = 'Rysunek 3D'): string
    {
        $svg = $this->toSvg();
        $safeTitle = htmlspecialchars($title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        return <<<HTML
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>{$safeTitle}</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 2rem; background: #f4f4f5; }
    h1 { font-size: 1.25rem; color: #18181b; }
    .frame { background: #fff; padding: 1rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,.1); display: inline-block; }
    svg { max-width: 100%; height: auto; }
  </style>
</head>
<body>
  <h1>{$safeTitle}</h1>
  <div class="frame">
{$svg}
  </div>
</body>
</html>
HTML;
    }
}
