<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $mode == 'setup' ? 'Buat PIN' : 'Verifikasi PIN' }} — AI Boss</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root {
    --bg: #090D17; --surface: rgba(255,255,255,0.035);
    --border: rgba(255,255,255,0.09); --blue-bright: #60A5FA;
    --gradient: linear-gradient(120deg, #3B82F6 0%, #8B5CF6 100%);
  }
  * { margin:0; padding:0; box-sizing:border-box; }
  body {
    background: var(--bg); color: #F7F9FC;
    font-family: 'Inter', sans-serif; height: 100vh;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
  }
  .bg-grid {
    position:fixed; inset:0; z-index:-1;
    background-image: linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
    background-size: 40px 40px;
    mask-image: radial-gradient(ellipse 70% 70% at 50% 50%, black 20%, transparent 80%);
  }

  .card {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 24px; padding: 48px 40px; text-align: center;
    box-shadow: 0 40px 100px -20px rgba(0,0,0,0.5);
    backdrop-filter: blur(16px); max-width: 440px; width: 100%;
    position: relative; overflow: hidden;
  }

  h2 { font-family: 'Space Grotesk', sans-serif; font-size: 24px; margin-bottom: 8px; transition: opacity 0.3s; }
  p { color: #9AA7BD; font-size: 14px; margin-bottom: 32px; transition: opacity 0.3s; }

  /* 3D PIN Inputs */
  .pin-container {
    display: flex; gap: 12px; justify-content: center; margin-bottom: 32px;
    transition: opacity 0.3s ease, transform 0.3s ease;
  }
  .pin-box {
    width: 48px; height: 56px; border-radius: 12px;
    background: linear-gradient(145deg, #111827, #0B1120);
    border: 1px solid rgba(255,255,255,0.05);
    box-shadow: 4px 4px 10px rgba(0,0,0,0.4), -2px -2px 6px rgba(255,255,255,0.03), inset 2px 2px 4px rgba(0,0,0,0.4);
    color: #fff; font-size: 24px; font-weight: bold; font-family: 'Space Grotesk', sans-serif;
    text-align: center; transition: all 0.2s ease; outline: none;
  }
  .pin-box:focus {
    transform: translateY(-2px); border-color: var(--blue-bright);
    box-shadow: 0 0 15px rgba(96,165,250,0.3), inset 2px 2px 4px rgba(0,0,0,0.4);
  }

  .btn {
    width: 100%; padding: 14px; border-radius: 100px;
    background: var(--gradient); color: #fff; font-weight: 600; font-size: 15px;
    border: none; cursor: pointer; transition: transform 0.2s;
    box-shadow: 0 1px 0 rgba(255,255,255,0.2) inset, 0 8px 24px -8px rgba(59,130,246,0.5);
  }
  .btn:hover { transform: translateY(-2px); }

  .error-text { color: #F87171; font-size: 13px; margin-bottom: 20px; display: none; }
  @if($errors->any())
    .server-error { color: #F87171; font-size: 13px; margin-bottom: 20px; }
  @endif

  /* ANIMASI LOADING OVERLAY */
  .loading-overlay {
    position: absolute; inset: 0; background: rgba(9, 13, 23, 0.9);
    backdrop-filter: blur(8px); display: flex; flex-direction: column;
    align-items: center; justify-content: center; z-index: 10;
    opacity: 0; pointer-events: none; transition: opacity 0.4s ease;
  }
  .loading-overlay.active { opacity: 1; pointer-events: auto; }

  .spinner {
    width: 56px; height: 56px; border: 3px solid rgba(96,165,250,0.2);
    border-top-color: #60A5FA; border-radius: 50%;
    animation: spin 1s linear infinite; box-shadow: 0 0 20px rgba(96,165,250,0.4);
  }
  @keyframes spin { to { transform: rotate(360deg); } }

  .check-icon {
    width: 56px; height: 56px; background: rgba(74,222,128,0.15);
    border: 2px solid #4ADE80; border-radius: 50%; display: flex;
    align-items: center; justify-content: center; color: #4ADE80;
    font-size: 28px; font-weight: bold; box-shadow: 0 0 30px rgba(74,222,128,0.3);
  }

  .verified-pop { animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
  @keyframes popIn {
    0% { transform: scale(0.5); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
  }

  .loading-text {
    margin-top: 20px; font-weight: 600; font-family: 'Space Grotesk', sans-serif;
    letter-spacing: 1px; transition: color 0.4s;
  }
  .text-process { color: #60A5FA; text-shadow: 0 0 10px rgba(96,165,250,0.5); }
  .text-success { color: #4ADE80; text-shadow: 0 0 10px rgba(74,222,128,0.5); }
</style>
</head>
<body>

<div class="bg-grid"></div>

<div class="card">

  <!-- OVERLAY LOADING -->
  <div class="loading-overlay" id="loadingOverlay">
    <div id="loadingIcon" class="spinner"></div>
    <div id="loadingText" class="loading-text text-process">Memverifikasi...</div>
  </div>

  <h2 id="pageTitle">{{ $mode == 'setup' ? 'Buat PIN Baru' : 'Masukkan PIN' }}</h2>
  <p id="pageSubtitle">{{ $mode == 'setup' ? 'Buat 6 digit PIN rahasia untuk melindungi operasional bisnis Anda.' : 'Masukkan 6 digit PIN untuk membuka dashboard.' }}</p>

  <div id="jsError" class="error-text"></div>
  @if($errors->any())
      <div class="server-error">{{ $errors->first() }}</div>
  @endif

  <form id="pinForm" method="POST" action="{{ $mode == 'setup' ? route('pin.store') : route('pin.check') }}">
    @csrf
    <input type="hidden" name="pin" id="final_pin_input">

    <!-- HANYA 1 BARIS KOTAK PIN (Dipakai bergantian via JS) -->
    <div class="pin-container" id="pinContainer">
      <input type="{{ $mode == 'setup' ? 'text' : 'password' }}" inputmode="numeric" maxlength="1" class="pin-box" autofocus autocomplete="off">
      <input type="{{ $mode == 'setup' ? 'text' : 'password' }}" inputmode="numeric" maxlength="1" class="pin-box" autocomplete="off">
      <input type="{{ $mode == 'setup' ? 'text' : 'password' }}" inputmode="numeric" maxlength="1" class="pin-box" autocomplete="off">
      <input type="{{ $mode == 'setup' ? 'text' : 'password' }}" inputmode="numeric" maxlength="1" class="pin-box" autocomplete="off">
      <input type="{{ $mode == 'setup' ? 'text' : 'password' }}" inputmode="numeric" maxlength="1" class="pin-box" autocomplete="off">
      <input type="{{ $mode == 'setup' ? 'text' : 'password' }}" inputmode="numeric" maxlength="1" class="pin-box" autocomplete="off">
    </div>

    <button type="submit" id="submitBtn" class="btn">{{ $mode == 'setup' ? 'Lanjut' : 'Buka Kunci' }}</button>
  </form>
</div>

<script>
  const mode = '{{ $mode }}';
  const form = document.getElementById('pinForm');
  const finalPinInput = document.getElementById('final_pin_input');
  const errorText = document.getElementById('jsError');
  const inputs = document.querySelectorAll('.pin-box');
  const pinContainer = document.getElementById('pinContainer');

  let setupStep = 1;
  let firstPin = '';

  // Navigasi Kotak PIN (Ketik langsung pindah)
  inputs.forEach((input, index) => {
      input.addEventListener('input', (e) => {
          e.target.value = e.target.value.replace(/[^0-9]/g, '');
          if (e.target.value !== '' && index < inputs.length - 1) {
              inputs[index + 1].focus();
          }
      });
      input.addEventListener('keydown', (e) => {
          if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
              inputs[index - 1].focus();
          }
      });
      input.addEventListener('paste', (e) => {
          e.preventDefault();
          const pastedData = e.clipboardData.getData('text').slice(0, 6).replace(/[^0-9]/g, '');
          for (let i = 0; i < pastedData.length; i++) {
              if(inputs[i]) {
                  inputs[i].value = pastedData[i];
                  if (i < inputs.length - 1) inputs[i + 1].focus();
              }
          }
      });
  });

  function showError(msg) {
      errorText.innerText = msg;
      errorText.style.display = 'block';
  }

  function playSuccessAnimationAndSubmit() {
      const overlay = document.getElementById('loadingOverlay');
      const icon = document.getElementById('loadingIcon');
      const text = document.getElementById('loadingText');

      overlay.classList.add('active');
      setTimeout(() => {
          icon.classList.remove('spinner');
          icon.innerHTML = '✓';
          icon.classList.add('check-icon', 'verified-pop');

          text.classList.remove('text-process');
          text.classList.add('text-success');
          text.innerText = "Terverifikasi & Aman";

          setTimeout(() => { form.submit(); }, 1000);
      }, 1200);
  }

  function transitionToStep(stepNumber) {
      // Efek Memudar (Fade Out)
      pinContainer.style.opacity = '0';
      pinContainer.style.transform = 'translateY(10px)';

      setTimeout(() => {
          // Kosongkan nilai kotak
          inputs.forEach(i => i.value = '');

          if (stepNumber === 2) {
              document.getElementById('pageTitle').innerText = 'Ulangi PIN';
              document.getElementById('pageSubtitle').innerText = 'Masukkan kembali PIN yang baru saja Anda buat untuk konfirmasi.';
              document.getElementById('submitBtn').innerText = 'Simpan PIN';
          } else {
              document.getElementById('pageTitle').innerText = 'Buat PIN Baru';
              document.getElementById('pageSubtitle').innerText = 'Buat 6 digit PIN rahasia untuk melindungi operasional bisnis Anda.';
              document.getElementById('submitBtn').innerText = 'Lanjut';
          }

          // Munculkan Kembali (Fade In)
          pinContainer.style.opacity = '1';
          pinContainer.style.transform = 'translateY(0)';
          inputs[0].focus();
      }, 300);
  }

  form.addEventListener('submit', (e) => {
      e.preventDefault();
      errorText.style.display = 'none';

      let currentPin = '';
      inputs.forEach(i => currentPin += i.value);

      if (currentPin.length < 6) {
          return showError('Lengkapi 6 digit PIN terlebih dahulu.');
      }

      if (mode === 'setup') {
          if (setupStep === 1) {
              // Simpan PIN pertama ke dalam memori, lalu transisi ke Step 2
              firstPin = currentPin;
              setupStep = 2;
              transitionToStep(2);
          } else {
              // Cek kecocokan di Step 2
              if (currentPin !== firstPin) {
                  showError('PIN tidak cocok! Silakan ulangi dari awal.');
                  setupStep = 1;
                  firstPin = '';
                  transitionToStep(1);
                  return;
              }
              // Jika Cocok -> Kirim!
              finalPinInput.value = currentPin;
              playSuccessAnimationAndSubmit();
          }
      } else {
          // Logika untuk Mode Verifikasi (Login Harian)
          finalPinInput.value = currentPin;
          playSuccessAnimationAndSubmit();
      }
  });
</script>

</body>
</html>
