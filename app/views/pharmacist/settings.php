<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaSync - Settings</title>
    <!-- Settings Specific Stylesheet -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Pharmacist/settings.css">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <div class="container">
        <!-- Reusable Sidebar Component -->
        <?php include 'sidebar.php'; ?>

        <?php
        // Get the active tab from the URL parameter, default to 'account' if empty
        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'account';
        ?>

        <!-- Main Workspace Viewport -->
        <main class="main-content">
            <!-- Top App Header Section -->
            <?php 
                $pageTitle = "Settings"; 
                include APP_PATH . '/views/Pharmacist/header.php'; 
            ?>

            <!-- Settings Layout Container Split Grid -->
            <div class="settings-grid">
                
                <!-- Left Column: Navigation Section Tabs -->
                <div class="settings-nav-stack">
                    <div class="card nav-tabs-card">
                        <a href="Settings.php?tab=account" class="tab-link <?php echo ($active_tab == 'account') ? 'active' : ''; ?>">
                            <i data-lucide="user"></i> Account Profile
                        </a>
                        <a href="Settings.php?tab=security" class="tab-link <?php echo ($active_tab == 'security') ? 'active' : ''; ?>">
                            <i data-lucide="shield-check"></i> Security & Password
                        </a>
                        <a href="Settings.php?tab=notifications" class="tab-link <?php echo ($active_tab == 'notifications') ? 'active' : ''; ?>">
                            <i data-lucide="bell-ring"></i> Notifications Settings
                        </a>
                    </div>
                </div>

                <!-- Right Column: Dynamic Form Target -->
                <div class="settings-form-stack">
                    <form action="save_settings.php" method="POST">
                        
                        <?php 
                        // Conditionally render the correct form panel based on URL parameter
                        switch ($active_tab) {
                            case 'preferences':
                                include 'settingsTabs/preferences.html';
                                break;
                            case 'security':
                                include 'settingsTabs/security.html';
                                break;
                            case 'pharmacy':
                                include 'settingsTabs/pharmacy.html';
                                break;
                            case 'notifications':
                                include 'settingsTabs/notifications.html';
                                break;
                            case 'account':
                            default:
                                include 'settingsTabs/account.html';
                                break;
                        }
                        ?>

                        <!-- Global Footer Action Submission Buttons Row -->
                        <div class="settings-action-bar">
                            <button type="button" class="btn btn-cancel" onclick="window.location.reload();">Cancel Adjustments</button>
                            <button type="submit" class="btn btn-save"><i data-lucide="save"></i> Save System Settings</button>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>