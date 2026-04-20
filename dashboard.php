<?php ?>
<!doctype html>
<html lang="th">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>หน้าหลัก</title>

  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet" />

  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Kanit', system-ui, Arial;
      background: #f7f7f7;
      color: #222;
    }

    .nav {
      position: sticky;
      top: 0;
      z-index: 5;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 14px;
      background: #fff;
      border-bottom: 1px solid #e5e7eb;
    }

    .brand {
      display: flex;
      gap: 10px;
      align-items: center;
    }

    .brand-title {
      font-weight: 700;
    }

    .brand-sub {
      font-size: 12px;
      color: #6b7280;
    }

    .muted {
      color: #6b7280;
    }

    .container {
      max-width: 1100px;
      margin: 0 auto;
      padding: 16px;
    }

    .hero {
      background: #fff;
      border-bottom: 1px solid #e5e7eb;
    }

    .hero .hero-content {
      max-width: 1100px;
      margin: 0 auto;
      padding: 16px;
    }

    .hero h1 {
      margin: 0 0 4px;
    }

    .card {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 10px;
      padding: 14px;
    }

    /* ===== badge สถานะ ===== */
    .badge {
      display: inline-block;
      padding: 8px 12px;
      border-radius: 999px;
      font-weight: 700;
      font-size: 14px;
    }

    .badge.pending {
      background: #fef3c7;
      color: #92400e;
      border: 1px solid #fde68a;
    }

    .badge.ok {
      background: #dcfce7;
      color: #166534;
      border: 1px solid #86efac;
    }

    .badge.ban {
      background: #fee2e2;
      color: #991b1b;
      border: 1px solid #fecaca;
    }

    /* ===== table ===== */
    .tbl {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0 8px;
    }

    .tbl th {
      background: #e5e7eb;
      padding: 10px 12px;
      font-weight: 700;
      font-size: 14px;
      text-align: center;
    }

    .tbl td {
      background: #fff;
      border: 1px solid #e5e7eb;
      padding: 10px 12px;
      text-align: center;
      vertical-align: middle;
    }

    .avatar {
      width: 84px;
      height: 84px;
      border-radius: 50%;
      object-fit: cover;
      border: 1px solid #e5e7eb;
    }

    /* ===== buttons ===== */
    .btn {
      padding: 8px 12px;
      border-radius: 999px;
      border: 0;
      font-weight: 700;
      cursor: pointer;
      font-size: 14px;
    }

    .btn.nav {
      border: 1px solid #e5e7eb;
      background: #fff;
    }

    .btn.nav-danger {
      background: #fee2e2;
      color: #b91c1c;
    }

    .btn.edit {
      background: #f59e0b;
      color: #fff;
    }

    .btn.menu {
      background: #3b82f6;
      color: #fff;
    }

    .btn.orders {
      background: #10b981;
      color: #fff;
    }

    /* admin only */
    .btn.approve {
      background: #16a34a;
      color: #fff;
    }

    .btn.ban {
      background: #ef4444;
      color: #fff;
    }

    .btn.unban {
      background: #10b981;
      color: #fff;
    }

    .btn[disabled] {
      opacity: .5;
      cursor: not-allowed;
    }

    .tbl td:last-child {
      display: flex;
      gap: 8px;
      justify-content: center;
      flex-wrap: wrap;
    }
  </style>
</head>

<body data-page="dashboard">
  <nav class="nav">
    <div class="brand">
      <div>🍜</div>
      <div>
        <div class="brand-title">ระบบร้านอาหารชุมชน</div>
      </div>
    </div>
    <div>
      <a class="btn nav" href="admin_profile.php">โปรไฟล์</a>
      <span id="userEmail" class="muted" style="margin: 0 8px;"></span>
      <a class="btn nav-danger" href="logout.php">ออกจากระบบ</a>
    </div>
  </nav>

  <header class="hero">
    <div class="hero-content">
      <h1>หน้าหลัก</h1>
      <!-- app.js จะเปลี่ยนข้อความ + class ให้ -->
      <div id="roleBadge" class="badge pending">
        กำลังตรวจสอบบทบาท…
      </div>
    </div>
  </header>

  <section class="container">
    <div class="card">
      <h3 style="margin-top:30px">รายชื่อร้านค้า</h3>
      <table class="tbl">
        <thead>
          <tr>
            <th style="width:28%">ชื่อร้าน</th>
            <th style="width:18%">ประเภทร้าน</th>
            <th style="width:18%">รูป</th>
            <th style="width:18%">สถานะ</th>
            <th style="width:18%">การจัดการ</th>
          </tr>
        </thead>


        <tbody id="storesBody">
          <tr>
            <td colspan="5" class="muted" style="background:transparent;border:0">
              กำลังโหลดข้อมูลร้าน…
            </td>
          </tr>
        </tbody>
      </table>
      <div id="ridersSection" style="display:none;">

        <h3>รายชื่อไรเดอร์</h3>

        <table class="tbl">
          <thead>
            <tr>
              <th>ชื่อ</th>
              <th>เบอร์</th>
              <th>สถานะ</th>
              <th>การจัดการ</th>
            </tr>
          </thead>
          <tbody id="ridersBody">
            <tr>
              <td colspan="4">กำลังโหลดข้อมูลไรเดอร์...</td>
            </tr>
          </tbody>
        </table>

      </div>
    </div>
  </section>

  <script src="firebase-config.js"></script>
  <script type="module" src="app.js"></script>
</body>

</html>