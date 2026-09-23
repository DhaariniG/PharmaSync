            <!-- Settings Layout Container Split Grid -->
            <div class="settings-grid">

                <!-- Left Column: Navigation Section Tabs -->
                <div class="settings-nav-stack">
                    <div class="card nav-tabs-card">
                        <a href="<?= url('/pharmacist/settings?tab=account') ?>" class="tab-link <?= $active_tab === 'account' ? 'active' : '' ?>">
                            <i data-lucide="user"></i> Account Profile
                        </a>
                        <a href="<?= url('/pharmacist/settings?tab=security') ?>" class="tab-link <?= $active_tab === 'security' ? 'active' : '' ?>">
                            <i data-lucide="shield-check"></i> Security & Password
                        </a>
                        <a href="<?= url('/pharmacist/settings?tab=notifications') ?>" class="tab-link <?= $active_tab === 'notifications' ? 'active' : '' ?>">
                            <i data-lucide="bell-ring"></i> Notifications Settings
                        </a>
                    </div>
                </div>

                <!-- Right Column: the panel for the open tab -->
                <div class="settings-form-stack">
                    <form action="<?= url('/pharmacist/settings') ?>" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="tab" value="<?= e($active_tab) ?>">

                        <?php
                        // $active_tab is checked against a fixed list in the controller,
                        // so only these three files can ever be included.
                        require __DIR__ . '/tabs/' . $active_tab . '.html';
                        ?>

                        <!-- Global Footer Action Submission Buttons Row -->
                        <div class="settings-action-bar">
                            <button type="button" class="btn btn-cancel" onclick="window.location.reload();">Cancel Adjustments</button>
                            <button type="submit" class="btn btn-save"><i data-lucide="save"></i> Save System Settings</button>
                        </div>
                    </form>
                </div>

            </div>
