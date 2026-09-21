# Icons

These are individual SVG files from **Lucide** (https://lucide.dev),
version 1.31.0, used under the **ISC License**.

    Copyright (c) 2026 Lucide Icons and Contributors

    Permission to use, copy, modify, and/or distribute this software for any
    purpose with or without fee is hereby granted, provided that the above
    copyright notice and this permission notice appear in all copies.

Lucide is itself a fork of Feather Icons (MIT, © 2013-2022 Cole Bemis).

## Why the files are committed here

The project rules don't allow external frameworks, libraries or CDN links.
These are not a library — there is no icon font and no JavaScript. They are
plain SVG image files sitting in the repo, exactly like the product photos in
`assets/img/`, and the page inlines them server-side.

## How to use one

Call the `icon()` helper from `config/helpers.php`:

    <?= icon('truck') ?>                          <!-- plain -->
    <?= icon('truck', 'me-2 text-success') ?>     <!-- extra CSS classes -->
    <?= icon('pill', 'size-5', 'color: red') ?>   <!-- inline style -->

The name is the filename without `.svg`. An unknown name renders nothing
rather than a broken box, so a typo can never break a page.

## Sizing and colour

Each SVG uses `stroke="currentColor"`, so it takes the colour of the
surrounding text. `.lucide` in `style.css` sets `width/height: 1em`, so the
existing `size-1` ... `size-6` classes (and any inline `font-size`) control
the size, the same way the old icon font worked.

## Adding a new icon

1. Find it at https://lucide.dev and download the SVG.
2. Save it here using the lucide name, e.g. `syringe.svg`.
3. Use it: `<?= icon('syringe') ?>`

Keep only the icons the project actually uses — don't commit all 2,000.
