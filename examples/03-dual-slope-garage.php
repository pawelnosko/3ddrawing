<?php

declare(strict_types=1);

/**
 * Przykład 3: Garaż dwuspadowy (kalenica).
 */
require __DIR__ . '/bootstrap.php';

use Drawing3D\Building\GarageDrawer;

$points = boxPoints(0, 0, 0, 650, 420, 350);
$drawing = exampleDrawing($points);

(new GarageDrawer($drawing))->drawDualSlope(
    width: 650,
    depth: 420,
    wallHeight: 250,
    ridgeHeight: 90,
    door: ['x' => 200, 'z' => 0, 'width' => 250, 'height' => 230],
);

$drawing->text3D([325, -55, 280], 'Garaż dwuspadowy', 14);

renderExample('03 — Garaż dwuspadowy', $drawing);
