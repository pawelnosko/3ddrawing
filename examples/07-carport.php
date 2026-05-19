<?php

declare(strict_types=1);

/**
 * Przykład 7: Wiata samochodowa (carport).
 */
require __DIR__ . '/bootstrap.php';

use Drawing3D\Building\CarportDrawer;

$points = monopitchPoints(0, 0, 0, 500, 350, 220, 30);
$drawing = exampleDrawing($points);

(new CarportDrawer($drawing))->draw();

renderExample('07 — Wiata samochodowa', $drawing);
