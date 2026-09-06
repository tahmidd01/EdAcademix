<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>


<nav class="second-navbar">
  <div class="container">
    <ul class="nav-list">
      <!-- LEFT SIDE -->
      <li class="left-group">
        <button class="home-button" onclick="location.href='index.php';">
          <img src="img/h1.png" alt="Home">
        </button>
      </li>

      <!-- RIGHT SIDE -->
      <li class="right-group">
        <a href="#" id="faqsOption">FAQs</a>

        <?php if (isset($_SESSION['user_name'])): ?>
          <!-- Show user's name -->
          <a href="#" class="user-name">
            <?php echo htmlspecialchars($_SESSION['user_name']); ?>
          </a>
<div class="profile-wrapper" style="position: relative; display: inline-block;">
  <div id="profileToggle" class="profile-button">
    <img src="img/p1.png" alt="Profile">
  </div>

  <div class="profile-dropdown" id="profileDropdown" style="top: 100%; left: -16px;">
    <div class="profile-header">
      <div class="avatar"></div>
      <div class="profile-info">

      </div>
    </div>

    <div class="dropdown-options">
      <button class="option">➕ Add another Account</button>
      <button class="option">📝 Customize Profile</button>
      <button class="option">👤 Manage your EdAcademix Account</button>
      <button class="option">🔑 Privacy & Security</button>
      <button class="option">⚙️ Settings</button>
    </div>

    <div class="logout-section">
      <form action="logout.php" method="post">
        <button type="submit" class="logout-btn">Log Out</button>
      </form>
    </div>
  </div>
</div>



          <!-- <a href="logout.php" class="logout-link">Logout</a> -->
        <?php else: ?>
          <!-- Show login/signup links -->
          <a href="login.php">Log in / Sign up</a>
          <button class="profile-button">
            <img src="img/p1.png" alt="Profile">
          </button>
        <?php endif; ?>
      </li>
    </ul>
  </div>
</nav>
