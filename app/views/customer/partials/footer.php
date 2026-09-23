    </main>

    <footer class="ps-footer py-3 px-4">
      <div class="flex wrap between">
        <span>&copy; <?= date('Y') ?> PharmaSync. All rights reserved.</span>
        <span>Need help? <a href="mailto:support@pharmasync.test">support@pharmasync.test</a></span>
      </div>
    </footer>
  </div>
</div>

<!-- No external JS: all interactivity lives in main.js. -->
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
<script>
  (function () {
    var sidebar = document.getElementById('psSidebar');
    var backdrop = document.getElementById('psSidebarBackdrop');
    var toggle = document.getElementById('psSidebarToggle');
    function close() {
      sidebar.classList.remove('ps-sidebar-open');
      backdrop.classList.remove('show');
    }
    if (toggle) {
      toggle.addEventListener('click', function () {
        sidebar.classList.toggle('ps-sidebar-open');
        backdrop.classList.toggle('show');
      });
    }
    if (backdrop) {
      backdrop.addEventListener('click', close);
    }
  })();
</script>
</body>
</html>
