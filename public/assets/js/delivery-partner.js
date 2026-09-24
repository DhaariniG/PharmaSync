/**
 * Loads the shared sidebar + topbar partials into every page and applies
 * per-page state (active nav item, signed-in user block).
 *
 * Usage on a page:
 *   <body data-page="dashboard" data-user-name="Admin User"
 *         data-user-role="Administrator" data-user-initials="AU">
 *   <script src="js/partials.js"></script>
 */
async function loadPartial(url, mountId) {
  const res = await fetch(url);
  const html = await res.text();
  document.getElementById(mountId).innerHTML = html;
}

function applyPageState() {
  const body = document.body;
  const page = body.dataset.page;
  const name = body.dataset.userName || 'Admin User';
  const role = body.dataset.userRole || 'Administrator';
  const initials = body.dataset.userInitials || 'AU';

  // Highlight active sidebar link
  document.querySelectorAll('.sidebar-nav a').forEach((a) => {
    a.classList.toggle('active', a.dataset.nav === page);
  });

  // Sidebar identity block
  const sName = document.getElementById('sidebarUserName');
  const sRole = document.getElementById('sidebarUserRole');
  const sInit = document.getElementById('sidebarAvatarInitials');
  if (sName) sName.textContent = name;
  if (sRole) sRole.textContent = role;
  if (sInit) sInit.textContent = initials;

  // Topbar identity block
  const tName = document.getElementById('topbarUserName');
  const tRole = document.getElementById('topbarUserRole');
  const tInit = document.getElementById('topbarAvatarInitials');
  if (tName) tName.textContent = name;
  if (tRole) tRole.textContent = role.toUpperCase();
  if (tInit) tInit.textContent = initials;

  // Topbar page title, e.g. <body data-page="deliveries" data-page-title="Deliveries">
  const titleEl = document.getElementById('topbarPageTitle');
  const pageTitle = body.dataset.pageTitle;
  if (titleEl && pageTitle) titleEl.textContent = pageTitle;
}

document.addEventListener('DOMContentLoaded', async () => {
  await Promise.all([
    Promise.resolve(), // Partials are rendered by PHP on authenticated pages.
  ]);
  applyPageState();
  initSidebarToggle();
  initDeliveryTabs();
  initDropdowns();
  if (window.lucide) lucide.createIcons();
});

/**
 * Shared toast helper. Usage: showToast('Account created successfully');
 * Optionally redirect after showing: showToast('Saved', 'accounts.php');
 *
 * Uses an inline SVG checkmark rather than an icon font/library so it
 * renders correctly on pages that don't load lucide (e.g. Delivery Partner).
 */
function showToast(message, redirectTo, delay = 1100) {
  let toast = document.getElementById('sharedToast');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'sharedToast';
    toast.className = 'toast';
    toast.innerHTML = '<svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg><span id="sharedToastMsg"></span>';
    document.body.appendChild(toast);
  }
  document.getElementById('sharedToastMsg').textContent = message;
  requestAnimationFrame(() => toast.classList.add('show'));
  if (redirectTo) {
    setTimeout(() => { window.location.href = redirectTo; }, delay);
  } else {
    setTimeout(() => toast.classList.remove('show'), 2200);
  }
}

/** Reads a query param from the current URL, e.g. getParam('id') */
function getParam(name) {
  return new URLSearchParams(window.location.search).get(name);
}

/**
 * Wires up the collapsible sidebar (mobile menu toggle + scrim) used by
 * roles like Delivery Partner. Safe no-op on pages without these elements
 * (e.g. Admin, which has no #menuToggle/#sidebarScrim).
 */
function initSidebarToggle() {
  const sidebar = document.getElementById('sidebar');
  const scrim = document.getElementById('sidebarScrim');
  const toggle = document.getElementById('menuToggle');
  if (!sidebar || !scrim || !toggle) return;

  const openSidebar = () => { sidebar.classList.add('open'); scrim.classList.add('show'); };
  const closeSidebar = () => { sidebar.classList.remove('open'); scrim.classList.remove('show'); };

  toggle.addEventListener('click', () => {
    sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
  });
  scrim.addEventListener('click', closeSidebar);
}

/**
 * Wires up any ".dropdown-toggle" button to open/close its sibling
 * ".dropdown-menu" (used by the "more options" row menus). Closes any
 * open menu when clicking elsewhere on the page. Safe no-op when no
 * dropdown triggers are present.
 */
function initDropdowns() {
  const toggles = document.querySelectorAll('.dropdown-toggle');
  if (!toggles.length) return;

  function closeAll(except) {
    document.querySelectorAll('.dropdown-menu.open').forEach((m) => {
      if (m !== except) m.classList.remove('open');
    });
  }

  toggles.forEach((btn) => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      const menu = btn.parentElement.querySelector('.dropdown-menu');
      if (!menu) return;
      const willOpen = !menu.classList.contains('open');
      closeAll();
      menu.classList.toggle('open', willOpen);
    });
  });

  document.addEventListener('click', () => closeAll());
}

/**
 * Filters a delivery table's rows by data-status when All/Pending/In
 * Transit style tabs are present. Safe no-op on pages without
 * #deliveryTabs.
 */
function initDeliveryTabs() {
  const tabs = document.getElementById('deliveryTabs');
  if (!tabs) return;

  const buttons = tabs.querySelectorAll('.tab-btn');
  const rows = document.querySelectorAll('table tbody tr[data-status]');

  buttons.forEach((btn) => {
    btn.addEventListener('click', () => {
      buttons.forEach((b) => b.classList.remove('active'));
      btn.classList.add('active');

      const filter = btn.dataset.filter;
      rows.forEach((row) => {
        const status = row.dataset.status;
        let show = true;
        if (filter === 'pending') show = status === 'pending';
        if (filter === 'transit') show = status === 'transit';
        row.style.display = show ? '' : 'none';
      });
    });
  });
}