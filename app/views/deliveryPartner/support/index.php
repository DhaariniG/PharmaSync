<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Support</title>
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/tokens.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/chrome.css') ?>" />
<link rel="stylesheet" href="<?= asset('assets/css/DeliveryPartner/pages.css') ?>" />
</head>
<body data-page="support" data-page-title="Support" data-user-name="<?= e($user['name'] ?? $user['full_name'] ?? 'Driver') ?>" data-user-role="On Shift" data-user-initials="AR">

<div class="app">
  <div id="sidebar-root"><?php require __DIR__ . '/../partials/sidebar.php'; ?></div>
  <div class="main">
    <div id="topbar-root"><?php require __DIR__ . '/../partials/topbar.php'; ?></div>

    <main class="content">

      <div class="page-header-row">
        <div>
          <h2 class="page-heading">Support</h2>
          <p class="page-subheading">Get help with deliveries, payments, or your account.</p>
        </div>
      </div>

      <div class="support-grid">
        <div class="support-card">
          <div class="icon-wrap"><svg viewBox="0 0 24 24"><path d="M3 5h18v14H3z"/><path d="M3 7l9 6 9-6"/></svg></div>
          <h3>Email Dispatch</h3>
          <p>dispatch@pharmasync.com &bull; Replies within 2 hours during shift hours.</p>
        </div>
        <div class="support-card">
          <div class="icon-wrap"><svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.13.96.36 1.9.68 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.91.32 1.85.55 2.81.68A2 2 0 0122 16.92z"/></svg></div>
          <h3>Call Dispatch</h3>
          <p>077-2932222 &bull; 24/7 for urgent, in-transit issues.</p>
        </div>
        <div class="support-card">
          <div class="icon-wrap"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M9.5 9a2.5 2.5 0 015 0c0 1.5-2.5 2-2.5 3.5M12 17h.01"/></svg></div>
          <h3>Help Center</h3>
          <p>Browse guides on deliveries, cold-chain handling, and payouts.</p>
        </div>
      </div>

      <form class="card" id="supportForm">
        <div class="card-header" style="padding:1.5rem 1.5rem 0;">
          <span class="card-title">Contact Dispatch</span>
        </div>
        <div class="form-panel form-grid">
          <div class="form-group">
            <label for="topic">Topic</label>
            <select id="topic">
              <option>Delivery issue</option>
              <option>Payment / payout issue</option>
              <option>Account &amp; compliance</option>
              <option>App bug report</option>
              <option>Other</option>
            </select>
          </div>
          <div class="form-group">
            <label for="orderRef">Related Order ID (optional)</label>
            <input type="text" id="orderRef" placeholder="e.g. PH-2024-8841" />
          </div>
          <div class="form-group full">
            <label for="message">Message</label>
            <textarea id="message" placeholder="Describe the issue..." required></textarea>
          </div>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn-primary">Send Message</button>
        </div>
      </form>

    </main>

    <footer class="app-footer">
      <div class="footer-left">
        <span class="footer-dot"></span>
        <span>PharmaSync Driver Logistics &bull; System status: All services operational</span>
      </div>
      <div class="footer-links">
        <a href="<?= url('/deliveryPartner/privacy') ?>">Privacy</a>
        <a href="<?= url('/deliveryPartner/terms') ?>">Terms</a>
        <a href="<?= url('/deliveryPartner/support') ?>">Contact Dispatch</a>
      </div>
    </footer>
  </div>
</div>

<script src="<?= asset('assets/js/delivery-partner.js') ?>"></script>
<script>
  document.getElementById('supportForm').addEventListener('submit', function (e) {
    e.preventDefault();
    showToast('Message sent to dispatch');
  });
</script>
</body>
</html>
