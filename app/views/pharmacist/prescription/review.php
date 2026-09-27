<div class="workspace-grid" style="max-width: 1200px; margin: 0 auto; display: flex; gap: 20px;">

    <!-- Left Column: Prescription Image / Document Viewer -->
    <div style="flex: 1; background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; overflow: hidden;">
        <div style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
            <h3 style="margin: 0; font-size: 15px; font-weight: 700; color: #0f172a;">Prescription Document</h3>
            <span style="font-size: 12px; color: #64748b;">Uploaded: Oct 24, 2026 • 14:32</span>
        </div>
        
        <div style="flex: 1; padding: 20px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; min-height: 500px;">
            <div style="background: #e2e8f0; width: 100%; height: 100%; max-height: 550px; border-radius: 8px; border: 2px dashed #cbd5e1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #64748b; gap: 12px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                <span style="font-size: 13px; font-weight: 500;">Prescription Preview Container</span>
            </div>
        </div>

        <div style="padding: 12px 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #64748b;">
            <span>File: PDF (1.2 MB)</span>
            <div style="display: flex; gap: 8px;">
                <button type="button" style="background: #ffffff; border: 1px solid #cbd5e1; padding: 4px 10px; border-radius: 4px; font-size: 12px; cursor: pointer; color: #334155; font-weight: 600;">Zoom In</button>
                <button type="button" style="background: #ffffff; border: 1px solid #cbd5e1; padding: 4px 10px; border-radius: 4px; font-size: 12px; cursor: pointer; color: #334155; font-weight: 600;">Download</button>
            </div>
        </div>
    </div>

    <!-- Right Column: Patient Profile, Identify Medicines & Actions -->
    <div style="width: 440px; display: flex; flex-direction: column; gap: 20px;">

        <!-- Patient Profile Box -->
        <div style="background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; padding: 20px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="font-size: 10px; font-weight: 700; color: #64748b; letter-spacing: 0.5px; display: block; margin-bottom: 2px;">FULL NAME</label>
                    <span style="font-size: 14px; font-weight: 700; color: #0f172a;">Dilani Silva</span>
                </div>
                <div>
                    <label style="font-size: 10px; font-weight: 700; color: #64748b; letter-spacing: 0.5px; display: block; margin-bottom: 2px;">AGE / SEX</label>
                    <span style="font-size: 14px; font-weight: 700; color: #0f172a;">34y / Female</span>
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="font-size: 10px; font-weight: 700; color: #64748b; letter-spacing: 0.5px; display: block; margin-bottom: 2px;">CONTACT</label>
                <span style="font-size: 13px; font-weight: 600; color: #334155;">0719876543</span>
            </div>

            <div>
                <label style="font-size: 10px; font-weight: 700; color: #64748b; letter-spacing: 0.5px; display: block; margin-bottom: 6px;">KNOWN ALLERGIES</label>
                <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                    <span style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 4px;">PENICILLIN</span>
                    <span style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 4px;">LATEX</span>
                </div>
            </div>
        </div>

        <!-- Identify Prescribed Medicines -->
        <div style="background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; padding: 20px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0f172a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m10.5 20.5 10-10a4.95 4.95 0 1 0-7-7l-10 10a4.95 4.95 0 1 0 7 7Z"/><path d="m8.5 8.5 7 7"/></svg>
                <h3 style="margin: 0; font-size: 15px; font-weight: 700; color: #0f172a;">Identify Prescribed Medicines</h3>
            </div>

            <!-- Medicine Dropdown Search Bar -->
            <div style="position: relative; margin-bottom: 16px;">
                <div style="position: relative;">
                    <input type="text" id="medicineSearchInput" placeholder="Search medicine (e.g. Panadol, Amoxicillin)..." autocomplete="off" style="width: 100%; padding: 8px 12px 8px 36px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; box-sizing: border-box;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%);"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>

                <!-- Autocomplete Dropdown List -->
                <div id="searchResultsDropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 6px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); max-height: 200px; overflow-y: auto; z-index: 50; margin-top: 4px;"></div>
            </div>

            <!-- Selected Prescribed Medicine Cards -->
            <div id="medicineListContainer" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 16px;">
                
                <!-- Card 1: Panadol -->
                <div class="medicine-item-card" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px; display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                    <div>
                        <h4 style="margin: 0; font-size: 13px; font-weight: 700; color: #0f172a;">Panadol 500mg Tablet</h4>
                        <span style="font-size: 11px; color: #64748b; margin-top: 2px; display: block;">1 tab TDS</span>
                    </div>
                    <div style="display: flex; align-items: flex-end; gap: 8px;">
                        <div style="display: flex; flex-direction: column; gap: 2px;">
                            <label style="font-size: 9px; font-weight: 700; color: #64748b; letter-spacing: 0.5px;">FREQUENCY</label>
                            <input type="text" name="medicines[0][frequency]" value="TDS (3x daily)" placeholder="e.g. TDS" style="width: 95px; padding: 5px 6px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 11px; text-align: center; color: #0f172a; font-weight: 600; background: #ffffff;">
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 2px;">
                            <label style="font-size: 9px; font-weight: 700; color: #64748b; letter-spacing: 0.5px;">QTY</label>
                            <input type="number" name="medicines[0][quantity]" value="20" style="width: 45px; padding: 5px 6px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 11px; text-align: center; color: #0f172a; font-weight: 600; background: #ffffff;">
                        </div>
                        <button type="button" onclick="this.closest('.medicine-item-card').remove()" style="background: none; border: none; cursor: pointer; padding: 5px; color: #ef4444; display: flex; align-items: center;" title="Remove Medicine">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Card 2: Cetirizine -->
                <div class="medicine-item-card" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px; display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                    <div>
                        <h4 style="margin: 0; font-size: 13px; font-weight: 700; color: #0f172a;">Cetirizine 10mg</h4>
                        <span style="font-size: 11px; color: #64748b; margin-top: 2px; display: block;">1 cap nocte</span>
                    </div>
                    <div style="display: flex; align-items: flex-end; gap: 8px;">
                        <div style="display: flex; flex-direction: column; gap: 2px;">
                            <label style="font-size: 9px; font-weight: 700; color: #64748b; letter-spacing: 0.5px;">FREQUENCY</label>
                            <input type="text" name="medicines[1][frequency]" value="OD (Night)" placeholder="e.g. OD" style="width: 95px; padding: 5px 6px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 11px; text-align: center; color: #0f172a; font-weight: 600; background: #ffffff;">
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 2px;">
                            <label style="font-size: 9px; font-weight: 700; color: #64748b; letter-spacing: 0.5px;">QTY</label>
                            <input type="number" name="medicines[1][quantity]" value="30" style="width: 45px; padding: 5px 6px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 11px; text-align: center; color: #0f172a; font-weight: 600; background: #ffffff;">
                        </div>
                        <button type="button" onclick="this.closest('.medicine-item-card').remove()" style="background: none; border: none; cursor: pointer; padding: 5px; color: #ef4444; display: flex; align-items: center;" title="Remove Medicine">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                        </button>
                    </div>
                </div>

            </div>

            <!-- Add Prescribed Medicine Button -->
            <button type="button" id="btnAddMedicine" style="width: 100%; background: #ffffff; border: 1px dashed #94a3b8; border-radius: 8px; padding: 10px; font-size: 13px; font-weight: 700; color: #0f172a; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                Add Prescribed Medicine
            </button>
        </div>

        <!-- Pharmacist Notes Card -->
        <div style="background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; padding: 20px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0f172a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                <h3 style="margin: 0; font-size: 15px; font-weight: 700; color: #0f172a;">Pharmacist Notes</h3>
            </div>
            <textarea id="pharmacistNotes" name="pharmacist_notes" rows="3" placeholder="Add optional dispensing notes or remarks for customer..." style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px; box-sizing: border-box; resize: vertical;"></textarea>
        </div>

        <!-- Review Decision Action Bar -->
        <div style="background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; padding: 16px; display: flex; flex-direction: column; gap: 10px;">
            <span style="font-size: 11px; font-weight: 700; color: #64748b; letter-spacing: 0.5px;">REVIEW ACTIONS</span>
            
            <div style="display: flex; gap: 8px;">
                <!-- Approve & Proceed -> Goes to /medicines/alternatives -->
                <button type="button" onclick="submitDecision('approve')" style="flex: 2; background: #0284c7; color: #ffffff; border: none; padding: 10px 14px; border-radius: 6px; font-size: 13px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    Approve & Proceed
                </button>

                <!-- Flag -> Returns to Prescription Queue -->
                <button type="button" onclick="submitDecision('flag')" style="flex: 1; background: #fffbebfb; color: #d97706; border: 1px solid #fcd34d; padding: 10px 10px; border-radius: 6px; font-size: 12px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 4px;" title="Flag for Clarification">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
                    Flag
                </button>

                <!-- Reject -> Returns to Prescription Queue -->
                <button type="button" onclick="submitDecision('reject')" style="flex: 1; background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 10px 10px; border-radius: 6px; font-size: 12px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 4px;" title="Reject Prescription">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    Reject
                </button>
            </div>
        </div>

    </div>

</div>

<script>
    // Sample Inventory Dataset
    const availableMedicines = [
        { name: 'Amoxicillin 500mg Capsule', dosage: '1 cap TDS', defaultFreq: 'TDS (3x daily)', defaultQty: 21 },
        { name: 'Metformin 500mg Tablet', dosage: '1 tab BD', defaultFreq: 'BD (2x daily)', defaultQty: 60 },
        { name: 'Atorvastatin 20mg Tablet', dosage: '1 tab OD', defaultFreq: 'OD (Night)', defaultQty: 30 },
        { name: 'Omeprazole 20mg Capsule', dosage: '1 cap OD before food', defaultFreq: 'OD (Morn)', defaultQty: 14 },
        { name: 'Paracetamol 500mg Tablet', dosage: '2 tab QDS PRN', defaultFreq: 'QDS (4x daily)', defaultQty: 16 },
        { name: 'Salbutamol Inhaler 100mcg', dosage: '2 puffs PRN', defaultFreq: 'PRN (As needed)', defaultQty: 1 }
    ];

    let cardCounter = 2;
    const searchInput = document.getElementById('medicineSearchInput');
    const dropdown = document.getElementById('searchResultsDropdown');
    const listContainer = document.getElementById('medicineListContainer');

    function renderDropdown() {
        const query = searchInput.value.toLowerCase().trim();
        const matches = availableMedicines.filter(m => m.name.toLowerCase().includes(query));

        if (matches.length === 0) {
            dropdown.style.display = 'none';
            return;
        }

        dropdown.innerHTML = matches.map(m => `
            <div onclick="addSelectedMedicine('${m.name}', '${m.dosage}', '${m.defaultFreq}', ${m.defaultQty})" style="padding: 10px 12px; cursor: pointer; border-bottom: 1px solid #f1f5f9;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#ffffff'">
                <div style="font-size: 13px; font-weight: 700; color: #0f172a;">${m.name}</div>
                <div style="font-size: 11px; color: #64748b;">${m.dosage}</div>
            </div>
        `).join('');

        dropdown.style.display = 'block';
    }

    searchInput.addEventListener('focus', renderDropdown);
    searchInput.addEventListener('input', renderDropdown);

    function addSelectedMedicine(name, dosage, freq, qty) {
        cardCounter++;
        const newCardHTML = `
            <div class="medicine-item-card" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px; display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                <div>
                    <h4 style="margin: 0; font-size: 13px; font-weight: 700; color: #0f172a;">${name}</h4>
                    <span style="font-size: 11px; color: #64748b; margin-top: 2px; display: block;">${dosage}</span>
                </div>
                <div style="display: flex; align-items: flex-end; gap: 8px;">
                    <div style="display: flex; flex-direction: column; gap: 2px;">
                        <label style="font-size: 9px; font-weight: 700; color: #64748b; letter-spacing: 0.5px;">FREQUENCY</label>
                        <input type="text" name="medicines[${cardCounter}][frequency]" value="${freq}" placeholder="e.g. TDS" style="width: 95px; padding: 5px 6px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 11px; text-align: center; color: #0f172a; font-weight: 600; background: #ffffff;">
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 2px;">
                        <label style="font-size: 9px; font-weight: 700; color: #64748b; letter-spacing: 0.5px;">QTY</label>
                        <input type="number" name="medicines[${cardCounter}][quantity]" value="${qty}" style="width: 45px; padding: 5px 6px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 11px; text-align: center; color: #0f172a; font-weight: 600; background: #ffffff;">
                    </div>
                    <button type="button" onclick="this.closest('.medicine-item-card').remove()" style="background: none; border: none; cursor: pointer; padding: 5px; color: #ef4444; display: flex; align-items: center;" title="Remove Medicine">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                    </button>
                </div>
            </div>
        `;
        listContainer.insertAdjacentHTML('beforeend', newCardHTML);
        searchInput.value = '';
        dropdown.style.display = 'none';
    }

    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });

    document.getElementById('btnAddMedicine').addEventListener('click', function() {
        renderDropdown();
        searchInput.focus();
    });

    // Dynamic Action Routing
    function submitDecision(actionType) {
    if (actionType === 'approve') {
        // Point to the full role-prefixed route URL
        window.location.href = '<?= url('/pharmacist/medicines/alternatives') ?>';
    } else if (actionType === 'flag' || actionType === 'reject') {
        window.location.href = '<?= url('/pharmacist/prescriptions') ?>';
    }
}
</script>