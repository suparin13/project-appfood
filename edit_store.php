<?php 

?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>แก้ไขข้อมูลร้าน</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <style>
    /* ใช้ง่ายสำหรับผู้สูงอายุ */
    body{font-size:18px}
    .card{max-width:560px;margin:0 auto}
    label{font-size:16px}
    input{font-size:18px;padding:14px}
    .btn{font-size:18px;padding:14px}
  </style>
</head>
<body data-page="edit_store">
  <nav class="nav">
    <div class="brand">
      <div class="logo">🍜</div>
      <div>
        <div class="brand-title">ระบบร้านอาหารชุมชน</div>
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
      <h1>แก้ไขข้อมูลร้าน</h1>
    </div>
  </header>

  <section class="container">
    <div class="card">
      <!-- ถ้ายังไม่มีร้าน จะแจ้งเตือน (app.js จะสลับให้เอง) -->
      <div id="noStore" class="muted" style="display:none;">
        ยังไม่มีร้าน โปรดไปที่หน้า “สร้างร้านของฉัน”
      </div>

      <!-- ฟอร์มแก้ไข (app.js จะเปิด/เติมค่าให้เอง) -->
      <div id="editForm" style="display:none;">
        <label>ชื่อร้าน</label>
        <input id="editStoreName" type="text" placeholder="เช่น ร้านข้าวแกงป้าทอง">

        <label>คำอธิบาย</label>
        <input id="editStoreDesc" type="text" placeholder="เช่น อร่อย ราคาถูก ใกล้บ้าน">

        <div style="height:10px"></div>
        <button id="btnUpdateStore" class="btn success w-full">บันทึก</button>
      </div>
    </div>
  </section>

  <script src="firebase-config.js"></script>
  <script type="module" src="app.js"></script>
</body>
</html>
