<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Drawing3D\Drawing3D;

/**
 * Tworzy rysunek z dopasowaną skalą do podanych punktów modelu.
 *
 * @param list<array{0: float, 1: float, 2: float}> $modelPoints
 */
function exampleDrawing(array $modelPoints, int $width = 900, int $height = 650): Drawing3D
{
    $drawing = Drawing3D::create($width, $height);
    $drawing->fitToModel($modelPoints);

    return $drawing;
}

function renderExample(string $title, Drawing3D $drawing): void
{
    header('Content-Type: text/html; charset=UTF-8');
    echo $drawing->toHtml($title);
}

function boxPoints(float $x, float $y, float $z, float $w, float $d, float $h): array
{
    $d3 = Drawing3D::create();

    return $d3->boxCorners($x, $y, $z, $w, $d, $h);
}

/**
 * Punkty obwiedni budynku jednospadowego (do fitToModel).
 */
function monopitchPoints(
    float $x,
    float $y,
    float $z,
    float $w,
    float $d,
    float $wallHeight,
    float $roofRise,
): array {
    $zFront = $z + $wallHeight;
    $zBack = $zFront + $roofRise;

    return [
        [$x, $y, $z],
        [$x + $w, $y, $z],
        [$x + $w, $y + $d, $z],
        [$x, $y + $d, $z],
        [$x, $y, $zFront],
        [$x + $w, $y, $zFront],
        [$x, $y + $d, $zBack],
        [$x + $w, $y + $d, $zBack],
    ];
}
