// Firebase SDK v11
import { initializeApp } from "https://www.gstatic.com/firebasejs/11.0.1/firebase-app.js";
import {
  getAuth, onAuthStateChanged,
  signInWithEmailAndPassword, createUserWithEmailAndPassword, signOut, updateProfile
} from "https://www.gstatic.com/firebasejs/11.0.1/firebase-auth.js";
import {
  getFirestore, doc, getDoc, setDoc, updateDoc,
  collection, addDoc, onSnapshot, query, where, orderBy, serverTimestamp, deleteDoc, limit, getDocs
} from "https://www.gstatic.com/firebasejs/11.0.1/firebase-firestore.js";
import {
  getStorage, ref as sRef, uploadBytes, getDownloadURL
} from "https://www.gstatic.com/firebasejs/11.0.1/firebase-storage.js";

/* ----------------------------------------------------
   Init
---------------------------------------------------- */
const app  = initializeApp(window.firebaseConfig);
const auth = getAuth(app);
const db   = getFirestore(app);
const storage = getStorage(app);

const page = document.body.dataset.page || "";
const userEmailEl = document.getElementById('userEmail');
const go    = (url) => (window.location.href = url);
const toast = (m) => alert(m);

/* ----------------------------------------------------
   Helpers
---------------------------------------------------- */

// ADDED: อ่านค่าประเภทร้านจากหน้า register และแปลงเป็น label ภาษาไทย
function selectedShopType(){
  return (document.querySelector('input[name="shopType"]:checked')?.value) || 'food';
}
function shopTypeToLabel(v){
  if (v === 'drink') return 'ร้านเครื่องดื่ม';
  if (v === 'both')  return 'ทั้งอาหารและเครื่องดื่ม';
  return 'ร้านอาหาร';
}

async function ensureUserDoc(user){
  const uref = doc(db, 'users', user.uid);
  const snap = await getDoc(uref);
  if (!snap.exists()){
    await setDoc(uref, {
      email: user.email,
      displayName: user.displayName || '',
      role: 'store',      // ผู้สมัครใหม่ = เจ้าของร้าน
      storeId: null,
      createdAt: serverTimestamp()
    });
  }
}
async function userDoc(uid){
  const uref = doc(db, 'users', uid);
  const snap = await getDoc(uref);
  return snap.exists() ? snap.data() : null;
}
const fmtDate = (ts) => {
  if (ts?.toDate) {
    const d = ts.toDate();
    const dd = String(d.getDate()).padStart(2,'0');
    const mm = String(d.getMonth()+1).padStart(2,'0');
    const yy = d.getFullYear();
    return `${dd}-${mm}-${yy}`;
  }
  return '-';
};

// แปลง path/gs:// → https (getDownloadURL)
async function resolveImageUrl(raw) {
  try {
    if (!raw) return null;
    if (/^https?:\/\//i.test(raw)) return raw; // เป็น https แล้ว
    const m = raw.match(/^gs:\/\/([^/]+)\/(.+)$/i);
    const path = m ? m[2] : raw; // กรณีเก็บ path ธรรมดา
    const ref = sRef(storage, path);
    return await getDownloadURL(ref);
  } catch (e) {
    console.warn('resolveImageUrl failed:', e);
    return null;
  }
}

const PLACEHOLDER = "https://placehold.co/88x88?text=No+Img";

// เตะเจ้าของร้านที่ถูกแบนออกหลังล็อกอิน
async function kickIfBanned(user) {
  try {
    const me = await userDoc(user.uid);
    if (me?.role === 'super') return; // super ไม่เตะ

    const qy = query(collection(db,'stores'), where('ownerUid','==', user.uid), limit(1));
    const snap = await getDocs(qy);
    if (!snap.empty) {
      const d = snap.docs[0].data();
      if (d.isBanned) {
        alert('บัญชีร้านนี้ถูกแบน');
        await signOut(auth);
        location.href = 'login.php';
      }
    }
  } catch (e) {
    console.warn('kickIfBanned failed:', e);
  }
}

onAuthStateChanged(auth, async (user) => {
  const page = document.body.dataset.page; // เช่น 'login' หรือ 'dashboard'
  const userEmailEl = document.getElementById('userEmail');

  if (userEmailEl) userEmailEl.textContent = user?.email || '';

  /* ================== LOGIN PAGE ================== */
  if (page === 'login') {
    if (user) {
      go('dashboard.php');
      return;
    }

    const $ = (id) => document.getElementById(id);
    const els = {
      loginEmail: $('loginEmail'),
      loginPassword: $('loginPassword'),
      btnLogin: $('btnLogin'),
      regName: $('regName'),
      regEmail: $('regEmail'),
      regStoreName: $('regStoreName'),
      regPassword: $('regPassword'),
      regPassword2: $('regPassword2'),
      btnRegister: $('btnRegister'),
      regStoreLogo: $('regStoreLogo'),
    };

    // --- login ---
    els.btnLogin?.addEventListener('click', async () => {
      try {
        els.btnLogin.disabled = true;
        const email = (els.loginEmail?.value || '').trim();
        const pass  = (els.loginPassword?.value || '').trim();
        await signInWithEmailAndPassword(auth, email, pass);
        go('dashboard.php');
      } catch (e) {
        toast('เข้าสู่ระบบไม่สำเร็จ: ' + e.message);
      } finally {
        els.btnLogin.disabled = false;
      }
    });

    // --- register ---
    els.btnRegister?.addEventListener('click', async () => {
      try {
        els.btnRegister.disabled = true;

        const name = (els.regName?.value || '').trim();
        const email = (els.regEmail?.value || '').trim();
        const p1   = (els.regPassword?.value || '').trim();
        const p2   = (els.regPassword2?.value || '').trim();
        const storeName = (els.regStoreName?.value || '').trim();
        const storeLogoFile = els.regStoreLogo?.files?.[0] || null;

        const shopType = selectedShopType();              // 'food' | 'drink' | 'both'
        const categoryLabel = shopTypeToLabel(shopType);  // 'ร้านอาหาร' | 'ร้านเครื่องดื่ม' | 'ทั้งสอง'

        if (!name || !email || !p1) return toast('กรอกข้อมูลให้ครบ');
        if (p1 !== p2) return toast('รหัสผ่านไม่ตรงกัน');
        if (storeLogoFile && storeLogoFile.size > 2 * 1024 * 1024)
          return toast('ไฟล์โลโก้ใหญ่เกิน 2MB');

        const cred = await createUserWithEmailAndPassword(auth, email, p1);
        await updateProfile(cred.user, { displayName: name });
        await ensureUserDoc(cred.user);

        await setDoc(doc(db, 'users', cred.user.uid), {
          preferredShopType: shopType,
        }, { merge: true });

        const existed = await getDocs(query(
          collection(db, 'stores'),
          where('ownerUid', '==', cred.user.uid),
          limit(1)
        ));

        let storeRef;
        if (existed.empty) {
          storeRef = await addDoc(collection(db, 'stores'), {
            name: storeName || `${name} - ร้านของฉัน`,
            description: '',
            category: categoryLabel,
            shopType,
            imageUrl: null,
            ownerUid: cred.user.uid,
            isBanned: false,
            createdAt: serverTimestamp(),
          });
          await updateDoc(doc(db, 'users', cred.user.uid), { storeId: storeRef.id });
        } else {
          storeRef = existed.docs[0].ref;
          const sd = existed.docs[0].data();
          if (!sd.shopType) {
            await updateDoc(storeRef, { shopType, category: categoryLabel });
          }
        }

        if (storeLogoFile && storeRef) {
          const path = `stores/${cred.user.uid}/${storeRef.id}/logo_${Date.now()}_${storeLogoFile.name}`;
          const r = sRef(storage, path);
          await uploadBytes(r, storeLogoFile);
          const url = await getDownloadURL(r);
          await updateDoc(storeRef, { imageUrl: url });
        }

        toast('สมัครและสร้างร้านสำเร็จ');
        go('dashboard.php');
      } catch (e) {
        toast('สมัครสมาชิกไม่สำเร็จ: ' + e.message);
      } finally {
        els.btnRegister.disabled = false;
      }
    });

    return; // ✅ สำคัญมาก — หยุดที่หน้า login
  }

  /* ================== REQUIRE LOGIN FOR OTHER PAGES ================== */
  if (page !== 'login' && !user) {
    go('login.php');
    return;
  }

  /* ================== AFTER LOGIN ================== */
  await ensureUserDoc(user);

  if (user?.email?.toLowerCase() === 'admin@gmail.com') {
    await setDoc(doc(db, 'users', user.uid), { role: 'super' }, { merge: true });
  }

  await kickIfBanned(user);
  const me = await userDoc(user.uid);

  /* ================== DASHBOARD PAGE ================== */
  if (page === 'dashboard' && user) {
    const userEmailEl = document.getElementById('userEmail');
    if (userEmailEl) userEmailEl.textContent = user.email;

    const logoutBtn = document.querySelector('.btn.nav-danger');
    if (logoutBtn) {
      logoutBtn.addEventListener('click', async (e) => {
        e.preventDefault();
        await signOut(auth);
        go('login.php');
      });
    }
  }

  /* ================== LOGOUT PAGE ================== */
  if (page === 'logout') {
    await signOut(auth);
    go('login.php');
    return;
  }

/* ============= DASHBOARD (ตาราง) ============= */
if (page === 'dashboard') {
  const roleBadge = document.getElementById('roleBadge');
  const tbody = document.getElementById('storesBody');

  // แปลง shopType -> label ไทย (ใช้ร่วมกัน)
  function shopTypeToLabel(v){
    if (v === 'drink') return 'ร้านเครื่องดื่ม';
    if (v === 'both')  return 'ร้านอาหารและเครื่องดื่ม';
    return 'ร้านอาหาร';
  }

  // แถวของตาราง — ใช้ shopType เป็นหลัก
  const rowHtml = (id, d, opts = {}) => {
    const { isSuper = false, canManage = false } = opts;
    const cat  = shopTypeToLabel(d.shopType || 'food'); // ✅ ใช้ shopType ก่อน
    const when = fmtDate(d.createdAt);

    const imgCell = `
      <div class="img-cell">
        <img class="avatar" src="${d.imageUrl || PLACEHOLDER}" alt="${d.name || ''}">
        ${canManage ? `
          <button class="btn tiny change-photo" data-id="${id}">เปลี่ยนรูป</button>
          <input type="file" accept="image/*" class="hidden file-logo" data-id="${id}">
        ` : ``}
      </div>
    `;

    const manageCol = isSuper
      ? `<button class="btn ${d.isBanned ? 'unban' : 'ban'}" data-act="toggleBan" data-id="${id}" data-banned="${d.isBanned ? 1 : 0}">
           ${d.isBanned ? 'ปลดแบน' : 'แบนร้าน'}
         </button>`
      : `<button class="btn edit" ${canManage ? '' : 'disabled'} data-act="edit">แก้ไขร้าน</button>
         <button class="btn menu" ${canManage ? '' : 'disabled'} data-act="menu">เพิ่มเมนู</button>
         <button class="btn orders" ${canManage ? '' : 'disabled'} data-act="orders">ดูคำสั่งซื้อ</button>`;

    return `
      <tr data-id="${id}">
        <td>${d.name || '-'}</td>
        <td>${cat}</td>
        <td>${imgCell}</td>
        <td>${when}</td>
        <td>${manageCol}</td>
      </tr>`;
  };

  try {
    // ---------- มุมมองแอดมินใหญ่ ----------
    if (me.role === 'super') {
      roleBadge.textContent = 'คุณคือ: แอดมินใหญ่';

      const qy = query(collection(db,'stores')); // ไม่ orderBy เพื่อเลี่ยง index
      onSnapshot(qy, async (snap) => {
        tbody.innerHTML = '';
        if (snap.empty) {
          tbody.innerHTML = `<tr><td colspan="5" class="muted" style="text-align:center;background:#fff;border:1px solid #e5e7eb;border-radius:8px">ยังไม่มีร้าน</td></tr>`;
          return;
        }
        const rows = await Promise.all(snap.docs.map(async (s) => {
          const d = s.data();
          d.imageUrl = (await resolveImageUrl(d.imageUrl)) || PLACEHOLDER;
          return rowHtml(s.id, d, { isSuper:true });
        }));
        tbody.innerHTML = rows.join('');
      });

      // แบน/ปลดแบน
      tbody.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-act="toggleBan"]');
        if (!btn) return;
        try{
          const id = btn.dataset.id;
          const banned = btn.dataset.banned === '1';
          await updateDoc(doc(db,'stores', id), { isBanned: !banned });
        }catch(err){ toast('อัปเดตสถานะไม่สำเร็จ: ' + err.message); }
      });

    // ---------- มุมมองเจ้าของร้าน ----------
    } else {
      // หา/สร้างร้านของ owner (ไม่ orderBy/ไม่ต้องทำ index)
      const qy = query(
        collection(db,'stores'),
        where('ownerUid','==',auth.currentUser.uid),
        limit(1)
      );
      let snap = await getDocs(qy);

      if (snap.empty) {
        const base =
          (auth.currentUser.displayName && auth.currentUser.displayName.trim()) ||
          (auth.currentUser.email && auth.currentUser.email.split('@')[0]) ||
          'ร้านของฉัน';

        const pref = (me?.preferredShopType) || 'food';
        const categoryLabel = shopTypeToLabel(pref);

        const ref = await addDoc(collection(db,'stores'), {
          name: base,
          description: '',
          category: categoryLabel,   // เก็บ label ไทย
          shopType: pref,            // เก็บค่าดิบ
          imageUrl: null,
          ownerUid: auth.currentUser.uid,
          isBanned: false,
          createdAt: serverTimestamp()
        });
        await updateDoc(doc(db,'users',auth.currentUser.uid), { storeId: ref.id });

        // โหลดใหม่
        snap = await getDocs(qy);
      }

      // เอกสารร้าน
      const storeDoc = snap.docs[0];
      const d = storeDoc.data();
      d.imageUrl = (await resolveImageUrl(d.imageUrl)) || PLACEHOLDER;

      // PATCH: ถ้าร้านเก่ายังไม่มี shopType ให้เติมให้สอดคล้องกับ preferred
      if (!d.shopType) {
        const pref = (me?.preferredShopType) || 'food';
        const label = shopTypeToLabel(pref);
        try {
          await updateDoc(storeDoc.ref, { shopType: pref, category: label });
          d.shopType = pref;
          d.category = label;
        } catch (e) {
          console.warn('patch shopType failed:', e);
        }
    }

      // Badge + ตาราง (ครั้งเดียวหลัง PATCH)
      roleBadge.textContent =
  `คุณคือ: แอดมินร้าน (${shopTypeToLabel(d.shopType || me?.preferredShopType || 'food')}, ร้าน: ${d.name || 'ร้านของฉัน'})`;

  tbody.innerHTML = rowHtml(storeDoc.id, d, { canManage:true });

      // ไปหน้าอื่น
      tbody.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-act]');
        if (!btn) return;
        const act = btn.dataset.act;
        if      (act === 'edit')   go('edit_store.php');
        else if (act === 'menu')   go('view_store.php');
        else if (act === 'orders') go('orders.php');
      });

      // เปลี่ยนรูป
      tbody.addEventListener('click', (e) => {
        const changeBtn = e.target.closest('.change-photo');
        if (!changeBtn) return;
        const id = changeBtn.dataset.id;
        const input = tbody.querySelector(`.file-logo[data-id="${id}"]`);
        if (input) input.click();
      });

      // อัปโหลดรูปใหม่
      tbody.addEventListener('change', async (e) => {
        const fileInput = e.target.closest('.file-logo');
        if (!fileInput) return;

        const id = fileInput.dataset.id;
        const file = fileInput.files?.[0];
        if (!file) return;
        if (file.size > 3 * 1024 * 1024) { toast('ไฟล์รูปใหญ่เกิน 3MB'); fileInput.value=''; return; }

        try{
          const path = `stores/${auth.currentUser.uid}/${id}/logo_${Date.now()}_${file.name}`;
          const r = sRef(storage, path);
          await uploadBytes(r, file);
          const httpsUrl = await getDownloadURL(r);
          await updateDoc(doc(db,'stores', id), { imageUrl: httpsUrl });

          const tr = tbody.querySelector(`tr[data-id="${id}"]`);
          const img = tr?.querySelector('img.avatar');
          if (img) img.src = httpsUrl;

          toast('อัปเดตรูปร้านเรียบร้อย');
        }catch(err){
          console.error(err);
          toast('อัปโหลดรูปไม่สำเร็จ: ' + err.message);
        }finally{
          fileInput.value = '';
        }
      });
    }

  } catch (err) {
    console.error('Dashboard error:', err);
    if (tbody) {
      tbody.innerHTML = `<tr><td colspan="5" class="muted">โหลดข้อมูลไม่สำเร็จ: ${err?.message || err}</td></tr>`;
    }
    if (roleBadge) roleBadge.textContent = 'เกิดข้อผิดพลาดในการโหลดข้อมูล';
  }
  return;
}

  /* ============= ADD STORE (รองรับหน้าเก่า) ============= */
  if (page === 'add_store'){
    const hasStoreBox    = document.getElementById('hasStoreBox');
    const createStoreBox = document.getElementById('createStoreBox');
    try{
      const qy = query(collection(db,'stores'), where('ownerUid','==',user.uid), limit(1));
      const snap = await getDocs(qy);
      if (!snap.empty){ hasStoreBox.style.display='block'; createStoreBox.style.display='none'; }
      else{
        hasStoreBox.style.display='none'; createStoreBox.style.display='block';
        document.getElementById('btnCreateStore')?.addEventListener('click', async ()=>{
          const name = (document.getElementById('storeName')?.value || '').trim();
          const desc = (document.getElementById('storeDesc')?.value || '').trim();
          if (!name) return toast('กรุณากรอกชื่อร้าน');

          // CHANGED: เคารพ preferredShopType ของผู้ใช้
          const pref = (me?.preferredShopType) || 'food';
          const categoryLabel = shopTypeToLabel(pref);

          const ref = await addDoc(collection(db,'stores'), {
            name, description:desc,
            category: categoryLabel, imageUrl:null,
            shopType: pref,
            ownerUid:user.uid, isBanned:false, createdAt:serverTimestamp()
          });
          await updateDoc(doc(db,'users',user.uid), { storeId: ref.id });
          toast('สร้างร้านสำเร็จ'); go('view_store.php');
        });
      }
    }catch(e){ toast('โหลดข้อมูลไม่สำเร็จ: '+e.message); }
    return;
  }

 /* ============= EDIT STORE (owner แก้ได้เฉพาะ name/description) ============= */
if (page === 'edit_store') {
  const nameEl = document.getElementById('editStoreName');
  const descEl = document.getElementById('editStoreDesc');
  const btn    = document.getElementById('btnUpdateStore');
  const noStore= document.getElementById('noStore');
  const form   = document.getElementById('editForm');

  try {
    // ใช้ storeId จาก users ก่อน
    const meDoc = await userDoc(auth.currentUser.uid);
    let storeId = meDoc?.storeId || null;

    // ถ้าไม่มี storeId ให้ fallback query ตาม ownerUid
    if (!storeId) {
      const qy   = query(collection(db,'stores'), where('ownerUid','==',auth.currentUser.uid), limit(1));
      const snap = await getDocs(qy);
      if (!snap.empty) storeId = snap.docs[0].id;
    }

    if (!storeId) { noStore.style.display='block'; form.style.display='none'; return; }

    const sref = doc(db,'stores', storeId);
    const ss   = await getDoc(sref);
    if (!ss.exists()) { noStore.style.display='block'; form.style.display='none'; return; }

    const data = ss.data();
    nameEl.value = data.name || '';
    descEl.value = data.description || '';
    form.style.display = 'block';

    btn.onclick = async () => {
      const name = (nameEl.value || '').trim();
      const desc = (descEl.value || '').trim();
      if (!name) return toast('กรุณากรอกชื่อร้าน');

      try {
        await updateDoc(sref, { name, description: desc });
        toast('บันทึกเรียบร้อย');
        // ✅ เพิ่มบรรทัดนี้ให้ redirect ไปหน้าหลักหลังบันทึก
        go('dashboard.php'); 
      } catch (e) {
        toast('บันทึกไม่สำเร็จ: ' + e.message);
      }
    };
  } catch (e) {
    toast('โหลดข้อมูลร้านไม่สำเร็จ: ' + e.message);
  }
  return;
}
/* ============= VIEW STORE (เมนู) ============= */
if (page === 'view_store') {
  try {
    // หา ID ร้านของ owner
    const qy = query(collection(db,'stores'), where('ownerUid','==',auth.currentUser.uid), limit(1));
    const snap = await getDocs(qy);
    if (snap.empty) {
      const n = document.getElementById('noStore');
      if (n) n.style.display = 'block';
      return;
    }
    const storeDoc = snap.docs[0];
    const storeId = storeDoc.id;

    // อ้างอิง DOM
    const menusGrid     = document.getElementById('menusGrid');
    const storeHeader   = document.getElementById('storeHeader');
    const bannedAlert   = document.getElementById('bannedAlert');
    const btnAddMenu    = document.getElementById('btnAddMenu');
    const nameEl        = document.getElementById('menuName');
    const priceEl       = document.getElementById('menuPrice');
    const imgUrlEl      = document.getElementById('menuImg');
    const imgFileEl     = document.getElementById('menuImgFile');

    // modal แก้ไข
    const editModal     = document.getElementById('editMenuModal');
    const backdrop      = document.getElementById('editMenuBackdrop');
    const editName      = document.getElementById('editMenuName');
    const editPrice     = document.getElementById('editMenuPrice');
    const editFile      = document.getElementById('editMenuFile');
    const btnSaveMenu   = document.getElementById('btnSaveMenu');
    const btnCancelEdit = document.getElementById('btnCancelEdit');

    let editingMenuId = null;

    // header ร้าน + สถานะแบน
    onSnapshot(doc(db,'stores', storeId), (ds) => {
      const d = ds.data();
      if (storeHeader) storeHeader.innerHTML = `
        <div class="store-header">
          <div>
            <h3>${d.name || 'ร้านของฉัน'}</h3>
            <div class="muted">${d.description || ''}</div>
          </div>
          <div class="pill ${d.isBanned ? 'ban':'ok'}">${d.isBanned ? 'ถูกแบน':'ปกติ'}</div>
        </div>`;
      if (bannedAlert) bannedAlert.style.display = d.isBanned ? 'block' : 'none';
      if (btnAddMenu)  btnAddMenu.disabled      = !!d.isBanned;
    });

    // แสดงรายการเมนู
    const mq = query(collection(db,'stores',storeId,'menus'), orderBy('createdAt','desc'));
    onSnapshot(mq, async (msnap) => {
      if (!menusGrid) return;
      menusGrid.innerHTML = '';
      if (msnap.empty) {
        menusGrid.innerHTML = '<div class="muted">ยังไม่มีเมนู</div>';
        return;
      }
      const cards = await Promise.all(msnap.docs.map(async (m) => {
        const d = m.data();
        const imgUrl = (await resolveImageUrl(d.imageUrl)) || '';
        return `
          <div class="card" data-id="${m.id}">
            ${imgUrl ? `<img class="menu-img" src="${imgUrl}" alt="">` : ''}
            <div class="menu-title" style="margin-top:8px">${d.name}</div>
            <div class="menu-price">${Number(d.price||0).toLocaleString()} บาท</div>
            <div style="height:8px"></div>
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
              <button class="btn small" data-edit="${m.id}">แก้ไข</button>
              <button class="btn danger small" data-del="${m.id}">ลบ</button>
            </div>
          </div>`;
      }));
      menusGrid.innerHTML = cards.join('');
    });

    // เพิ่มเมนูใหม่
    if (btnAddMenu) btnAddMenu.onclick = async () => {
      try {
        const name  = (nameEl?.value || '').trim();
        const price = Number(priceEl?.value || 0);
        const urlFromInput = (imgUrlEl?.value || '').trim();
        const file = imgFileEl?.files?.[0] || null;

        if (!name) return toast('กรุณากรอกชื่อเมนู');
        if (Number.isNaN(price) || price < 0) return toast('กรุณากรอกราคาเป็นตัวเลข');
        if (file && file.size > 3 * 1024 * 1024) return toast('ไฟล์รูปใหญ่เกิน 3MB');

        const newRef = await addDoc(collection(db,'stores',storeId,'menus'), {
          name, price, imageUrl: urlFromInput || null, createdAt: serverTimestamp()
        });

        if (file) {
          const path = `stores/${storeId}/menus/${newRef.id}/img_${Date.now()}_${file.name}`;
          const r = sRef(storage, path);
          await uploadBytes(r, file);
          const httpsUrl = await getDownloadURL(r);
          await updateDoc(newRef, { imageUrl: httpsUrl });
        }

        if (nameEl)  nameEl.value = '';
        if (priceEl) priceEl.value = '';
        if (imgUrlEl) imgUrlEl.value = '';
        if (imgFileEl) imgFileEl.value = '';
        toast('เพิ่มเมนูแล้ว');
      } catch (e) {
        console.error(e);
        toast('เพิ่มเมนูไม่สำเร็จ: ' + e.message);
      }
    };

    // ⚡ จับคลิกปุ่มแก้ไข/ลบ ด้วย data-attribute (เสถียรกว่า class)
    menusGrid.addEventListener('click', async (e) => {
      const btnEdit = e.target.closest('[data-edit]');
      const btnDel  = e.target.closest('[data-del]');

      // ลบเมนู
      if (btnDel) {
        const id = btnDel.getAttribute('data-del');
        if (!confirm('ลบเมนูนี้?')) return;
        try {
          await deleteDoc(doc(db,'stores',storeId,'menus',id));
          toast('ลบเมนูเรียบร้อย');
        } catch (err) {
          console.error(err);
          toast('ลบเมนูไม่สำเร็จ: ' + err.message);
        }
        return;
      }

      // แก้ไขเมนู
      if (btnEdit) {
        const id = btnEdit.getAttribute('data-edit');
        try {
          const md = await getDoc(doc(db,'stores',storeId,'menus',id));
          if (!md.exists()) return toast('ไม่พบเมนู');

          const m = md.data();
          editingMenuId = id;
          editName.value  = m.name || '';
          editPrice.value = m.price || 0;
          editFile.value  = '';
          if (backdrop)  backdrop.style.display = 'block';
          if (editModal) editModal.style.display = 'block';
        } catch (err) {
          console.error(err);
          toast('โหลดข้อมูลเมนูไม่สำเร็จ: ' + err.message);
        }
        return;
      }
    });

    // บันทึกเมนูที่แก้ไข
    if (btnSaveMenu) btnSaveMenu.onclick = async () => {
      if (!editingMenuId) return;
      try {
        const name  = editName.value.trim();
        const price = Number(editPrice.value || 0);
        if (!name) return toast('กรุณากรอกชื่อเมนู');
        if (Number.isNaN(price) || price < 0) return toast('กรุณากรอกราคาให้ถูกต้อง');

        const up = { name, price };
        const file = editFile.files?.[0] || null;
        if (file) {
          if (file.size > 3 * 1024 * 1024) return toast('ไฟล์รูปใหญ่เกิน 3MB');
          const path = `stores/${storeId}/menus/${editingMenuId}/img_${Date.now()}_${file.name}`;
          const r = sRef(storage, path);
          await uploadBytes(r, file);
          up.imageUrl = await getDownloadURL(r);
        }

        await updateDoc(doc(db,'stores',storeId,'menus',editingMenuId), up);
        toast('อัปเดตเมนูเรียบร้อย');
      } catch (err) {
        console.error(err);
        toast('อัปเดตเมนูไม่สำเร็จ: ' + err.message);
      } finally {
        editingMenuId = null;
        if (backdrop)  backdrop.style.display = 'none';
        if (editModal) editModal.style.display = 'none';
      }
    };

    // ยกเลิกแก้ไข
    if (btnCancelEdit) btnCancelEdit.onclick = () => {
      editingMenuId = null;
      if (backdrop)  backdrop.style.display = 'none';
      if (editModal) editModal.style.display = 'none';
    };

  } catch (e) {
    console.error('view_store error:', e);
    toast('โหลดหน้าจัดการเมนูไม่สำเร็จ: ' + e.message);
  }
  return;
}


  /* ============= ORDERS ============= */
  if (page === 'orders'){
    const qy = query(collection(db,'stores'), where('ownerUid','==',user.uid), limit(1));
    const snap = await getDocs(qy);
    if (snap.empty){ const n=document.getElementById('noStore'); if(n) n.style.display='block'; return; }
    const storeDoc = snap.docs[0];

    const tbody = document.getElementById('ordersBody');
    const oq = query(collection(db,'stores',storeDoc.id,'orders'), orderBy('createdAt','desc'));
    onSnapshot(oq, osnap => {
      tbody.innerHTML='';
      if (osnap.empty){ tbody.innerHTML='<tr><td colspan="5" class="muted">ยังไม่มีออเดอร์</td></tr>'; return; }
      osnap.forEach(o => {
        const d = o.data();
        const when = d.createdAt?.toDate ? d.createdAt.toDate() : new Date();
        const total = (d.items||[]).reduce((s,i)=>s + (i.price * i.qty), 0);
        const itemsStr = (d.items||[]).map(i => `${i.name} x${i.qty}`).join(', ');
        const tr = document.createElement('tr');
        tr.innerHTML = `<td>${when.toLocaleString()}</td><td>${d.customerName || '-'}</td><td>${itemsStr}</td><td>${Number(total).toLocaleString()} บาท</td><td>${d.status||'pending'}</td>`;
        tbody.appendChild(tr);
      });
    });
    return;
  }

  /* ============= BAN STORE (ถ้ามีหน้าเก่า ไว้ดูเฉย ๆ) ============= */
  if (page === 'ban_store'){
    if (me.role !== 'super'){ toast('หน้านี้สำหรับแอดมินใหญ่เท่านั้น'); go('dashboard.php'); return; }
    const grid = document.getElementById('storesGrid');
    const qy = query(collection(db,'stores'), orderBy('name'));
    onSnapshot(qy, snap => {
      grid.innerHTML='';
      if (snap.empty){ grid.innerHTML='<div class="muted">ยังไม่มีร้าน</div>'; return; }
      snap.forEach(s => {
        const d = s.data();
        const card = document.createElement('div'); card.className='card';
        card.innerHTML = `
          <div class="store-header">
            <div>
              <div class="menu-title" style="font-size:18px">${d.name || '(ไม่มีชื่อร้าน)'}</div>
              <div class="muted">Owner UID: ${d.ownerUid || '-'}</div>
              <div class="muted">Store ID: ${s.id}</div>
            </div>
            <div class="pill ${d.isBanned ? 'ban':'ok'}">${d.isBanned ? 'ถูกแบน':'ปกติ'}</div>
          </div>`;
        grid.appendChild(card);
      });
    });
    return;
  }

  /* ============= ADMIN PROFILE ============= */
if (page === 'admin_profile'){
  const u = await userDoc(user.uid);
  const el = (id,v)=>{ const x=document.getElementById(id); if(x) x.textContent=v; };

  // ข้อมูลผู้ใช้
  el('profEmail', user.email || '-');
  el('profName',  user.displayName || u?.displayName || '-');
  el('profRole',  u?.role || '-');

  // ====== ข้อมูลร้าน ======
  // helper แปลง shopType -> label ไทย (ให้มีในสโคปนี้หรือมีที่ global ก็ได้)
  const shopTypeToLabel = (v)=>{
    if (v === 'drink') return 'ร้านเครื่องดื่ม';
    if (v === 'both')  return 'ทั้งอาหารและเครื่องดื่ม';
    return 'ร้านอาหาร';
  };

  const noBox   = document.getElementById('noStoreProfile');
  const box     = document.getElementById('storeProfileBox');
  const nameEl  = document.getElementById('profStoreName');
  const typeEl  = document.getElementById('profStoreType');
  const descEl  = document.getElementById('profStoreDesc');

  try {
    // วิธีที่ 1: ใช้ storeId จาก users ถ้ามี
    let storeId = u?.storeId || null;

    // วิธีที่ 2: ถ้าไม่มี storeId ให้หาโดย ownerUid
    let storeSnap = null;
    if (storeId) {
      const sref = doc(db,'stores', storeId);
      const sdoc = await getDoc(sref);
      if (sdoc.exists()) {
        storeSnap = sdoc;
      }
    }
    if (!storeSnap) {
      const qy = query(collection(db,'stores'), where('ownerUid','==', user.uid), limit(1));
      const snap = await getDocs(qy);
      if (!snap.empty) storeSnap = snap.docs[0];
    }

    if (!storeSnap) {
      // ไม่มีร้าน
      if (noBox) noBox.style.display = 'block';
      if (box)   box.style.display   = 'none';
      return;
    }

    const d = storeSnap.data();
    // เติมค่า UI
    if (nameEl) nameEl.textContent = d?.name || '-';
    if (typeEl) typeEl.textContent = shopTypeToLabel(d?.shopType || 'food');
    if (descEl) descEl.textContent = d?.description || '-';

    if (noBox) noBox.style.display = 'none';
    if (box)   box.style.display   = 'block';

  } catch (e) {
    console.warn('load admin_profile store failed:', e);
    if (noBox) noBox.style.display = 'block';
    if (box)   box.style.display   = 'none';
  }
  return;
}
});
