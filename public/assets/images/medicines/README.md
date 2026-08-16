# Medicine product images

Drop image files into this folder using the exact filenames below — the code
already references them (`app/models/Medicine.php` -> `medicine_image()` in
`config/helpers.php`). No code changes needed once a file exists here.

Recommended: JPG, roughly 500x360px (product page) — the same file is reused
at smaller sizes for cards, cart rows, and thumbnails, so one image per
medicine is enough.

If a file is missing, `_placeholder.svg` (already included) is shown instead
— nothing will break while you're still adding photos.

| Medicine | Expected filename |
|---|---|
| Paracetamol 500mg | `paracetamol-500mg.jpg` |
| Ibuprofen 400mg | `ibuprofen-400mg.jpg` |
| Amoxicillin 500mg | `amoxicillin-500mg.jpg` |
| Cetirizine 10mg | `cetirizine-10mg.jpg` |
| Vitamin C 1000mg | `vitamin-c-1000mg.jpg` |
| Multivitamin Daily | `multivitamin-daily.jpg` |
| Metformin 500mg | `metformin-500mg.jpg` |
| Hydrocortisone Cream 1% | `hydrocortisone-cream-1.jpg` |
| Cough Syrup 100ml | `cough-syrup-100ml.jpg` |
| Baby Paracetamol Drops | `baby-paracetamol-drops.jpg` |
| Azithromycin 250mg | `azithromycin-250mg.jpg` |
| Omega-3 Fish Oil | `omega-3-fish-oil.jpg` |
| Clarithromycin 500mg | `clarithromycin-500mg.jpg` |
| Doxycycline 100mg | `doxycycline-100mg.jpg` |
