<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <title>จัดการไรเดอร์</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body data-page="riders_manage">

<nav class="nav">
  <div class="brand">
    <div class="logo">🛵</div>
    <div>
      <div class="brand-title">จัดการไรเดอร์</div>
    </div>
  </div>
  <div class="nav-actions">
    <a href="dashboard.php" class="btn ghost small">กลับหน้าหลัก</a>
    <span id="userEmail"></span>
    <a href="logout.php" class="btn ghost">ออกจากระบบ</a>
  </div>
</nav>

<section class="container">
  <div class="card">
    <h3>รายชื่อไรเดอร์ทั้งหมด</h3>
    <table class="table">
      <thead>
        <tr>
          <th>ชื่อ</th>
          <th>เบอร์</th>
          <th>สถานะ</th>
          <th>จัดการ</th>
        </tr>
      </thead>
      <tbody id="ridersBody"></tbody>
    </table>
  </div>
</section>

<script src="firebase-config.js"></script>
<script type="module" src="app.js"></script>
</body>
</html>
