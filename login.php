<?php
// ถ้ามี session/check อะไรค่อยเพิ่มด้านบนได้
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
      font-size: 18px;
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

    .hero { text-align: center; margin-bottom: 18px; }
    .hero h1 { margin: 0 0 6px; font-size: 28px; }
    .note { color: #6b7280; font-size: 16px; margin: 0; }

    /* layout 2 คอลัมน์ */
    .grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }
    @media (max-width: 900px) {
      .grid-2 { grid-template-columns: 1fr; }
    }

    .card {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      padding: 16px;
    }
    .card h3 { margin: 0 0 12px; font-size: 22px; }

    .narrow {
      max-width: 480px;
      margin: 0 auto;
    }

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

    .row {
      display: grid; grid-template-columns: 1fr 1fr; gap: 12px;
    }
    @media (max-width: 520px) { .row { grid-template-columns: 1fr; } }

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

    .spacer { height: 10px; }

    /* ประเภทร้านแบบ pill */
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
      display: inline-flex; align-items: center; gap: 8px;
      transition: transform .02s ease-in-out, box-shadow .2s, background .2s, border-color .2s;
    }
    .shoptype .pill:active { transform: scale(0.98); }
    .shoptype input[type="radio"]:checked + .pill {
      background: #eef2ff;
      border-color: #6366f1;
      box-shadow: 0 0 0 2px rgba(99,102,241,.15) inset;
    }

    /* ซ่อน element (ใช้กับกล่อง OTP) */
    .hidden { display: none; }

    /* preview โลโก้ร้าน */
    #regLogoPreview {
      margin-top: 8px;
      max-width: 120px;
      max-height: 120px;
      border-radius: 8px;
      border: 1px solid #e5e7eb;
      object-fit: cover;
      display: none; /* เริ่มต้นซ่อน */
    }
  </style>
</head>

<body data-page="login">
  <!-- แถบบน -->
  <div class="topbar">
    <div class="brand">🍜 ระบบร้านอาหารภายในชุมชน</div>
  </div>

  <div class="wrap">
    <div class="hero">
      <h1>เข้าใช้งาน / สมัครสมาชิก</h1>
      <p class="note">สมัครแล้วระบบจะสร้าง “ร้านของฉัน” ให้โดยอัตโนมัติ</p>
    </div>

    <div class="grid-2">
      <!-- ========= เข้าสู่ระบบ ========= -->
      <div class="card">
        <div class="narrow">
          <h3>เข้าใช้งาน</h3>

          <!-- เบอร์โทร + ปุ่มส่ง OTP -->
          <div class="field">
            <label for="loginPhone">เบอร์โทรศัพท์</label>
            <div style="display:flex; gap:8px;">
              <input id="loginPhone" type="tel" placeholder="เช่น 0811234567" autocomplete="tel" />
              <button id="btnSendLoginOtp" type="button" class="btn">
                ส่งรหัส OTP
              </button>
            </div>
          </div>

          <!-- ช่อง OTP (ซ่อนก่อน) -->
          <div class="field hidden" id="loginOtpGroup">
            <label for="loginOtp">รหัส OTP</label>
            <input
              id="loginOtp"
              type="text"
              placeholder="เช่น 123456"
              inputmode="numeric"
              autocomplete="one-time-code"
            />
          </div>

          <div class="spacer"></div>
          <button id="btnLogin" class="btn primary block" type="button">เข้าสู่ระบบ</button>
        </div>
      </div>

      <!-- ========= สมัครสมาชิก ========= -->
      <div class="card">
        <div class="narrow">
          <h3>สมัครสมาชิก (สำหรับผู้ที่ยังไม่ได้เป็นสมาชิก)</h3>

          <div class="field">
            <label for="regName">ชื่อที่แสดง</label>
            <input id="regName" type="text" placeholder="เช่น ป้าทอง (ร้านข้าวแกง)" autocomplete="name" />
          </div>

          <!-- ประเภทร้าน -->
          <div class="field">
            <label>ประเภทร้าน</label>
            <div class="shoptype">
              <input type="radio" id="type-food" name="shopType" value="food" checked>
              <label for="type-food" class="pill">🍛 ร้านอาหาร</label>

              <input type="radio" id="type-drink" name="shopType" value="drink">
              <label for="type-drink" class="pill">🥤 ร้านเครื่องดื่ม</label>
            </div>
            <div class="note">เลือกได้เพียง 1 ประเภท (แก้ไขได้ภายหลัง)</div>
          </div>

          <div class="field">
            <label for="regStoreName">ชื่อร้านของคุณ</label>
            <input id="regStoreName" type="text" placeholder="เช่น ร้านข้าวแกงป้าทอง" />
          </div>

          <!-- โลโก้ร้าน + preview -->
          <div class="field">
            <label for="regStoreLogo">โลโก้ร้าน (ไม่บังคับ)</label>
            <input id="regStoreLogo" type="file" accept="image/*" />
            <div class="note">รองรับ JPG/PNG ≤ 2MB</div>
            <!-- รูปตัวอย่าง -->
            <img id="regLogoPreview" alt="ตัวอย่างโลโก้ร้าน" />
          </div>

          <!-- เบอร์ + ปุ่มส่ง OTP สมัครสมาชิก -->
          <div class="field">
            <label for="regPhone">เบอร์โทรศัพท์สำหรับล็อกอิน</label>
            <div style="display:flex; gap:8px;">
              <input id="regPhone" type="tel" placeholder="เช่น 0811234567" autocomplete="tel" />
              <button id="btnSendRegOtp" type="button" class="btn">
                ส่งรหัส OTP สำหรับสมัครสมาชิก
              </button>
            </div>
          </div>

          <!-- ช่อง OTP สมัครสมาชิก (ซ่อนก่อน) -->
          <div class="field hidden" id="regOtpGroup">
            <label for="regOtp">รหัส OTP</label>
            <input
              id="regOtp"
              type="text"
              placeholder="เช่น 123456"
              inputmode="numeric"
              autocomplete="one-time-code"
            />
          </div>

          <div class="spacer"></div>
          <button id="btnRegister" class="btn success block" type="button">สมัครสมาชิก</button>
        </div>
      </div>
    </div>
  </div>

  <!-- ✅ สำคัญ: กล่อง reCAPTCHA สำหรับ Phone Auth -->
  <div id="recaptcha-container" style="display:none;"></div>

  <script src="firebase-config.js"></script>
  <script type="module" src="app.js"></script>

  <!-- สคริปต์เล็ก ๆ สำหรับ preview โลโก้ที่เลือก -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const fileInput = document.getElementById('regStoreLogo');
      const preview   = document.getElementById('regLogoPreview');

      if (!fileInput || !preview) return;

      fileInput.addEventListener('change', (e) => {
        const file = e.target.files && e.target.files[0];
        if (!file) {
          preview.style.display = 'none';
          preview.src = '';
          return;
        }
        const url = URL.createObjectURL(file);
        preview.src = url;
        preview.style.display = 'block';
      });
    });
  </script>
</body>
</html>
