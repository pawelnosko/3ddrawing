<?php

declare(strict_types=1);

/**
 * Przykład 2: Garaż jednospadowy z bramą.
 */
require __DIR__ . '/bootstrap.php';

use Drawing3D\Building\GarageDrawer;

$points = monopitchPoints(0, 0, 0, 600, 400, 250, 70);
$drawing = exampleDrawing($points);

(new GarageDrawer($drawing))->draw(
    size: ['width' => 600, 'depth' => 400, 'height' => 250],
    roof: ['rise' => 70],
    door: ['x' => 180, 'z' => 0, 'width' => 240, 'height' => 220],
);

$drawing->arrowDimension([0, 0, 0], [600, 0, 0], 'Szerokość: 600 cm', offset: 55, extensions: true, offsetDirection: [0, 0, -1]);
$drawing->arrowDimension([0, 0, 0], [0, 400, 0], 'Głębokość: 400 cm', offset: 55, extensions: true, offsetDirection: [0, 0, -1]);

$drawing->text3D([300, -50, 200], 'Garaż jednospadowy', 14);

renderExample('02 — Garaż jednospadowy', $drawing);
