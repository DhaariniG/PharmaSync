<!-- Top Navbar Sub-header Toolbar -->
<div class="top-navbar">
    <div class="header-left">
        <a href="<?= url('/pharmacist/prescriptions') ?>" class="btn-back">
            <i data-lucide="arrow-left"></i> Back to Queue
        </a>
        <div class="divider"></div>
        <h2 class="header-title">Review Prescription #RX-<?= (int) ($prescriptionId ?? 101) ?></h2>
        <span class="badge-status">UNDER REVIEW</span>
    </div>

    <div class="header-right">
        <button class="icon-btn" title="Refresh"><i data-lucide="rotate-cw"></i></button>
        <button class="icon-btn" title="Help"><i data-lucide="help-circle"></i></button>
    </div>
</div>

<!-- Two-Column Grid Setup -->
<div class="review-grid">
    <!-- Left Side: Prescription Document Canvas Box -->
    <section class="card document-viewer-card">
        <div class="card-header">
            <h3>Prescription Document</h3>
            <button class="zoom-btn"><i data-lucide="search-code"></i></button>
        </div>
        <div class="document-body">
            <div class="placeholder-image-box">
                <i data-lucide="image" class="image-icon"></i>
            </div>
        </div>
        <div class="card-footer-info">
            <span>Uploaded: Oct 24, 2026 • 14:32</span>
            <span>File: PDF (1.2 MB)</span>
        </div>
    </section>

    <!-- Right Side: Patient Info, Medicine Search & Actions -->
    <div class="details-column">
        <!-- Patient Information Panel -->
        <section class="card detail-card">
            <div class="panel-title"><i data-lucide="user"></i> Patient Information</div>
            <div class="info-grid">
                <div>
                    <label>FULL NAME</label>
                    <p class="info-value">Dilani Silva</p>
                </div>
                <div>
                    <label>AGE / SEX</label>
                    <p class="info-value">34y / Female</p>
                </div>
            </div>
            <div class="info-single">
                <label>CONTACT</label>
                <p class="info-value">0719876543</p>
            </div>
            <div class="allergy-section">
                <label>KNOWN ALLERGIES</label>
                <div class="allergy-tags">
                    <span class="tag tag-red">PENICILLIN</span>
                    <span class="tag tag-red">LATEX</span>
                </div>
            </div>
        </section>

        <!-- Prescribed Medicine Search & Autocomplete Input Panel -->
        <section class="card detail-card" style="position: relative;">
            <div class="panel-title"><i data-lucide="pill"></i> Identify Prescribed Medicines</div>
            
            <!-- Quick Search Bar with Interactive Dropdown Menu -->
            <div class="search-box-group" style="position: relative; margin-bottom: 12px;">
                <input 
                    type="text" 
                    id="medicineSearchInput" 
                    placeholder="Search medicine (e.g. Panadol, Amoxicillin)..." 
                    onfocus="showDropdown()" 
                    oninput="filterDropdown()" 
                    autocomplete="off"
                    style="width: 100%; padding: 10px 12px 10px 32px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px; outline: none;"
                >
                <i data-lucide="search" style="position: absolute; left: 10px; top: 10px; width: 14px; height: 14px; color: #64748b;"></i>

                <!-- Mock Autocomplete Dropdown List -->
                <div id="medicineDropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); z-index: 100; max-height: 180px; overflow-y: auto; margin-top: 4px;">
                    <div class="dropdown-item" onclick="selectMedicine('Panadol 500mg Tablet', '20 Tablets • 1 tab TDS')" style="padding: 10px 12px; cursor: pointer; border-bottom: 1px solid #f1f5f9; font-size: 12px;">
                        <strong style="color: #1e293b;">Panadol 500mg Tablet</strong>
                        <span style="font-size: 10px; color: #64748b; display: block;">Analgesic / Paracetamol</span>
                    </div>
                    <div class="dropdown-item" onclick="selectMedicine('Amoxicillin 500mg Capsule', '15 Capsules • 1 cap BD')" style="padding: 10px 12px; cursor: pointer; border-bottom: 1px solid #f1f5f9; font-size: 12px;">
                        <strong style="color: #1e293b;">Amoxicillin 500mg Capsule</strong>
                        <span style="font-size: 10px; color: #ef4444; display: block;">⚠️ Penicillin Derivative</span>
                    </div>
                    <div class="dropdown-item" onclick="selectMedicine('Cetirizine 10mg Tablet', '30 Tablets • 1 tab nocte')" style="padding: 10px 12px; cursor: pointer; border-bottom: 1px solid #f1f5f9; font-size: 12px;">
                        <strong style="color: #1e293b;">Cetirizine 10mg Tablet</strong>
                        <span style="font-size: 10px; color: #64748b; display: block;">Antihistamine</span>
                    </div>
                    <div class="dropdown-item" onclick="selectMedicine('Atorvastatin 20mg Tablet', '30 Tablets • 1 tab nocte')" style="padding: 10px 12px; cursor: pointer; font-size: 12px;">
                        <strong style="color: #1e293b;">Atorvastatin 20mg Tablet</strong>
                        <span style="font-size: 10px; color: #64748b; display: block;">Lipid Lowering Agent</span>
                    </div>
                </div>
            </div>

            <!-- List of Added Prescribed Medicines -->
            <div class="prescribed-list" id="prescribedList" style="display: flex; flex-direction: column; gap: 8px;">
                <div class="med-item-row" style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0;">
                    <div>
                        <strong style="font-size: 13px; color: #1e293b;">Panadol 500mg Tablet</strong>
                        <div style="font-size: 11px; color: #64748b;">Qty: 20 Tablets • 1 tab TDS</div>
                    </div>
                    <button type="button" style="background: none; border: none; color: #ef4444; cursor: pointer;" onclick="this.closest('.med-item-row').remove();"><i data-lucide="trash-2" style="width: 14px; height: 14px;"></i></button>
                </div>

                <div class="med-item-row" style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0;">
                    <div>
                        <strong style="font-size: 13px; color: #1e293b;">Cetirizine 10mg</strong>
                        <div style="font-size: 11px; color: #64748b;">Qty: 30 Capsules • 1 cap nocte</div>
                    </div>
                    <button type="button" style="background: none; border: none; color: #ef4444; cursor: pointer;" onclick="this.closest('.med-item-row').remove();"><i data-lucide="trash-2" style="width: 14px; height: 14px;"></i></button>
                </div>
            </div>

            <button type="button" class="btn btn-secondary" onclick="showDropdown()" style="width: 100%; margin-top: 10px; justify-content: center; font-size: 12px;">
                <i data-lucide="plus"></i> Add Prescribed Medicine
            </button>
        </section>

        <!-- Pharmacist Notes Typing Area -->
        <section class="card detail-card">
            <div class="panel-title"><i data-lucide="file-text"></i> Pharmacist Notes</div>
            <div class="textarea-container">
                <label>CLINICAL NOTES</label>
                <textarea placeholder="Add any notes about legibility, doctor verification, or general observations..."></textarea>
            </div>
            <div class="notes-hint">
                <i data-lucide="info"></i> Notes are saved automatically and visible to other pharmacists.
            </div>
        </section>

        <!-- Action Verification Buttons Box -->
        <section class="card actions-card">
            <a href="<?= url('/pharmacist/alternative-review') ?>" class="btn btn-approve" style="text-decoration: none; justify-content: center;">
                <i data-lucide="check-circle-2"></i> Approve & Proceed to Alternatives
            </a>
            
            <a href="<?= url('/pharmacist/prescriptions') ?>" class="btn btn-reject" onclick="return confirm('Are you sure you want to reject this prescription?');" style="text-decoration: none; justify-content: center;">
                <i data-lucide="ban"></i> Reject / Flag
            </a>
            <div class="action-logs-text">Action will be logged under UID: PHAR-8821</div>
        </section>
    </div>
</div>

<script>
function showDropdown() {
    document.getElementById('medicineDropdown').style.display = 'block';
}

function filterDropdown() {
    const input = document.getElementById('medicineSearchInput').value.toLowerCase();
    const dropdown = document.getElementById('medicineDropdown');
    const items = dropdown.getElementsByClassName('dropdown-item');
    let hasVisible = false;

    for (let item of items) {
        const text = item.textContent.toLowerCase();
        if (text.includes(input)) {
            item.style.display = 'block';
            hasVisible = true;
        } else {
            item.style.display = 'none';
        }
    }
    dropdown.style.display = hasVisible ? 'block' : 'none';
}

function selectMedicine(name, dosage) {
    const list = document.getElementById('prescribedList');
    const div = document.createElement('div');
    div.className = 'med-item-row';
    div.style = 'display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0;';
    div.innerHTML = `
        <div>
            <strong style="font-size: 13px; color: #1e293b;">${name}</strong>
            <div style="font-size: 11px; color: #64748b;">Qty: ${dosage}</div>
        </div>
        <button type="button" style="background: none; border: none; color: #ef4444; cursor: pointer;" onclick="this.closest('.med-item-row').remove();"><i data-lucide="trash-2" style="width: 14px; height: 14px;"></i></button>
    `;
    list.appendChild(div);
    
    // Reset search input and close dropdown
    document.getElementById('medicineSearchInput').value = '';
    document.getElementById('medicineDropdown').style.display = 'none';
    if (window.lucide) lucide.createIcons();
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const searchGroup = document.querySelector('.search-box-group');
    if (searchGroup && !searchGroup.contains(e.target)) {
        document.getElementById('medicineDropdown').style.display = 'none';
    }
});
</script>