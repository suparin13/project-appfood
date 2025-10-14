<?php 
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>เมนูของร้าน</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <style>
    /* โหมดใช้ง่ายสำหรับผู้สูงอายุ */
    body{font-size:18px}
    .card{max-width:980px;margin:0 auto}
    label{font-size:16px}
    input{font-size:18px;padding:14px}
    .btn{font-size:18px;padding:12px 16px}
    .tips{color:var(--muted); margin:6px 0 12px}

    /* ทำให้กริดเมนูอ่านง่าย */
    #menusGrid{
      display:grid;
      grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
      gap:16px
    }
    .menu-img{
      width:100%;
      height:140px;
      object-fit:cover;
      border-radius:8px;
      border:1px solid var(--border)
    }

    /* ช่องไฟเล็กน้อย */
    .sp8{height:8px}
    .sp10{height:10px}
  </style>
</head>
<body data-page="view_store">
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
      <h1>เมนูของร้าน</h1>
      <p class="tips">ใส่ชื่อ ราคา แล้ว “เลือกไฟล์รูป” หรือ “วางลิงก์รูป” จากนั้นกด “เพิ่มเมนู”</p>
    </div>
  </header>

  <section class="container">
    <div class="card">
      <!-- ส่วนหัวร้าน + สถานะแบน (เติมโดย app.js) -->
      <div id="storeHeader"></div>
      <div id="bannedAlert" class="alert danger" style="display:none;">
        ร้านถูกแบนชั่วคราว ไม่สามารถเพิ่ม/แก้ไขเมนูได้
      </div>

      <!-- ฟอร์มเพิ่มเมนู -->
      <h3>เพิ่มเมนู</h3>

      <div class="grid-2">
        <div>
          <label for="menuName">ชื่อเมนู</label>
          <input id="menuName" type="text" placeholder="เช่น ข้าวหมูทอด หรือ ชาไทย">
        </div>
        <div>
          <label for="menuPrice">ราคา (บาท)</label>
          <input id="menuPrice" type="number" min="0" step="1" placeholder="เช่น 45">
        </div>
      </div>

      <div class="sp8"></div>

      <!-- อัปโหลดจากไฟล์ (แนะนำ ≤ 3MB) -->
      <label for="menuImgFile">เลือกรูปภาพจากไฟล์ (แนะนำ ≤ 3MB)</label>
      <input id="menuImgFile" type="file" accept="image/*">

      <div class="sp8"></div>

      <!-- หรือ วางลิงก์รูป -->
      <label for="menuImg">หรือวางลิงก์รูปภาพ (ไม่บังคับ)</label>
      <input id="menuImg" type="url" placeholder="https://...">

      <div class="sp10"></div>
      <button id="btnAddMenu" class="btn success">เพิ่มเมนู</button>

      <!-- รายการเมนู -->
      <h3 style="margin-top:16px">รายการเมนู</h3>
      <div id="menusGrid"></div>

      <!-- Modal แก้ไขเมนู -->
<div id="editMenuModal" class="card"
     style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%);
            max-width:420px; width:92%; background:#fff; z-index:9999; border:1px solid #ddd; padding:16px; border-radius:12px">
  <h3>แก้ไขเมนู</h3>

  <label>ชื่อเมนู</label>
  <input id="editMenuName" type="text" />

  <label>ราคา (บาท)</label>
  <input id="editMenuPrice" type="number" min="0" step="1" />

  <label>รูปภาพใหม่ (ถ้าต้องการเปลี่ยน)</label>
  <input id="editMenuFile" type="file" accept="image/*" />

  <div style="margin-top:12px; display:flex; gap:8px; justify-content:flex-end;">
    <button id="btnSaveMenu" class="btn success">บันทึก</button>
    <button id="btnCancelEdit" class="btn ghost">ยกเลิก</button>
  </div>
</div>

<!-- (ออปชัน) ฉากหลังมืด -->
<div id="editMenuBackdrop"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.35); z-index:9998;"></div>


      <!-- กรณียังไม่มีร้าน -->
      <div id="noStore" class="muted" style="display:none;">
        ยังไม่มีร้าน โปรดไปที่หน้า “สร้างร้านของฉัน”
      </div>

      <!-- ✅ ปุ่มกลับหน้าหลัก -->
      <div style="margin-top:20px; text-align:center;">
        <a href="dashboard.php" class="btn">กลับหน้าหลัก</a>
      </div>
    </div>
  </section>
  <script src="firebase-config.js"></script>
  <script type="module" src="app.js"></script>
</body>
</html>
