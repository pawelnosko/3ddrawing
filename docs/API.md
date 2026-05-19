# Dokumentacja API — 3Ddrawing

**Autor:** Paweł Nosko  
**Namespace:** `Drawing3D\`

---

## Drawing3D

Główna klasa rysunku. Łączy projektor 3D→2D i bufor SVG.

### Tworzenie

```php
$drawing = Drawing3D::create($width = 800, $height = 600);
```

### Metody

| Metoda | Opis |
|--------|------|
| `line3D(array $from, array $to, ?StrokeStyle $style)` | Linia między punktami `[x, y, z]` |
| `box3D($x, $y, $z, $width, $depth, $height, ?StrokeStyle)` | Prostopadłościan (12 krawędzi) |
| `face3D(array $corners3D, ?string $fill, ?StrokeStyle)` | Wypełniona ściana (wielokąt) |
| `text3D(array $position, string $text, int $fontSize, string $color)` | Etykieta w przestrzeni 3D |
| `dimension3D(array $from, array $to, ?string $label, float $offset)` | Wymiar między punktami |
| `fitToModel(array $points, float $padding)` | Auto-skala izometrii do punktów modelu |
| `boxCorners(...)` | 8 narożników prostopadłościanu (do fitToModel) |
| `toSvg()` | Zwraca string SVG |
| `toHtml(string $title)` | Pełna strona HTML z osadzonym SVG |

### Układ współrzędnych modelu

- **X** — szerokość (w prawo)
- **Y** — głębokość (w tył)
- **Z** — wysokość (do góry)
- Ściana **frontowa** (drzwi, okna): `y = 0` (lub stałe `y` budynku)

---

## IsometricProjector

Rzut izometryczny z opcjonalną skalą i przesunięciem.

```php
$projector = new IsometricProjector($scale, $offsetX, $offsetY);
[$px, $py] = $projector->project($x, $y, $z);
$fitted = $projector->fitToBounds($points, $targetWidth, $targetHeight, $padding);
```

Wzór:

- `px = (x - y) × cos(30°) × scale + offsetX`
- `py = (x + y) × sin(30°) × scale - z × scale + offsetY`

---

## StrokeStyle

Style linii: `main()`, `light()`, `hidden()` (przerywana), `accent()` (niebieska).

---

## Budynki jednospadowe (monopitch)

Ściany boczne i tylna mają górną krawędź **w tej samej płaszczyźnie co dach** (nie płaski box + osobny dach).

Metoda wewnętrzna: `drawMonopitchShell()` — spad w kierunku **+Y** (od frontu do tyłu).

---

## GarageDrawer

```php
(new GarageDrawer($drawing))->draw(
    x: 0, y: 0, z: 0,
    size: ['width' => 600, 'depth' => 400, 'height' => 250],
    roof: ['rise' => 70],                    // jednospad
    // roof: ['left' => 50, 'right' => 110], // asymetryczny tylny parapet
    door: ['x' => 180, 'z' => 0, 'width' => 240, 'height' => 220],
    windows: [
        ['x' => 60, 'z' => 140, 'width' => 80, 'height' => 70],
    ],
);

(new GarageDrawer($drawing))->drawDualSlope(
    width: 650, depth: 420, wallHeight: 250, ridgeHeight: 90,
    door: [...],
);
```

---

## HouseDrawer

```php
(new HouseDrawer($drawing))->draw(
    width: 500, depth: 400, wallHeight: 280, ridgeHeight: 100,
    door: ['x' => 200, 'z' => 0, 'width' => 100, 'height' => 200],
    windows: [...],
);
```

---

## ShedDrawer

Wychodek — mały budynek z jednospadowym dachem.

```php
(new ShedDrawer($drawing))->draw(
    width: 120, depth: 120, wallHeight: 200, roofRise: 40, withDoor: true,
);
```

---

## CarportDrawer

Wiata — słupy + dach bez pełnych ścian.

---

## BarnDrawer, LShapeDrawer, WorkshopDrawer, GreenhouseDrawer

Gotowe kompozycje — patrz pliki w `src/Building/` i przykłady w `examples/`.

---

## ArrowDimension — wymiary ze strzałkami

Najprostsze użycie (na obiekcie `Drawing3D`):

```php
$drawing->arrowDimension([0, 0, 0], [600, 0, 0], 'Szerokość: 600 cm');
$drawing->arrowDimension([0, 0, 0], [600, 0, 0], '500 cm', offset: 50, extensions: true, offsetDirection: [0, 0, -1]);
```

Alternatywnie z konfiguracją koloru:

```php
use Drawing3D\Dimension\ArrowDimension;

ArrowDimension::on($drawing)
    ->color('#b45309')
    ->fontSize(13)
    ->between3D([0, 0, 0], [0, 0, 250], 'Wysokość: 250 cm', offset: 40, extensions: true);
```

| Parametr | Znaczenie |
|----------|-----------|
| `$label` | Dowolny tekst, np. `500 cm`, `Szerokość: 50 cm` |
| `offset` | Odsunięcie linii wymiarowej od punktów (0 = wzdłuż krawędzi) |
| `extensions` | `true` — linie pomocnicze od narożników do wymiaru |
| `offsetDirection` | Kierunek odsunięcia, np. `[0,0,-1]` — wymiar pod spodem |

Wymiar 2D (piksele SVG):

```php
ArrowDimension::on($drawing)->between2D(100, 200, 400, 200, '600 cm');
```

---

## DimensionDrawer

Wymiary na elewacji (oś X / Z przy stałym Y):

```php
use Drawing3D\Dimension\DimensionDrawer;

$dims = new DimensionDrawer($drawing);
$dims->horizontal($x1, $x2, $y, $z, '600');
$dims->vertical($x, $y, $z1, $z2, '250');
```

---

## Własne rysunki

```php
$drawing->line3D([0, 0, 0], [600, 0, 0]);
$drawing->line3D([0, 0, 0], [0, 400, 0]);
$drawing->line3D([0, 0, 250], [600, 0, 250]);
```

Rozszerzanie: dziedzicz po `BuildingDrawer` i używaj metod chronionych `drawOpeningFront()`, `drawSingleSlopeRoof()`, `drawDualSlopeRoof()`.

---

## Eksport

- **SVG** — `file_put_contents('out.svg', $drawing->toSvg());`
- **HTML** — `echo $drawing->toHtml('Tytuł');`
- **PDF** — wygeneruj SVG, potem przekonwertuj zewnętrznym narzędziem (np. Inkscape, wkhtmltopdf z HTML)

---

## Ograniczenia

- Brak ukrywania krawędzi zasłoniętych (rysunek schematyczny).
- Wymiary 3D są uproszczone; precyzyjne wymiarowanie lepiej robić na elewacji 2D.
- Jedna projekcja (izometria) na jeden rysunek.
