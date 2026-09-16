<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>loony loot - enter the realm</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="public/css/style.css" />
  <style>
    body {
      background: var(--bg-dark);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 16px;
      font-family: 'Press Start 2P', monospace, sans-serif;
    }
    .auth-wrapper {
      width: 100%;
      max-width: 460px;
      background: var(--card-bg);
      border: 4px solid var(--border-line);
      box-shadow: 6px 6px 0 var(--black);
      padding: 32px 24px;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }
    .auth-title {
      font-size: 14px;
      text-align: center;
      color: var(--pink);
      text-shadow: var(--pink-glow);
      line-height: 1.5;
    }
    .auth-sub {
      text-align: center;
      color: var(--lilac);
      font-size: 9px;
      line-height: 1.6;
      margin-bottom: 8px;
    }
    .form-group {
      display: flex;
      flex-direction: column;
      gap: 8px;
      margin-bottom: 12px;
    }
    .form-group label {
      font-size: 10px;
      color: var(--lilac);
      text-transform: lowercase;
      letter-spacing: 0.5px;
    }
    .form-group input {
      background: var(--black);
      border: 2px solid var(--border-line);
      padding: 14px 12px;
      color: var(--white);
      font-family: inherit;
      font-size: 11px;
      outline: none;
    }
    .form-group input:focus {
      border-color: var(--pink);
    }
    .submit-btn {
      width: 100%;
      background: var(--pink);
      border: 3px solid var(--black);
      box-shadow: 3px 3px 0 var(--black);
      color: var(--white);
      font-family: inherit;
      padding: 14px;
      cursor: pointer;
      font-size: 11px;
      margin-top: 8px;
    }
    .submit-btn:active {
      transform: translate(2px, 2px);
      box-shadow: 1px 1px 0 var(--black);
    }
    .toggle-link {
      text-align: center;
      margin-top: 12px;
      font-size: 9px;
      color: var(--muted);
      cursor: pointer;
      line-height: 1.6;
    }
    .toggle-link span {
      color: var(--lilac);
      text-decoration: underline;
    }
    .error-msg {
      color: var(--pink);
      font-size: 9px;
      margin-bottom: 8px;
      display: none;
      text-align: center;
      line-height: 1.5;
    }
  </style>
</head>
<body>

<div class="auth-wrapper">
  <h1 class="auth-title" id="form-heading">enter the realm</h1>
  <p class="auth-sub" id="form-sub">log in to resume your daily quests</p>
  
  <div class="error-msg" id="error-msg"></div>

  <form id="auth-form" onsubmit="handleAuth(event)">
    <div class="form-group">
      <label for="username">hero name</label>
      <input type="text" id="username" required autocomplete="off" />
    </div>
    
    <div class="form-group">
      <label for="password">password</label>
      <input type="password" id="password" required />
    </div>

    <button type="submit" class="submit-btn" id="submit-btn">login</button>
  </form>

  <div class="toggle-link" onclick="toggleAuthMode()">
    <span id="toggle-text">need an account? register as a new hero</span>
  </div>
</div>

<script>
  let isLoginMode = true;

  function toggleAuthMode() {
    isLoginMode = !isLoginMode;
    document.getElementById('form-heading').textContent = isLoginMode ? 'enter the realm' : 'new hero registry';
    document.getElementById('form-sub').textContent = isLoginMode ? 'log in to resume your daily quests' : 'create an account and start your quest';
    document.getElementById('submit-btn').textContent = isLoginMode ? 'login' : 'create hero';
    document.getElementById('toggle-text').textContent = isLoginMode ? 'need an account? register as a new hero' : 'already have a hero? login here';
    document.getElementById('error-msg').style.display = 'none';
  }

  async function handleAuth(e) {
    e.preventDefault();
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const errorEl = document.getElementById('error-msg');
    errorEl.style.display = 'none';

    const endpoint = isLoginMode ? 'api/login.php' : 'api/register.php';

    try {
      const res = await fetch(endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password })
      });
      const data = await res.json();

      if (data.success) {
        // direct user straight to the gender & avatar selection page
        window.location.href = 'choose-hero.php';
      } else {
        errorEl.textContent = (data.error || 'failed to authenticate.').toLowerCase();
        errorEl.style.display = 'block';
      }
    } catch (err) {
      errorEl.textContent = 'server connection error.';
      errorEl.style.display = 'block';
    }
  }
</script>
</body>
</html>
