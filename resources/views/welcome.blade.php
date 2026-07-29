<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AI Boss — Jalankan Bisnis Anda Seperti Perusahaan Besar</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  :root{
    --bg: #05070E;
    --bg-2: #0B1120;
    --surface: rgba(255,255,255,0.02);
    --surface-solid: #0B1120;
    --border: rgba(255,255,255,0.07);
    --border-strong: rgba(255,255,255,0.12);
    --text: #F7F9FC;
    --text-dim: #A1B0C8;
    --text-dimmer: #6B7789;
    --primary: #4F46E5; /* Indigo */
    --primary-bright: #818CF8;
    --secondary: #EC4899; /* Pink */
    --gradient: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    --gradient-subtle: linear-gradient(135deg, rgba(79,70,229,0.2) 0%, rgba(236,72,153,0.2) 100%);
    --radius: 24px;
    --radius-sm: 14px;
    --ease: cubic-bezier(.19,1,.22,1);
  }
  *{ margin:0; padding:0; box-sizing:border-box; }
  html{ scroll-behavior:smooth; }
  body{
    background: var(--bg);
    color: var(--text);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    font-size: 16px;
    line-height: 1.6;
    overflow-x: hidden;
    -webkit-font-smoothing: antialiased;
  }
  h1,h2,h3,h4{
    font-family: 'Space Grotesk', 'Inter', sans-serif;
    font-weight: 700;
    letter-spacing: -0.03em;
    line-height: 1.1;
  }
  a{ color:inherit; text-decoration:none; }
  img{ max-width:100%; display:block; }
  button{ font-family:inherit; cursor:pointer; border:none; background:none; color:inherit; }
  .wrap{ max-width:1180px; margin:0 auto; padding:0 32px; }
  ::selection{ background: rgba(236,72,153,0.3); color: #fff; }

  /* Ambient background grid + glow */
  .bg-grid{
    position:fixed; inset:0; z-index:0; pointer-events:none;
    background-image:
      linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
      linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
    background-size: 40px 40px;
    mask-image: radial-gradient(ellipse 80% 60% at 50% 0%, black 20%, transparent 75%);
    -webkit-mask-image: radial-gradient(ellipse 80% 60% at 50% 0%, black 20%, transparent 75%);
  }
  #mouse-glow{
    position:fixed; width:700px; height:700px; border-radius:50%;
    background: radial-gradient(circle, rgba(79,70,229,0.12), rgba(236,72,153,0.04) 40%, transparent 70%);
    pointer-events:none; z-index:0; transform: translate(-50%,-50%);
    transition: opacity .4s ease; opacity:0;
  }

  /* Nav */
  header{
    position:fixed; top:0; left:0; right:0; z-index:100;
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
    background: rgba(5,7,14,0.7);
    border-bottom: 1px solid var(--border);
  }
  nav{ display:flex; align-items:center; justify-content:space-between; height:76px; }
  .logo{ display:flex; align-items:center; gap:10px; font-weight:700; font-size:20px; font-family:'Space Grotesk',sans-serif; letter-spacing: -0.02em;}
  .logo-mark{ width:32px; height:32px; border-radius:10px; background:var(--gradient); position:relative; flex-shrink:0; box-shadow: 0 0 15px rgba(236,72,153,0.4); }
  .logo-mark::after{ content:''; position:absolute; inset:8px; border-radius:5px; background:var(--bg); }
  .nav-links{ display:flex; gap:36px; font-size:14.5px; font-weight: 500; color:var(--text-dim); }
  .nav-links a{ transition:color .2s var(--ease); position:relative; }
  .nav-links a:hover{ color:var(--text); text-shadow: 0 0 10px rgba(255,255,255,0.3); }
  .nav-actions{ display:flex; align-items:center; gap:24px; }
  .nav-actions .login{ font-size:14.5px; font-weight:500; color:var(--text-dim); transition:color .2s; }
  .nav-actions .login:hover{ color:var(--text); }

  /* Buttons */
  .btn{
    display:inline-flex; align-items:center; justify-content:center; gap:8px;
    padding:12px 24px; border-radius:100px; font-size:14.5px; font-weight:600;
    transition: transform .25s var(--ease), box-shadow .25s var(--ease), background .25s;
    position: relative;
  }
  .btn-primary{
    background: var(--gradient); color:#fff;
    box-shadow: inset 0 1px 1px rgba(255,255,255,0.3), 0 8px 24px -8px rgba(236,72,153,0.5);
  }
  .btn-primary:hover{
    transform: translateY(-2px);
    box-shadow: inset 0 1px 1px rgba(255,255,255,0.4), 0 12px 32px -6px rgba(236,72,153,0.7);
  }
  .btn-ghost{ background: var(--surface); border:1px solid var(--border-strong); color:var(--text); box-shadow: inset 0 1px 0 rgba(255,255,255,0.05); }
  .btn-ghost:hover{ background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.2); transform: translateY(-2px); }
  .btn-lg{ padding:16px 32px; font-size:16px; border-radius:100px; }

  /* Section rhythm */
  section{ position:relative; z-index:1; padding:140px 0; }
  .eyebrow{
    display:inline-flex; align-items:center; gap:10px; font-size:13px; font-weight:700;
    color: var(--primary-bright); text-transform:uppercase; letter-spacing:0.1em;
    margin-bottom:24px;
    background: var(--surface); border: 1px solid var(--border); padding: 6px 14px; border-radius: 100px;
  }
  .eyebrow .dot{ width:6px; height:6px; border-radius:50%; background:var(--primary-bright); box-shadow:0 0 12px var(--primary-bright); animation: pulse 2s infinite; }
  .section-head{ max-width:680px; margin-bottom:72px; }
  .section-head h2{ font-size:clamp(36px,4.5vw,52px); margin-bottom:20px; }
  .section-head p{ font-size:18px; color:var(--text-dim); font-weight: 400; }
  .center{ text-align:center; margin-left:auto; margin-right:auto; }

  .reveal{ opacity:0; transform: translateY(30px); transition: opacity 0.8s var(--ease), transform 0.8s var(--ease); }
  .reveal.in{ opacity:1; transform:none; }

  /* HERO */
  .hero{ padding:220px 0 120px; text-align:center; position: relative; }
  .hero::before {
    content: ''; position: absolute; top: 0; left: 50%; transform: translateX(-50%);
    width: 80%; height: 500px; background: radial-gradient(circle, rgba(79,70,229,0.15), transparent 70%);
    z-index: -1; pointer-events: none;
  }
  .hero h1{ font-size:clamp(46px,7vw,84px); max-width:1000px; margin:0 auto 28px; }
  .hero h1 .grad{
    background: var(--gradient); -webkit-background-clip:text; background-clip:text; color:transparent;
    text-shadow: 0 0 40px rgba(236,72,153,0.3);
  }
  .hero-sub{ font-size:20px; color:var(--text-dim); max-width:640px; margin:0 auto 48px; font-weight: 400; }
  .hero-cta{ display:flex; gap:16px; justify-content:center; margin-bottom:120px; flex-wrap:wrap; }
  .btn-secondary-play{ display:inline-flex; align-items:center; gap:12px; }
  .play-icon{ width:36px; height:36px; border-radius:50%; background:var(--bg); border:1px solid var(--border-strong); display:flex; align-items:center; justify-content:center; padding-left: 2px; }

  /* Dashboard mockup - macOS Style */
  .mockup-stage{ perspective: 2000px; max-width:1000px; margin:0 auto; }
  .mockup{
    border-radius: 24px; border:1px solid var(--border-strong);
    background: rgba(11,17,32,0.8); backdrop-filter: blur(20px);
    box-shadow: 0 40px 100px -20px rgba(0,0,0,0.8), inset 0 1px 0 rgba(255,255,255,0.1);
    transform: rotateX(8deg) rotateY(-2deg);
    transition: transform .6s var(--ease), box-shadow .6s var(--ease);
    overflow:hidden;
  }
  .mockup-stage:hover .mockup{ transform: rotateX(4deg) rotateY(-1deg) translateY(-8px); box-shadow: 0 50px 120px -20px rgba(79,70,229,0.2), inset 0 1px 0 rgba(255,255,255,0.15); }
  .mockup-bar{ display:flex; align-items:center; gap:8px; padding:16px 20px; border-bottom:1px solid var(--border); background: rgba(0,0,0,0.4); }

  /* Mac Window Dots */
  .mockup-bar span{ width:12px; height:12px; border-radius:50%; }
  .mockup-bar span:nth-child(1) { background: #FF5F56; border: 1px solid #E0443E; }
  .mockup-bar span:nth-child(2) { background: #FFBD2E; border: 1px solid #DEA123; }
  .mockup-bar span:nth-child(3) { background: #27C93F; border: 1px solid #1AAB29; }

  .mockup-body{ display:grid; grid-template-columns: 220px 1fr; min-height:420px; }
  .mockup-nav{ border-right:1px solid var(--border); background: rgba(255,255,255,0.01); padding:24px 16px; display:flex; flex-direction:column; gap:8px; }
  .mockup-nav .item{ padding:10px 14px; border-radius:10px; font-size:13.5px; font-weight:500; color:var(--text-dimmer); transition: all 0.2s;}
  .mockup-nav .item:hover { background: rgba(255,255,255,0.05); color: var(--text); }
  .mockup-nav .item.active{ background: var(--gradient-subtle); color:var(--text); font-weight:600; border: 1px solid rgba(236,72,153,0.2); }
    .mockup-main{ padding:32px 40px; text-align:left; background: url("data:image/svg+xml,%3Csvg width='20' height='20' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='1' cy='1' r='1' fill='rgba(255,255,255,0.05)'/%3E%3C/svg%3E"); }  .mockup-main .greet{ font-size:14px; color:var(--primary-bright); font-weight: 600; margin-bottom:8px; text-transform: uppercase; letter-spacing: 0.05em; }
  .mockup-main h4{ font-size:24px; margin-bottom:24px; }
  .kpi-row{ display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:28px; }
  .kpi{ background:rgba(0,0,0,0.3); border:1px solid var(--border); box-shadow: inset 0 1px 0 rgba(255,255,255,0.05); border-radius:16px; padding:18px 20px; }
  .kpi .num{ font-family:'Space Grotesk',sans-serif; font-size:26px; font-weight:700; color:#fff; }
  .kpi .lbl{ font-size:13px; color:var(--text-dim); margin-top: 4px; }
  .kpi .delta{ color:#4ADE80; font-size:12px; font-weight: 600; background: rgba(74,222,128,0.1); padding: 2px 8px; border-radius: 100px; display: inline-block; margin-top: 8px;}
  .task-row{ display:flex; align-items:center; gap:14px; padding:14px 0; border-bottom:1px dashed var(--border); font-size:14px; font-weight: 500; color:var(--text-dim); }
  .task-row .check{ width:20px; height:20px; border-radius:6px; border:2px solid var(--border-strong); flex-shrink:0; transition: 0.2s;}
  .task-row .check.on{ background:var(--gradient); border:none; display: flex; align-items: center; justify-content: center;}
  .task-row .check.on::after { content: '✓'; color: white; font-size: 12px; font-weight: bold; }

  /* Integrations marquee */
  .marquee-section{ padding:0 0 100px; }
  .marquee-head{ text-align:center; max-width:600px; margin:0 auto 40px; }
  .marquee-head .eyebrow{ justify-content:center; display:inline-flex; }
  .marquee-head h3{ font-size:clamp(22px,2.8vw,28px); color:var(--text-dim); font-weight:500; }
  .marquee-head h3 b{ color:var(--text); font-weight:700; }
  .marquee-shell{
    position:relative; overflow:hidden; border-radius:24px;
    border:1px solid var(--border-strong);
    background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.005));
    box-shadow: 0 30px 70px -30px rgba(0,0,0,0.8), inset 0 1px 0 rgba(255,255,255,0.1);
    padding:32px 0;
    perspective: 1000px;
  }
  .marquee-shell::before, .marquee-shell::after{
    content:''; position:absolute; top:0; bottom:0; width:120px; z-index:3; pointer-events:none;
  }
  .marquee-shell::before{ left:0; background: linear-gradient(90deg, var(--bg) 10%, transparent); }
  .marquee-shell::after{ right:0; background: linear-gradient(270deg, var(--bg) 10%, transparent); }
  .marquee-track{
    display:flex; width:max-content; padding:10px 0;
    animation: marquee-scroll 35s linear infinite;
    transform: rotateX(8deg);
    transform-style: preserve-3d;
    will-change: transform;
  }
  .marquee-shell:hover .marquee-track{ animation-play-state: paused; }
  @keyframes marquee-scroll{
    from{ transform: rotateX(8deg) translate3d(0,0,0); }
    to{ transform: rotateX(8deg) translate3d(-50%,0,0); }
  }
  .marquee-item{
    display:flex; align-items:center; gap:32px; padding:0 32px; flex-shrink:0;
    font-family:'Space Grotesk',sans-serif; font-size:18px; font-weight:600; color:var(--text-dimmer);
    white-space:nowrap; transition: color .3s var(--ease), transform .3s var(--ease), text-shadow .3s;
  }
  .marquee-item:hover{ color:#fff; transform: translateZ(30px) scale(1.1); text-shadow: 0 10px 30px rgba(236,72,153,0.5); }
  .marquee-item .sep{ color: var(--border-strong); font-size:16px; }

  .marquee-glow-top, .marquee-glow-bottom{
    position:absolute; left:50%; width:80%; height:80px; transform:translateX(-50%);
    background: radial-gradient(ellipse at center, rgba(79,70,229,0.3), transparent 70%);
    filter: blur(24px); pointer-events:none; z-index:0;
  }
  .marquee-glow-top{ top:-40px; }
  .marquee-glow-bottom{ bottom:-40px; }

  /* Social proof strip */
  .proof-strip{ padding:64px 0; border-top:1px solid var(--border); border-bottom:1px solid var(--border); background: rgba(255,255,255,0.01); }
  .proof-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:24px; text-align:center; }
  .proof-grid .num{ font-family:'Space Grotesk',sans-serif; font-size:clamp(36px,4.5vw,52px); font-weight:700; }
  .proof-grid .num .grad{ background:var(--gradient); -webkit-background-clip:text; background-clip:text; color:transparent; }
  .proof-grid .lbl{ font-size:15px; font-weight:500; color:var(--text-dim); margin-top:8px; text-transform: uppercase; letter-spacing: 0.05em;}

  /* Cards grid generic */
  .grid-3{ display:grid; grid-template-columns:repeat(3,1fr); gap:24px; }
  .card{
    background: var(--surface); border:1px solid var(--border); border-radius: var(--radius);
    padding:32px; transition: transform .3s var(--ease), border-color .3s, box-shadow .3s;
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.05);
  }
  .card:hover{
    transform: translateY(-8px); border-color: rgba(236,72,153,0.3);
    background: rgba(255,255,255,0.04);
    box-shadow: 0 20px 40px -10px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.1);
  }
  .icon-tile{
    width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center;
    background: var(--gradient-subtle); border: 1px solid rgba(255,255,255,0.05); margin-bottom:24px; font-size:24px;
  }
  .card h3{ font-size:20px; margin-bottom:12px; }
  .card p{ color:var(--text-dim); font-size:15px; }

  /* Problem section pain cards */
  .pain-card{ display:flex; gap:16px; align-items:flex-start; }
  .pain-check{ width:28px; height:28px; border-radius:8px; background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color:#F87171; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight: bold; flex-shrink:0; margin-top:2px; }

  /* Solution cards */
  .team-grid{ display:grid; grid-template-columns:repeat(4,1fr); gap:20px; }
  .team-card{ text-align:center; padding:32px 20px; }
  .team-card .icon-tile{ margin:0 auto 20px; }

  /* How it works */
  .steps{ display:grid; grid-template-columns:repeat(5,1fr); gap:20px; }
  .step{ position:relative; padding:32px 20px; background: var(--surface); border: 1px solid var(--border); border-radius: 20px; text-align: center; box-shadow: inset 0 1px 0 rgba(255,255,255,0.02);}
  .step .n{ display: inline-block; background: var(--gradient); -webkit-background-clip: text; color: transparent; font-family:'Space Grotesk',sans-serif; font-size:42px; font-weight:700; margin-bottom:16px; opacity: 0.5;}
  .step h4{ font-size:18px; margin-bottom:10px; }
  .step p{ font-size:14px; color:var(--text-dim); }

  /* Interactive simulation */
  .sim-wrap{
    background: linear-gradient(180deg, rgba(255,255,255,0.04), rgba(255,255,255,0.01));
    border:1px solid var(--border-strong); border-radius:32px; padding:48px; overflow:hidden; position:relative;
    box-shadow: 0 40px 100px -20px rgba(0,0,0,0.8), inset 0 1px 0 rgba(255,255,255,0.1);
  }
  .sim-input-row{ display:flex; align-items:center; gap:16px; background:rgba(0,0,0,0.4); border:1px solid var(--border); border-radius:16px; padding:20px 24px; margin-bottom:36px; box-shadow: inset 0 2px 10px rgba(0,0,0,0.5);}
  .sim-input-row .dotpulse{ width:12px; height:12px; border-radius:50%; background:var(--primary-bright); box-shadow:0 0 15px var(--primary-bright); flex-shrink:0; animation:pulse 1.6s infinite; }
  @keyframes pulse{ 0%,100%{opacity:1;} 50%{opacity:.3;} }
  #typed-text{ font-family:'Space Grotesk',sans-serif; font-size:20px; font-weight: 500; color: #fff;}
  #typed-text::after{ content:'|'; animation: blink 1s infinite; color:var(--secondary); font-weight: bold;}
  @keyframes blink{ 50%{opacity:0;} }
  .sim-output{ display:grid; grid-template-columns:repeat(3,1fr); gap:20px; }
  .sim-item{
    background: rgba(255,255,255,0.03); border:1px solid var(--border-strong); border-radius:20px; padding:24px;
    opacity:0; transform: translateY(20px) scale(.95); transition: all .6s cubic-bezier(0.34, 1.56, 0.64, 1);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.05);
  }
  .sim-item.show{ opacity:1; transform:none; }
  .sim-item .tag{ font-size:12px; text-transform:uppercase; letter-spacing:.08em; color:var(--primary-bright); font-weight:700; margin-bottom:12px; display:inline-block; background: rgba(79,70,229,0.15); padding: 4px 10px; border-radius: 6px;}
  .sim-item p{ font-size:14.5px; color:var(--text-dim); }

  /* Comparison table */
  .compare{ border:1px solid var(--border-strong); border-radius:24px; overflow:hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.4); }
  .compare-row{ display:grid; grid-template-columns:1fr 1fr 1fr; border-bottom:1px solid var(--border); background: var(--surface-solid);}
  .compare-row:last-child{ border-bottom:none; }
  .compare-row > div{ padding:26px 32px; font-size:15.5px; }
  .compare-row.head > div{ font-weight:700; font-family:'Space Grotesk',sans-serif; background:rgba(255,255,255,0.05); font-size: 18px;}
  .compare-row > div:first-child{ color:var(--text-dim); font-weight: 500; font-size:15px; background:rgba(255,255,255,0.02); }
  .compare-row > div:nth-child(2){ color:var(--text-dim); }
  .compare-row > div:nth-child(3){ color:#fff; font-weight:600; background:var(--gradient-subtle); position: relative;}

  /* Testimonials */
  .testi-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:24px; }
  .testi-card { position: relative; }
  .testi-card::before { content: '"'; position: absolute; top: 10px; right: 20px; font-size: 80px; font-family: serif; color: rgba(255,255,255,0.05); line-height: 1;}
  .testi-card p{ font-size:16px; color:var(--text); margin-bottom:28px; font-style: italic; line-height: 1.7;}
  .testi-foot{ display:flex; align-items:center; gap:16px; mt-auto;}
  .avatar{ width:44px; height:44px; border-radius:50%; background:var(--gradient); flex-shrink:0; border: 2px solid var(--border-strong);}
  .testi-name{ font-size:15px; font-weight:700; }
  .testi-role{ font-size:13px; color:var(--text-dimmer); }
  .testi-growth{ margin-left:auto; font-size:14px; color:#4ADE80; font-weight:700; background: rgba(74,222,128,0.1); padding: 4px 10px; border-radius: 8px;}

  /* Pricing */
  .toggle-row{ display:flex; justify-content:center; align-items:center; gap:16px; margin-bottom:56px; font-size:15px; font-weight: 500; color:var(--text-dim); }
  .switch{ width:52px; height:30px; border-radius:100px; background:rgba(0,0,0,0.5); border:1px solid var(--border-strong); position:relative; cursor: pointer; box-shadow: inset 0 2px 4px rgba(0,0,0,0.5);}
  .switch .knob{ width:22px; height:22px; border-radius:50%; background:var(--gradient); position:absolute; top:3px; left:4px; transition: transform .3s cubic-bezier(0.34, 1.56, 0.64, 1); box-shadow: 0 2px 5px rgba(0,0,0,0.3);}
  .switch.on .knob{ transform: translateX(20px); }
  .price-grid{ display:grid; grid-template-columns:repeat(4,1fr); gap:20px; align-items:stretch; }
  .price-card{ display:flex; flex-direction:column; padding: 36px 28px; border-radius: 24px;}
  .price-card.featured{
    border-color: rgba(236,72,153,0.5);
    background: linear-gradient(180deg, rgba(79,70,229,0.15), rgba(236,72,153,0.05));
    position:relative; transform: scale(1.04);
    box-shadow: 0 20px 40px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.2);
    z-index: 10;
  }
  .price-card.featured:hover { transform: scale(1.06); border-color: rgba(236,72,153,0.8); box-shadow: 0 30px 60px rgba(236,72,153,0.2), inset 0 1px 0 rgba(255,255,255,0.3); }
  .price-card.featured .pop{ position:absolute; top:-14px; left:50%; transform: translateX(-50%); background:var(--gradient); font-size:12px; font-weight:700; padding:6px 16px; border-radius:100px; letter-spacing:.05em; color: white; box-shadow: 0 4px 15px rgba(236,72,153,0.4); white-space: nowrap;}
  .price-card h3{ font-size:22px; margin-bottom:8px; }
  .price-card .tagline{ font-size:14px; color:var(--text-dimmer); margin-bottom:24px; }
  .price-card .amount{ font-family:'Space Grotesk',sans-serif; font-size:42px; font-weight:700; margin-bottom:4px; color: #fff;}
  .price-card .amount span{ font-size:15px; color:var(--text-dim); font-weight:500; }
  .price-card .btn{ margin:28px 0; width:100%; padding: 14px 0;}
  .price-feat{ list-style:none; font-size:14.5px; color:var(--text-dim); display:flex; flex-direction:column; gap:14px; }
  .price-feat li{ display:flex; gap:12px; align-items:flex-start; }
  .price-feat li::before{ content:'✓'; color:var(--secondary); font-weight:700; flex-shrink:0; background: rgba(236,72,153,0.15); border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 11px;}

  /* FAQ */
  .faq-item{ border-bottom:1px solid var(--border); transition: background 0.3s;}
  .faq-item:hover { background: rgba(255,255,255,0.01); }
  .faq-q{ display:flex; justify-content:space-between; align-items:center; padding:28px 20px; font-size:18px; font-weight:600; cursor: pointer; color: #fff;}
  .faq-q .plus{ font-size:24px; color:var(--text-dimmer); transition: transform .4s var(--ease), color .4s; flex-shrink:0; margin-left:20px; }
  .faq-item.open .faq-q .plus{ transform: rotate(45deg); color:var(--secondary); }
  .faq-a{ max-height:0; overflow:hidden; transition: max-height .4s var(--ease); }
  .faq-a p{ padding:0 20px 28px; color:var(--text-dim); font-size:15.5px; max-width:800px; line-height: 1.7;}

  /* Final CTA */
  .final-cta{
    text-align:center; border-radius:40px; padding:100px 40px;
    background: radial-gradient(ellipse at 50% 0%, rgba(236,72,153,0.2), transparent 70%), var(--surface-solid);
    border:1px solid var(--border-strong); position:relative; overflow:hidden;
    box-shadow: 0 40px 100px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.1);
  }
  .final-cta::before { content: ''; position: absolute; inset: 0; background: url("data:image/svg+xml,%3Csvg width='40' height='40' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='1' cy='1' r='1' fill='rgba(255,255,255,0.03)'/%3E%3C/svg%3E"); pointer-events: none;}
  .final-cta h2{ font-size:clamp(36px,5vw,56px); margin-bottom:20px; position: relative; z-index: 2;}
  .final-cta p{ color:var(--text-dim); font-size:18px; margin-bottom:40px; position: relative; z-index: 2;}
  .final-cta .btn { position: relative; z-index: 2;}

  /* Footer */
  footer{ border-top:1px solid var(--border); padding:80px 0 40px; background: #030409;}
  .foot-grid{ display:grid; grid-template-columns:1.5fr 1fr 1fr 1fr; gap:48px; margin-bottom:70px; }
  .foot-col h5{ font-size:14px; text-transform:uppercase; letter-spacing:.08em; color:var(--text-dim); margin-bottom:24px; font-weight: 700;}
  .foot-col a{ display:block; font-size:15px; color:var(--text-dimmer); margin-bottom:14px; transition:color .2s; }
  .foot-col a:hover{ color:var(--secondary); }
  .foot-desc{ color:var(--text-dimmer); font-size:15px; max-width:300px; margin-top:18px; line-height: 1.7;}
  .foot-bottom{ display:flex; justify-content:space-between; align-items:center; padding-top:36px; border-top:1px solid var(--border); font-size:14px; color:var(--text-dimmer); }
  .foot-social{ display:flex; gap:20px; }
  .foot-social a:hover { color: #fff;}

  /* Mobile Responsive Adjustments */
  @media (max-width: 900px){
    .nav-links{ display:none; }
    /* Memaksa elemen baris grid (termasuk .kpi-row) untuk membagi kolomnya secara pas */
    .grid-3, .grid-4, .team-grid, .steps, .sim-output, .price-grid, .proof-grid, .testi-grid { grid-template-columns: 1fr 1fr; }
    .steps{ grid-template-columns:1fr; gap: 16px;}
    .step { padding: 24px; }
    .mockup-body{ grid-template-columns:1fr; }
    .mockup-nav{ display:none; }
    .mockup-stage{ perspective:1000px; }
    .mockup{ animation: mockup-float 6s ease-in-out infinite; }
    @keyframes mockup-float{
      0%,100%{ transform: rotateX(9deg) rotateY(-3deg) translateY(0); }
      50%{ transform: rotateX(5deg) rotateY(-1deg) translateY(-12px); }
    }
    .compare-row{ grid-template-columns: 1fr 1fr 1fr; font-size:13px; }
    .compare-row > div{ padding:16px; }
    .foot-grid{ grid-template-columns:1fr 1fr; }

    /* Marquee */
    .marquee-shell{ perspective:800px; padding:24px 0; border-radius:16px; }
    .marquee-track{ animation-duration:20s; padding:12px 0; transform: rotateX(9deg) rotateY(-2deg); }
    @keyframes marquee-scroll{
      from{ transform: rotateX(9deg) rotateY(-2deg) translate3d(0,0,0); }
      to{ transform: rotateX(9deg) rotateY(-2deg) translate3d(-50%,0,0); }
    }
    .marquee-item{ font-size:15px; padding:0 20px; gap:20px; }
    .marquee-shell::before, .marquee-shell::after{ width:60px; }
  }

  /* LAYAR HP (Mobile Kecil) */
  @media (max-width: 620px){
    .wrap{ padding:0 24px; }
    section{ padding:100px 0; }

    /* DI SINI LETAK PERBAIKANNYA - .kpi-row dipaksa jadi 1 kolom (atas-bawah) */
    .grid-3, .grid-4, .team-grid, .price-grid, .proof-grid, .testi-grid, .sim-output, .kpi-row { grid-template-columns:1fr; }

    /* Penyesuaian padding kotak KPI agar tidak terlalu besar di HP */
    .mockup-main { padding: 24px 20px; }
    .kpi-row { gap: 12px; margin-bottom: 20px; }
    .task-row { font-size: 13px; padding: 12px 0; }

    .compare-row{ grid-template-columns:1fr; }
    .compare-row > div:first-child{ font-weight:700; background: rgba(255,255,255,0.05); border-bottom: 1px solid var(--border);}
    .compare-row > div:nth-child(3) { border-bottom: 2px solid var(--border); }
    .price-card.featured{ transform:none; }
    .price-card.featured:hover { transform: none; }
    .hero{ padding:160px 0 80px; }
    .foot-grid{ grid-template-columns:1fr; gap:40px; }
    .foot-bottom{ flex-direction:column; gap:20px; text-align: center;}
    nav .nav-actions .login{ display:none; }

    /* Marquee small phones */
    .marquee-section{ padding-bottom:70px; }
    .marquee-shell{ perspective:700px; padding:20px 0; }
    .marquee-track{ animation-duration:15s; padding:10px 0; transform: rotateX(10deg) rotateY(-2deg); }
    @keyframes marquee-scroll{
      from{ transform: rotateX(10deg) rotateY(-2deg) translate3d(0,0,0); }
      to{ transform: rotateX(10deg) rotateY(-2deg) translate3d(-50%,0,0); }
    }
    .marquee-item{ font-size:14px; padding:0 16px; gap:16px; }
  }
  @media (prefers-reduced-motion: reduce){
    *{ animation:none !important; transition:none !important; }
  }
</style>
</head>
<body>

<div class="bg-grid"></div>
<div id="mouse-glow"></div>

<header>
  <div class="wrap">
    <nav>
      <div class="logo"><span class="logo-mark"></span> AI Boss</div>
      <div class="nav-links">
        <a href="#features">Features</a>
        <a href="#solution">Solutions</a>
        <a href="#pricing">Pricing</a>
        <a href="#testimonials">Testimonials</a>
        <a href="#faq">FAQ</a>
      </div>
        <div class="nav-actions">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="login">Buka Dashboard</a>
                <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard 🚀</a>
            @else
                <a href="{{ route('login') }}" class="login">Masuk</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-primary">Mulai Gratis</a>
                @endif
            @endauth
        @endif
      </div>
    </nav>
  </div>
</header>

<main>

  <!-- HERO -->
  <section class="hero">
    <div class="wrap">
      <div class="eyebrow" style="margin-bottom: 32px;"><span class="dot"></span> BARU: Dipercaya oleh 3800 pemilik bisnis modern</div>
      <h1>Berhenti Menjalankan<br>Bisnis <span class="grad">Sendirian.</span></h1>
      <p class="hero-sub">AI Boss menjalankan marketing, konten, sales, customer service, dan keuangan bisnis Anda — dalam satu dashboard. Anda cukup mengambil keputusan.</p>
        <div class="hero-cta">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg">Buka Workspace</a>
            @else
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Mulai Coba Gratis</a>
            @endauth
        @endif
        <a href="#demo" class="btn btn-ghost btn-lg btn-secondary-play">
          <span class="play-icon">▶</span> Lihat Cara Kerja
        </a>
      </div>

      <div class="mockup-stage reveal">
        <div class="mockup">
          <div class="mockup-bar"><span></span><span></span><span></span></div>
          <div class="mockup-body">
            <div class="mockup-nav">
              <div class="item active">🏠 Dashboard</div>
              <div class="item">✅ Today's Task</div>
              <div class="item">📝 Content Studio</div>
              <div class="item">💬 Customer Service</div>
              <div class="item">💰 Profit Calculator</div>
              <div class="item">📊 Analytics</div>
            </div>
            <div class="mockup-main">
              <div class="greet">Selamat pagi, Owner 👋</div>
              <h4>Ringkasan bisnis hari ini</h4>
              <div class="kpi-row">
                <div class="kpi"><div class="num">Rp 4,2jt</div><div class="lbl">Omzet Hari Ini</div><div class="delta">↑ 18% vs Kemarin</div></div>
                <div class="kpi"><div class="num">37</div><div class="lbl">Chat Terjawab</div><div class="delta">Dibalas AI 100%</div></div>
                <div class="kpi"><div class="num">6</div><div class="lbl">Konten Tersimpan</div><div class="delta">Siap Posting</div></div>
              </div>
              <div class="task-row"><span class="check on"></span> Balas 12 chat pelanggan yang tertunda</div>
              <div class="task-row"><span class="check on"></span> Publish konten promo flash sale TikTok</div>
              <div class="task-row"><span class="check"></span> Review laporan profit dan margin mingguan</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- INTEGRATIONS MARQUEE -->
  <section class="marquee-section reveal">
    <div class="wrap">
      <div class="marquee-head">
        <div class="eyebrow"><span class="dot"></span>Terhubung Penuh</div>
        <h3>Nyambung langsung dengan <b>tools yang sudah Anda pakai</b> setiap hari</h3>
      </div>
    </div>
    <div class="marquee-shell">
      <div class="marquee-glow-top"></div>
      <div class="marquee-track">
        <div class="marquee-item">WhatsApp Business<span class="sep">/</span></div>
        <div class="marquee-item">TikTok Shop<span class="sep">/</span></div>
        <div class="marquee-item">Shopee<span class="sep">/</span></div>
        <div class="marquee-item">Tokopedia<span class="sep">/</span></div>
        <div class="marquee-item">Instagram<span class="sep">/</span></div>
        <div class="marquee-item">Meta Ads<span class="sep">/</span></div>
        <div class="marquee-item">QRIS & Midtrans<span class="sep">/</span></div>
        <div class="marquee-item">Google Sheets<span class="sep">/</span></div>
        <div class="marquee-item">Canva<span class="sep">/</span></div>
        <div class="marquee-item">Email Marketing<span class="sep">/</span></div>
        <!-- Duplicate untuk infinite loop -->
        <div class="marquee-item">WhatsApp Business<span class="sep">/</span></div>
        <div class="marquee-item">TikTok Shop<span class="sep">/</span></div>
        <div class="marquee-item">Shopee<span class="sep">/</span></div>
        <div class="marquee-item">Tokopedia<span class="sep">/</span></div>
        <div class="marquee-item">Instagram<span class="sep">/</span></div>
        <div class="marquee-item">Meta Ads<span class="sep">/</span></div>
        <div class="marquee-item">QRIS & Midtrans<span class="sep">/</span></div>
        <div class="marquee-item">Google Sheets<span class="sep">/</span></div>
        <div class="marquee-item">Canva<span class="sep">/</span></div>
        <div class="marquee-item">Email Marketing<span class="sep">/</span></div>
      </div>
      <div class="marquee-glow-bottom"></div>
    </div>
  </section>

  <!-- SOCIAL PROOF -->
  <section class="proof-strip">
    <div class="wrap proof-grid reveal">
      <div><div class="num"><span class="grad">3800</span></div><div class="lbl">Business Owners</div></div>
      <div><div class="num"><span class="grad">10.000+</span></div><div class="lbl">Workflow Dihasilkan</div></div>
      <div><div class="num"><span class="grad">1.000.000+</span></div><div class="lbl">Task Terselesaikan</div></div>
    </div>
  </section>

  <!-- PROBLEM -->
  <section id="problem">
    <div class="wrap">
      <div class="section-head reveal">
        <div class="eyebrow"><span class="dot"></span>Masalah Nyata</div>
        <h2>Masih Mengelola Bisnis Sendirian?</h2>
        <p>Anda bukan hanya owner. Anda juga marketing, admin, customer service, dan akuntan — sekaligus, setiap hari.</p>
      </div>
      <div class="grid-3">
        <div class="card pain-card reveal"><span class="pain-check">✕</span><div><h3>Bingung bikin konten</h3><p>Setiap hari harus mikir caption, ide promo, dan script — sampai kehabisan ide.</p></div></div>
        <div class="card pain-card reveal"><span class="pain-check">✕</span><div><h3>Balas chat sampai malam</h3><p>Pelanggan bertanya kapan saja, dan Anda tidak bisa mengabaikan satu pun.</p></div></div>
        <div class="card pain-card reveal"><span class="pain-check">✕</span><div><h3>Tidak sempat analisa</h3><p>Data jualan ada, tapi tidak pernah sempat dibaca apalagi dijadikan keputusan.</p></div></div>
        <div class="card pain-card reveal"><span class="pain-check">✕</span><div><h3>Tidak punya SOP</h3><p>Semua berjalan dari ingatan. Bisnis sulit berkembang dan tidak bisa diwariskan.</p></div></div>
        <div class="card pain-card reveal"><span class="pain-check">✕</span><div><h3>Kehilangan prioritas</h3><p>Bangun pagi dengan puluhan hal yang harus dikerjakan tanpa tahu mana yang penting.</p></div></div>
        <div class="card pain-card reveal"><span class="pain-check">✕</span><div><h3>Omzet segitu-gitu saja</h3><p>Jam kerja terus bertambah, tapi hasil tidak sebanding dengan waktu yang hilang.</p></div></div>
      </div>
    </div>
  </section>

  <!-- SOLUTION -->
  <section id="solution">
    <div class="wrap">
      <div class="section-head center reveal">
        <div class="eyebrow center" style="justify-content:center;"><span class="dot"></span>Solusinya</div>
        <h2>Kenalkan Tim AI Bisnis Anda</h2>
        <p style="margin:0 auto;">Bukan chatbot yang menjawab. AI Boss adalah tim yang bekerja — menyelesaikan pekerjaan bisnis Anda dari awal sampai selesai.</p>
      </div>
      <div class="team-grid">
        <div class="card team-card reveal"><div class="icon-tile">📈</div><h3>Marketing</h3><p>Strategi & funnel otomatis</p></div>
        <div class="card team-card reveal"><div class="icon-tile">✍️</div><h3>Content</h3><p>Caption, script, ide promo</p></div>
        <div class="card team-card reveal"><div class="icon-tile">💰</div><h3>Finance</h3><p>Profit & cashflow terpantau</p></div>
        <div class="card team-card reveal"><div class="icon-tile">🎯</div><h3>Sales</h3><p>Closing & follow-up otomatis</p></div>
        <div class="card team-card reveal"><div class="icon-tile">💬</div><h3>CS Center</h3><p>Balas chat 24 jam responsif</p></div>
        <div class="card team-card reveal"><div class="icon-tile">⚙️</div><h3>Operations</h3><p>SOP & checklist harian</p></div>
        <div class="card team-card reveal"><div class="icon-tile">🎨</div><h3>Designer</h3><p>Visual promo siap pakai</p></div>
        <div class="card team-card reveal" style="background: var(--gradient-subtle); border-color: rgba(236,72,153,0.3);"><div class="icon-tile" style="background: var(--gradient); box-shadow: 0 4px 15px rgba(236,72,153,0.4);">🧠</div><h3>AI Boss</h3><p>Mengatur semuanya di 1 tempat</p></div>
      </div>
    </div>
  </section>

  <!-- HOW IT WORKS -->
  <section id="how">
    <div class="wrap">
      <div class="section-head reveal">
        <div class="eyebrow"><span class="dot"></span>Cara Kerja</div>
        <h2>Dari Bingung ke Bergerak, Dalam 5 Langkah</h2>
      </div>
      <div class="steps">
        <div class="step reveal"><div class="n">01</div><h4>Pilih tujuan</h4><p>Naikkan omzet, buat konten, atau rapikan operasional.</p></div>
        <div class="step reveal"><div class="n">02</div><h4>Analisa AI</h4><p>Membaca kondisi & data bisnis Anda saat ini secara instan.</p></div>
        <div class="step reveal"><div class="n">03</div><h4>Action Plan</h4><p>Rencana kerja yang sangat spesifik dan siap dijalankan.</p></div>
        <div class="step reveal"><div class="n">04</div><h4>Eksekusi</h4><p>Konten, chat, dan laporan akan dikerjakan otomatis.</p></div>
        <div class="step reveal"><div class="n">05</div><h4>Pantau Hasil</h4><p>Lihat dampak profitnya langsung dari dalam dashboard.</p></div>
      </div>
    </div>
  </section>

  <!-- FEATURES -->
  <section id="features">
    <div class="wrap">
      <div class="section-head reveal">
        <div class="eyebrow"><span class="dot"></span>Di dalam AI Boss</div>
        <h2>Satu Dashboard, Semua Beres</h2>
        <p>Setiap alat yang biasanya butuh karyawan berbeda, sekarang ada dalam satu tempat.</p>
      </div>
      <div class="grid-3">
        <div class="card reveal"><div class="icon-tile">🗂️</div><h3>Today's Task</h3><p>Prioritas kerja harian yang disusun otomatis oleh AI.</p></div>
        <div class="card reveal"><div class="icon-tile">🧩</div><h3>AI Workspace</h3><p>Ruang asisten cerdas untuk segala operasional bisnis.</p></div>
        <div class="card reveal"><div class="icon-tile">🖋️</div><h3>Content Studio</h3><p>Caption, naskah promo, dan ide konten dalam hitungan detik.</p></div>
        <div class="card reveal"><div class="icon-tile">📊</div><h3>Profit Calculator</h3><p>Hitung HPP, potong admin, dan pastikan produk tidak boncos.</p></div>
        <div class="card reveal"><div class="icon-tile">🎥</div><h3>Live Script Generator</h3><p>Script jualan siap pakai untuk host live TikTok & Shopee.</p></div>
        <div class="card reveal"><div class="icon-tile">💬</div><h3>Customer Service</h3><p>Ubah komplain menjadi loyalitas dengan balasan chat empati.</p></div>
        <div class="card reveal"><div class="icon-tile">📘</div><h3>Business Playbook</h3><p>Pabrik SOP. Susun sistem bisnis yang rapi dan bisa diwariskan.</p></div>
        <div class="card reveal"><div class="icon-tile">📈</div><h3>Analytics</h3><p>Insight metrik dan grafik produktivitas AI Anda secara real-time.</p></div>
        <div class="card reveal"><div class="icon-tile">🔁</div><h3>Workflow Automation</h3><p>Pekerjaan berulang yang berjalan sendiri di latar belakang.</p></div>
      </div>
    </div>
  </section>

  <!-- INTERACTIVE DEMO -->
  <section id="demo">
    <div class="wrap">
      <div class="section-head center reveal">
        <div class="eyebrow center" style="justify-content:center;"><span class="dot"></span>Coba Sendiri</div>
        <h2>Ketik Masalah Anda. Lihat AI Boss Bekerja.</h2>
        <p style="margin:0 auto;">Ini bukan simulasi kosong — begini persisnya cara AI Boss merubah niat Anda menjadi sebuah rencana kerja komplit.</p>
      </div>
      <div class="sim-wrap reveal">
        <div class="sim-input-row">
          <span class="dotpulse"></span>
          <span id="typed-text"></span>
        </div>
        <div class="sim-output" id="sim-output">
          <div class="sim-item"><span class="tag">Content Plan</span><p>7 ide konten mingguan berdasar tren audiens TikTok Anda.</p></div>
          <div class="sim-item"><span class="tag">Promo Strategy</span><p>Bundling diskon 20% untuk produk dengan margin tertinggi.</p></div>
          <div class="sim-item"><span class="tag">Copywriting</span><p>3 variasi caption siap posting, gaya santai & hard-selling.</p></div>
          <div class="sim-item"><span class="tag">Live Script</span><p>Script live 30 menit lengkap dengan Hook & Call-to-Action.</p></div>
          <div class="sim-item"><span class="tag">Checklist</span><p>5 tugas prioritas yang wajib diselesaikan hari ini.</p></div>
          <div class="sim-item"><span class="tag">KPI Target</span><p>Target omzet & konversi metrik yang perlu dipantau minggu ini.</p></div>
        </div>
      </div>
    </div>
  </section>

  <!-- COMPARISON -->
  <section id="compare">
    <div class="wrap">
      <div class="section-head reveal">
        <div class="eyebrow"><span class="dot"></span>Perbandingan</div>
        <h2>Bukan ChatGPT Biasa</h2>
      </div>
      <div class="compare reveal">
        <div class="compare-row head"><div>Fitur</div><div>AI Generik (ChatGPT)</div><div>AI Boss Platform</div></div>
        <div class="compare-row"><div>Fungsi Utama</div><div>Sekadar menjawab pertanyaan</div><div>Menyelesaikan task/pekerjaan</div></div>
        <div class="compare-row"><div>Cara Pakai</div><div>Mengetik prompt manual & rumit</div><div>Menu/Form terstruktur & otomatis</div></div>
        <div class="compare-row"><div>User Interface</div><div>Satu jendela chat yang bertumpuk</div><div>Business Operating System (Dashboard)</div></div>
        <div class="compare-row"><div>Hasil Output</div><div>Teks panjang yang harus diedit</div><div>SOP, Laporan, & Konten Siap Pakai</div></div>
        <div class="compare-row"><div>Konteks Bisnis</div><div>Harus dijelaskan dari nol tiap saat</div><div>Paham profil & keuangan bisnis Anda</div></div>
      </div>
    </div>
  </section>

  <!-- TESTIMONIALS -->
  <section id="testimonials">
    <div class="wrap">
      <div class="section-head reveal">
        <div class="eyebrow"><span class="dot"></span>Cerita Nyata</div>
        <h2>Dipercaya Oleh Pemilik Bisnis Seperti Anda</h2>
      </div>
      <div class="testi-grid">
        <div class="card testi-card reveal">
          <p>"Dulu saya begadang balas chat pelanggan. Sekarang modul CS AI Boss yang menyusun jawabannya, saya tinggal klik copy-paste. Tidur nyenyak!"</p>
          <div class="testi-foot"><div class="avatar"></div><div><div class="testi-name">Dinda R.</div><div class="testi-role">Owner, Skincare Lokal</div></div><div class="testi-growth">+42% omzet</div></div>
        </div>
        <div class="card testi-card reveal">
          <p>"Konten yang biasanya butuh mikir 2 jam, sekarang 5 menit beres di Content Studio. Kualitas hook dan copywritingnya malah lebih gila."</p>
          <div class="testi-foot"><div class="avatar"></div><div><div class="testi-name">Fajar S.</div><div class="testi-role">Seller TikTok Shop</div></div><div class="testi-growth">+65% konten</div></div>
        </div>
        <div class="card testi-card reveal">
          <p>"Baru pertama kali saya tahu produk mana yang ternyata selama ini boncos. Profit Studio benar-benar menyelamatkan cashflow bisnis saya."</p>
          <div class="testi-foot"><div class="avatar"></div><div><div class="testi-name">Rima A.</div><div class="testi-role">Distributor Fashion</div></div><div class="testi-growth">+30% margin</div></div>
        </div>
      </div>
    </div>
  </section>

  <!-- PRICING -->
  <section id="pricing">
    <div class="wrap">
      <div class="section-head center reveal">
        <div class="eyebrow center" style="justify-content:center;"><span class="dot"></span>Harga Langganan</div>
        <h2>Investasi Sekecil Gaji Karyawan Part-Time</h2>
        <p style="margin:0 auto;">Dapatkan hasil kerja setara menyewa satu divisi manajemen lengkap.</p>
      </div>
      <div class="toggle-row reveal">
        <span>Bulanan</span>
        <button class="switch" id="billing-switch" onclick="toggleBilling()"><span class="knob"></span></button>
        <span>Tahunan <span style="color:#4ADE80; font-weight: 700; margin-left: 4px;">(Hemat 20%)</span></span>
      </div>
      <div class="price-grid">
        <div class="card price-card reveal">
          <h3>Starter</h3><div class="tagline">Untuk bisnis yang baru mulai</div>
          <div class="amount" data-monthly="149" data-yearly="119">Rp 149rb<span>/bln</span></div>
          <a href="#" class="btn btn-ghost">Coba Gratis 7 Hari</a>
          <ul class="price-feat"><li>1 Pengguna</li><li>Content Studio & Live Script</li><li>100 Output AI / bulan</li><li>Dashboard Analytics</li></ul>
        </div>
        <div class="card price-card featured reveal">
          <span class="pop">PALING POPULER</span>
          <h3>Professional</h3><div class="tagline">Untuk akselerasi toko online</div>
          <div class="amount" data-monthly="349" data-yearly="279">Rp 349rb<span>/bln</span></div>
          <a href="#" class="btn btn-primary">Mulai Akses Sekarang</a>
          <ul class="price-feat"><li>Semua fitur Starter</li><li>Customer Service Center</li><li>Profit Calculator</li><li>Akses AI Tanpa Batas</li></ul>
        </div>
        <div class="card price-card reveal">
          <h3>Business</h3><div class="tagline">Untuk tim skala kecil-menengah</div>
          <div class="amount" data-monthly="799" data-yearly="639">Rp 799rb<span>/bln</span></div>
          <a href="#" class="btn btn-ghost">Mulai Akses Tim</a>
          <ul class="price-feat"><li>Semua fitur Professional</li><li>5 Pengguna/Staff</li><li>SOP Business Playbook</li><li>Workflow Automation</li></ul>
        </div>
        <div class="card price-card reveal">
          <h3>Enterprise</h3><div class="tagline">Kustomisasi untuk brand besar</div>
          <div class="amount" style="font-size:32px; padding: 5px 0;">Kustom</div>
          <a href="#" class="btn btn-ghost">Hubungi Sales</a>
          <ul class="price-feat"><li>Fitur Business Tanpa Batas</li><li>Integrasi API Khusus</li><li>Onboarding Langsung</li><li>Dedicated Support 24/7</li></ul>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ -->
  <section id="faq">
    <div class="wrap" style="max-width:820px;">
      <div class="section-head reveal">
        <div class="eyebrow"><span class="dot"></span>FAQ</div>
        <h2>Pertanyaan yang Sering Diajukan</h2>
      </div>
      <div class="reveal" style="background: var(--surface-solid); border: 1px solid var(--border); border-radius: 24px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
        <div class="faq-item">
          <div class="faq-q">Apakah saya harus paham koding atau AI untuk memakai ini? <span class="plus">+</span></div>
          <div class="faq-a"><p>Sama sekali tidak. AI Boss dirancang untuk owner awam. Antarmukanya berupa formulir isian biasa, biarkan mesin di belakang layar yang mengurus kerumitan AI-nya.</p></div>
        </div>
        <div class="faq-item">
          <div class="faq-q">Apa bedanya AI Boss dengan ChatGPT versi gratis? <span class="plus">+</span></div>
          <div class="faq-a"><p>ChatGPT itu ibarat kertas kosong, Anda harus tahu cara memerintahnya (Prompting). AI Boss adalah "Sistem", kami sudah memasukkan prompt profesional tersembunyi untuk Marketing, Keuangan, dan CS. Hasilnya 10x lebih spesifik dan siap pakai.</p></div>
        </div>
        <div class="faq-item">
          <div class="faq-q">Apakah data jualan & produk saya aman? <span class="plus">+</span></div>
          <div class="faq-a"><p>Sangat aman. Database Anda dienkripsi dalam server terpisah dan sistem AI Google Gemini yang kami gunakan tidak menggunakan privasi Anda untuk melatih model publik mereka.</p></div>
        </div>
        <div class="faq-item">
          <div class="faq-q">Apakah AI Boss bisa menangani komplain pelanggan? <span class="plus">+</span></div>
          <div class="faq-a"><p>Tentu. Modul CS Center kami dilatih khusus untuk meredam emosi pelanggan, menawarkan solusi kompensasi yang masuk akal, dan menggunakan bahasa yang sangat empati & sopan.</p></div>
        </div>
        <div class="faq-item">
          <div class="faq-q">Apakah bisa dibatalkan kapan saja? <span class="plus">+</span></div>
          <div class="faq-a"><p>Bisa. Tidak ada kontrak mengikat. Anda dapat membatalkan atau menurunkan paket langganan Anda kapan pun langsung dari dalam dashboard.</p></div>
        </div>
      </div>
    </div>
  </section>

  <!-- FINAL CTA -->
  <section style="padding-bottom: 80px;">
    <div class="wrap">
    <div class="final-cta reveal">
        <h2>Bisnis Anda Layak Punya<br>Sistem yang Lebih Baik.</h2>
        <p>Berhentilah menjadi karyawan di bisnis Anda sendiri. Bangun tim AI Anda hari ini.</p>

        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg">Buka Workspace Anda</a>
            @else
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Mulai Coba Gratis Sekarang</a>
            @endauth
        @endif
      </div>
    </div>
  </section>

</main>

<footer>
  <div class="wrap">
    <div class="foot-grid">
      <div class="foot-col">
        <div class="logo" style="margin-bottom: 8px;"><span class="logo-mark"></span> AI Boss</div>
        <p class="foot-desc">Sistem operasi bisnis bertenaga Kecerdasan Buatan (AI) untuk owner yang ingin scale-up lebih cepat.</p>
      </div>
      <div class="foot-col"><h5>Perusahaan</h5><a href="#">Tentang Kami</a><a href="#">Karir</a><a href="#">Blog Tips Bisnis</a></div>
      <div class="foot-col"><h5>Produk & Fitur</h5><a href="#features">Fitur Lengkap</a><a href="#pricing">Harga Langganan</a><a href="#faq">Pusat Bantuan</a></div>
      <div class="foot-col"><h5>Legalitas</h5><a href="#">Syarat & Ketentuan</a><a href="#">Kebijakan Privasi</a><a href="#">Status Server</a></div>
    </div>
    <div class="foot-bottom">
      <div>© 2026 AI Boss Indonesia. Seluruh hak cipta dilindungi.</div>
      <div class="foot-social"><a href="#">Instagram</a><a href="#">TikTok</a><a href="#">LinkedIn</a></div>
    </div>
  </div>
</footer>

<script>
  // Mouse follow glow effect
  const glow = document.getElementById('mouse-glow');
  window.addEventListener('mousemove', (e)=>{
    glow.style.opacity = 1;
    glow.style.left = e.clientX + 'px';
    glow.style.top = e.clientY + 'px';
  });
  window.addEventListener('mouseleave', ()=> glow.style.opacity = 0);

  // Scroll reveal animation
  const revealEls = document.querySelectorAll('.reveal');
  const io = new IntersectionObserver((entries)=>{
    entries.forEach(en=>{ if(en.isIntersecting){ en.target.classList.add('in'); io.unobserve(en.target); } });
  }, { threshold:0.1 });
  revealEls.forEach(el=> io.observe(el));

  // FAQ accordion logic
  document.querySelectorAll('.faq-item').forEach(item=>{
    const q = item.querySelector('.faq-q');
    const a = item.querySelector('.faq-a');
    q.addEventListener('click', ()=>{
      const isOpen = item.classList.contains('open');
      document.querySelectorAll('.faq-item.open').forEach(o=>{
        o.classList.remove('open'); o.querySelector('.faq-a').style.maxHeight = null;
      });
      if(!isOpen){ item.classList.add('open'); a.style.maxHeight = a.scrollHeight + 'px'; }
    });
  });

  // Pricing billing toggle
  let yearly = false;
  function toggleBilling(){
    yearly = !yearly;
    document.getElementById('billing-switch').classList.toggle('on', yearly);
    document.querySelectorAll('.amount[data-monthly]').forEach(el=>{
      const val = yearly ? el.dataset.yearly : el.dataset.monthly;
      el.innerHTML = 'Rp ' + val + 'rb<span>/bln</span>';
    });
  }

  // Interactive Typing Simulation
  const phrases = ["Saya ingin menaikkan konversi jualan.", "Saya butuh ide promo untuk bulan ini.", "Bikinkan SOP untuk admin gudang saya."];
  let phraseIdx = 0, charIdx = 0, deleting = false;
  const typedEl = document.getElementById('typed-text');
  const simItems = document.querySelectorAll('#sim-output .sim-item');

  function revealSimItems(){ simItems.forEach((el,i)=> setTimeout(()=> el.classList.add('show'), i*150)); }
  function hideSimItems(){ simItems.forEach(el=> el.classList.remove('show')); }

  function typeLoop(){
    const current = phrases[phraseIdx];
    if(!deleting){
      typedEl.textContent = current.slice(0, charIdx+1);
      charIdx++;
      if(charIdx === current.length){
        revealSimItems();
        setTimeout(()=>{ deleting = true; typeLoop(); }, 3500);
        return;
      }
    } else {
      typedEl.textContent = current.slice(0, charIdx-1);
      charIdx--;
      if(charIdx === 0){
        deleting = false; hideSimItems();
        phraseIdx = (phraseIdx+1) % phrases.length;
        setTimeout(typeLoop, 800);
        return;
      }
    }
    setTimeout(typeLoop, deleting ? 25 : 55);
  }
  typeLoop();

  // Animated counters for social proof
  const proofNums = document.querySelectorAll('.proof-grid .num');
  const targets = [3800, 10000, 1000000];
  const suffix = ['','+','+'];
  let counted = false;
  const proofObs = new IntersectionObserver((entries)=>{
    entries.forEach(en=>{
      if(en.isIntersecting && !counted){
        counted = true;
        proofNums.forEach((el, i)=>{
          const target = targets[i];
          let start = 0; const duration = 2000; const startTime = performance.now();
          function step(now){
            const p = Math.min((now-startTime)/duration, 1);
            const eased = 1 - Math.pow(1-p, 4); // Quartic ease out
            const val = Math.floor(eased * target);
            el.innerHTML = '<span class="grad">' + val.toLocaleString('id-ID') + suffix[i] + '</span>';
            if(p < 1) requestAnimationFrame(step);
          }
          requestAnimationFrame(step);
        });
      }
    });
  }, { threshold:0.5 });
  if(proofNums.length) proofObs.observe(document.querySelector('.proof-strip'));
</script>

</body>
</html>
