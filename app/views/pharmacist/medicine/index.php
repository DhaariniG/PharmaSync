<!-- Search & Filter Controls Bar -->
<div class="card" style="padding: 18px 24px; margin-bottom: 24px; background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
    <div style="display: flex; gap: 12px; align-items: center;">
        
        <!-- Search Input -->
        <div style="position: relative; flex: 2; min-width: 260px;">
            <input 
                type="text" 
                id="medSearchInput" 
                placeholder="Search medicine by name, generic name, or category..." 
                onkeyup="filterMedicineInventory()" 
                style="width: 100%; height: 42px; padding: 0 14px 0 38px; border: 1px solid #cbd5e1; border-radius: 8px; background-color: #f8fafc; font-size: 13px; color: #1e293b; outline: none; transition: all 0.2s ease;"
            >
            <i data-lucide="search" style="position: absolute; left: 12px; top: 13px; width: 16px; height: 16px; color: #64748b;"></i>
        </div>
        
        <!-- Category Dropdown Filter -->
        <div style="flex: 1; min-width: 160px;">
            <select 
                id="categorySelect" 
                onchange="filterMedicineInventory()" 
                style="width: 100%; height: 42px; padding: 0 12px; border: 1px solid #cbd5e1; border-radius: 8px; background-color: #f8fafc; font-size: 13px; color: #334155; outline: none; cursor: pointer;"
            >
                <option value="ALL">All Categories</option>
                <option value="Antidepressant">Antidepressant</option>
                <option value="Antidiabetic">Antidiabetic</option>
                <option value="Diabetes">Diabetes</option>
                <option value="Antibiotics">Antibiotics</option>
            </select>
        </div>

        <!-- Stock Status Filter -->
        <div style="flex: 1; min-width: 150px;">
            <select 
                id="stockStatusSelect" 
                onchange="filterMedicineInventory()" 
                style="width: 100%; height: 42px; padding: 0 12px; border: 1px solid #cbd5e1; border-radius: 8px; background-color: #f8fafc; font-size: 13px; color: #334155; outline: none; cursor: pointer;"
            >
                <option value="ALL">All Stock Status</option>
                <option value="LOW">Low Stock Only</option>
                <option value="OUT">Out of Stock Only</option>
            </select>
        </div>

        <!-- Reset Button -->
        <button 
            type="button" 
            onclick="resetFilters()" 
            class="btn btn-secondary" 
            title="Reset Filters"
            style="height: 42px; padding: 0 14px; font-size: 13px; font-weight: 600; border-radius: 8px; display: inline-flex; align-items: center;"
        >
            <i data-lucide="rotate-ccw" style="width: 15px; height: 15px; color: #64748b;"></i>
        </button>

    </div>
</div>

<!-- Medicine Cards Grid Row -->
<div id="medicineCardsGrid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 28px;">
    
    <!-- Card 1: Sertraline 50mg -->
    <div class="card med-card-item" data-category="Antidepressant" data-stock="LOW" data-name="sertraline 50mg sertraline hydrochloride" style="padding: 20px; display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px;">
                <h4 style="font-size: 15px; font-weight: 700; color: #0f172a;">Sertraline 50mg</h4>
                <span style="background-color: #e0f2fe; color: #0369a1; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 12px;">Antidepressant</span>
            </div>
            <p style="font-size: 11px; color: #64748b; margin-bottom: 16px;">Generic: Sertraline Hydrochloride</p>
            
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 16px;">
                <div>
                    <span style="font-size: 10px; font-weight: 700; color: #64748b; letter-spacing: 0.5px;">CURRENT STOCK</span>
                    <div style="font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1.2;">12 <span style="font-size: 12px; font-weight: 500; color: #64748b;">units</span></div>
                </div>
                <span style="background-color: #fef3c7; color: #b45309; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 12px; display: inline-flex; align-items: center; gap: 4px;">
                    <span style="font-size: 12px;">•</span> Low Stock
                </span>
            </div>
        </div>

        <div style="border-top: 1px solid #f1f5f9; padding-top: 12px; font-size: 11px;">
            <span style="color: #ef4444; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                <i data-lucide="triangle-alert" style="width: 13px; height: 13px;"></i> Exp: Oct 28, 2026
            </span>
        </div>
    </div>

    <!-- Card 2: Metformin 500mg -->
    <div class="card med-card-item" data-category="Antidiabetic" data-stock="LOW" data-name="metformin 500mg metformin hydrochloride" style="padding: 20px; display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px;">
                <h4 style="font-size: 15px; font-weight: 700; color: #0f172a;">Metformin 500mg</h4>
                <span style="background-color: #fef3c7; color: #b45309; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 12px;">Antidiabetic</span>
            </div>
            <p style="font-size: 11px; color: #64748b; margin-bottom: 16px;">Generic: Metformin Hydrochloride</p>

            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 16px;">
                <div>
                    <span style="font-size: 10px; font-weight: 700; color: #64748b; letter-spacing: 0.5px;">CURRENT STOCK</span>
                    <div style="font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1.2;">45 <span style="font-size: 12px; font-weight: 500; color: #64748b;">units</span></div>
                </div>
                <span style="background-color: #fef3c7; color: #b45309; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 12px; display: inline-flex; align-items: center; gap: 4px;">
                    <span style="font-size: 12px;">•</span> Low Stock
                </span>
            </div>
        </div>

        <div style="border-top: 1px solid #f1f5f9; padding-top: 12px; font-size: 11px;">
            <span style="color: #ef4444; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                <i data-lucide="triangle-alert" style="width: 13px; height: 13px;"></i> Exp: Nov 10, 2026
            </span>
        </div>
    </div>

    <!-- Card 3: Insulin Glargine -->
    <div class="card med-card-item" data-category="Diabetes" data-stock="OUT" data-name="insulin glargine insulin glargine injection" style="padding: 20px; display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px;">
                <h4 style="font-size: 15px; font-weight: 700; color: #0f172a;">Insulin Glargine</h4>
                <span style="background-color: #ccfbf1; color: #0f766e; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 12px;">Diabetes</span>
            </div>
            <p style="font-size: 11px; color: #64748b; margin-bottom: 16px;">Generic: Insulin Glargine Injection</p>

            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 16px;">
                <div>
                    <span style="font-size: 10px; font-weight: 700; color: #64748b; letter-spacing: 0.5px;">CURRENT STOCK</span>
                    <div style="font-size: 22px; font-weight: 800; color: #ef4444; line-height: 1.2;">0 <span style="font-size: 12px; font-weight: 500; color: #64748b;">units</span></div>
                </div>
                <span style="background-color: #fee2e2; color: #ef4444; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 12px; display: inline-flex; align-items: center; gap: 4px;">
                    <span style="font-size: 12px;">•</span> Out of Stock
                </span>
            </div>
        </div>

        <div style="border-top: 1px solid #f1f5f9; padding-top: 12px; font-size: 11px;">
            <span style="color: #64748b; font-weight: 500;">
                Exp: N/A
            </span>
        </div>
    </div>

</div>

<!-- Table Section: Recent Batch Details -->
<div class="card" style="padding: 24px;">
    <div style="margin-bottom: 20px;">
        <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px;">
            <i data-lucide="layers" style="width: 18px; height: 18px; color: #00766c;"></i> Recent Batch Details
        </h3>
    </div>

    <div class="table-responsive">
        <table class="custom-table" id="batchDetailsTable" style="width: 100%; border-collapse: collapse; font-size: 13px;">
            <thead>
                <tr style="background-color: #f0fdfa; border-bottom: 1px solid #ccfbf1; text-align: left;">
                    <th style="padding: 12px 16px; color: #0f766e; font-size: 11px; font-weight: 700;">BATCH NO.</th>
                    <th style="padding: 12px 16px; color: #0f766e; font-size: 11px; font-weight: 700;">MEDICINE NAME</th>
                    <th style="padding: 12px 16px; color: #0f766e; font-size: 11px; font-weight: 700;">QUANTITY</th>
                    <th style="padding: 12px 16px; color: #0f766e; font-size: 11px; font-weight: 700;">EXPIRY DATE</th>
                    <th style="padding: 12px 16px; color: #0f766e; font-size: 11px; font-weight: 700;">STORAGE ZONE</th>
                </tr>
            </thead>
            <tbody>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 16px; font-weight: 600; color: #334155;">B-2023-045</td>
                    <td style="padding: 16px; font-weight: 700; color: #1e293b;">Metformin 500mg</td>
                    <td style="padding: 16px; color: #475569;">45 units</td>
                    <td style="padding: 16px; color: #ef4444; font-weight: 600;">Nov 10, 2026</td>
                    <td style="padding: 16px;"><span style="background-color: #f1f5f9; color: #475569; font-size: 11px; font-weight: 600; padding: 4px 8px; border-radius: 4px;">Zone A-4</span></td>
                </tr>

                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 16px; font-weight: 600; color: #334155;">B-2023-098</td>
                    <td style="padding: 16px; font-weight: 700; color: #1e293b;">Sertraline 50mg</td>
                    <td style="padding: 16px; color: #475569;">12 units</td>
                    <td style="padding: 16px; color: #ef4444; font-weight: 600;">Oct 28, 2026</td>
                    <td style="padding: 16px;"><span style="background-color: #f1f5f9; color: #475569; font-size: 11px; font-weight: 600; padding: 4px 8px; border-radius: 4px;">Zone C-2</span></td>
                </tr>

                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 16px; font-weight: 600; color: #334155;">B-2024-001</td>
                    <td style="padding: 16px; font-weight: 700; color: #1e293b;">Amoxicillin 500mg</td>
                    <td style="padding: 16px; color: #475569;">640 units</td>
                    <td style="padding: 16px; color: #64748b;">Dec 15, 2026</td>
                    <td style="padding: 16px;"><span style="background-color: #f1f5f9; color: #475569; font-size: 11px; font-weight: 600; padding: 4px 8px; border-radius: 4px;">Zone B-1</span></td>
                </tr>

                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 16px; font-weight: 600; color: #334155;">B-2024-012</td>
                    <td style="padding: 16px; font-weight: 700; color: #1e293b;">Lisinopril 10mg</td>
                    <td style="padding: 16px; color: #475569;">850 units</td>
                    <td style="padding: 16px; color: #64748b;">Jan 20, 2027</td>
                    <td style="padding: 16px;"><span style="background-color: #f1f5f9; color: #475569; font-size: 11px; font-weight: 600; padding: 4px 8px; border-radius: 4px;">Zone A-1</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
function filterMedicineInventory() {
    const searchText = document.getElementById('medSearchInput').value.toLowerCase().trim();
    const selectedCategory = document.getElementById('categorySelect').value;
    const selectedStock = document.getElementById('stockStatusSelect').value;

    // 1. Filter Medicine Cards
    const cards = document.querySelectorAll('.med-card-item');
    cards.forEach(card => {
        const cardName = card.getAttribute('data-name');
        const cardCategory = card.getAttribute('data-category');
        const cardStock = card.getAttribute('data-stock');

        const matchesSearch = searchText === '' || cardName.includes(searchText);
        const matchesCategory = (selectedCategory === 'ALL' || cardCategory === selectedCategory);
        const matchesStock = (selectedStock === 'ALL' || cardStock === selectedStock);

        if (matchesSearch && matchesCategory && matchesStock) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });

    // 2. Filter Table Rows
    const rows = document.querySelectorAll('#batchDetailsTable tbody tr');
    rows.forEach(row => {
        const rowText = row.textContent.toLowerCase();
        if (searchText === '' || rowText.includes(searchText)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function resetFilters() {
    document.getElementById('medSearchInput').value = '';
    document.getElementById('categorySelect').value = 'ALL';
    document.getElementById('stockStatusSelect').value = 'ALL';
    filterMedicineInventory();
}
</script>