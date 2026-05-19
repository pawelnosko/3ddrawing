<?php

declare(strict_types=1);

/**
 * Przykład 8: Stodoła / budynek gospodarczy.
 */
require __DIR__ . '/bootstrap.php';

use Drawing3D\Building\BarnDrawer;

$points = boxPoints(0, 0, 0, 800, 500, 500);
$drawing = exampleDrawing($points, 1000, 700);

(new BarnDrawer($drawing))->draw();

renderExample('08 — Stodoła', $drawing);
