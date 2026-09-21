<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaSync - Prescription Queue</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Pharmacist/prescriptionQueue.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <div class="dashboard-container">
        <?php include APP_PATH . '/views/Pharmacist/sidebar.php'; ?>

        <main class="main-content">
            <?php 
                $pageTitle = "Prescription Queue"; 
                include APP_PATH . '/views/Pharmacist/header.php'; 
            ?>

            <section class="queue-card">
                <div class="card-title-group">
                    <h3>Online Orders Staging Queue</h3>
                </div>

                <div class="queue-filter-bar">
                    <div class="search-field-wrapper">
                        <i data-lucide="search"></i>
                        <input type="text" id="queueSearch" placeholder="Search queue by patient or RX ID..." onkeyup="filterQueueTable()">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="custom-table" id="queueTable">
                        <thead>
                            <tr>
                                <th>Patient Name</th>
                                <th>Prescription ID</th>
                                <th>Uploaded Date</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($queue)): ?>
                                <?php foreach ($queue as $item): ?>
                                    <tr>
                                        <td style="font-weight: 600; color: #1e293b;"><?= htmlspecialchars($item['patient_name']) ?></td>
                                        <td class="rx-code">#RX-<?= $item['prescription_id'] ?></td>
                                        <td><?= date('M d, Y h:i A', strtotime($item['uploaded_date'])) ?></td>
                                        <td>
                                            <?php $isHigh = ($item['priority'] ?? '') === 'High'; ?>
                                            <span class="priority-tag <?= $isHigh ? 'high' : 'normal' ?>">
                                                <?= htmlspecialchars($item['priority'] ?? 'Normal') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-tag review">
                                                <?= htmlspecialchars($item['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn-action-review" onclick="alert('Reviewing Prescription #RX-<?= $item['prescription_id'] ?>')">
                                                <i data-lucide="eye" style="width: 13px; height: 13px;"></i> Review
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; color: #94a3b8; padding: 35px 20px;">
                                        No pending online prescriptions in queue.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script>
        lucide.createIcons();

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
</body>
</html>