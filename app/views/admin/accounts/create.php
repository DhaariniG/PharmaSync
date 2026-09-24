<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Create Account</title>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/tokens.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/chrome.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/pages.css" />
</head>

<body
    data-page="accounts"
    data-user-name="<?= htmlspecialchars($user['name'] ?? 'Admin User') ?>"
    data-user-role="Administrator"
    data-user-initials="AU"
>

<div class="app-shell">

    <div id="sidebar-root">
        <?php require APP_PATH . '/views/admin/partials/sidebar.php'; ?>
    </div>

    <div class="main-col">

        <div id="topbar-root">
            <?php require APP_PATH . '/views/admin/partials/topbar.php'; ?>
        </div>

        <main class="page-content">

            <a class="back-link" href="<?= BASE_URL ?>/admin/accounts">
                <i data-lucide="arrow-left"></i>
                Back to Accounts
            </a>

            <div class="page-head">
                <div>
                    <h1>Create New Account</h1>
                    <p>Add a new user to the PharmaSync system.</p>
                </div>
            </div>

            <?php require APP_PATH . '/views/admin/partials/flash.php'; ?>

            <?php if (!empty($error)): ?>
                <div style="
                    padding: 14px 18px;
                    margin-bottom: 20px;
                    background: #fee2e2;
                    color: #b91c1c;
                    border-radius: 8px;
                ">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form
                  method="POST"
                  action="<?= BASE_URL ?>/admin/accounts/create"
                  style="display:block !important; visibility:visible !important; opacity:1 !important; background:white; padding:30px; margin-top:20px;"
              >
                <?= csrf_field() ?>

                

                <div style="display:block !important; visibility:visible !important; opacity:1 !important;">
                

                    <div class="form-group">
                        <label for="full_name">Full Name *</label>

                        <input
                            type="text"
                            id="full_name"
                            name="full_name"
                            placeholder="e.g. Nadeesha Perera"
                            required
                        >
                    </div>


                    <div class="form-group">
                        <label for="role">Role *</label>

                        <select id="role" name="role" required>

                            <option value="">Select a role</option>

                            <option value="Customer">
                                Customer
                            </option>

                            <option value="Pharmacist">
                                Pharmacist
                            </option>

                            <option value="Admin">
                                Administrator
                            </option>

                            <option value="Inventory_Manager">
                                Inventory Manager
                            </option>

                            <option value="Delivery_Partner">
                                Delivery Partner
                            </option>

                        </select>
                    </div>


                    <div class="form-group">
                        <label for="email">Email Address *</label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="name@example.com"
                            required
                        >
                    </div>


                    <div class="form-group">
                        <label for="phone">Phone Number *</label>

                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            placeholder="+94 77 123 4567"
                            required
                        >
                    </div>


                    <div class="form-group full">
                        <label for="address">Address</label>

                        <input
                            type="text"
                            id="address"
                            name="address"
                            placeholder="Enter address"
                        >
                    </div>


                    <div class="form-group">
                        <label for="status">Initial Status *</label>

                        <select id="status" name="status" required>

                            <option value="Active">
                                Active
                            </option>

                            <option value="Inactive">
                                Inactive
                            </option>

                            <option value="Suspended">
                                Suspended
                            </option>

                        </select>
                    </div>


                    <div class="form-group">
                        <label for="password">Password *</label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            minlength="8"
                            required
                        >
                    </div>


                    <div class="form-group">
                        <label for="confirm_password">
                            Confirm Password *
                        </label>

                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            minlength="8"
                            required
                        >
                    </div>

                </div>


                <div class="form-actions">

                    <a
                        href="<?= BASE_URL ?>/admin/accounts"
                        class="btn btn-outline"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i data-lucide="user-plus"></i>
                        Create Account
                    </button>

                </div>

            </form>

        </main>

    </div>

</div>

<script src="https://unpkg.com/lucide@latest"></script>

<script>
    if (window.lucide) {
        lucide.createIcons();
    }
</script>

</body>
</html>