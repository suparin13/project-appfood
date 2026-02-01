<?php
// ยังไม่ต้องมี PHP logic ตอนนี้
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>หน้าหลัก</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Main CSS -->
  <link rel="stylesheet" href="styles.css">

  <!-- CSS เฉพาะหน้านี้ -->
  <style>
    /* ใช้งานง่ายสำหรับผู้สูงอายุ */
    body { font-size:18px; }
    .card { max-width:560px; margin:0 auto; }
    .btn { font-size:18px; padding:14px; }
    p { margin:8px 0; }

    /* 🔧 แก้ปุ่มซ้อน */
    .action-buttons {
      display: flex;
      flex-direction: column;   /* เรียงบน-ล่าง */
      gap: 12px;
      align-items: center;
      margin-top: 16px;
    }
  </style>
</head>

<body data-page="index">

  <!-- Navbar -->
  <nav class="nav">
    <div class="brand">
      <div class="logo">🍜</div>
      <div>
        <div class="brand-title">ระบบร้านอาหารชุมชน</div>
        <div class="brand-sub">ใช้ง่าย • ตัวใหญ่</div>
      </div>
    </div>
    <div class="nav-actions">
      <a href="login.php" class="btn ghost small">เข้าสู่ระบบ</a>
      <a href="dashboard.php" class="btn ghost small">แดชบอร์ด</a>
    </div>
  </nav>

  <!-- Header -->
  <header class="hero">
    <div class="hero-content">
      <h1>ยินดีต้อนรับ</h1>
      <p class="muted">กำลังตรวจสอบสถานะผู้ใช้…</p>
    </div>
  </header>

  <!-- Main -->
  <section class="container">
    <div class="card" style="text-align:center">
      <p id="statusText">
        กรุณารอสักครู่ ระบบจะพาไปหน้าที่เหมาะสมให้อัตโนมัติ
      </p>

      <!-- ✅ ปุ่มสำรอง (ไม่ซ้อนแล้ว) -->
      <div class="action-buttons">
        <a href="login.php" class="btn primary">ไปหน้าเข้าสู่ระบบ</a>
        <a href="dashboard.php" class="btn">ไปแดชบอร์ด</a>
      </div>
    </div>
  </section>

  <!-- Firebase Config -->
  <script src="firebase-config.js"></script>

  <!-- Script หลัก -->
  <script type="module" src="app.js"></script>

  <!-- Redirect อัตโนมัติ -->
  <script type="module">
    import { getAuth, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-auth.js";

    const auth = getAuth();
    const txt = document.getElementById("statusText");

    onAuthStateChanged(auth, (user) => {
      if (user) {
        if (txt) txt.textContent = "พบผู้ใช้ที่ล็อกอินแล้ว กำลังพาไปแดชบอร์ด…";
        setTimeout(() => {
          window.location.href = "dashboard.php";
        }, 800);
      } else {
        if (txt) txt.textContent = "ยังไม่ได้ล็อกอิน กำลังพาไปหน้าเข้าสู่ระบบ…";
        setTimeout(() => {
          window.location.href = "login.php";
        }, 800);
      }
    });
  </script>

</body>
</html>
