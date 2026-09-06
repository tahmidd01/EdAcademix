<?php
session_start();
require_once '../connect.php';

date_default_timezone_set('Asia/Dhaka');

if (!isset($_SESSION['AdminID'])) {
    header("Location: admin_login.php");
    exit();
}
// Sanitize and get ID from URL if set
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

function safeOutput($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
$adminId = $_SESSION['AdminID'];
$adminName = "Admin";
$stmt = $conn->prepare("SELECT Username FROM admin WHERE AdminID = ?");
$stmt->bind_param("i", $adminId);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $adminName = $row['Username'];
}
$stmt->close();

$usersQuery = "SELECT * FROM users ORDER BY UserID ASC";
$usersResult = mysqli_query($conn, $usersQuery);

$selectedUser = isset($_GET['user']) ? (int)$_GET['user'] : null;
$selectedUserFullName = "";
if ($selectedUser) {
    $userInfoResult = mysqli_query($conn, "SELECT FullName FROM users WHERE UserID = $selectedUser");
    if ($userInfoResult && mysqli_num_rows($userInfoResult) > 0) {
        $userInfo = mysqli_fetch_assoc($userInfoResult);
        $selectedUserFullName = $userInfo['FullName'];
    }
}

$conn = new mysqli('localhost', 'root', '', 'edacademix');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all contact messages
$sql = "SELECT id, name, email, message, submitted_at FROM contact_messages ORDER BY submitted_at DESC";
$result = $conn->query($sql);

$bdTime = date("l, j F Y - h:i:s A");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Messages</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
:root {
    --bg-light: #f1f3f7;
    --bg-dark: #121212;
    --text-light: #2c3e50;
    --text-dark: #f1f1f1;
    --card-light: #ffffff;
    --card-dark: #1e1e1e;
    --accent: #3498db;
    --sidebar-width: 220px;
    --sidebar-collapsed-width: 60px;
}
* {
    box-sizing: border-box;
}
body {
    font-family: 'Segoe UI', sans-serif;
    margin: 0;
    padding: 0;
    background-color: var(--bg-light);
    color: var(--text-light);
    transition: background 0.3s ease, color 0.3s ease;
    display: flex;
    height: 100vh;
    overflow: hidden;
}
.dark-mode {
    background-color: var(--bg-dark);
    color: var(--text-dark);
}

/* SIDEBAR */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: var(--sidebar-width);
    height: 100%;
    background: var(--card-light);
    box-shadow: 2px 0 12px rgba(0,0,0,0.1);
    padding-top: 20px;
    transition: width 0.3s ease;
    overflow: hidden;
    z-index: 1000;
}
.dark-mode .sidebar {
    background: var(--card-dark);
    box-shadow: 2px 0 12px rgba(255,255,255,0.1);
}
.sidebar.collapsed {
    width: var(--sidebar-collapsed-width);
}
.logo {
  padding: 20px;
  margin-bottom: 20px;
  text-align: center;
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.logo img {
  height: 80px; /* Set explicit height */
  width: auto;
  object-fit: contain;
  border-radius: 8px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}
@media (max-width: 768px) {
  .logo img {
    height: 60px;
  }
}

.sidebar.collapsed .logo {
  opacity: 0;
  transform: translateX(-20px);
  pointer-events: none;
}

.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
.sidebar ul li {
    padding: 15px 25px;
    cursor: pointer;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 15px;
    font-weight: 600;
    color: var(--text-light);
    transition: background 0.2s ease;
}
.dark-mode .sidebar ul li {
    color: var(--text-dark);
}
.sidebar ul li:hover {
    background: linear-gradient(135deg, #4a00e0, #8e2de2);
    color: white;
    box-shadow: inset 2px 2px 8px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
}
.sidebar ul li a {
    color: inherit;
    text-decoration: none;
    flex-grow: 1;
}
.sidebar.collapsed ul li span.text {
    display: none;
}
.sidebar ul li .icon {
    width: 28px;
    font-size: 1.3em;
    color: var(--accent);
}
.sidebar ul li:hover .icon {
    color: white;
}
.sidebar.collapsed ul li {
    justify-content: center;
}

/* MAIN CONTENT */
.main-content {
    margin-left: var(--sidebar-width);
    padding: 40px;
    width: calc(100% - var(--sidebar-width));
    overflow-y: auto;
    transition: margin-left 0.3s ease, width 0.3s ease;
    height: 100vh;
}
.sidebar.collapsed + .main-content {
    margin-left: var(--sidebar-collapsed-width);
    width: calc(100% - var(--sidebar-collapsed-width));
}

/* TOP BAR */
.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
}
.top-bar-left {
    display: flex;
    align-items: center;
    gap: 15px;
}
.sidebar-toggle-btn {
    background: linear-gradient(135deg, #4a00e0, #8e2de2);
    border: none;
    color: white;
    padding: 10px 14px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 1.1em;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 10px auto;
    transition: all 0.3s ease;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.sidebar-toggle-btn:hover {
    background: linear-gradient(135deg, #5f00e5, #9a30f5);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    transform: scale(1.05);
}

.top-bar button, .top-bar a button {
    padding: 10px 15px;
    background: var(--accent);
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s ease;
}
.top-bar button:hover, .top-bar a button:hover {
    background: #2980b9;
}
body {
    margin: 0;
    height: 100vh;
    background: linear-gradient(145deg, #e0eaff, #f0f4ff);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    perspective: 1000px;
    color: #333;
    transition: background 0.5s, color 0.5s;
}

.admin-container {
    text-align: center;
    background: #ffffff;
    padding: 40px 60px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    transform-style: preserve-3d;
    animation: floatCard 5s ease-in-out infinite;
    transition: transform 0.3s, background 0.5s, box-shadow 0.5s;
    color: #333;
}

.dashboard-title {
    font-size: 2.5rem;
    color: #1e3a8a;
    text-shadow: 2px 4px 8px rgba(30, 58, 138, 0.4);
    margin-bottom: 15px;
    transition: color 0.5s, text-shadow 0.5s;
}

.welcome {
    font-size: 1.3rem;
    color: #333;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
    transition: color 0.5s, text-shadow 0.5s;
}

@keyframes floatCard {
    0%, 100% {
        transform: rotateX(0deg) rotateY(0deg) translateY(0px);
    }
    50% {
        transform: rotateX(5deg) rotateY(5deg) translateY(-10px);
    }
}

/* Dark Mode Styles */
body.dark-mode {
    background: linear-gradient(145deg,rgb(15, 15, 15),rgb(8, 8, 8));
    color: #ccc;
}

body.dark-mode .admin-container {
    background: #121627;
    box-shadow: 0 10px 30px rgba(255,255,255,0.1);
    color: #ccc;
}

body.dark-mode .dashboard-title {
    color: #66aaff;
    text-shadow: 2px 4px 8px rgba(102, 170, 255, 0.7);
}

body.dark-mode .welcome {
    color: #bbb;
    text-shadow: 1px 1px 2px rgba(255,255,255,0.1);
}

.time-display {
    font-family: 'Orbitron', sans-serif;
    font-size: 2.2rem;
    font-weight: 700;
    color: #00bfff;
    padding: 18px 36px;
    border-radius: 20px;
    min-width: 280px;
    text-align: center;

    /* 3D Glass + toned-down glow */
    background: transparent;
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    box-shadow:
        inset 0 0 6px rgba(0, 191, 255, 0.25),
        0 0 6px rgba(0, 191, 255, 0.3),
        0 0 14px rgba(0, 191, 255, 0.25),
        2px 2px 14px rgba(0, 0, 0, 0.2);

    text-shadow:
        0 0 2px #00bfff,
        0 0 4px #00bfff;

    animation: floatTimer 4s ease-in-out infinite, glowShift 3s ease-in-out infinite alternate;
    transition: all 0.4s ease-in-out;
}

/* Float up and down */
@keyframes floatTimer {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-5px); }
    100% { transform: translateY(0px); }
}

/* Subtle glow pulsing */
@keyframes glowShift {
    0% {
        box-shadow:
            inset 0 0 4px rgba(0, 191, 255, 0.25),
            0 0 5px rgba(0, 191, 255, 0.3),
            0 0 12px rgba(0, 191, 255, 0.25),
            1px 1px 8px rgba(0, 0, 0, 0.1);
    }
    100% {
        box-shadow:
            inset 0 0 6px rgba(0, 191, 255, 0.35),
            0 0 8px rgba(0, 191, 255, 0.4),
            0 0 18px rgba(0, 191, 255, 0.35),
            2px 2px 12px rgba(0, 0, 0, 0.2);
    }
}

/* CARDS */
.card {
    background: var(--card-light);
    border-radius: 15px;
    box-shadow: 6px 6px 15px rgba(0,0,0,0.1);
    margin-bottom: 40px;
    padding: 25px;
    transition: all 0.3s ease-in-out;
}
.dark-mode .card {
    background: var(--card-dark);
    box-shadow: 0 0 20px rgba(255,255,255,0.05);
}
.card:hover {
    transform: scale(1.01);
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}
th, td {
    padding: 12px;
    text-align: center;
    border: 1px solid #ccc;
}
th {
    background: var(--accent);
    color: white;
}
.user-list a {
    color: var(--accent);
    text-decoration: none;
}
.user-list a:hover {
    text-decoration: underline;
}
input[type="text"] {
    padding: 10px;
    width: 300px;
    max-width: 100%;
    margin-bottom: 15px;
    border-radius: 8px;
    border: 1px solid #ccc;
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar {
        position: fixed;
        width: var(--sidebar-collapsed-width);
    }
    .sidebar.collapsed {
        width: 0;
    }
    .main-content {
        margin-left: var(--sidebar-collapsed-width);
        width: calc(100% - var(--sidebar-collapsed-width));
    }
    .sidebar.collapsed + .main-content {
        margin-left: 0;
        width: 100%;
    }
}

/* USER DISPLAY CARD */
.user-display-card {
    background: linear-gradient(135deg, #e0f7fa, #e3f2fd);
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    transform-style: preserve-3d;
    animation: userCardFloat 4s ease-in-out infinite;
    transition: transform 0.3s, box-shadow 0.3s;
    text-align: center;
    margin: 20px auto;
    max-width: 500px;
}

.user-display-card h2 {
    font-size: 2rem;
    color: #1565c0;
    text-shadow: 1px 2px 6px rgba(21, 101, 192, 0.3);
}

.user-display-card p {
    font-size: 1.2rem;
    color: #333;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
}

@keyframes userCardFloat {
    0%, 100% {
        transform: rotateX(0deg) rotateY(0deg) translateY(0px);
    }
    50% {
        transform: rotateX(3deg) rotateY(3deg) translateY(-8px);
    }
}
.sidebar.collapsed .logo {
    display: none;
}
.switch {
    position: relative;
    display: inline-block;
    width: 52px;
    height: 28px;
    margin-right: 10px;
    vertical-align: middle;
}
.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #ccc;
    transition: 0.4s;
    border-radius: 28px;
}
.slider:before {
    position: absolute;
    content: "";
    height: 22px;
    width: 22px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.4s;
    border-radius: 50%;
}
input:checked + .slider {
    background-color: #2196F3;
}
input:checked + .slider:before {
    transform: translateX(24px);
}

#speaking-notifications {
    background-color: #fff5e6;
    padding: 10px 20px;
    border-left: 5px solid #ff9900;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    color: #333;
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
#sidebarLogo {
  padding: 20px;
  text-align: center;
  transition: all 0.3s ease;
}

#sidebarLogo img {
  height: 190px;
  width: auto;
  object-fit: contain;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  transition: all 0.3s ease;
}

/* Adjust when sidebar is collapsed */
.sidebar.collapsed #sidebarLogo {
  padding: 10px;
}

.sidebar.collapsed #sidebarLogo img {
  height: 40px;
  margin: auto;
}
.welcome {
  font-size: 1.2rem;
  font-weight: 500;
  color: #333;
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: 'Poppins', sans-serif;
  gap: 6px;
  /* Adjust font family or other styles as needed */
}

.welcome strong {
  border-right: 2px solid #00bfff;
  white-space: nowrap;
  overflow: hidden;
  display: inline-block;
  min-width: max-content;  /* prevents shrinking */
  animation: blinkCursor 0.8s infinite;
}

@keyframes blinkCursor {
  0%, 100% { border-color: transparent; }
  50% { border-color: #00bfff; }
}

.dashboard-title {
    font-family: 'Orbitron', sans-serif;
    font-size: 3.5rem;   /* Larger size */
    font-weight: 700;
    color: #00bfff;      /* Sky blue color for style */
    letter-spacing: 2px; /* Spacing for uniqueness */
    text-transform: uppercase;
    margin-bottom: 20px;
}
body {
      background-color: #f8f9fa;
    }
    .table-container {
      margin: 40px auto;
      max-width: 90%;
    }
    .table thead {
      background-color: #ffc107;
    }
    .table thead {
  background: linear-gradient(90deg, #007bff, #0056b3);
  color: #ffffff;
  font-weight: 600;
  font-size: 16px;
  border-radius: 12px;
}
.table-container {
  max-width: 95%;
  margin: 40px auto;
  padding: 20px;
  background-color: #ffffff;
  border-radius: 12px;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
  font-family: 'Segoe UI', sans-serif;
}

.table-container h2 {
  font-size: 24px;
  font-weight: 600;
  color: #333;
  margin-bottom: 25px;
}

.table {
  background-color: #fff;
  border: 1px solid #dee2e6;
  border-radius: 8px;
  overflow: hidden;
}

.table thead {
  background-color: #007bff;
  color: #fff;
}

.table th,
.table td {
  vertical-align: middle !important;
  padding: 14px;
  font-size: 15px;
  border: 1px solid #dee2e6;
}

.table-hover tbody tr:hover {
  background-color: #f1f1f1;
}

.text-muted {
  text-align: center;
  font-size: 16px;
  color: #888;
}
.table thead th {
  color:rgb(254, 254, 254); /* Bootstrap primary blue */
  background-color:rgb(70, 146, 234);/* Light background */
  font-weight: 600;
  font-size: 15px;
  text-align: center;
}
body.dark-mode {
  background-color: #121212;
  color:rgb(2, 0, 0);
}

.dark-mode .table-container {
  max-width: 95%;
  margin: 40px auto;
  padding: 20px;
  background-color: rgb(13, 13, 15);
  border-radius: 12px;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
  font-family: 'Segoe UI', sans-serif;
}

.dark-mode .table-container h2 {
  font-size: 24px;
  font-weight: 600;
  color: #f1f1f1;
  margin-bottom: 25px;
}

.dark-mode .table {
  background-color:rgb(13, 13, 15);
  border: 1px solid #3a3a4d;
  border-radius: 8px;
  overflow: hidden;
  color: #eaeaea;
}

.dark-mode .table thead {
  background: linear-gradient(90deg, #1a73e8, #155ab6);
  color: #ffffff;
  border-radius: 12px;
}

.dark-mode .table thead th {
  color: #ffffff;
  background-color:#66aaff;
  font-weight: 600;
  font-size: 15px;
  text-align: center;
}

.dark-mode .table th,
.dark-mode .table td {
  vertical-align: middle !important;
  padding: 14px;
  font-size: 15px;
  border: 1px solid #3c3c3c;
}

.dark-mode .table-hover tbody tr:hover {
  background-color: rgba(255, 255, 255, 0.05);
}

.dark-mode .text-muted {
  text-align: center;
  font-size: 16px;
  color: #aaa;
}
.dark-mode .table tbody tr {
  background-color: transparent; /* default bg */
  color: #e0e0e0; /* light text */
  transition: background-color 0.3s ease;
}

.dark-mode .table tbody tr:hover {
  background-color: rgba(255, 255, 255, 0.1); /* subtle lighter hover */
  cursor: pointer;
}

.dark-mode .table tbody td {
  border-color: #3c3c3c;
  color: #e0e0e0; /* ensure text color */
  background-color: transparent;
}

</style>

</head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&display=swap" rel="stylesheet">
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const strongTag = document.querySelector(".welcome strong");
    const fullName = strongTag.textContent.trim();
    let i = 0;

    function type() {
      if (i <= fullName.length) {
        strongTag.textContent = fullName.substring(0, i);
        i++;
        setTimeout(type, 100);
      } else {
        // After typing completes, wait 1.5s then erase and start again
        setTimeout(erase, 1500);
      }
    }

    function erase() {
      if (i >= 0) {
        strongTag.textContent = fullName.substring(0, i);
        i--;
        setTimeout(erase, 50);
      } else {
        // Restart typing
        setTimeout(type, 500);
      }
    }

    // Start typing on page load
    type();
  });
  
</script>

<body>

   <nav class="sidebar" id="sidebar">
    <button class="sidebar-toggle-btn" id="sidebarToggle" aria-label="Toggle Sidebar">
  <i class="fas fa-bars"></i>
</button>

       <div class="logo" id="sidebarLogo">
   <img src="../images/edlo2.png"/>
</div>
    <ul>
        <li><a href="admin_dashboard.php"><i class="fas fa-home icon"></i><span class="text">Dashboard</span></a></li>
        <li><a href="admin_users.php"><i class="fas fa-users icon"></i><span class="text">Users</span></a></li>
        <li><a href="admin_speaking.php"><i class="fas fa-microphone icon"></i><span class="text">Speaking Test</span></a></li>
        <li><a href="admin_report.php"><i class="fas fa-chart-line icon"></i><span class="text">Reports</span></a></li>
        <li><a href="admin_cert.php"><i class="fas fa-certificate icon"></i><span class="text">Certificate Request</span></a></li>
        <li><a href="admin_contact.php"><i class="fas fa-comments icon"></i><span class="text"> Feedback</span></a></li>
        <li><a href="admin_login.php"><i class="fas fa-sign-out-alt icon"></i><span class="text">Logout</span></a></li>
    </ul>
</nav>



    <div class="main-content" id="main-content">
        <div class="top-bar">
            <div class="top-bar-left">
                <div class="time-display" id="time"></div>
                

            </div>
            <div>
    <label class="switch">
        <input type="checkbox" id="darkModeToggle" onchange="toggleDarkMode()">
        <span class="slider round"></span>
    </label>
    <a href="admin_login.php"><button>Logout</button></a>
</div>

        </div>

        <div class="admin-container">
  <h1 class="dashboard-title">Admin Dashboard</h1>
  <div class="welcome">
    Welcome, <strong><?= htmlspecialchars($adminName) ?></strong> 👋
  </div>
</div>

<script>
        function toggleCardContent(id) {
            const content = document.getElementById(id);
            if (content.style.display === "none") {
                content.style.display = "block";
            } else {
                content.style.display = "none";
            }
        }
    </script>

     <script>
    function updateBDTime() {
        const timeElement = document.getElementById("time");

        const now = new Date();

        const optionsDate = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: '2-digit',
            timeZone: 'Asia/Dhaka'
        };

        const optionsTime = {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true,
            timeZone: 'Asia/Dhaka'
        };

        const datePart = now.toLocaleDateString('en-GB', optionsDate);
        const timePart = now.toLocaleTimeString('en-US', optionsTime);

        timeElement.innerHTML = `${datePart} - ${timePart} (BD)`;
    }

    updateBDTime(); // initial call
    setInterval(updateBDTime, 1000); // update every second

    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
    });

    function filterTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('userTable');
        const trs = table.getElementsByTagName('tr');

        for (let i = 1; i < trs.length; i++) {
            const tds = trs[i].getElementsByTagName('td');
            const name = tds[1].textContent.toLowerCase();
            const email = tds[2].textContent.toLowerCase();

            if (name.indexOf(filter) > -1 || email.indexOf(filter) > -1) {
                trs[i].style.display = "";
            } else {
                trs[i].style.display = "none";
            }
        }
    }

    function toggleDarkMode() {
        document.body.classList.toggle('dark-mode');
        if (document.body.classList.contains('dark-mode')) {
            localStorage.setItem('darkMode', 'enabled');
        } else {
            localStorage.setItem('darkMode', 'disabled');
        }
    }

    function toggleCardContent(id, btn) {
        const content = document.getElementById(id);
        const isVisible = content.style.display !== "none";

        content.style.display = isVisible ? "none" : "block";
        btn.innerHTML = isVisible ? "➕" : "➖";

        localStorage.setItem(id + "_visible", !isVisible);
    }

    // ✅ Merged all window.onload logic
    window.onload = function () {
        document.getElementById('darkModeToggle').checked = localStorage.getItem('darkMode') === 'enabled';

        // Restore dark mode
       if (localStorage.getItem('darkMode') === 'enabled') {
        document.body.classList.add('dark-mode');
        document.getElementById('darkModeToggle').checked = true; // ✅ this line
    }


        // Restore toggle states
        const sections = ['responses-content', 'results-content', 'tests-content'];
        sections.forEach(id => {
            const savedState = localStorage.getItem(id + "_visible");
            const isVisible = savedState === null ? true : savedState === "true";
            const content = document.getElementById(id);
            const btn = document.querySelector(`button[onclick*="${id}"]`);

            if (content && btn) {
                content.style.display = isVisible ? "block" : "none";
                btn.innerHTML = isVisible ? "➖" : "➕";
            }
        });
    };
</script>
<div class="table-container">
  <h2 class="mb-4">User Feedback / Contact Messages</h2>

  <?php if ($result && $result->num_rows > 0): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="text-center">
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Submitted At</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
            <td><?= $row['submitted_at'] ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <p class="text-muted">No messages found.</p>
  <?php endif; ?>

</div>

</body>
</html>

<?php $conn->close(); ?>