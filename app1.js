// active user cache
let currentuser = null;

// exact counts for girls and boys
const avatar_counts = {
  girls: 20,
  boys: 11
};

let chosen_gender = 'girls';
let currentindex = 0;

// local path resolver
function getavatarurl(gender, index) {
  const filenumber = index + 1;
  const prefix = gender === 'girls' ? 'girl' : 'boy';
  return `public/assets/avatars/${gender}/${prefix}_${filenumber}.png`;
}

// initialization
document.addEventListener('DOMContentLoaded', () => {
  init();

  const questform = document.getElementById('quest-form');
  if (questform) questform.addEventListener('submit', handlecreatequest);

  const logoutbtn = document.getElementById('logout-btn');
  if (logoutbtn) logoutbtn.addEventListener('click', handlelogout);
});

async function init() {
  await fetchuser();
  await fetchhabits();
}

// get session user
async function fetchuser() {
  try {
    const res = await fetch('api/me.php');
    const data = await res.json();

    if (!data.authenticated) {
      window.location.href = 'login.php';
      return;
    }

    currentuser = data.user;
    renderuser();
  } catch (err) {
    console.error('session read error:', err);
  }
}

// update dashboard user profile and avatar
function renderuser() {
  document.getElementById('username').textContent = currentuser.username.toLowerCase();
  document.getElementById('level-badge').textContent = `lvl ${currentuser.current_level}`;

  const currenttierxp = currentuser.xp % 100;
  document.getElementById('xp-text').textContent = `${currenttierxp} / 100 xp`;
  document.getElementById('xp-bar').style.width = `${currenttierxp}%`;

  const savedavatar = currentuser.avatar || 'girl_1';
  const category = savedavatar.startsWith('boy') ? 'boys' : 'girls';
  document.getElementById('current-avatar').src = `public/assets/avatars/${category}/${savedavatar}.png`;
}

// load quests
async function fetchhabits() {
  try {
    const res = await fetch('api/get-habit.php');
    const data = await res.json();
    renderhabits(data.habits || []);
  } catch (err) {
    console.error('habits read error:', err);
  }
}

// render quest items
function renderhabits(habits) {
  const container = document.getElementById('quest-list');
  if (!habits.length) {
    container.innerHTML = `<div style="text-align: center; font-size: 8px; color: var(--text-muted); padding: 16px;">no active quests logged today.</div>`;
    return;
  }

  container.innerHTML = habits.map(h => {
    const isdone = parseInt(h.completed_today, 10) === 1;
    return `
      <div class="quest-row ${isdone ? 'done' : ''}">
        <div>
          <div class="quest-name">${escapehtml(h.title)}</div>
          <div style="font-size: 7px; color: var(--lilac); margin-top: 4px;">streak: ${h.streak_count} days</div>
        </div>
        <div>
          <button 
            type="button" 
            class="action-btn" 
            onclick="handlecompletequest(${h.id})" 
            ${isdone ? 'disabled' : ''}>
            ${isdone ? 'done' : '+15 xp'}
          </button>
          <button type="button" class="del-btn" onclick="handledeletequest(${h.id})">x</button>
        </div>
      </div>
    `;
  }).join('');
}

// add quest
async function handlecreatequest(e) {
  e.preventDefault();
  const input = document.getElementById('quest-input');
  const title = input.value.trim();
  if (!title) return;

  const res = await fetch('api/create-habit.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ title, category: 'general' })
  });
  const data = await res.json();

  if (data.success) {
    input.value = '';
    fetchhabits();
  }
}

// complete quest
async function handlecompletequest(habitid) {
  const res = await fetch('api/complete-habit.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ habit_id: habitid })
  });
  const data = await res.json();

  if (data.success) {
    fetchuser();
    fetchhabits();
  }
}

// delete quest
async function handledeletequest(habitid) {
  if (!confirm('abandon quest?')) return;
  const res = await fetch('api/delete-habit.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ habit_id: habitid })
  });
  const data = await res.json();
  if (data.success) fetchhabits();
}

// logout
async function handlelogout() {
  await fetch('api/logout.php');
  window.location.reload();
}

// open gender choice modal step 1
function openGenderStep() {
  document.getElementById('avatar-modal').style.display = 'flex';
  document.getElementById('gender-selection-view').style.display = 'flex';
  document.getElementById('carousel-selection-view').style.display = 'none';
}

// pick gender and proceed to carousel step 2
function selectGender(gender) {
  chosen_gender = gender;
  currentindex = 0;

  document.getElementById('gender-selection-view').style.display = 'none';
  document.getElementById('carousel-selection-view').style.display = 'flex';
  document.getElementById('carousel-title').textContent = `${gender === 'girls' ? 'heroines' : 'heroes'} (${avatar_counts[gender]})`;

  resetpickbutton();
  updatecarouselview();
}

// navigate carousel
function navAvatar(direction) {
  const total = avatar_counts[chosen_gender];
  currentindex = (currentindex + direction + total) % total;
  resetpickbutton();
  updatecarouselview();
}

// update 3-card carousel view
function updatecarouselview() {
  const total = avatar_counts[chosen_gender];

  const leftindex = (currentindex - 1 + total) % total;
  const rightindex = (currentindex + 1) % total;

  document.getElementById('img-left').src = getavatarurl(chosen_gender, leftindex);
  document.getElementById('img-mid').src = getavatarurl(chosen_gender, currentindex);
  document.getElementById('img-right').src = getavatarurl(chosen_gender, rightindex);

  document.getElementById('avatar-index-label').textContent = `${currentindex + 1} / ${total}`;
}

// reset pick button
function resetpickbutton() {
  const btn = document.getElementById('pick-hero-btn');
  btn.textContent = 'pick';
  btn.classList.remove('picked');
  btn.disabled = false;
}

// save selection to db
async function confirmPick() {
  const filenumber = currentindex + 1;
  const prefix = chosen_gender === 'girls' ? 'girl' : 'boy';
  const pickedseed = `${prefix}_${filenumber}`;

  const btn = document.getElementById('pick-hero-btn');
  btn.textContent = 'picked!';
  btn.classList.add('picked');
  btn.disabled = true;

  try {
    const res = await fetch('api/avatar.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ avatar: pickedseed })
    });
    const data = await res.json();

    if (data.success) {
      if (currentuser) currentuser.avatar = pickedseed;
      document.getElementById('current-avatar').src = `public/assets/avatars/${chosen_gender}/${pickedseed}.png`;

      setTimeout(() => {
        document.getElementById('avatar-modal').style.display = 'none';
        resetpickbutton();
      }, 450);
    }
  } catch (err) {
    console.error('save avatar error:', err);
    resetpickbutton();
  }
}

// sanitize html
function escapehtml(str) {
  return str.replace(/[&<>'"]/g, tag => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;'
  }[tag] || tag));
}
