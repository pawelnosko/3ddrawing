<?php

declare(strict_types=1);

/**
 * Przykład 6: Garaż z asymetrycznym spadem dachu (lewy/prawy) i oknami.
 */
require __DIR__ . '/bootstrap.php';

use Drawing3D\Building\GarageDrawer;

$points = [
    [0, 0, 0], [600, 0, 0], [600, 400, 0], [0, 400, 0],
    [0, 0, 250], [600, 0, 250], [0, 400, 360], [600, 400, 300],
];
$drawing = exampleDrawing($points);

(new GarageDrawer($drawing))->draw(
    size: ['width' => 600, 'depth' => 400, 'height' => 250],
    roof: ['left' => 50, 'right' => 110],
    door: ['x' => 200, 'z' => 0, 'width' => 200, 'height' => 215],
    windows: [
        ['x' => 60, 'z' => 140, 'width' => 80, 'height' => 70],
        ['x' => 460, 'z' => 140, 'width' => 80, 'height' => 70],
    ],
);

$drawing->text3D([300, -50, 300], 'Spad: L=50 mm, P=110 mm', 12);

renderExample('06 — Garaż z asymetrycznym dachem', $drawing);
