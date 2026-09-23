<!-- Metric Summary Cards Row -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px;">
    <!-- Pending Prescriptions -->
    <div class="card" style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <p style="font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 8px;">Pending Prescriptions</p>
            <h3 style="font-size: 28px; font-weight: 800; color: #0f172a; margin: 0;">24</h3>
        </div>
        <div style="width: 40px; height: 40px; border-radius: 8px; background: #ccfbf1; display: flex; align-items: center; justify-content: center; color: #0d9488;">
            <i data-lucide="clock" style="width: 20px; height: 20px;"></i>
        </div>
    </div>

    <!-- Approved Today -->
    <div class="card" style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <p style="font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 8px;">Approved Today</p>
            <h3 style="font-size: 28px; font-weight: 800; color: #0f172a; margin: 0;">142</h3>
        </div>
        <div style="width: 40px; height: 40px; border-radius: 8px; background: #dcfce7; display: flex; align-items: center; justify-content: center; color: #16a34a;">
            <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i>
        </div>
    </div>

    <!-- Rejected Today -->
    <div class="card" style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <p style="font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 8px;">Rejected Today</p>
            <h3 style="font-size: 28px; font-weight: 800; color: #dc2626; margin: 0;">12</h3>
        </div>
        <div style="width: 40px; height: 40px; border-radius: 8px; background: #fee2e2; display: flex; align-items: center; justify-content: center; color: #dc2626;">
            <i data-lucide="x-circle" style="width: 20px; height: 20px;"></i>
        </div>
    </div>

    <!-- Physical Sales Today -->
    <div class="card" style="padding: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <p style="font-size: 11px; font-weight: 700; color: #64748b; margin-bottom: 8px;">Physical Sales Today</p>
            <h3 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 0;">Rs. 34,500</h3>
        </div>
        <div style="width: 40px; height: 40px; border-radius: 8px; background: #e0f2fe; display: flex; align-items: center; justify-content: center; color: #0284c7;">
            <i data-lucide="banknote" style="width: 20px; height: 20px;"></i>
        </div>
    </div>
</div>

<!-- Main Section: Recent Activity & Priority Alerts -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px; align-items: start;">
    
    <!-- Recent Prescription Activity Table -->
    <div class="card" style="padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 15px; font-weight: 700; color: #0f172a;">Recent Prescription Activity</h3>
            <a href="<?= url('/pharmacist/prescriptions') ?>" style="font-size: 12px; font-weight: 600; color: #00766c; text-decoration: none;">View All</a>
        </div>

        <div class="table-responsive">
            <table class="custom-table" style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <thead>
                    <tr style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; text-align: left;">
                        <th style="padding: 12px 16px; color: #64748b; font-size: 11px; font-weight: 700;">PATIENT NAME</th>
                        <th style="padding: 12px 16px; color: #64748b; font-size: 11px; font-weight: 700;">MEDICINE(S)</th>
                        <th style="padding: 12px 16px; color: #64748b; font-size: 11px; font-weight: 700;">STATUS</th>
                        <th style="padding: 12px 16px; color: #64748b; font-size: 11px; font-weight: 700;">SUBMITTED</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 16px; font-weight: 600; color: #334155;">Sarah Silva</td>
                        <td style="padding: 16px; color: #475569;">Amoxicillin 500mg</td>
                        <td style="padding: 16px;"><span style="background-color: #dcfce7; color: #16a34a; font-size: 10px; font-weight: 700; padding: 4px 8px; border-radius: 4px;">APPROVED</span></td>
                        <td style="padding: 16px; color: #64748b; font-size: 12px;">10:45 AM</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 16px; font-weight: 600; color: #334155;">David Miller</td>
                        <td style="padding: 16px; color: #475569;">Lisinopril 10mg, Metformin</td>
                        <td style="padding: 16px;"><span style="background-color: #e0fbf6; color: #00766c; font-size: 10px; font-weight: 700; padding: 4px 8px; border-radius: 4px;">PENDING</span></td>
                        <td style="padding: 16px; color: #64748b; font-size: 12px;">11:12 AM</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 16px; font-weight: 600; color: #334155;">Virat Kohli</td>
                        <td style="padding: 16px; color: #475569;">Atorvastatin 20mg</td>
                        <td style="padding: 16px;"><span style="background-color: #dcfce7; color: #16a34a; font-size: 10px; font-weight: 700; padding: 4px 8px; border-radius: 4px;">APPROVED</span></td>
                        <td style="padding: 16px; color: #64748b; font-size: 12px;">11:30 AM</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 16px; font-weight: 600; color: #334155;">Mahela Jayakodi</td>
                        <td style="padding: 16px; color: #475569;">Prednisone 5mg (10 Day)</td>
                        <td style="padding: 16px;"><span style="background-color: #fee2e2; color: #ef4444; font-size: 10px; font-weight: 700; padding: 4px 8px; border-radius: 4px;">REJECTED</span></td>
                        <td style="padding: 16px; color: #64748b; font-size: 12px;">11:55 AM</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 16px; font-weight: 600; color: #334155;">John Doe</td>
                        <td style="padding: 16px; color: #475569;">Sertraline 50mg</td>
                        <td style="padding: 16px;"><span style="background-color: #dcfce7; color: #16a34a; font-size: 10px; font-weight: 700; padding: 4px 8px; border-radius: 4px;">APPROVED</span></td>
                        <td style="padding: 16px; color: #64748b; font-size: 12px;">12:15 PM</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Priority Alerts Sidebar Container -->
    <div class="card" style="padding: 24px;">
        <h3 style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 20px;">Priority Alerts</h3>
        
        <div style="display: flex; flex-direction: column; gap: 16px;">
            <!-- Alert 1 -->
            <div style="background-color: #fffbeb; border: 1px solid #fef3c7; border-radius: 8px; padding: 16px;">
                <div style="display: flex; align-items: center; gap: 8px; color: #b45309; font-weight: 700; font-size: 13px; margin-bottom: 6px;">
                    <i data-lucide="package-search" style="width: 16px; height: 16px;"></i> Low Stock Alert
                </div>
                <p style="font-size: 12px; color: #334155; margin-bottom: 4px; font-weight: 600;">Medicine: <span style="font-weight: 500;">Metformin 500mg</span></p>
                <p style="font-size: 11px; color: #78350f;">Only 45 units remaining in current inventory.</p>
            </div>

            <!-- Alert 2 -->
            <div style="background-color: #f0fdf4; border: 1px solid #dcfce7; border-radius: 8px; padding: 16px;">
                <div style="display: flex; align-items: center; gap: 8px; color: #15803d; font-weight: 700; font-size: 13px; margin-bottom: 6px;">
                    <i data-lucide="calendar-x" style="width: 16px; height: 16px;"></i> Expiring Batch
                </div>
                <p style="font-size: 12px; color: #334155; margin-bottom: 4px; font-weight: 600;">Batch: <span style="font-weight: 500;">RX-892-B</span></p>
                <p style="font-size: 11px; color: #166534;">Insulin Glargine expires in 14 days.</p>
            </div>
        </div>
    </div>

</div>