<?php

declare(strict_types=1);

/**
 * Przykład 10: Warsztat z bramą, drzwiami i oknami.
 */
require __DIR__ . '/bootstrap.php';

use Drawing3D\Building\WorkshopDrawer;

$points = monopitchPoints(0, 0, 0, 700, 450, 280, 70);
$drawing = exampleDrawing($points, 1000, 700);

(new WorkshopDrawer($drawing))->draw();

renderExample('10 — Warsztat', $drawing);
