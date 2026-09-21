<!-- ============ MAIN CONTENT ============ -->
<main class="main-content">

   
    <div class="content-area">

        <h1 class="page-title">Profile</h1>
        <p class="page-subtitle">Manage your account information and settings</p>

        <div class="avatar-row">
            <div class="avatar-large">IM</div>
            <div class="avatar-row-right">
                <button type="button" class="btn-change-photo">
                    <?= icon('camera', 'material-symbols-outlined', 'font-size:18px;') ?>
                    Change Photo
                </button>
                <span class="avatar-hint">JPG, GIF or PNG. Max size of 800K</span>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="form-card">
            <div class="card-header">
                <?= icon('id-card', 'material-symbols-outlined') ?>
                <h3>Personal Information</h3>
            </div>
            <form>
                <div class="form-grid-2col">
                    <div class="form-field">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" value="IM User" placeholder="Enter your full name">
                    </div>
                    <div class="form-field">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="im.user@pharmasync.lk" placeholder="name@company.com">
                    </div>
                    <div class="form-field">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="+94 77 123 4567" placeholder="+XX XXXXXXXX">
                    </div>
                    <div class="form-field">
                        <label for="role">Role</label>
                        <div class="field-with-icon">
                            <input type="text" id="role" name="role" value="Inventory Manager" disabled>
                            <?= icon('lock', 'material-symbols-outlined') ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Change Password -->
        <div class="form-card">
            <div class="card-header">
                <?= icon('lock', 'material-symbols-outlined') ?>
                <h3>Change Password</h3>
            </div>
            <form>
                <div class="form-grid-3col">
                    <div class="form-field">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" placeholder="••••••••">
                    </div>
                    <div class="form-field">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" placeholder="••••••••">
                    </div>
                    <div class="form-field">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••">
                    </div>
                </div>
            </form>
        </div>

        <div class="form-footer">
            <button type="button" class="btn-cancel">Cancel</button>
            <button type="button" class="btn-save">Save Changes</button>
        </div>

    </div>
</main>
