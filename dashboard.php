<?php ?>

<!doctype html>
<html lang="th">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>หน้าหลัก</title>

    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet" />

    <style>
      * { box-sizing: border-box; }
      body {
        margin: 0;
        font-family: 'Kanit', system-ui, Arial;
        background: #f7f7f7;
        color: #222;
      }

      .nav {
        position: sticky; top: 0; z-index: 5;
        display: flex; justify-content: space-between; align-items: center;
        padding: 10px 14px; background: #fff; border-bottom: 1px solid #e5e7eb;
      }
      .brand { display: flex; gap: 10px; align-items: center; }
      .brand-title { font-weight: 700; }
      .brand-sub { font-size: 12px; color: #6b7280; }
      .muted { color: #6b7280; }

      .container { max-width: 1100px; margin: 0 auto; padding: 16px; }
      .hero { background: #fff; border-bottom: 1px solid #e5e7eb; }
      .hero .hero-content { max-width: 1100px; margin: 0 auto; padding: 16px; }
      .hero h1 { margin: 0 0 4px; }

      .card {
        background: #fff; border: 1px solid #e5e7eb;
        border-radius: 10px; padding: 14px;
      }

      .badge {
        display: inline-block; padding: 8px 12px;
        border: 1px solid #e5e7eb; border-radius: 999px;
        background: #fff; font-weight: 700;
      }

      .tbl { width: 100%; border-collapse: separate; border-spacing: 0 8px; }

      .tbl th {
        background: #e5e7eb; color: #111;
        padding: 10px 12px; font-weight: 700; font-size: 14px;
        border-right: 1px solid #f7f7f7; white-space: nowrap;
        text-align: center;
      }
      .tbl th:first-child { border-top-left-radius: 8px; border-bottom-left-radius: 8px; }
      .tbl th:last-child  { border-top-right-radius: 8px; border-bottom-right-radius: 8px; border-right: none; }

      .tbl td {
        background: #fff; border: 1px solid #e5e7eb;
        border-left: none; border-right: none;
        padding: 10px 12px; position: relative;
        text-align: center; vertical-align: middle;
      }
      .tbl tr td:first-child { border-left: 1px solid #e5e7eb; border-top-left-radius: 8px; border-bottom-left-radius: 8px; }
      .tbl tr td:last-child  { border-right: 1px solid #e5e7eb; border-top-right-radius: 8px; border-bottom-right-radius: 8px; }

      .col-img { width: 18%; }
      .img-cell { display: flex; align-items: center; gap: 10px; justify-content: center; }

      .avatar {
        width: 84px; height: 84px; border-radius: 50%;
        object-fit: cover; border: 1px solid #e5e7eb; display: block;
        transition: transform .15s ease, box-shadow .15s ease; will-change: transform;
      }
      .avatar:hover { transform: scale(1.6); z-index: 3; box-shadow: 0 8px 24px rgba(0,0,0,.18); }

      .btn {
        display: inline-block; padding: 8px 12px;
        border-radius: 999px; border: 0; font-weight: 700;
        cursor: pointer; font-size: 14px;
      }
      .btn.nav        { border: 1px solid #e5e7eb; background: #fff; color: #111; }
      .btn.nav-danger { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

      .btn.edit   { background: #f59e0b; color: #fff; }
      .btn.menu   { background: #3b82f6; color: #fff; }
      .btn.orders { background: #10b981; color: #fff; }

      .btn.ban   { background: #ef4444; color: #fff; }
      .btn.unban { background: #10b981; color: #fff; }

      .btn.tiny { padding: 6px 10px; font-size: 12px; border: 1px solid #e5e7eb; background: #fff; }
      .btn[disabled] { opacity: .5; cursor: not-allowed; }

      .hidden { display: none; }

      .tbl td:last-child {
        display: flex; gap: 10px; align-items: center; justify-content: center;
        flex-wrap: wrap;
      }
      .tbl td:last-child .btn { white-space: nowrap; }

      @media (max-width: 860px) {
        .tbl th:nth-child(2), .tbl td:nth-child(2) { display: none; }
        .col-img { width: 22%; }
        .avatar { width: 68px; height: 68px; }
        .tbl td:last-child { gap: 8px; justify-content: center; }
      }
    </style>
  </head>

  <body data-page="dashboard">
    <nav class="nav">
      <div class="brand">
        <div>🍜</div>
        <div>
          <div class="brand-title">ระบบร้านอาหารชุมชน</div>
          <div class="brand-sub">Firebase + PHP</div>
        </div>
      </div>
      <div>
        <a class="btn nav" href="admin_profile.php">โปรไฟล์</a>
        <span id="userEmail" class="muted" style="margin: 0 8px; font-size: 14px;"></span>
        <a class="btn nav-danger" href="logout.php">ออกจากระบบ</a>
      </div>
    </nav>

    <header class="hero">
      <div class="hero-content">
        <h1>หน้าหลัก</h1>
        <div id="roleBadge" class="badge">กำลังตรวจสอบบทบาท…</div>
      </div>
    </header>

    <section class="container">
      <div class="card">
        <table class="tbl">
          <thead>
            <tr>
              <th style="width:30%;">ชื่อร้านค้า</th>
              <th style="width:20%;">ประเภทร้านค้า</th>
              <th class="col-img">รูปภาพ</th>
              <th style="width:18%;">วันที่เพิ่ม</th>
              <th style="width:18%;">การจัดการ</th>
            </tr>
          </thead>
          <tbody id="storesBody">
            <tr>
              <td colspan="5" class="muted" style="text-align:center; background:transparent; border:0;">
                กำลังโหลดข้อมูลร้าน…
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <script src="firebase-config.js"></script>
    <script type="module" src="app.js"></script>
  </body>
</html>
