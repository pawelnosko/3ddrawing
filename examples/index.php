<?php

declare(strict_types=1);

$examples = [
    '01-simple-box.php' => 'Prosty prostopadłościan',
    '02-simple-garage.php' => 'Garaż jednospadowy',
    '03-dual-slope-garage.php' => 'Garaż dwuspadowy',
    '04-simple-house.php' => 'Prosty domek',
    '05-shed-wychodek.php' => 'Wychodek',
    '06-garage-asymmetric-roof.php' => 'Garaż — asymetryczny spad dachu',
    '07-carport.php' => 'Wiata samochodowa',
    '08-barn.php' => 'Stodoła',
    '09-l-shape-building.php' => 'Budynek w kształcie L',
    '10-workshop.php' => 'Warsztat',
];

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>3Ddrawing — przykłady</title>
  <style>
    body { font-family: Arial, sans-serif; max-width: 640px; margin: 2rem auto; padding: 0 1rem; }
    h1 { font-size: 1.5rem; }
    ul { line-height: 1.9; }
    a { color: #2563eb; }
    .meta { color: #71717a; font-size: 0.9rem; }
  </style>
</head>
<body>
  <h1>3Ddrawing — przykłady</h1>
  <p class="meta">Autor: Paweł Nosko</p>
  <ul>
    <?php foreach ($examples as $file => $label): ?>
      <li><a href="<?= htmlspecialchars($file, ENT_QUOTES) ?>"><?= htmlspecialchars($label, ENT_QUOTES) ?></a></li>
    <?php endforeach; ?>
  </ul>
</body>
</html>
