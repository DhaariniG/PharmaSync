<?php
/**
 * Public landing page - the first page anyone sees at the site address.
 *
 * Open to guests. Shop links (catalog, product, cart) work without an
 * account; "Upload a prescription" goes through the login page and comes
 * back to the upload form afterwards.
 *
 * Variables: $user (null for guests), $categories, $featured, $cartCount, $flash
 */
$user      = $user ?? null;
$flash     = $flash ?? [];
$cartCount = (int) ($cartCount ?? 0);
$auth      = '/' . AUTH_SLUG;

// One icon per catalog category (ids from CustomerMedicine::categories()).
$categoryIcons = [
    1 => 'pill',
    2 => 'thermometer',
    3 => 'leaf',
    4 => 'flask-conical',
    5 => 'droplet',
    6 => 'activity',
    7 => 'baby',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PharmaSync Pharmacy - medicines, prescriptions and delivery in Colombo</title>
    <meta name="description" content="Browse medicines, upload a prescription for a pharmacist to check, and get it delivered or ready for pickup at <?= e(STORE_ADDRESS) ?>.">
    <link rel="stylesheet" href="<?= role_css('Customer', 'style.css') ?>">
    <link rel="stylesheet" href="<?= role_css('Customer', 'landing.css') ?>">
</head>
<body class="lp">

<a class="lp-skip" href="#main">Skip to content</a>

<!-- ============ Top bar ============ -->
<header class="lp-nav">
    <div class="lp-wrap lp-nav-inner">
        <a href="<?= url('/') ?>" class="lp-brand" aria-label="PharmaSync home">
            <span class="lp-brand-mark"><?= icon('plus') ?></span>
            PharmaSync
        </a>

        <nav class="lp-nav-links" aria-label="Main">
            <a href="<?= url('/customer/catalog') ?>">Medicines</a>
            <a href="<?= url('/customer/prescription/upload') ?>">Upload a prescription</a>
        </nav>

        <div class="lp-nav-actions">
            <a href="<?= url('/customer/cart') ?>" class="lp-cart" aria-label="Cart, <?= $cartCount ?> item<?= $cartCount === 1 ? '' : 's' ?>">
                <?= icon('shopping-cart') ?>
                <?php if ($cartCount > 0): ?><span class="lp-cart-count"><?= $cartCount ?></span><?php endif; ?>
            </a>
            <?php if ($user): ?>
                <a href="<?= url('/customer/dashboard') ?>" class="btn btn-ps-primary btn-sm">My dashboard</a>
            <?php else: ?>
                <a href="<?= url($auth . '/login') ?>" class="btn btn-ps-outline btn-sm">Log in</a>
                <a href="<?= url($auth . '/register') ?>" class="btn btn-ps-primary btn-sm lp-hide-sm">Create account</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<?php foreach ($flash as $type => $message): ?>
    <div class="lp-flash lp-flash-<?= $type === 'error' ? 'error' : 'ok' ?>" role="status"><?= e($message) ?></div>
<?php endforeach; ?>

<main id="main">

    <!-- ============ Hero ============ -->
    <section class="lp-hero">
        <div class="lp-wrap lp-hero-grid">
            <div class="lp-hero-copy">
                <h1>Order your medicines online. A&nbsp;pharmacist checks every prescription.</h1>
                <p class="lp-lead">
                    Browse and fill your cart without an account. Send us your prescription
                    and a pharmacist reviews it before anything is packed. Have it delivered,
                    or collect it from <?= e(STORE_ADDRESS) ?>.
                </p>

                <form class="lp-search" action="<?= url('/customer/search') ?>" method="get" role="search">
                    <label for="lp-q" class="lp-sr">Search medicines</label>
                    <?= icon('search', 'lp-search-icon') ?>
                    <input id="lp-q" type="search" name="q" placeholder="Search medicines, e.g. Paracetamol" autocomplete="off">
                    <button type="submit" class="btn btn-ps-primary">Search</button>
                </form>

                <div class="lp-hero-actions">
                    <a href="<?= url('/customer/catalog') ?>" class="lp-link-strong">Browse all medicines</a>
                    <span class="lp-or">or</span>
                    <a href="<?= url('/customer/prescription/upload') ?>" class="lp-link-strong"><?= icon('file-up') ?>Upload a prescription</a>
                </div>
            </div>

            <!-- The prescription slip: shows the real journey of an order -->
            <figure class="lp-slip" aria-label="Example prescription moving through review">
                <div class="lp-slip-paper">
                    <div class="lp-slip-head">
                        <span class="lp-rx">R<span>x</span></span>
                        <div>
                            <div class="lp-slip-title">Prescription #504</div>
                            <div class="lp-slip-sub">Uploaded today, 9:12 am</div>
                        </div>
                    </div>

                    <ul class="lp-slip-lines">
                        <li><span>Amoxicillin 500mg</span><span>21 caps</span></li>
                        <li><span>Paracetamol 500mg</span><span>20 tabs</span></li>
                        <li><span>Cetirizine 10mg</span><span>10 tabs</span></li>
                    </ul>

                    <ol class="lp-slip-steps">
                        <li class="done"><?= icon('check') ?><span>Uploaded</span></li>
                        <li class="done"><?= icon('check') ?><span>Pharmacist checked</span></li>
                        <li class="now"><?= icon('package') ?><span>Being packed</span></li>
                    </ol>

                    <div class="lp-stamp" aria-hidden="true">
                        <span>Checked</span>
                        <small>Pharmacist</small>
                    </div>
                </div>
                <figcaption class="lp-slip-note">
                    <?= icon('shuffle') ?>
                    Out of stock? The pharmacist suggests an approved alternative and you decide.
                </figcaption>
            </figure>
        </div>
    </section>

    <!-- ============ Categories ============ -->
    <section class="lp-cats" aria-labelledby="lp-cats-title">
        <div class="lp-wrap">
            <h2 id="lp-cats-title" class="lp-h2">Shop by need</h2>
            <ul class="lp-cat-list">
                <?php foreach ($categories as $id => $name): ?>
                    <li>
                        <a href="<?= url('/customer/catalog?category=' . (int) $id) ?>">
                            <span class="lp-cat-icon"><?= icon($categoryIcons[$id] ?? 'pill') ?></span>
                            <?= e($name) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>

    <!-- ============ Popular medicines ============ -->
    <section class="lp-popular" aria-labelledby="lp-pop-title">
        <div class="lp-wrap">
            <div class="lp-section-head">
                <h2 id="lp-pop-title" class="lp-h2">In stock now</h2>
                <a href="<?= url('/customer/catalog') ?>" class="lp-link-strong">See all medicines</a>
            </div>

            <div class="lp-cards">
                <?php foreach ($featured as $m): ?>
                    <div class="lp-card-cell"><?php require __DIR__ . '/../partials/medicine-card.php'; ?></div>
                <?php endforeach; ?>
            </div>

            <p class="lp-note">
                <?= icon('info') ?>
                <span>Medicines marked <strong>Rx Required</strong> need a prescription. You can add
                over-the-counter items to your cart right away.</span>
            </p>
        </div>
    </section>

    <!-- ============ How a prescription order works ============ -->
    <section class="lp-how" aria-labelledby="lp-how-title">
        <div class="lp-wrap">
            <h2 id="lp-how-title" class="lp-h2">How a prescription order works</h2>
            <ol class="lp-steps">
                <li>
                    <span class="lp-step-no">1</span>
                    <h3>Upload your prescription</h3>
                    <p>A clear photo or a PDF. You can order for yourself or a family member.</p>
                </li>
                <li>
                    <span class="lp-step-no">2</span>
                    <h3>A pharmacist checks it</h3>
                    <p>We confirm the medicines and doses. If something is out of stock, you get an approved alternative to accept or decline.</p>
                </li>
                <li>
                    <span class="lp-step-no">3</span>
                    <h3>Delivery or pickup</h3>
                    <p>Home delivery for <?= e(money(DELIVERY_FEE)) ?>, or free pickup about <?= (int) STORE_PICKUP_PREP_HOURS ?> hours after you order.</p>
                </li>
            </ol>
            <a href="<?= url('/customer/prescription/upload') ?>" class="btn btn-ps-primary lp-how-cta"><?= icon('file-up', 'me-2') ?>Upload a prescription</a>
        </div>
    </section>

    <!-- ============ Visit the store ============ -->
    <section class="lp-store" aria-labelledby="lp-store-title">
        <div class="lp-wrap lp-store-grid">
            <div>
                <h2 id="lp-store-title"><?= e(STORE_NAME) ?></h2>
                <p>Order online and collect at the counter, or just walk in.</p>
            </div>
            <dl class="lp-store-facts">
                <div><dt><?= icon('map-pin') ?>Address</dt><dd><?= e(STORE_ADDRESS) ?></dd></div>
                <div><dt><?= icon('clock') ?>Opening hours</dt><dd><?= e(str_replace(' | ', "\n", STORE_HOURS)) ?></dd></div>
                <div><dt><?= icon('phone') ?>Phone</dt><dd><a href="tel:<?= e(preg_replace('/[^0-9+]/', '', STORE_PHONE)) ?>"><?= e(STORE_PHONE) ?></a></dd></div>
            </dl>
        </div>
    </section>
</main>

<footer class="lp-footer">
    <div class="lp-wrap lp-footer-inner">
        <span>&copy; <?= date('Y') ?> PharmaSync. All rights reserved.</span>
        <span>Help: <a href="mailto:support@pharmasync.test">support@pharmasync.test</a></span>
        <a href="<?= url($auth . '/login') ?>" class="lp-staff">Staff login</a>
    </div>
</footer>

</body>
</html>
