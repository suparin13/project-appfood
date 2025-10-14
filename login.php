<?php

?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>เข้าใช้งาน / สมัครสมาชิก</title>

  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet" />

  <style>
    * { box-sizing: border-box; }

    body {
      margin: 0;
      font-family: 'Kanit', system-ui, Arial;
      background: #f7f7f7;
      color: #222;
      font-size: 18px;    /* อ่านง่าย ไม่ใหญ่เกินไป */
      line-height: 1.6;
    }

    /* แถบบน */
    .topbar {
      display: flex; justify-content: center; align-items: center;
      padding: 12px 16px;
      background: #fff; border-bottom: 1px solid #e5e7eb;
    }
    .brand { font-weight: 600; font-size: 20px; }

    /* พื้นที่เนื้อหา */
    .wrap {
      max-width: 1040px;
      margin: 24px auto;
      padding: 0 16px;
    }

    /* ส่วนหัวหน้า */
    .hero { text-align: center; margin-bottom: 18px; }
    .hero h1 { margin: 0 0 6px; font-size: 28px; }
    .note { color: #6b7280; font-size: 16px; margin: 0; }

    /* วางเลย์เอาต์ 2 คอลัมน์ (จอกว้าง) / 1 คอลัมน์ (จอเล็ก) */
    .grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }
    @media (max-width: 900px) {
      .grid-2 { grid-template-columns: 1fr; }
    }

    /* การ์ด */
    .card {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      padding: 16px;
    }
    .card h3 { margin: 0 0 12px; font-size: 22px; }

    /* ทำฟอร์มให้ “แคบพอดีตา” และอยู่กึ่งกลางในการ์ด */
    .narrow {
      max-width: 480px;
      margin: 0 auto;
    }

    /* ฟิลด์ฟอร์ม */
    .field { margin-bottom: 12px; }
    label { display: block; font-size: 16px; color: #374151; margin-bottom: 6px; }
    input {
      width: 100%;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      padding: 10px 12px;
      font-size: 16px;
      background: #fff;
      color: #111;
    }

    /* สองคอลัมน์เฉพาะในกรอบ narrow (รหัสผ่าน/ยืนยันรหัสผ่าน) */
    .row {
      display: grid; grid-template-columns: 1fr 1fr; gap: 12px;
    }
    @media (max-width: 520px) { .row { grid-template-columns: 1fr; } }

    /* ปุ่ม */
    .btn {
      display: inline-block;
      border: 1px solid #e5e7eb;
      background: #fff;
      color: #111;
      padding: 10px 14px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 700;
      font-size: 16px;
    }
    .btn.primary { background: #2563eb; color: #fff; border-color: #2563eb; }
    .btn.success { background: #16a34a; color: #fff; border-color: #16a34a; }
    .btn.block { width: 100%; }

    /* ระยะห่างเล็กน้อยก่อนปุ่ม */
    .spacer { height: 10px; }

    /* ====== ปุ่มเลือกประเภทร้านแบบ pill ====== */
    .shoptype {
      display: flex; gap: 10px; flex-wrap: wrap;
    }
    .shoptype input[type="radio"] {
      position: absolute; opacity: 0; pointer-events: none;
    }
    .shoptype .pill {
      border: 1px solid #e5e7eb;
      background: #fff;
      border-radius: 999px;
      padding: 8px 14px;
      cursor: pointer;
      user-select: none;
      font-weight: 600;
      transition: transform .02s ease-in-out, box-shadow .2s, background .2s, border-color .2s;
      display: inline-flex; align-items: center; gap: 8px;
    }
    .shoptype .pill:active { transform: scale(0.98); }
    .shoptype input[type="radio"]:checked + .pill {
      background: #eef2ff;
      border-color: #6366f1;
      box-shadow: 0 0 0 2px rgba(99,102,241,.15) inset;
    }
    /* ========================================= */
  </style>
</head>

<body data-page="login">
  <!-- แถบหัว -->
  <div class="topbar">
    <div class="brand">🍜 ระบบร้านอาหารภายในชุมชน</div>
  </div>

  <!-- เนื้อหา -->
  <div class="wrap">
    <div class="hero">
      <h1>เข้าใช้งาน / สมัครสมาชิก</h1>
      <p class="note">สมัครแล้วระบบจะสร้าง “ร้านของฉัน” ให้โดยอัตโนมัติ</p>
    </div>

    <div class="grid-2">
      <!-- เข้าสู่ระบบ -->
      <div class="card">
        <div class="narrow">
          <h3>เข้าใช้งาน</h3>

          <div class="field">
            <label for="loginEmail">อีเมล</label>
            <input id="loginEmail" type="email" placeholder="เช่น pla01@gmail.com" autocomplete="email" />
          </div>

          <div class="field">
            <label for="loginPassword">รหัสผ่าน</label>
            <input id="loginPassword" type="password" placeholder="รหัสผ่าน" autocomplete="current-password" />
          </div>

          <div class="spacer"></div>
          <button id="btnLogin" class="btn primary block">เข้าสู่ระบบ</button>
        </div>
      </div>

      <!-- สมัครสมาชิก -->
      <div class="card">
        <div class="narrow">
          <h3>สมัครสมาชิก (สำหรับผู้ที่ยังไม่ได้เป็นสมาชิก)</h3>

          <div class="field">
            <label for="regName">ชื่อที่แสดง</label>
            <input id="regName" type="text" placeholder="เช่น ป้าทอง (ร้านข้าวแกง)" autocomplete="name" />
          </div>

          <div class="field">
            <label for="regEmail">อีเมลสำหรับล็อกอิน</label>
            <input id="regEmail" type="email" placeholder="เช่น owner@gmail.com" autocomplete="email" />
          </div>

          <!-- ✅ ประเภทร้าน -->
          <div class="field">
            <label>ประเภทร้าน</label>
            <div class="shoptype">
              <input type="radio" id="type-food" name="shopType" value="food" checked>
              <label for="type-food" class="pill">🍛 ร้านอาหาร</label>
              <input type="radio" id="type-drink" name="shopType" value="drink">
              <label for="type-drink" class="pill">🥤 ร้านเครื่องดื่ม</label>
              <input type="radio" id="type-both" name="shopType" value="both">
              <label for="type-both" class="pill">🍱 ร้านอาหารและเครื่องดื่ม</label>
            </div>
          <div class="note">เลือกได้เพียง 1 ประเภท (แก้ไขได้ภายหลัง)</div>
          </div>
          <!-- /ประเภทร้าน -->

          <div class="field">
            <label for="regStoreName">ชื่อร้านของคุณ</label>
            <input id="regStoreName" type="text" placeholder="เช่น ร้านข้าวแกงป้าทอง" />
          </div>

          <div class="field">
            <label for="regStoreLogo">โลโก้ร้าน (ไม่บังคับ)</label>
            <input id="regStoreLogo" type="file" accept="image/*" />
            <div class="note">รองรับ JPG/PNG ≤ 2MB</div>
          </div>
          
          <div class="row">
            <div class="field">
              <label for="regPassword">ตั้งรหัสผ่าน</label>
              <input id="regPassword" type="password" placeholder="อย่างน้อย 6 ตัวอักษร" autocomplete="new-password" />
            </div>
            <div class="field">
              <label for="regPassword2">ยืนยันรหัสผ่าน</label>
              <input id="regPassword2" type="password" placeholder="พิมพ์รหัสผ่านซ้ำ" autocomplete="new-password" />
            </div>
          </div>

          <div class="spacer"></div>
          <button id="btnRegister" class="btn success block">สมัครสมาชิก</button>
        </div>
      </div>
    </div>
  </div>

  <!-- สคริปต์เดิมของคุณ -->
  <script src="firebase-config.js"></script>
  <script type="module" src="app.js"></script>

  <!-- (ไม่บังคับ) ตัวอย่างอ่านค่าไว้เทสเร็ว ๆ: ลบได้ถ้าใช้ใน app.js แล้ว -->
  <script>
    // แค่ตัวอย่าง debug: กดสมัครแล้ว log shopType
    document.getElementById('btnRegister')?.addEventListener('click', () => {
      const shopType = document.querySelector('input[name="shopType"]:checked')?.value;
      console.log('Selected shopType =', shopType); // 'food' หรือ 'drink'
    });
  </script>
</body>
</html>
