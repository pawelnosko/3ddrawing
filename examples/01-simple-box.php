<?php

declare(strict_types=1);

/**
 * Przykład 1: Prosty prostopadłościan i wymiary.
 */
require __DIR__ . '/bootstrap.php';

use Drawing3D\Drawing3D;
use Drawing3D\Style\StrokeStyle;

$points = boxPoints(0, 0, 0, 300, 200, 150);
$drawing = exampleDrawing($points);

$drawing->box3D(0, 0, 0, 300, 200, 150);
$drawing->line3D([0, 0, 150], [300, 200, 150], StrokeStyle::hidden());
$drawing->arrowDimension([0, 0, 0], [300, 0, 0], '300 cm', offset: 45, extensions: true);
$drawing->arrowDimension([0, 0, 0], [0, 200, 0], '200 cm', offset: 45, extensions: true);
$drawing->arrowDimension([0, 0, 0], [0, 0, 150], '150 cm', offset: 35, extensions: true);
$drawing->text3D([150, -40, 75], 'Prosty blok 3D', 14);

renderExample('01 — Prosty prostopadłościan', $drawing);
