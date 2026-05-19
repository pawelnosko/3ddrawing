<?php

declare(strict_types=1);

/**
 * Przykład 4: Prosty domek z drzwiami i oknami.
 */
require __DIR__ . '/bootstrap.php';

use Drawing3D\Building\HouseDrawer;

$points = boxPoints(0, 0, 0, 500, 400, 400);
$drawing = exampleDrawing($points);

(new HouseDrawer($drawing))->draw();

renderExample('04 — Prosty domek', $drawing);
