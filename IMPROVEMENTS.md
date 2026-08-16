# PharmaSync — Improvements applied

This build layers security and design fixes onto the original integration
without changing the MVC structure or the mock-data approach. All views,
controllers, and routes still work with `DB_ENABLED = false`.

## Security

1. **CSRF protection on every POST.**
   - `Controller::csrfToken()` / `verifyCsrf()` added (`app/core/Controller.php`).
   - `csrf_token()` / `csrf_field()` / `e()` helpers added (`config/helpers.php`,
     auto-loaded from `config/config.php`).
   - `<?= csrf_field() ?>` embedded in all 18 POST forms.
   - `verifyCsrf()` called at the top of every POST action (auth, cart,
     checkout, profile, notifications, prescription upload). Invalid/expired
     tokens return HTTP 419 and bounce back with a flash message.

2. **Open-redirect fixed.** Cart "add to cart" previously redirected to the
   raw `HTTP_REFERER`. Now routed through `Controller::safeReferer()`, which
   only allows same-host internal paths.

3. **Prescriptions moved outside the web root.** Uploads now live in
   `/storage/prescriptions/` (with a deny-all `.htaccess`) instead of
   `public/uploads/`. They are served only through
   `GET /prescription/file/{id}` (`CustomerPrescriptionController::file()`),
   which checks that the logged-in user owns the file. This stops one customer
   from reading another's prescription by guessing a filename.

4. **Upload extension no longer trusted.** The saved filename's extension is
   derived from the validated MIME type (`PRESCRIPTION_MIME_EXT`), and a random
   suffix is added to prevent collisions/guessing.

## Design & correctness

5. **Dynamic greeting.** Dashboard says Good Morning/Afternoon/Evening based on
   the current hour instead of a hardcoded "Good Morning".

6. **Data-driven order status.** The dashboard status widget now renders from
   the customer's most recent order's real `tracking` data
   (`partials/tracking-steps.php`) and can no longer contradict the detailed
   timeline on the order page. Shows a proper empty state when there are no
   orders.

7. **Working promo code.** `HEALTH30` (advertised on the dashboard) now applies
   a real 30% discount end-to-end: `Cart::PROMOS`, `Cart::discount()`, the
   cart summary discount line, and correct total. Invalid codes show an error.
   Also fixes a latent bug where the cart view used `$delivery`/`$tax` that the
   controller never passed.

8. **Honest "Next Refill".** No longer a fake `+13 days` date; shows a real
   value if present, otherwise an em dash.

9. **Accessibility & visual polish (CSS):**
   - `:focus-visible` outlines for keyboard users on links, buttons, inputs.
   - Fixed `.ps-badge-rx` contrast (was white-on-amber, ~1.9:1 — failed WCAG).
   - Cards get a subtle resting shadow for depth (not only on hover).
   - Empty-state icons use `--ps-text-muted` (were near-invisible border grey).

## Refactor (reusable partials)

- `partials/stat-card.php` — dashboard stat tiles.
- `partials/tracking-steps.php` — horizontal order-progress widget.
- `partials/tracking-timeline.php` — vertical order timeline (now reused by
  `order/show.php` instead of an inline copy).

## Note for the DB migration

When wiring the real database, `prescriptions.file_path` should store the
**filename only**; keep files in `/storage/prescriptions/` and keep serving
them through the authenticated route.

## Product images

All 6 places that previously used `placehold.co` (medicine cards, cart rows,
saved-for-later, alternates modal, prescription-status alternates, product
page + thumbnails) now go through `medicine_image()` in `config/helpers.php`.

- Every medicine in `Medicine::seed()` got an `'image' => 'slug.jpg'` key.
- Drop real files into `public/assets/img/medicines/` using the exact
  filenames listed in that folder's `README.md` — no code changes needed.
- Until a file exists, each `<img>` has an `onerror` fallback to
  `_placeholder.svg` (a local generic pill icon, no external network call),
  so nothing shows a broken-image icon while you're still adding photos.
