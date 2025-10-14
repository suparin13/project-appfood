<?php 

?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>หน้าหลัก</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <style>
    /* ใช้งานง่ายสำหรับผู้สูงอายุ */
    body{font-size:18px}
    .card{max-width:560px;margin:0 auto}
    .btn{font-size:18px;padding:14px}
    p{margin:8px 0}
  </style>
</head>
<body data-page="index">
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

  <header class="hero">
    <div class="hero-content">
      <h1>ยินดีต้อนรับ</h1>
      <p class="muted">กำลังตรวจสอบสถานะผู้ใช้…</p>
    </div>
  </header>

  <section class="container">
    <div class="card" style="text-align:center">
      <p id="statusText">กรุณารอสักครู่ ระบบจะพาไปหน้าที่เหมาะสมให้อัตโนมัติ</p>
      <div style="height:8px"></div>
      <!-- ปุ่มสำรองเผื่อเน็ตช้า -->
      <p>
        <a class="btn primary" href="login.php">ไปหน้าเข้าสู่ระบบ</a>
      </p>
      <p>
        <a class="btn" href="dashboard.php">ไปแดชบอร์ด</a>
      </p>
    </div>
  </section>

  <!-- ใช้ config และสคริปต์หลักตามเดิม -->
  <script src="firebase-config.js"></script>
  <script type="module" src="app.js"></script>

  <!-- สคริปต์สั้น ๆ พาไปอัตโนมัติ (อาศัย app.js สร้าง Firebase App ให้แล้ว) -->
  <script type="module">
    import { getAuth, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-auth.js";
    const auth = getAuth(); // ใช้แอปที่ถูก init โดย app.js
    const txt = document.getElementById('statusText');

    onAuthStateChanged(auth, (user) => {
      if (user) {
        txt && (txt.textContent = 'พบผู้ใช้ที่ล็อกอินแล้ว กำลังพาไปแดชบอร์ด…');
        window.location.href = 'dashboard.php';
      } else {
        txt && (txt.textContent = 'ยังไม่ได้ล็อกอิน กำลังพาไปหน้าเข้าสู่ระบบ…');
        window.location.href = 'login.php';
      }
    });
  </script>
</body>
</html>
