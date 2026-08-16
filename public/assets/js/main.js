// PharmaSync — Customer module global JS (vanilla, no external libs)

document.addEventListener('DOMContentLoaded', function () {
  // Auto-dismiss alerts after 4s
  document.querySelectorAll('.note[data-autohide]').forEach(function (el) {
    setTimeout(function () {
      el.classList.remove('show');
      el.classList.add('hidden');
    }, 4000);
  });

  // Quantity steppers (used on cart + product pages)
  document.querySelectorAll('[data-qty-stepper]').forEach(function (stepper) {
    var input = stepper.querySelector('input[type="number"]');
    var minus = stepper.querySelector('[data-qty-minus]');
    var plus = stepper.querySelector('[data-qty-plus]');
    if (!input) return;

    function clamp(val) {
      var min = parseInt(input.min || '1', 10);
      var max = parseInt(input.max || '99', 10);
      return Math.max(min, Math.min(max, val));
    }

    if (minus) minus.addEventListener('click', function () {
      input.value = clamp((parseInt(input.value, 10) || 1) - 1);
      // { bubbles: true } is required so this synthetic event bubbles up to
      // the parent <form onchange="this.submit()"> — without it, clicking
      // the +/- buttons updated the input's value but never submitted the
      // form, so cart quantity changes silently did nothing.
      input.dispatchEvent(new Event('change', { bubbles: true }));
    });
    if (plus) plus.addEventListener('click', function () {
      input.value = clamp((parseInt(input.value, 10) || 1) + 1);
      input.dispatchEvent(new Event('change', { bubbles: true }));
    });
  });

  // Show validation messages when a form is submitted
  document.querySelectorAll('form[data-validate]').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
      }
      form.classList.add('was-validated');
    });
  });

  // File input preview / filename display (prescription upload)
  document.querySelectorAll('input[type="file"][data-file-preview]').forEach(function (input) {
    input.addEventListener('change', function () {
      var label = document.querySelector(input.dataset.filePreview);
      if (label) {
        label.textContent = input.files.length ? input.files[0].name : 'No file chosen';
      }
    });
  });

  // Pop-ups: a button with data-open="#id" opens that .ps-modal. Clicking the
  // dark background, a [data-close] button, or Escape closes it.
  document.querySelectorAll('[data-open]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var modal = document.querySelector(btn.getAttribute('data-open'));
      if (modal) modal.classList.add('open');
    });
  });
  document.querySelectorAll('.ps-modal').forEach(function (modal) {
    modal.addEventListener('click', function (e) {
      if (e.target === modal || e.target.closest('[data-close]')) {
        modal.classList.remove('open');
      }
    });
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      document.querySelectorAll('.ps-modal.open').forEach(function (m) { m.classList.remove('open'); });
    }
  });

  // Dropdown menu: click the toggle to open, click anywhere else to close.
  document.querySelectorAll('[data-menu-toggle]').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      btn.closest('.ps-menu').classList.toggle('open');
    });
  });
  document.addEventListener('click', function () {
    document.querySelectorAll('.ps-menu.open').forEach(function (m) { m.classList.remove('open'); });
  });

  // Dismissable alerts (the little X)
  document.querySelectorAll('[data-dismiss-alert]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var alert = btn.closest('.note');
      if (alert) alert.remove();
    });
  });

  // Tabs: click a tab button to show its panel (product page).
  document.querySelectorAll('[data-tab]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var group = btn.closest('.ps-tab-group');
      group.querySelectorAll('[data-tab]').forEach(function (b) { b.classList.remove('active'); });
      group.querySelectorAll('.ps-tab-pane').forEach(function (p) { p.classList.remove('active'); });
      btn.classList.add('active');
      var pane = group.querySelector(btn.getAttribute('data-tab'));
      if (pane) pane.classList.add('active');
    });
  });
});
