/**
 * Loads the shared sidebar + topbar partials into every page and applies
 * per-page state (active nav item, signed-in user block).
 *
 * Usage on a page:
 *   <body data-page="dashboard" data-user-name="Admin User"
 *         data-user-role="Administrator" data-user-initials="AU">
 *   <script src="js/partials.js"></script>
 */


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
}

document.addEventListener('DOMContentLoaded', () => {
  applyPageState();

  if (window.lucide) {
    lucide.createIcons();
  }
});

/**
 * Shared toast helper. Usage: showToast('Account created successfully');
 * Optionally redirect after showing: showToast('Saved', 'accounts.php');
 */
function showToast(message, redirectTo, delay = 1100) {
  let toast = document.getElementById('sharedToast');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'sharedToast';
    toast.className = 'toast';
    toast.innerHTML = '<i data-lucide="check-circle"></i><span id="sharedToastMsg"></span>';
    document.body.appendChild(toast);
  }
  document.getElementById('sharedToastMsg').textContent = message;
  if (window.lucide) lucide.createIcons();
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

// Admin logout confirmation UI. Clicking a [data-logout-open] button shows the
// popup instead of submitting; the popup's Logout button submits the POST form.
document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('logoutModal');
  if (!modal) return;

  const closeLogoutModal = () => {
    modal.classList.remove('open');
    modal.setAttribute('aria-hidden', 'true');
  };

  document.querySelectorAll('[data-logout-open]').forEach((link) => {
    link.addEventListener('click', (event) => {
      event.preventDefault();
      modal.classList.add('open');
      modal.setAttribute('aria-hidden', 'false');
      const cancel = modal.querySelector('[data-logout-cancel]');
      if (cancel) cancel.focus();
    });
  });

  const cancel = modal.querySelector('[data-logout-cancel]');
  if (cancel) cancel.addEventListener('click', closeLogoutModal);

  modal.addEventListener('click', (event) => {
    if (event.target === modal) closeLogoutModal();
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && modal.classList.contains('open')) closeLogoutModal();
  });
});
