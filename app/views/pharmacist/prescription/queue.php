<section class="queue-card">
    <div class="card-title-group">
        <h3>Online Orders Staging Queue</h3>
    </div>

   <!-- Search Input Wrapper Box -->
<div style="position: relative; max-width: 380px; margin-top: 12px; margin-bottom: 20px;">
    <input 
        type="text" 
        id="queueSearchInput" 
        placeholder="Search queue by patient or RX ID..." 
        onkeyup="filterQueueTable()" 
        style="width: 100%; height: 38px; padding: 0 12px 0 36px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; color: #1e293b; outline: none; background-color: #ffffff;"
    >
    <i data-lucide="search" style="position: absolute; left: 11px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: #64748b; pointer-events: none;"></i>
</div>

    <div class="table-responsive">
        <table class="custom-table" id="queueTable">
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Prescription ID</th>
                    <th>Uploaded Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($queue)): ?>
                    <?php foreach ($queue as $item): ?>
                        <tr>
                            <td style="font-weight: 600; color: #1e293b;"><?= e($item['patient_name']) ?></td>
                            <td class="rx-code">#RX-<?= (int) $item['prescription_id'] ?></td>
                            <td><?= date('M d, Y h:i A', strtotime($item['uploaded_date'])) ?></td>
                            <td>
                                <span class="status-tag review">
                                    <?= e($item['status']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= url('/pharmacist/review-prescription?id=' . (int)$item['prescription_id']) ?>" class="btn-action-review">
                                    <i data-lucide="eye"></i> Review
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: #94a3b8; padding: 35px 20px;">
                            No pending online prescriptions in queue.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<script>
function filterQueueTable() {
    const input = document.getElementById('queueSearch');
    const filter = input.value.toLowerCase();
    const tr = document.getElementById('queueTable').getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        const tdPatient = tr[i].getElementsByTagName('td')[0];
        const tdRx = tr[i].getElementsByTagName('td')[1];
        if (tdPatient || tdRx) {
            const txtPatient = tdPatient.textContent || tdPatient.innerText;
            const txtRx = tdRx.textContent || tdRx.innerText;
            tr[i].style.display = (txtPatient.toLowerCase().includes(filter) || txtRx.toLowerCase().includes(filter)) ? "" : "none";
        }
    }
}
</script>
