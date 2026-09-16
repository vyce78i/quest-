<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Habit Quest — Enter the Realm</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="public/css/style.css" />
  <style>
    .auth-wrapper {
      width: 100%;
      max-width: 400px;
      margin: auto;
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 32px 24px;
      box-shadow: 0 12px 32px rgba(0, 0, 0, 0.5);
    }
    .auth-title {
      font-family: 'Cinzel', serif;
      font-size: 1.5rem;
      text-align: center;
      margin-bottom: 8px;
      color: #fff;
    }
    .auth-sub {
      text-align: center;
      color: var(--text-muted);
      font-size: 0.85rem;
      margin-bottom: 24px;
    }
    .form-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
      margin-bottom: 16px;
    }
    .form-group label {
      font-size: 0.8rem;
      font-weight: 600;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .form-group input {
      background: #0d0f14;
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 10px 12px;
      color: #fff;
      font-size: 0.95rem;
      outline: none;
      transition: border-color 0.2s;
    }
    .form-group input:focus {
      border-color: var(--accent);
    }
    .submit-btn {
      width: 100%;
      background: var(--accent);
      border: none;
      color: #fff;
      font-weight: 600;
      padding: 12px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 0.95rem;
      margin-top: 8px;
      transition: opacity 0.2s;
    }
    .submit-btn:hover {
      opacity: 0.9;
    }
    .toggle-link {
      text-align: center;
      margin-top: 16px;
      font-size: 0.85rem;
      color: var(--text-muted);
      cursor: pointer;
    }
    .toggle-link span {
      color: var(--accent);
      text-decoration: underline;
    }
    .error-msg {
      color: var(--danger);
      font-size: 0.85rem;
      margin-bottom: 12px;
      display: none;
      text-align: center;
    }
  </style>
</head>
<body>

<div class="auth-wrapper">
  <h1 class="auth-title" id="form-heading">Enter the Realm</h1>
  <p class="auth-sub" id="form-sub">Log in to resume your daily quests</p>
  
  <div class="error-msg" id="error-msg"></div>

  <form id="auth-form" onsubmit="handleAuth(event)">
    <div class="form-group">
      <label for="username">Hero Name</label>
      <input type="text" id="username" required autocomplete="off" />
    </div>
    
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" id="password" required />
    </div>

    <button type="submit" class="submit-btn" id="submit-btn">Login</button>
  </form>

  <div class="toggle-link" onclick="toggleAuthMode()">
    <span id="toggle-text">Need an account? Register as a New Hero</span>
  </div>
</div>

<script>
  let isLoginMode = true;

  function toggleAuthMode() {
    isLoginMode = !isLoginMode;
    document.getElementById('form-heading').textContent = isLoginMode ? 'Enter the Realm' : 'New Hero Registry';
    document.getElementById('form-sub').textContent = isLoginMode ? 'Log in to resume your daily quests' : 'Create an account and start your quest';
    document.getElementById('submit-btn').textContent = isLoginMode ? 'Login' : 'Create Hero';
    document.getElementById('toggle-text').textContent = isLoginMode ? 'Need an account? Register as a New Hero' : 'Already have a hero? Login here';
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
        window.location.href = 'index.php';
      } else {
        errorEl.textContent = data.error || 'Failed to authenticate.';
        errorEl.style.display = 'block';
      }
    } catch (err) {
      errorEl.textContent = 'Server connection error.';
      errorEl.style.display = 'block';
    }
  }
</script>
</body>
</html>
