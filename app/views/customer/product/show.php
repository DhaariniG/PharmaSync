<?php $rating = Medicine::ratingFor($medicine['id']); ?>
<nav aria-label="breadcrumb">
  <ol class="crumbs small">
    <li class="crumb"><a href="<?= BASE_URL ?>/">Home</a></li>
    <li class="crumb"><a href="<?= BASE_URL ?>/catalog">Medicines</a></li>
    <li class="crumb"><a href="<?= BASE_URL ?>/catalog?category=<?= $medicine['category_id'] ?>"><?= htmlspecialchars(Medicine::categoryName($medicine['category_id'])) ?></a></li>
    <li class="crumb active"><?= htmlspecialchars($medicine['name']) ?></li>
  </ol>
</nav>

<div class="row g-4">
  <div class="col-md-5">
    <?php
      // All images for this medicine (main first, then any extra angles).
      $gallery = medicine_gallery($medicine);
      $placeholder = BASE_URL . '/assets/images/medicines/_placeholder.svg';
    ?>
    <div class="ps-card p-3 mb-2">
      <img id="psMainImage" src="<?= $gallery[0] ?>" class="w-100 rounded" style="height:360px;object-fit:cover;" alt="<?= htmlspecialchars($medicine['name']) ?>" onerror="this.onerror=null;this.src='<?= $placeholder ?>';">
    </div>
    <?php if (count($gallery) > 1): ?>
      <div class="flex gap-2" id="psThumbs">
        <?php foreach ($gallery as $i => $src): ?>
          <button type="button" class="ps-card p-1 border-0 ps-thumb<?= $i === 0 ? ' active' : '' ?>" style="width:70px;height:70px;cursor:pointer;" data-full="<?= $src ?>" aria-label="View image <?= $i + 1 ?> of <?= htmlspecialchars($medicine['name']) ?>">
            <img src="<?= $src ?>" class="w-100 h-100 rounded" style="object-fit:cover;" alt="<?= htmlspecialchars($medicine['name']) ?> view <?= $i + 1 ?>" onerror="this.onerror=null;this.src='<?= $placeholder ?>';">
          </button>
        <?php endforeach; ?>
      </div>
      <script>
        (function () {
          var main = document.getElementById('psMainImage');
          var thumbs = document.querySelectorAll('#psThumbs .ps-thumb');
          thumbs.forEach(function (t) {
            t.addEventListener('click', function () {
              main.src = t.dataset.full;
              thumbs.forEach(function (o) { o.classList.remove('active'); });
              t.classList.add('active');
            });
          });
        })();
      </script>
    <?php endif; ?>
  </div>
  <div class="col-md-7">
    <div class="flex gap-2 mb-2">
      <span class="tag <?= $medicine['requires_rx'] ? 'ps-badge-rx' : 'ps-badge-otc' ?>">
        <?= $medicine['requires_rx'] ? 'Prescription Required' : 'Over the Counter' ?>
      </span>
      <span class="tag <?= $medicine['stock'] > 0 ? 'ps-badge-otc' : 'bg-secondary' ?>"><?= $medicine['stock'] > 0 ? 'In Stock' : 'Out of Stock' ?></span>
    </div>
    <h3 class="bold mb-1"><?= htmlspecialchars($medicine['name']) ?></h3>
    <p class="muted mb-2">Manufacturer: <a href="#"><?= htmlspecialchars($medicine['manufacturer']) ?></a></p>
    <div class="mb-2 small">
      <?php $full = floor($rating['stars']); for ($i = 0; $i < 5; $i++): ?>
        <?= icon('star', $i < $full ? 'text-warning' : 'muted') ?>
      <?php endfor; ?>
      <span class="semibold ms-1"><?= number_format($rating['stars'], 1) ?>/5</span>
      <span class="muted">(<?= $rating['count'] ?> Reviews)</span>
    </div>
    <div class="size-3 ps-price mb-3">Rs. <?= number_format($medicine['price'], 2) ?></div>

    <?php if ($medicine['stock'] > 0): ?>
      <?php if ($medicine['requires_rx']): ?>
        <div class="note note-warn flex middle gap-2 mb-3">
          <?= icon('scroll-text') ?>
          <div class="small">This medicine requires a valid prescription. Upload one and a pharmacist will review, prepare, and confirm your order before it's added to your cart.</div>
        </div>

        <form method="GET" action="<?= BASE_URL ?>/prescription/upload" class="flex middle gap-3 wrap mb-3">
          <input type="hidden" name="medicine_id" value="<?= $medicine['id'] ?>">
          <div class="flex middle border rounded" data-qty-stepper>
            <button type="button" class="btn btn-sm" data-qty-minus><?= icon('minus') ?></button>
            <input type="number" name="quantity" value="1" min="1" max="<?= $medicine['stock'] ?>" class="field border-0 text-center" style="width:60px;">
            <button type="button" class="btn btn-sm" data-qty-plus><?= icon('plus') ?></button>
          </div>
          <button type="submit" class="btn btn-ps-primary px-4"><?= icon('scroll-text', 'me-2') ?>Upload Prescription</button>
        </form>
        <div class="flex gap-4 small muted">
          <span><?= icon('shield-check', 'text-success me-1') ?>Authentic Product</span>
          <span><?= icon('truck', 'text-success me-1') ?>Delivered by our own staff</span>
        </div>
      <?php else: ?>
        <form method="POST" action="<?= BASE_URL ?>/cart/add" class="flex middle gap-3 wrap mb-3" data-validate>
            <?= csrf_field() ?>
          <input type="hidden" name="medicine_id" value="<?= $medicine['id'] ?>">
          <div class="flex middle border rounded" data-qty-stepper>
            <button type="button" class="btn btn-sm" data-qty-minus><?= icon('minus') ?></button>
            <input type="number" name="quantity" value="1" min="1" max="<?= $medicine['stock'] ?>" class="field border-0 text-center" style="width:60px;">
            <button type="button" class="btn btn-sm" data-qty-plus><?= icon('plus') ?></button>
          </div>
          <button type="submit" class="btn btn-ps-outline px-4"><?= icon('shopping-cart', 'me-2') ?>Add to Cart</button>
          <button type="submit" name="buy_now" value="1" class="btn btn-ps-primary px-4">Buy Now</button>
        </form>
        <div class="flex gap-4 small muted">
          <span><?= icon('shield-check', 'text-success me-1') ?>Authentic Product</span>
          <span><?= icon('truck', 'text-success me-1') ?>Delivered by our own staff</span>
        </div>
      <?php endif; ?>
    <?php else: ?>
      <div class="note note-plain flex middle gap-2">
        <?= icon('package-open') ?>
        <div>Currently out of stock.</div>
      </div>
      <button type="button" class="btn btn-ps-primary" data-open="#altModalPD<?= $medicine['id'] ?>"><?= icon('shuffle', 'me-2') ?>See Alternatives</button>
      <?php
        $modalId = 'altModalPD' . $medicine['id'];
        $original = $medicine;
        $alternates = (new Medicine())->alternatesFor($medicine['id']);
        require __DIR__ . '/../partials/alternative-modal.php';
      ?>
    <?php endif; ?>
  </div>
</div>

<div class="row g-4 mt-1">
  <div class="col-lg-8 ps-tab-group">
    <ul class="nav ps-tabs mb-3" role="tablist">
      <li class="nav-item"><button class="nav-link active" data-tab="#tabDesc" type="button">Description</button></li>
      <li class="nav-item"><button class="nav-link" data-tab="#tabUsage" type="button">Usage Guide</button></li>
      <li class="nav-item"><button class="nav-link" data-tab="#tabReviews" type="button">Reviews</button></li>
    </ul>
    <div class="tab-content">
      <div class="ps-tab-pane active" id="tabDesc">
        <h6 class="bold">Product Overview</h6>
        <p><?= htmlspecialchars($medicine['description']) ?></p>
        <div class="row g-2">
          <div class="col-md-6">
            <div class="ps-card p-3">
              <div class="small muted bold mb-1"><?= icon('flask-conical', 'me-1') ?>Ingredients</div>
              <div class="small"><?= htmlspecialchars($medicine['name']) ?>, standard excipients.</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="ps-card p-3">
              <div class="small muted bold mb-1"><?= icon('triangle-alert', 'me-1') ?>Side Effects</div>
              <div class="small">Common: nausea, mild drowsiness. Consult your physician if symptoms persist.</div>
            </div>
          </div>
        </div>
      </div>
      <div class="ps-tab-pane" id="tabUsage">
        <p class="muted">Take as directed by your pharmacist or physician. Do not exceed the recommended dose. Store in a cool, dry place away from direct sunlight.</p>
      </div>
      <div class="ps-tab-pane" id="tabReviews">
        <p class="muted">Average rating <?= number_format($rating['stars'], 1) ?>/5 from <?= $rating['count'] ?> customers.</p>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="ps-card p-4 mb-3">
      <h6 class="bold mb-3">Quick Details</h6>
      <div class="flex between small mb-2"><span class="muted">Storage</span><span>Cool, Dry Place</span></div>
      <div class="flex between small mb-2"><span class="muted">Dosage Form</span><span>Tablet/Capsule</span></div>
      <div class="flex between small"><span class="muted">Pack Size</span><span>As listed</span></div>
    </div>
    <div class="ps-banner p-4 text-center">
      <h6 class="bold mb-1">Need a Refill?</h6>
      <p class="small mb-3">Set up an automatic refill and save 5% on your next order.</p>
      <a href="<?= BASE_URL ?>/catalog?category=<?= $medicine['category_id'] ?>" class="btn btn-plain btn-sm">Enable Refill</a>
    </div>
  </div>
</div>

<?php if (!empty($related)): ?>
  <h5 class="ps-section-title mt-5 mb-3">Related <?= htmlspecialchars(Medicine::categoryName($medicine['category_id'])) ?></h5>
  <div class="row g-3">
    <?php foreach ($related as $m): ?>
      <div class="col-6 col-md-3">
        <?php require __DIR__ . '/../partials/medicine-card.php'; ?>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
