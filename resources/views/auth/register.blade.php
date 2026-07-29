<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar — AI Boss</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  :root{
    --bg: #090D17; --bg-2: #0B1120;
    --surface: rgba(255,255,255,0.035); --surface-solid: #111827;
    --border: rgba(255,255,255,0.09); --border-strong: rgba(255,255,255,0.16);
    --text: #F7F9FC; --text-dim: #9AA7BD; --text-dimmer: #6B7789;
    --blue: #3B82F6; --blue-bright: #60A5FA; --purple: #8B5CF6;
    --gradient: linear-gradient(120deg, var(--blue) 0%, var(--purple) 100%);
    --radius: 24px; --ease: cubic-bezier(.19,1,.22,1);
  }
  *{ margin:0; padding:0; box-sizing:border-box; }
  body{
    background: var(--bg); color: var(--text);
    font-family: 'Inter', sans-serif;
    font-size: 16px; line-height: 1.6; overflow-x: hidden;
    -webkit-font-smoothing: antialiased;
  }
  h1,h2,h3,h4{ font-family: 'Space Grotesk', sans-serif; font-weight: 600; letter-spacing: -0.02em; }
  a{ color:inherit; text-decoration:none; transition: color 0.2s var(--ease); }
  a:hover{ color: var(--blue-bright); }
  button{ font-family:inherit; cursor:pointer; border:none; }

  .bg-grid{
    position:fixed; inset:0; z-index:0; pointer-events:none;
    background-image: linear-gradient(rgba(255,255,255,0.028) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.028) 1px, transparent 1px);
    background-size: 64px 64px;
    mask-image: radial-gradient(ellipse 80% 60% at 50% 0%, black 20%, transparent 75%);
  }
  #mouse-glow{
    position:fixed; width:600px; height:600px; border-radius:50%;
    background: radial-gradient(circle, rgba(59,130,246,0.10), rgba(139,92,246,0.05) 40%, transparent 70%);
    pointer-events:none; z-index:0; transform: translate(-50%,-50%);
    transition: opacity .4s ease; opacity:0;
  }

  .logo{ display:flex; align-items:center; justify-content:center; gap:10px; font-weight:700; font-size:22px; font-family:'Space Grotesk',sans-serif; margin-bottom: 40px; }
  .logo-mark{ width:32px; height:32px; border-radius:10px; background:var(--gradient); position:relative; flex-shrink:0; }
  .logo-mark::after{ content:''; position:absolute; inset:8px; border-radius:4px; background:var(--bg); }

  .auth-wrapper{ min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 20px; position: relative; z-index: 1; }
  .auth-card{
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 48px 40px; width: 100%; max-width: 480px;
    box-shadow: 0 40px 100px -20px rgba(0,0,0,0.5);
    backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
  }
  .auth-head{ text-align: center; margin-bottom: 32px; }
  .auth-head h2{ font-size: 26px; margin-bottom: 8px; }
  .auth-head p{ font-size: 14.5px; color: var(--text-dim); }

  .form-group{ margin-bottom: 20px; text-align: left; }
  .form-label{ display: block; font-size: 13.5px; color: var(--text-dim); margin-bottom: 8px; font-weight: 500; }
  .form-input{
    width: 100%; padding: 14px 16px; border-radius: 12px;
    background: rgba(0,0,0,0.2); border: 1px solid var(--border);
    color: var(--text); font-family: inherit; font-size: 14.5px;
    transition: all 0.25s var(--ease); outline: none;
  }
  .form-input:focus{ border-color: var(--blue-bright); background: rgba(0,0,0,0.4); box-shadow: 0 0 0 4px rgba(59,130,246,0.15); }
  .form-error{ color: #F87171; font-size: 12.5px; margin-top: 6px; display: block; }

  .grid-2{ display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

  .btn{
    display:inline-flex; align-items:center; justify-content:center; gap:8px;
    padding:14px 22px; border-radius:100px; font-size:15px; font-weight:600;
    transition: transform .25s var(--ease), box-shadow .25s var(--ease), background .25s;
    width: 100%; margin-top: 12px;
  }
  .btn-primary{ background: var(--gradient); color:#fff; box-shadow: 0 1px 0 rgba(255,255,255,0.2) inset, 0 8px 24px -8px rgba(59,130,246,0.5); }
  .btn-primary:hover{ transform: translateY(-2px); box-shadow: 0 1px 0 rgba(255,255,255,0.25) inset, 0 12px 32px -6px rgba(139,92,246,0.6); }

  .auth-footer{ text-align: center; margin-top: 24px; font-size: 14px; color: var(--text-dim); }
  .auth-footer a{ color: var(--text); font-weight: 600; text-decoration: underline; text-decoration-color: var(--border-strong); text-underline-offset: 4px; }
  .auth-footer a:hover{ color: var(--blue-bright); text-decoration-color: var(--blue-bright); }
</style>
</head>
<body>

<div class="bg-grid"></div>
<div id="mouse-glow"></div>

<div class="auth-wrapper">
  <a href="{{ url('/') }}" class="logo">
    <span class="logo-mark"></span> AI Boss
  </a>

  <div class="auth-card">
    <div class="auth-head">
      <h2>Buat Akun Anda</h2>
      <p>Mulai otomatisasikan bisnis Anda dalam 1 menit.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
      @csrf

      <!-- Name -->
      <div class="form-group">
        <label for="name" class="form-label">Nama Bisnis</label>
        <input id="name" class="form-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe">
        @error('name')
            <span class="form-error">{{ $message }}</span>
        @enderror
      </div>

      <!-- Email Address -->
      <div class="form-group">
        <label for="email" class="form-label">Email Bisnis</label>
        <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="nama@perusahaan.com">
        @error('email')
            <span class="form-error">{{ $message }}</span>
        @enderror
      </div>

      <div class="grid-2">
          <!-- Password -->
          <div class="form-group">
            <label for="password" class="form-label">Kata Sandi</label>
            <input id="password" class="form-input" type="password" name="password" required autocomplete="new-password" placeholder="••••••••">
            @error('password')
                <span class="form-error">{{ $message }}</span>
            @enderror
          </div>

          <!-- Confirm Password -->
          <div class="form-group">
            <label for="password_confirmation" class="form-label">Ulangi Sandi</label>
            <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
            @error('password_confirmation')
                <span class="form-error">{{ $message }}</span>
            @enderror
          </div>
      </div>

      <button type="submit" class="btn btn-primary">Daftar Sekarang Secara Gratis</button>
    </form>

    <div class="auth-footer">
      Sudah memiliki akun? <a href="{{ route('login') }}">Masuk di sini</a>
    </div>
  </div>
</div>

<script>
  const glow = document.getElementById('mouse-glow');
  window.addEventListener('mousemove', (e)=>{
    glow.style.opacity = 1;
    glow.style.left = e.clientX + 'px';
    glow.style.top = e.clientY + 'px';
  });
  window.addEventListener('mouseleave', ()=> glow.style.opacity = 0);
</script>

</body>
</html>
