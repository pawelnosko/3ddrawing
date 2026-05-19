<?php

declare(strict_types=1);

/**
 * Przykład 9: Budynek w kształcie litery L.
 */
require __DIR__ . '/bootstrap.php';

use Drawing3D\Building\LShapeDrawer;

$points = array_merge(
    boxPoints(0, 0, 0, 500, 300, 320),
    boxPoints(0, 300, 0, 250, 350, 320),
);
$drawing = exampleDrawing($points, 950, 700);

(new LShapeDrawer($drawing))->draw();

renderExample('09 — Budynek L', $drawing);
