<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>loony loot</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="public/css/style.css" />
  </head>
  <body>
    <div class="app-container">
      <!-- header player profile section -->
      <header class="pixel-card">
        <div class="profile-row">
          <div class="hero-identity">
            <!-- circle hero icon frame -->
            <div class="avatar-circle-frame" onclick="openGenderStep()" title="change hero">
              <img id="current-avatar" src="public/assets/avatars/girls/girl_1.png" alt="hero avatar" />
            </div>
            <!-- username on top left -->
            <h1 class="player-username" id="username">hero</h1>
          </div>

          <!-- level tag and logout -->
          <div class="header-actions">
            <span class="level-tag" id="level-badge">lvl 1</span>
            <button type="button" class="exit-btn" id="logout-btn">logout</button>
          </div>
        </div>

        <!-- xp progression bar -->
        <div class="xp-section">
          <div class="xp-meta">
            <span>daily quest progress</span>
            <span id="xp-text">0 / 100 xp</span>
          </div>
          <div class="xp-track">
            <div class="xp-fill" id="xp-bar"></div>
          </div>
        </div>
      </header>

      <!-- quest creation input -->
      <form class="quest-form" id="quest-form">
        <input 
          type="text" 
          id="quest-input" 
          class="quest-input" 
          placeholder="level up with a quest of urs..." 
          required 
          autocomplete="off"
        />
        <button type="submit" class="quest-btn">+ quest</button>
      </form>

      <!-- quest feed -->
      <main class="quest-list" id="quest-list"></main>
    </div>

    <!-- character selection modal -->
    <div class="picker-overlay" id="avatar-modal" style="display: none;">
      <div class="picker-box">
        
        <!-- step 1: hero gender choice -->
        <div id="gender-selection-view" class="gender-step">
          <h2 class="picker-title">hero's gender</h2>
          <button type="button" class="pixel-dashed-btn" onclick="selectGender('girls')">girl</button>
          <button type="button" class="pixel-dashed-btn" onclick="selectGender('boys')">boy</button>
        </div>

        <!-- step 2: 3-slot carousel -->
        <div id="carousel-selection-view" class="carousel-step" style="display: none;">
          <h2 class="picker-title" id="carousel-title">choose hero</h2>
          
          <div class="carousel-viewport">
            <button type="button" class="nav-arrow-btn" onclick="navAvatar(-1)">&lt;</button>

            <!-- shadowy left -->
            <div class="avatar-preview-card shadowy">
              <img id="img-left" src="" alt="prev hero" />
            </div>

            <!-- illuminated center active -->
            <div class="avatar-preview-card active">
              <img id="img-mid" src="" alt="chosen hero" />
            </div>

            <!-- shadowy right -->
            <div class="avatar-preview-card shadowy">
              <img id="img-right" src="" alt="next hero" />
            </div>

            <button type="button" class="nav-arrow-btn" onclick="navAvatar(1)">&gt;</button>
          </div>

          <span style="font-size: 8px; color: var(--lilac);" id="avatar-index-label">1 / 20</span>

          <!-- pick action -->
          <button type="button" class="pick-btn" id="pick-hero-btn" onclick="confirmPick()">pick</button>
        </div>

      </div>
    </div>

    <script src="public/js/app.js"></script>
  </body>
</html>
