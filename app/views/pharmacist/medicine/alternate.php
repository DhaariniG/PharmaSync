<!-- Top Navbar Sub-header Toolbar -->
<div class="top-navbar">
    <a href="<?= url('/pharmacist/review-prescription') ?>" class="back-link">
        <i data-lucide="arrow-left"></i> Back to Review
    </a>

    <div class="header-right">
        <span class="badge-action-required">
            <span class="status-dot">•</span> Action Required
        </span>
        <button class="icon-btn"><i data-lucide="help-circle"></i></button>
    </div>
</div>

<h2 class="page-title">Alternative Medicine Review</h2>

<!-- Top Informational Notification Banner -->
<div class="info-banner">
    <i data-lucide="info" class="banner-icon"></i>
    <p>The selected medicines were checked against current inventory. One or more medicines are unavailable or have insufficient stock. Please review the suggested alternatives before continuing.</p>
</div>

<!-- Two-Column Grid Container -->
<div class="workspace-grid">
    
    <!-- LEFT COLUMN: Alternatives Stack -->
    <div class="alternatives-stack">
        <section class="card master-alternatives-card">
            <div class="card-section-header">
                <h3>Unavailable Medicines</h3>
                <span class="badge-count">2 ITEMS IDENTIFIED</span>
            </div>

            <!-- Medicine Item 1 Block: Panadol -->
            <div class="medicine-review-block">
                <div class="med-status-row">
                    <div>
                        <h4 class="med-title">Panadol 500mg Tablet</h4>
                        <p class="med-qty-label">Requested Qty: 20 Tablets</p>
                    </div>
                    <span class="badge-stock stock-out">OUT OF STOCK</span>
                </div>

                <div class="alternatives-section">
                    <h5>SUGGESTED ALTERNATIVES</h5>
                    
                    <label class="alt-option-card selected">
                        <div class="option-left">
                            <input type="radio" name="panadol-alt" checked>
                            <span class="alt-name">Paracetamol 500mg</span>
                        </div>
                        <span class="badge-recommended">RECOMMENDED</span>
                    </label>

                    <label class="alt-option-card">
                        <div class="option-left">
                            <input type="radio" name="panadol-alt">
                            <span class="alt-name">Tylenol Extra Strength</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Medicine Item 2 Block: Cetirizine -->
            <div class="medicine-review-block no-border">
                <div class="med-status-row">
                    <div>
                        <h4 class="med-title">Cetirizine 10mg</h4>
                        <p class="med-qty-label">Requested Qty: 30 Capsules</p>
                    </div>
                    <span class="badge-stock stock-insufficient">INSUFFICIENT STOCK (5/30)</span>
                </div>

                <div class="error-inline-banner">
                    <i data-lucide="triangle-alert"></i>
                    <span>No suitable alternative medicine found in current system inventory.</span>
                </div>

                <div class="local-actions-row">
                    <!-- Triggers Warning Modal for Demo -->
                    <button type="button" class="btn btn-secondary" onclick="openSafetyModal('issues')">
                        <i data-lucide="package-open"></i> Continue as Partial Order
                    </button>
                    <button type="button" class="btn btn-danger" onclick="this.closest('.medicine-review-block').style.display='none';">
                        <i data-lucide="trash-2"></i> Remove Medicine
                    </button>
                </div>
                
                <a href="<?= url('/pharmacist/prescriptions') ?>" class="btn-cancel-link">Cancel Processing</a>
            </div>
        </section>
    </div>

    <!-- RIGHT COLUMN: Metrics Sidebar -->
    <div class="metrics-sidebar">
        <section class="card impact-card">
            <h3>Order Impact</h3>
            
            <div class="impact-metrics-list">
                <div class="metric-row">
                    <span class="metric-label">Original Total</span>
                    <span class="metric-val text-dark">Rs. 400.00</span>
                </div>
                <div class="metric-row highlight-row">
                    <span class="metric-label">Updated Total</span>
                    <span class="metric-val text-teal">Rs. 350.00</span>
                </div>
            </div>

            <div class="summary-counters">
                <div class="counter-row">
                    <span>Substitutions Applied</span>
                    <span class="counter-num">1</span>
                </div>
                <div class="counter-row">
                    <span>Pending Resolution</span>
                    <span class="counter-num color-alert">1</span>
                </div>
            </div>

            <div style="margin-top: 20px; display: flex; flex-direction: column; gap: 8px;">
                <!-- Triggers Passed Check Modal -->
                <button type="button" class="btn btn-approve" onclick="openSafetyModal('passed')" style="width: 100%; justify-content: center;">
                    <i data-lucide="check-check"></i> Finalize Order (Passed Check)
                </button>
                
                <!-- Triggers Issues Found Modal -->
                <button type="button" class="btn btn-approve" onclick="openSafetyModal('issues')" style="width: 100%; justify-content: center; background-color: #0284c7;">
                    <i data-lucide="shield-alert"></i> Finalize Order (With Warnings)
                </button>
            </div>
        </section>
    </div>

</div>

<!-- ========================================== -->
<!-- MODAL 1: SAFETY CHECK PASSED               -->
<!-- ========================================== -->
<div id="safetyModalPassed" class="modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); z-index: 1000; align-items: center; justify-content: center;">
    <div class="modal-card" style="background: #ffffff; width: 100%; max-width: 480px; border-radius: 12px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
        
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
            <div style="width: 42px; height: 42px; border-radius: 50%; background: #14b8a6; display: flex; align-items: center; justify-content: center; color: #ffffff;">
                <i data-lucide="check" style="width: 24px; height: 24px; stroke-width: 3;"></i>
            </div>
            <div>
                <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 0;">Safety Check Passed</h3>
                <span style="font-size: 12px; color: #0d9488; font-weight: 600;">• All checks green</span>
            </div>
        </div>

        <div style="margin-bottom: 16px;">
            <label style="font-size: 10px; font-weight: 700; color: #64748b; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">DRUG INTERACTION CHECK</label>
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 12px 14px; border-radius: 8px; color: #166534; font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i data-lucide="check-circle-2" style="width: 16px; height: 16px;"></i> Success banner "No known interactions detected"
            </div>
        </div>

        <div style="margin-bottom: 24px;">
            <label style="font-size: 10px; font-weight: 700; color: #64748b; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">DOSAGE VERIFICATION</label>
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 12px 14px; border-radius: 8px; color: #166534; font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i data-lucide="check-circle-2" style="width: 16px; height: 16px;"></i> Success banner "All dosages within safe range"
            </div>
        </div>

        <div style="text-align: right;">
            <a href="<?= url('/pharmacist/sales/4/completed') ?>" class="btn btn-approve" style="padding: 10px 20px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                Confirm & Proceed <i data-lucide="arrow-right" style="width: 16px; height: 16px;"></i>
            </a>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL 2: SAFETY CHECK ISSUES FOUND         -->
<!-- ========================================== -->
<div id="safetyModalIssues" class="modal-overlay" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); z-index: 1000; align-items: center; justify-content: center;">
    <div class="modal-card" style="background: #ffffff; width: 100%; max-width: 520px; border-radius: 12px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); position: relative;">
        
        <button type="button" onclick="closeSafetyModals()" style="position: absolute; right: 16px; top: 16px; background: none; border: none; cursor: pointer; color: #64748b;">
            <i data-lucide="x" style="width: 20px; height: 20px;"></i>
        </button>

        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
            <i data-lucide="alert-triangle" style="width: 22px; height: 22px; color: #ef4444;"></i>
            <h3 style="font-size: 18px; font-weight: 700; color: #ef4444; margin: 0;">Safety Check — Issues Found</h3>
        </div>
        <p style="font-size: 11px; color: #64748b; margin: 0 0 16px 0;">Checked against 4 medicines in this order</p>

        <!-- Drug Interaction Warning -->
        <div style="margin-bottom: 16px;">
            <label style="font-size: 10px; font-weight: 700; color: #64748b; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">DRUG INTERACTION CHECK</label>
            <div style="background: #fef2f2; border: 1px solid #fecaca; padding: 12px; border-radius: 8px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <div style="font-size: 13px; font-weight: 700; color: #991b1b; display: flex; align-items: center; gap: 6px;">
                        <i data-lucide="alert-circle" style="width: 14px; height: 14px; color: #ef4444;"></i> Warfarin ⚠️ Ibuprofen
                    </div>
                    <span style="background: #ef4444; color: #ffffff; font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 4px;">HIGH SEVERITY</span>
                </div>
                <p style="font-size: 11px; color: #7f1d1d; margin: 0;">Increases bleeding risk — monitor INR closely if co-administration is necessary.</p>
            </div>
        </div>

        <!-- Dosage Verification Range Bar -->
        <div style="margin-bottom: 16px;">
            <label style="font-size: 10px; font-weight: 700; color: #64748b; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">DOSAGE VERIFICATION</label>
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 12px; border-radius: 8px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <span style="font-size: 12px; font-weight: 700; color: #1e293b;">Lisinopril <span style="font-size: 10px; font-weight: 400; color: #64748b;">Oral Tablet</span></span>
                    <span style="color: #ef4444; font-size: 10px; font-weight: 700;">⚠️ High Dose</span>
                </div>
                <!-- Visual Dose Progress Indicator -->
                <div style="width: 100%; height: 8px; background: #cbd5e1; border-radius: 4px; overflow: hidden; margin-bottom: 6px; position: relative;">
                    <div style="width: 80%; height: 100%; background: #94a3b8; float: left;"></div>
                    <div style="width: 20%; height: 100%; background: #ef4444; float: left;"></div>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 10px; font-weight: 700;">
                    <span style="color: #ef4444;">PRESCRIBED: 800mg</span>
                    <span style="color: #64748b;">MAX RECOMMENDED: 500mg</span>
                </div>
            </div>
        </div>

        <!-- Override Textarea Reason -->
        <div style="margin-bottom: 16px;">
            <label style="font-size: 10px; font-weight: 700; color: #64748b; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">OVERRIDE JUSTIFICATION (REQUIRED)</label>
            <textarea placeholder="Explain why these clinical warnings are being bypassed..." style="width: 100%; height: 60px; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px; outline: none; resize: none;"></textarea>
            <span style="font-size: 10px; color: #64748b; display: flex; align-items: center; gap: 4px; margin-top: 4px;">
                <i data-lucide="info" style="width: 12px; height: 12px;"></i> This override will be logged in the audit trail with your digital signature.
            </span>
        </div>

        <!-- Modal Actions -->
        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
            <button type="button" onclick="closeSafetyModals()" class="btn btn-secondary" style="padding: 8px 16px; font-size: 12px;">
                Go Back and Edit
            </button>
            <a href="<?= url('/pharmacist/sales/4/completed') ?>" class="btn" style="background: #f87171; color: #ffffff; padding: 8px 16px; font-size: 12px; font-weight: 700; text-decoration: none; border-radius: 6px;">
                Override & Proceed
            </a>
        </div>

    </div>
</div>

<script>
function openSafetyModal(type) {
    closeSafetyModals();
    if (type === 'passed') {
        document.getElementById('safetyModalPassed').style.display = 'flex';
    } else {
        document.getElementById('safetyModalIssues').style.display = 'flex';
    }
    if (window.lucide) lucide.createIcons();
}

function closeSafetyModals() {
    document.getElementById('safetyModalPassed').style.display = 'none';
    document.getElementById('safetyModalIssues').style.display = 'none';
}
</script>