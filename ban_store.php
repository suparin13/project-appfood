<?php

 ?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>แอดมินใหญ่: จัดการร้าน</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <style>
    /* โหมดใช้ง่ายสำหรับผู้สูงอายุ */
    body{font-size:18px}
    .btn{font-size:18px;padding:14px}
    .card{padding:16px}
    /* จัดตารางการ์ดร้านให้ดูง่าย */
    #storesGrid{
      display:grid;
      grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
      gap:16px;
      margin-top:12px;
    }
    .note{color:var(--muted); font-size:16px}
  </style>
</head>
<body data-page="ban_store">
  <nav class="nav">
    <div class="brand">
      <div class="logo">🍜</div>
      <div>
        <div class="brand-title">ระบบร้านอาหารชุมชน</div>
        <div class="brand-sub">หน้าสำหรับแอดมินใหญ่</div>
      </div>
    </div>
    <div class="nav-actions">
      <a href="dashboard.php" class="btn ghost small">แดชบอร์ด</a>
      <span id="userEmail" class="user-email"></span>
      <a href="logout.php" class="btn ghost">ออกจากระบบ</a>
    </div>
  </nav>

  <header class="hero">
    <div class="hero-content">
      <h1>จัดการร้านทั้งหมด</h1>
      <p class="note">เฉพาะ “แอดมิน”</p>
    </div>
  </header>

  <section class="container">
    <div class="card">
      <div class="note">รายการร้านในระบบ</div>
      <!-- app.js จะเติมการ์ดร้านลงในกล่องนี้ให้เอง -->
      <div id="storesGrid"></div>
    </div>
  </section>

  <script src="firebase-config.js"></script>
  <script type="module" src="app.js"></script>
</body>
</html>
