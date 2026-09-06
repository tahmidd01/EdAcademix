<?php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user_name'])) {

}
?>
<style>
  .profile-dropdown {
    position: absolute;
    top: 65px; /* Adjust based on navbar height */
    right: 16px;
    background-color: #111;
    width: 290px;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    display: none;
    opacity: 0;
    transform: translateY(-10px);
    transition: opacity 0.3s ease, transform 0.3s ease;
    z-index: 9999;
  }

  .profile-dropdown.show {
    display: block;
    opacity: 1;
    transform: translateY(0);
  }

  .profile-header {
    background-color: #d3d3d3;
    padding: 20px;
    text-align: center;
  }

  .avatar {
    background-color: white;
    border-radius: 50%;
    width: 70px;
    height: 70px;
    margin: 0 auto 10px;
    background-image: url('https://upload.wikimedia.org/wikipedia/commons/9/99/Sample_User_Icon.png');
    background-size: cover;
    background-position: center;
  }

  .profile-info h3 {
    margin: 0;
    font-size: 16px;
    color: #000;
  }

  .profile-info p {
    margin: 5px 0 0;
    font-size: 13px;
    color: #444;
  }

  .dropdown-options .option {
    padding: 12px 16px;
    display: flex;
    align-items: center;
    font-size: 14px;
    border: none;
    background-color: white;
    color: #000;
    width: 100%;
    cursor: pointer;
    border-top: 1px solid #eee;
    transition: background-color 0.2s ease;
  }

  .dropdown-options .option:hover {
    background-color: rgb(1, 95, 227);
    color: white;
  }

  .logout-section {
    padding: 16px;
    text-align: center;
    background-color: #fff;
  }

  .logout-btn {
    background: linear-gradient(90deg, #0062cc, #004095);
    color: white;
    font-weight: bold;
    border: none;
    padding: 10px 30px;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .logout-btn:hover {
    background-color: rgb(0, 120, 248);
    transform: scale(1.1);
    box-shadow: 0 0 12px rgba(0, 123, 255, 0.8);
  }
</style>

<!-- Dropdown Panel -->
<div class="profile-dropdown" id="profileDropdown">
  <div class="profile-header">
    <div class="avatar"></div>
    <div class="profile-info">
      <h3><?php echo htmlspecialchars($_SESSION['user_name']); ?></h3>
      <p><?php echo htmlspecialchars($_SESSION['email']); ?></p>
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

<script>
  const profileToggle = document.getElementById('profileToggle');
  const dropdown = document.getElementById('profileDropdown');

  profileToggle.addEventListener('click', (e) => {
    e.stopPropagation();
    dropdown.classList.toggle('show');
  });

  document.addEventListener('click', (e) => {
    if (!dropdown.contains(e.target) && e.target !== profileToggle && !profileToggle.contains(e.target)) {
      dropdown.classList.remove('show');
    }
  });

  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      dropdown.classList.remove('show');
    }
  });
</script>