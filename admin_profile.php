<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>โปรไฟล์ผู้ดูแลระบบ</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="styles.css">

  <style>
    /* badge สถานะร้าน */
    .status-badge {
      display: inline-block;
      padding: 6px 12px;
      border-radius: 999px;
      font-weight: 600;
      font-size: 14px;
      margin-left: 6px;
    }
    .status-pending {
      background: #fef3c7;
      color: #92400e;
      border: 1px solid #fde68a;
    }
    .status-approved {
      background: #dcfce7;
      color: #166534;
      border: 1px solid #86efac;
    }
    .status-banned {
      background: #fee2e2;
      color: #991b1b;
      border: 1px solid #fecaca;
    }
    .btn[disabled] {
      opacity: .5;
      pointer-events: none;
    }
  </style>
</head>

<body data-page="admin_profile">
  <nav class="nav">
    <div class="brand">
      <div class="logo">🍜</div>
      <div>
        <div class="brand-title">ร้านอาหารหลายสาขา</div>
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
      <h1>โปรไฟล์ผู้ดูแลระบบ</h1>
    </div>
  </header>

  <section class="container" style="display:grid; gap:16px;">

    <!-- โปรไฟล์ผู้ใช้ -->
    <div class="card">
      <h3>ข้อมูลโปรไฟล์</h3>
      <p><b>ชื่อผู้ใช้:</b> <span id="profName">-</span></p>
      <p><b>เบอร์โทรศัพท์:</b> <span id="profEmail">-</span></p>
      <p><b>สิทธิ์ / บทบาท:</b> <span id="profRole">-</span></p>
      <div style="height:10px"></div>
      <a href="dashboard.php" class="btn">กลับหน้าหลัก</a>
    </div>

    <!-- ข้อมูลร้าน -->
    <div class="card">
      <h3>
        ข้อมูลร้านของฉัน
        <span id="storeStatusBadge" class="status-badge status-pending hidden">
          ⏳ กำลังตรวจสอบ
        </span>
      </h3>

      <div id="noStoreProfile" class="muted" style="display:none;">
        ยังไม่มีร้าน ระบบจะสร้างให้เมื่อสมัคร
      </div>

      <div id="storeProfileBox" style="display:none;">
        <p><b>ชื่อร้าน:</b> <span id="profStoreName">-</span></p>
        <p><b>ประเภทร้าน:</b> <span id="profStoreType">-</span></p>
        <p><b>คำอธิบาย:</b> <span id="profStoreDesc">-</span></p>

        <div style="height:10px"></div>

        <a id="btnEditStore" href="edit_store.php" class="btn">
          แก้ไขข้อมูลร้าน
        </a>
        <a id="btnManageMenu" href="view_store.php" class="btn ghost">
          จัดการเมนู
        </a>
      </div>
    </div>

  </section>

  <script src="firebase-config.js"></script>
  <script type="module" src="app.js"></script>
</body>
</html>
