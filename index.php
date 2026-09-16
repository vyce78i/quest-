<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>loony loot</title>
    <link rel="stylesheet" href="public/css/style.css" />
  </head>
  <body>
    <div class="app-container">
      <!-- player card -->
      <header class="player-card">
        <div class="player-header">
          <!-- click avatar frame to open swipe picker -->
          <div class="avatar-frame" onclick="openAvatarSelector()" style="cursor: pointer;" title="change avatar">
            <img id="current-avatar" src="https://api.dicebear.com/7.x/pixel-art/svg?seed=girl_1" alt="avatar" />
          </div>

          <div style="flex: 1;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
              <h1 class="player-title"><span id="username">hero</span>'s quest log</h1>
              <div class="player-controls">
                <div class="level-badge" id="level-badge">lvl 1</div>
                <button class="logout-btn" id="logout-btn">logout</button>
              </div>
            </div>

            <!-- xp progression track -->
            <div class="xp-section">
              <div class="xp-meta">
                <span>daily quest progress</span>
                <span id="xp-text">0 / 100 xp</span>
              </div>
              <div class="xp-track">
                <div class="xp-bar" id="xp-bar"></div>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- add quest form -->
      <form class="add-quest-card" id="quest-form">
        <input 
          type="text" 
          id="quest-input" 
          class="add-quest-input" 
          placeholder="embark on a new quest..." 
          required 
          autocomplete="off"
        />
        <button type="submit" class="add-quest-btn">+ quest</button>
      </form>

      <!-- quest list -->
      <main class="quest-list" id="quest-list">
        <div class="empty-state">loading active quests...</div>
      </main>
    </div>

    <!-- 40 pixel hero swipe picker modal -->
    <div class="avatar-modal-overlay" id="avatar-modal" style="display: none;">
      <div class="avatar-selector-box">
        <h2 class="selector-title">choose your hero</h2>

        <!-- gender tabs -->
        <div class="gender-switch">
          <button type="button" class="gender-tab active" id="tab-girls" onclick="switchCategory('girls')">heroines (20)</button>
          <button type="button" class="gender-tab" id="tab-boys" onclick="switchCategory('boys')">heroes (20)</button>
        </div>

        <!-- sides carousel track -->
        <div class="carousel-viewport">
          <button type="button" class="carousel-arrow" onclick="navAvatar(-1)">&lt;</button>

          <!-- shadowy left side -->
          <div class="avatar-slot shadowy" id="slot-left">
            <img id="img-left" src="" alt="prev hero" />
          </div>

          <!-- active center pixy -->
          <div class="avatar-slot active" id="slot-mid">
            <img id="img-mid" src="" alt="current hero" />
          </div>

          <!-- shadowy right side -->
          <div class="avatar-slot shadowy" id="slot-right">
            <img id="img-right" src="" alt="next hero" />
          </div>

          <button type="button" class="carousel-arrow" onclick="navAvatar(1)">&gt;</button>
        </div>

        <div style="font-size: 8px; color: var(--text-muted);" id="avatar-index-label">1 / 20</div>

        <!-- pick action button -->
        <button type="button" class="pick-btn" id="pick-hero-btn" onclick="confirmPick()">pick</button>
      </div>
    </div>

    <script src="public/js/app.js"></script>
  </body>
</html>
