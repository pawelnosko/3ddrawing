# 3Ddrawing

Biblioteka PHP do generowania **prostych rysunków technicznych 3D** (rzut izometryczny) jako **SVG**.

**Autor:** Paweł Nosko  
**Wymagania:** PHP 8.1+

## Instalacja

```bash
cd /var/www/html/3Ddrawing
composer install
```

## Szybki start

```php
<?php
require 'vendor/autoload.php';

use Drawing3D\Drawing3D;
use Drawing3D\Building\GarageDrawer;

$drawing = Drawing3D::create(900, 650);
$drawing->fitToModel($drawing->boxCorners(0, 0, 0, 600, 400, 320));

(new GarageDrawer($drawing))->draw(
    size: ['width' => 600, 'depth' => 400, 'height' => 250],
    roof: ['rise' => 70],
    door: ['x' => 180, 'z' => 0, 'width' => 240, 'height' => 220],
);

file_put_contents('garaz.svg', $drawing->toSvg());
// lub: echo $drawing->toHtml('Mój garaż');
```

## Przykłady

Uruchom lokalny serwer PHP:

```bash
php -S localhost:8080 -t examples
```

Otwórz: [http://localhost:8080/](http://localhost:8080/) — lista 10 przykładów.

| Plik | Opis |
|------|------|
| `01-simple-box.php` | Prostopadłościan i wymiary |
| `02-simple-garage.php` | Garaż jednospadowy |
| `03-dual-slope-garage.php` | Garaż dwuspadowy |
| `04-simple-house.php` | Prosty domek |
| `05-shed-wychodek.php` | Wychodek |
| `06-garage-asymmetric-roof.php` | Garaż z różnym spadem lewo/prawo |
| `07-carport.php` | Wiata samochodowa |
| `08-barn.php` | Stodoła |
| `09-l-shape-building.php` | Budynek L |
| `10-workshop.php` | Warsztat |

## Struktura projektu

```
src/
  Drawing3D.php           # Główne API (line3D, box3D, wymiary)
  Math/Vector3.php
  Projection/             # Rzut izometryczny
  Canvas/SvgCanvas.php    # Bufor SVG
  Style/StrokeStyle.php
  Dimension/              # Wymiary na elewacji
  Building/               # Gotowe szkice budynków
examples/                 # 10 demonstracji
docs/API.md               # Dokumentacja API
```

## Wymiary ze strzałkami

```php
$drawing->arrowDimension([0, 0, 0], [600, 0, 0], 'Szerokość: 600 cm', offset: 50, extensions: true);
```

Szczegóły: [docs/API.md](docs/API.md#arrowdimension--wymiary-ze-strzałkami).

## Klasy budynków

- `GarageDrawer` — garaż (jedno- i dwuspadowy, asymetryczny spad)
- `HouseDrawer` — domek
- `ShedDrawer` — wychodek
- `CarportDrawer` — wiata
- `BarnDrawer` — stodoła
- `LShapeDrawer` — budynek L
- `WorkshopDrawer` — warsztat
- `GreenhouseDrawer` — szklarnia (do własnych kompozycji)

## Jednostki

Współrzędne modelu są w **dowolnych jednostkach** (np. mm). Biblioteka nie konwertuje jednostek — podajesz spójne wartości, a `fitToModel()` dopasowuje skalę do rozmiaru SVG.

## Licencja

MIT — zobacz [LICENSE](LICENSE).

## Dokumentacja

Szczegółowe API: [docs/API.md](docs/API.md).
