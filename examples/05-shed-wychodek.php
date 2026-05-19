<?php

declare(strict_types=1);

/**
 * Przykład 5: Wychodek / mała szopa.
 */
require __DIR__ . '/bootstrap.php';

use Drawing3D\Building\ShedDrawer;

$points = monopitchPoints(0, 0, 0, 120, 120, 200, 40);
$drawing = exampleDrawing($points, 700, 500);

(new ShedDrawer($drawing))->draw();

renderExample('05 — Wychodek', $drawing);
