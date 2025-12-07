<?php 
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>คำสั่งซื้อของร้าน</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <style>
    body{font-size:18px}
    .card{max-width:980px;margin:0 auto}
    .btn{font-size:18px;padding:12px 16px}
    .section-header{display:flex;justify-content:space-between;align-items:center;gap:10px;margin-bottom:8px}
    .table-wrap{overflow-x:auto}
    table{width:100%;border-collapse:collapse}
    th,td{padding:12px 10px;border-bottom:1px solid var(--border)}
    th{text-align:left;background:#f9fafb;position:sticky;top:0}
    tr:nth-child(even) td{background:#fcfcfc}
    .muted{color:var(--muted)}
  </style>
</head>
<body data-page="orders">
  <nav class="nav">
    <div class="brand">
      <div class="logo">🍜</div>
      <div>
        <div class="brand-title">ระบบร้านอาหารชุมชน</div>
        <div class="brand-sub">ใช้ง่าย • ตัวใหญ่</div>
      </div>
    </div>
    <div class="nav-actions">
      <a href="dashboard.php" class="btn ghost small">หน้าหลัก</a>
      <span id="userEmail" class="user-email"></span>
      <a href="logout.php" class="btn ghost">ออกจากระบบ</a>
    </div>
  </nav>

  <header class="hero">
    <div class="hero-content">
      <h1>คำสั่งซื้อของร้าน</h1>
      <p class="muted">รายการจะอัปเดตอัตโนมัติ</p>
    </div>
  </header>

  <section class="container">
    <div class="card">
      <div class="section-header">
        <h3 style="margin:0">รายการคำสั่งซื้อ</h3>
        <button id="btnDemoOrder" class="btn">+ สร้างออเดอร์ตัวอย่าง</button>
      </div>

      <div class="table-wrap">
        <table class="table">
          <thead>
            <tr>
              <th>เวลา</th>
              <th>ลูกค้า</th>
              <th>รายการ</th>
              <th>ราคารวม</th>
              <th>สถานะ</th>
            </tr>
          </thead>
          <tbody id="ordersBody"></tbody>
        </table>
      </div>

      <div id="noStore" class="muted" style="display:none;margin-top:8px;">
        ยังไม่มีร้าน โปรดไปที่หน้า “สร้างร้านของฉัน”
      </div>
    </div>
  </section>

  <script src="firebase-config.js"></script>
  <script type="module" src="app.js"></script>
</body>
</html>
