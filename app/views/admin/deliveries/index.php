<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>PharmaSync — Delivery Logistics</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/tokens.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/chrome.css" />
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Admin/pages.css" />
</head>
<body data-page="deliveries" data-user-name="Admin User" data-user-role="Administrator" data-user-initials="AU">

<div class="app-shell">
  <div id="sidebar-root">
    <?php require APP_PATH . '/views/admin/partials/sidebar.php'; ?>
  </div>

  <div class="main-col">
    <div id="topbar-root">
      <?php require APP_PATH . '/views/admin/partials/topbar.php'; ?>
    </div>

    <main class="page-content">
      <div class="page-head">
        <div>
          <h1>Delivery Logistics</h1>
          <p>Macro-overview of the pharmaceutical supply network.</p>
        </div>
        <div class="page-head-actions">
          <a href="<?= BASE_URL ?>/admin/deliveries/optimize" class="btn btn-outline"><i data-lucide="sliders-horizontal"></i> Optimize Schedule</a>
          <a href="<?= BASE_URL ?>/admin/deliveries/create" class="btn btn-dark"><i data-lucide="plus"></i> New Shipment</a>
        </div>
      </div>

      <div class="stat-grid cols-4">
        <div class="kpi-card">
          <div class="kpi-top">Active Deliveries</div>
          <div class="kpi-value-row"><span class="kpi-value">124</span></div>
          <div class="bar-track"><div class="bar-fill" style="width:70%"></div></div>
        </div>
        <div class="kpi-card">
          <div class="kpi-top">Available Drivers</div>
          <div class="kpi-value-row"><span class="kpi-value">42</span></div>
          <div class="bar-track"><div class="bar-fill" style="width:86%"></div></div>
        </div>
        <div class="kpi-card">
          <div class="kpi-top">Cold Chain Integrity</div>
          <div class="kpi-value-row"><span class="kpi-value">99.8%</span></div>
          <div class="mini-bars">
            <span style="height:40%"></span><span style="height:65%"></span><span class="peak" style="height:100%"></span><span style="height:55%"></span>
          </div>
        </div>
        <div class="kpi-card">
          <div class="kpi-top">Average Delivery Time</div>
          <div class="kpi-value-row"><span class="kpi-value">42m</span></div>
          <div class="bar-track"><div class="bar-fill" style="width:45%;background:#b45309"></div></div>
        </div>
      </div>

      <div class="body-grid">
        <div class="panel">
          <div class="panel-header">
            <h2>Current Shipments</h2>
            <div style="display:flex;gap:8px;">
              <button class="icon-btn"><i data-lucide="filter"></i></button>
              <button class="icon-btn"><i data-lucide="download"></i></button>
            </div>
          </div>
          <table class="data-table">
            <thead>
              <tr><th>Destination</th><th>Driver</th><th>Status</th><th>ETA</th><th>Actions</th></tr>
            </thead>
            <tbody>
              <tr>
                <td>Southside Community Clinic<br><span style="color:var(--ink-400);font-size:11.5px;">#220</span></td>
                <td><div class="driver-cell"><span class="driver-initials">SJ</span> Sarah Jenkins</div></td>
                <td><span class="badge badge-cyan"><span class="dot dot-cyan" style="margin-right:5px;"></span>Loading</span></td>
                <td>11:15 AM</td>
                <td><a href="<?= BASE_URL ?>/admin/deliveries/detail?id=220" class="icon-btn" style="border:none;"><i data-lucide="eye"></i></a></td>
              </tr>
              <tr>
                <td>University Research Lab<br><span style="color:var(--ink-400);font-size:11.5px;">#221</span></td>
                <td><div class="driver-cell"><span class="driver-initials">DM</span> David Miller</div></td>
                <td><span class="badge badge-gray"><span class="dot dot-gray" style="margin-right:5px;"></span>Scheduled</span></td>
                <td>1:00 PM</td>
                <td><a href="<?= BASE_URL ?>/admin/deliveries/detail?id=221" class="icon-btn" style="border:none;"><i data-lucide="eye"></i></a></td>
              </tr>
              <tr>
                <td>North Hills Hospice<br><span style="color:var(--ink-400);font-size:11.5px;">#194</span></td>
                <td><div class="driver-cell"><span class="driver-initials">ER</span> Elena Rodriguez</div></td>
                <td><span class="badge badge-amber"><span class="dot dot-amber" style="margin-right:5px;"></span>Delayed</span></td>
                <td>11:30 AM*</td>
                <td><a href="<?= BASE_URL ?>/admin/deliveries/detail?id=194" class="icon-btn" style="border:none;"><i data-lucide="eye"></i></a></td>
              </tr>
              <tr>
                <td>City Children's Hospital<br><span style="color:var(--ink-400);font-size:11.5px;">#195</span></td>
                <td><div class="driver-cell"><span class="driver-initials">TB</span> Tom Baker</div></td>
                <td><span class="badge badge-teal"><span class="dot dot-cyan" style="margin-right:5px;"></span>En Route</span></td>
                <td>11:45 AM</td>
                <td><a href="<?= BASE_URL ?>/admin/deliveries/detail?id=195" class="icon-btn" style="border:none;"><i data-lucide="eye"></i></a></td>
              </tr>
            </tbody>
          </table>
          <div class="pager">
            <span>Showing 6 of 124 active deliveries</span>
            <div class="pager-btns">
              <button><i data-lucide="chevron-left" style="width:14px;height:14px;"></i></button>
              <button class="active">1</button>
              <button>2</button>
              <button><i data-lucide="chevron-right" style="width:14px;height:14px;"></i></button>
            </div>
          </div>
        </div>

        <div class="panel">
          <div class="panel-header"><h2>Priority Dispatch</h2></div>
          <div>
            <div class="priority-item">
              <div class="priority-icon"><i data-lucide="snowflake"></i></div>
              <div style="flex:1;">
                <div class="priority-top"><span class="priority-name">Vaccine Batch #099</span><span class="badge badge-cyan">Critical</span></div>
                <div class="priority-sub">To: St. Jude Medical Center</div>
                <div class="priority-sub">Est: 14 mins</div>
              </div>
            </div>
            <div class="priority-item">
              <div class="priority-icon"><i data-lucide="syringe"></i></div>
              <div style="flex:1;">
                <div class="priority-top"><span class="priority-name">Insulin Restock</span><span class="badge badge-amber">Urgent</span></div>
                <div class="priority-sub">To: Central Pharmacy Hub</div>
                <div class="priority-sub">Est: 32 mins</div>
              </div>
            </div>
            <div class="priority-item">
              <div class="priority-icon"><i data-lucide="first-aid-kit"></i></div>
              <div style="flex:1;">
                <div class="priority-top"><span class="priority-name">General Supplies</span><span class="badge badge-gray">Routine</span></div>
                <div class="priority-sub">To: Westview Clinic</div>
                <div class="priority-sub">Est: 58 mins</div>
              </div>
            </div>
            <div class="priority-item">
              <div class="priority-icon"><i data-lucide="flask-conical"></i></div>
              <div style="flex:1;">
                <div class="priority-top"><span class="priority-name">Reagent Refill #204</span><span class="badge badge-amber">Urgent</span></div>
                <div class="priority-sub">To: BioLabs South</div>
                <div class="priority-sub">Est: 1h 05m</div>
              </div>
            </div>
            <div class="priority-item">
              <div class="priority-icon"><i data-lucide="asterisk"></i></div>
              <div style="flex:1;">
                <div class="priority-top"><span class="priority-name">Stat Antibiotics</span><span class="badge badge-cyan">Critical</span></div>
                <div class="priority-sub">To: Mercy Urgent Care</div>
                <div class="priority-sub">Est: 12 mins</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<a href="<?= BASE_URL ?>/admin/deliveries/create" class="fab" aria-label="New delivery"><i data-lucide="truck"></i></a>

<script src="<?= asset('assets/js/lucide.min.js') ?>"></script>
<script src="<?= BASE_URL ?>/assets/js/partials.js"></script>
</body>
</html>
