<?php
session_start();
include 'connect.php'; // Include database connection

date_default_timezone_set('Asia/Dhaka');

// Check if the user is logged in
if (!isset($_SESSION['UserID'])) {
    header("Location: login_form.html"); // Redirect to login page if not logged in
    exit();
}

$userID = $_SESSION['UserID']; // Get the UserID from session


// Query to fetch user's information from the database
$stmt = $conn->prepare("SELECT FullName, Email FROM Users WHERE UserID = ?");
$stmt->bind_param("i", $userID); // Bind the UserID to the query
$stmt->execute();
$result = $stmt->get_result();

$testResults = null;

if ($userID) {
    $stmt = $conn->prepare("SELECT TestType, Score FROM testresults WHERE UserID = ? ORDER BY DateTaken DESC");
    $stmt->bind_param("i", $UserID);
    $stmt->execute();
    $testResults = $stmt->get_result();
}

// Check if the user exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fullName = htmlspecialchars($row['FullName']);
    $email = htmlspecialchars($row['Email']);
} else {
    echo "User not found.";
    exit();
}

// Query to fetch user's test scores
$stmt = $conn->prepare("SELECT TestType, Score, TestDate FROM TestResults WHERE UserID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$testResults = $stmt->get_result();

// Initialize variables for average score and section analysis
$totalScore = 0;
$totalTests = 0;
$sections = [
    'Listening' => [],
    'Reading' => [],
    'Writing' => [],
    'Speaking' => []
];

// Collect scores and prepare for section analysis
$testDates = [];
$testScores = [];
while ($row = $testResults->fetch_assoc()) {
    $score = $row['Score'];
    $testType = $row['TestType'];
    $testDate = $row['TestDate'];

    $totalScore += $score;
    $totalTests++;

    // Store section-wise scores
    if (in_array($testType, ['Listening', 'Reading', 'Writing', 'Speaking'])) {
        $sections[$testType][] = $score;
    }

    // Prepare data for the performance trends graph
    $testDates[] = $testDate;
    $testScores[] = $score;
}

// Calculate average score
$averageScore = $totalTests > 0 ? round($totalScore / $totalTests, 2) : 0;

// Prepare section-wise performance analysis
$sectionPerformance = [];
foreach ($sections as $section => $scores) {
    if (count($scores) > 0) {
        $averageSectionScore = round(array_sum($scores) / count($scores), 2);
        $sectionPerformance[$section] = $averageSectionScore;
    } else {
        $sectionPerformance[$section] = null; // No scores for this section
    }
}

// Close the database connection
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - EdAcademixIELTS</title>
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
    background: linear-gradient(135deg,rgb(5, 3, 9),rgb(37, 7, 62));
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

.container {
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

body.dark-mode .container {
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

/* Floating Chatbot Icon */
#chatbot-icon {
  position: fixed;
  bottom: 20px;
  right: 25px;
  z-index: 999;
  cursor: pointer;
  width: 60px;
  height: 60px;
  background: #0077cc;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
}

#chatbot-icon img {
  width: 35px;
  height: 35px;
}

/* Container Animation */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px) scale(0.98);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

@keyframes pulseShadow {
  0%, 100% {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
  }
  50% {
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.35);
  }
}

#chatbot-container {
  position: fixed;
  bottom: 90px;
  right: 25px;
  width: 320px;
  max-height: 450px;
  background: #fff;
  border-radius: 15px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
  overflow: hidden;
  display: flex;
  flex-direction: column;
  z-index: 1000;
  animation: fadeInUp 0.5s ease forwards, pulseShadow 6s ease-in-out infinite;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

#chatbot-container:hover {
  transform: translateY(-4px) scale(1.02);
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.35);
}

/* Header */
.chatbot-header {
  background: #1a73e8;
  color: white;
  padding: 2px 8px;            /* Minimal vertical padding */
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-weight: 600;
  letter-spacing: 0.4px;
  font-size: 10px;             /* Smaller font size */
  line-height: 1;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.chatbot-header:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(0, 115, 230, 0.3);
}

.chatbot-header .close-btn {
  font-size: 20px;
  cursor: pointer;
  transition: transform 0.3s, color 0.3s;
}

.chatbot-header .close-btn:hover {
  transform: rotate(90deg) scale(1.2);
  color: #ffdede;
}
.chatbot-header {
  animation: slideDown 0.4s ease-out;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Messages Section */
.messages {
  flex-grow: 1;
  padding: 12px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  gap: 8px;
  scroll-behavior: smooth;
}

/* Message Appearance */
@keyframes messagePop {
  from {
    opacity: 0;
    transform: scale(0.95) translateY(10px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

.message {
  margin-bottom: 4px;
  padding: 10px 14px;
  border-radius: 12px;
  max-width: 80%;
  animation: messagePop 0.3s ease;
  transition: background 0.3s, transform 0.3s;
}

.message:hover {
  transform: scale(1.02);
}

/* User Message */
.user {
  background: linear-gradient(135deg, #0077cc, #005fa3);
  color: white;
  align-self: flex-end;
  text-align: right;
}

/* Bot Message */
.bot {
  background: #f1f1f1;
  color: #333;
  align-self: flex-start;
  text-align: left;
}

/* Input Area */
.input-container {
  border-top: 1px solid #ddd;
  padding: 10px;
  background: #f9f9f9;
  transition: background 0.3s;
}

.input-container input {
  width: 100%;
  padding: 10px 12px;
  border-radius: 10px;
  border: 1px solid #ccc;
  outline: none;
  transition: border 0.3s ease, box-shadow 0.3s ease;
}

.input-container input:focus {
  border-color: #0077cc;
  box-shadow: 0 0 6px rgba(0, 123, 255, 0.2);
}

/* Hidden State */
.hidden {
  display: none;
}


/* Animation */
@keyframes fadeInUp {
  from {
    transform: translateY(30px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}
/* Dark Mode Styles */
.dark-mode #chatbot-icon {
  background: #1a73e8;
  box-shadow: 0 6px 15px rgba(255, 255, 255, 0.1);
}

.dark-mode #chatbot-container {
  background: #1e1e1e;
  box-shadow: 0 10px 30px rgba(255, 255, 255, 0.15);
  border: 1px solid #333;
}

.dark-mode .chatbot-header {
 background: linear-gradient(135deg, #0077cc, #005fa3);
  color: #fff;
}

.dark-mode .chatbot-header .close-btn {
  color: #fff;
}

.dark-mode .messages {
  background-color: #1e1e1e;
  color: #f1f1f1;
}

.dark-mode .input-container {
  background: #2a2a2a;
  border-top: 1px solid #444;
}

.dark-mode .input-container input {
  background: #333;
  color: #fff;
  border: 1px solid #555;
}

.dark-mode .message.user {
  background: #1a73e8;
  color: #fff;
}

.dark-mode .message.bot {
  background: #333;
  color: #ddd;
}

.dark-mode #chatbot-toggle {
  background-color: #1a73e8;
  color: #fff;
  box-shadow: 0 4px 12px rgba(255,255,255,0.1);
}

.test-actions {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(240, 240, 255, 0.06));
  border: 1px solid rgba(255, 255, 255, 0.18);
  backdrop-filter: blur(20px) saturate(180%);
  -webkit-backdrop-filter: blur(20px) saturate(180%);
  border-radius: 30px;
  box-shadow: 0 25px 60px rgba(0, 102, 255, 0.2);
  padding: 60px 50px;
  margin: 70px auto;
  max-width: 1200px;
  text-align: center;
  animation: fadeInScale 0.6s ease-out forwards;
  transform: perspective(1000px);
  transition: all 0.4s ease-in-out;
}

.test-actions h3 {
  font-size: 36px;
  color: #00264d;
  margin-bottom: 20px;
  font-weight: 800;
  letter-spacing: 1.2px;
}

.test-actions p {
  font-size: 18px;
  color: #444;
  margin-bottom: 35px;
  font-weight: 500;
}

.test-links {
  display: flex;
  flex-wrap: wrap;
  gap: 25px;
  justify-content: center;
  align-items: center;
}

.test-links a {
  display: inline-block;
  padding: 18px 32px;
  font-size: 18px;
  font-weight: 600;
  color: white;
  text-decoration: none;
  background: linear-gradient(135deg, #005be0, #0080ff);
  border-radius: 14px;
  box-shadow: 0 12px 30px rgba(0, 123, 255, 0.35);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  transform: translateZ(0);
  position: relative;
  overflow: hidden;
}

.test-links a::before {
  content: "";
  position: absolute;
  top: 0;
  left: -75%;
  width: 50%;
  height: 100%;
  background: rgba(255, 255, 255, 0.2);
  transform: skewX(-25deg);
  transition: left 0.5s;
}

.test-links a:hover::before {
  left: 130%;
}

.test-links a:hover {
  transform: translateY(-6px) scale(1.05);
  box-shadow: 0 18px 40px rgba(0, 123, 255, 0.5);
}

@keyframes fadeInScale {
  0% {
    opacity: 0;
    transform: scale(0.95) translateY(20px);
  }
  100% {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

/* Dark Mode */
.dark-mode .test-actions {
  background: linear-gradient(135deg, rgba(20, 20, 30, 0.6), rgba(10, 10, 20, 0.4));
  border: 1px solid rgba(255, 255, 255, 0.08);
  box-shadow: 0 25px 60px rgba(0, 123, 255, 0.1);
}

.dark-mode .test-actions h3,
.dark-mode .test-actions p {
  color: #e3eaf5;
}

.dark-mode .test-links a {
  background: linear-gradient(135deg, #1a73e8, #339dff);
  box-shadow: 0 12px 30px rgba(26, 115, 232, 0.3);
}

.dark-mode .test-links a:hover {
  box-shadow: 0 18px 40px rgba(26, 115, 232, 0.5);
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


/* footer css part start */
.footer-container {
  background-color: #95CCCF;
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  padding: 30px 20px;
  font-family: 'Segoe UI', sans-serif;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
  border-radius: 12px;
  margin: 40px auto;
  max-width: 1500px;
}

.footer-section {
  flex: 0 0 30%;
  margin: 10px;
}

.address {
  text-align: left;
}

.useful-links {
  text-align: center;
}

.useful-links a {
  color: black;
  text-decoration: none;
  font-size: 16px;
  transition: all 0.3s ease;
}

.useful-links a:hover {
  text-decoration: underline;
}

.follow-us {
  text-align: left;
  padding-left: 130px;
}

.follow-us a {
  text-decoration: none;
  color: black;
  font-weight: bold;
  transition: all 0.3s ease;
}

.follow-us a:hover {
  text-decoration: underline;
  color: black;
}

/* Privacy and Cookies */
.follow-us-links {
  display: flex;
  gap: 10px;
  margin-top: 20px;
  align-items: center;
  font-size: 14px;
}

.follow-us-links a {
  text-decoration: none;
  color: black;
  font-weight: bold;
  transition: all 0.3s ease;
}

.follow-us-links a:hover {
  text-decoration: underline;
}

/* Left-align copyright */
.footer-bottom {
  margin-top: 20px;
}

.dark-mode .footer-container {
  background-color: #001f2b;
  color: #ffffff;
}

.dark-mode .footer-section h2,
.dark-mode .footer-section p {
  color: #ffffff;
}

.dark-mode .useful-links a {
  color: #ffffff;
}

.dark-mode .useful-links a:hover {
  color: #66d9ff;
}

.dark-mode .follow-us a {
  color: #ffffff;
}
.dark-mode .follow-us a:hover {
  color: #66d9ff;
}

.dark-mode .footer-bottom {
  color: #ffffff;
}

.dark-mode .footer-bottom span {
  color: #ffffff;
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
   <img src="images/edlo2.png"/>
</div>
    <ul>
 <li><a href="dashboard.php"><i class="fas fa-home icon"></i><span class="text">Dashboard</span></a></li>
 <li><a href="tests.php"><i class="fas fa-clipboard-list icon"></i><span class="text">Tests</span></a></li>
 <li><a href="videos.php"><i class="fas fa-video icon"></i><span class="text">Lecture Videos</span></a></li>
 <?php if ($averageScore >= 6): ?>
  <li>
    <a href="cert.php?score=<?php echo urlencode($averageScore); ?>">
      <i class="fas fa-certificate icon"></i>
      <span class="text">Apply for Certificate</span>
    </a>
  </li>
<?php else: ?>
  <li>
    <a href="cert_restrict.php">
      <i class="fas fa-certificate icon"></i>
      <span class="text">Apply for Certificate</span>
    </a>
  </li>
<?php endif; ?>
 <!-- <li><a href="index.php"><i class="fas fa-sign-out-alt icon"></i><span class="text">Logout</span></a></li> -->
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
    <a href="index.php"><button>Home</button></a>
    <!-- <a href="index.php"><button>Logout</button></a> -->
</div>

        </div>

        <div class="container">
  <h1 class="dashboard-title">Dashboard</h1>
  <div class="welcome">
    Welcome, <strong><?= htmlspecialchars($fullName) ?></strong> 👋
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
<script>
    setInterval(() => {
        fetch('check_speaking_sessions.php')
            .then(res => res.text())
            .then(html => {
                document.getElementById('speaking-notifications').innerHTML = html;
            });
    }, 10000); // every 10 seconds
</script>

<!-- Floating Chat Icon -->
<div id="chatbot-toggle" onclick="openChatbot()">
  <img src="images/cb.png" alt="Chatbot Icon" class="chatbot-icon" />
</div>

<!-- Chatbot Interface -->
<div id="chatbot-container" class="hidden">
  <div class="chatbot-header">
    <h1 class="title">IELTS Chat Bot</h1>
    <span onclick="closeChatbot()" class="close-btn">&times;</span>
  </div>
  <div class="messages" id="messages"></div>
  <div class="input-container">
    <input type="text" id="userMessage" placeholder="Type your message..." onkeyup="autoReply(event)">
  </div>
</div>

<!-- Minimal CSS for floating layout -->
<style>
#chatbot-toggle {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background-color: #007bff;
  color: #fff;
  font-size: 24px;
  padding: 14px 16px;
  border-radius: 50%;
  box-shadow: 0 4px 12px rgba(0,0,0,0.3);
  cursor: pointer;
  z-index: 9999;

  display: inline-flex;         /* added */
  align-items: center;          /* added */
  gap: 6px;                    /* spacing between emoji and image */
}
.dark-mode #chatbot-toggle {
  background-color: #0056b3; /* Darker blue for dark mode */
  color: #e0e0e0;            /* Light gray text for contrast */
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.7); /* Stronger shadow for dark bg */
}

#chatbot-toggle .chatbot-icon {
  height: 50px;                 /* image size, matches emoji height */
  width: auto;
  object-fit: contain;
  border-radius: 50%;           /* keep it round to match button */
}

  #chatbot-container {
    position: fixed;
    bottom: 80px;
    right: 20px;
    width: 320px;
    height: 420px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    display: none; /* Start hidden */
    flex-direction: column;
    overflow: hidden;
    z-index: 9999;
  }

  .chatbot-header {
    background-color: #007bff;
    color: white;
    padding: 10px 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .messages {
    flex: 1;
    padding: 10px;
    overflow-y: auto;
  }

  .input-container {
    padding: 10px;
  }

  .input-container input {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
  }

  .message.user {
    text-align: right;
    margin-bottom: 10px;
    color: #333;
  }

  .message.bot {
    text-align: left;
    margin-bottom: 10px;
    color: #007bff;
  }

  .close-btn {
    font-size: 20px;
    cursor: pointer;
  }

  .hidden {
    display: none !important;
  }
</style>

<!-- JS -->
<script>
  const chatbot = document.getElementById("chatbot-container");

  function openChatbot() {
    chatbot.classList.remove("hidden");
    chatbot.style.display = "flex";
  }

  function closeChatbot() {
    chatbot.classList.add("hidden");
    chatbot.style.display = "none";
  }

function getChatbotResponse(message) {
  const responses = {
"hello": "Hi there! How can I assist you with your IELTS preparation today?",
    "hi": "Hello! Ready to improve your IELTS score?",
    "hey": "Hey! Need help with IELTS reading, writing, speaking or listening?",

    // Thanks and Farewells
    "thank you": "You're welcome! I'm always here to help you succeed.",
    "thanks": "No problem! Let me know if you have any other IELTS questions.",
    "bye": "Goodbye! Best of luck with your IELTS journey!",
    "see you": "See you! Don’t forget to keep practicing.",

    // Writing
    "writing tips": "Focus on task achievement, coherence, vocabulary, and grammar. Practice both Task 1 and Task 2 regularly.",
    "how to improve writing": "Practice structured essay writing, use linking words, and check grammar. Review band 9 sample answers to learn formatting.",
    "writing task 1": "For Task 1, describe the visual (chart, graph, etc.) clearly. Summarize key trends and avoid personal opinions.",
    "writing task 2": "For Task 2, present your opinion, support it with examples, and structure your essay with clear introduction, body, and conclusion.",
    "common writing mistakes": "Avoid contractions, informal language, and vague arguments. Always revise your writing for grammar and coherence.",

    // Speaking
    "speaking tips": "Practice speaking fluently without too many pauses. Use a range of vocabulary and correct grammar.",
    "how to improve speaking": "Speak English daily, record yourself, and practice with IELTS sample questions. Focus on pronunciation and fluency.",
    "part 1 speaking": "Part 1 is about familiar topics like hobbies or family. Keep answers short but clear.",
    "part 2 speaking": "Part 2 is a long turn. Prepare using cue cards. Speak for 1–2 minutes, and stay on topic.",
    "part 3 speaking": "Part 3 involves deeper discussion. Give detailed answers, reasons, and examples.",

    // Listening
    "listening tips": "Practice with official IELTS recordings. Focus on keywords and note-taking.",
    "how to improve listening": "Listen to English podcasts, news, and IELTS materials. Practice identifying synonyms and paraphrasing.",
    "common listening problems": "Many students miss answers due to distractions or unfamiliar accents. Stay focused and read questions ahead.",

    // Reading
    "reading tips": "Skim for general meaning, scan for specific info, and don’t spend too much time on one question.",
    "how to improve reading": "Read newspapers, articles, and practice IELTS reading tests daily. Learn to identify main ideas quickly.",
    "true false not given tips": "Carefully compare the passage and statement. Focus on what is said, not what you know.",
    "time management in reading": "Spend about 20 minutes per passage. Don’t get stuck. Mark difficult ones and return later if needed.",

    // IELTS General
    "what is ielts": "IELTS stands for International English Language Testing System. It tests your English proficiency in Listening, Reading, Writing, and Speaking.",
    "ielts full form": "IELTS stands for International English Language Testing System.",
    "how to prepare for ielts": "Set a study schedule, take mock tests, and improve vocabulary. Use reliable practice materials and track your progress.",
    "ielts band score": "IELTS is scored on a 0–9 band scale for each skill. An average is taken for your overall band score.",
    "how many sections in ielts": "There are 4 sections: Listening, Reading, Writing, and Speaking.",
    "difference between academic and general ielts": "Academic IELTS is for higher education, while General Training is for work or immigration purposes.",
    "minimum score for uk": "It depends on the institution, but generally 6.5 or above is required for most UK universities.",
    "minimum score for canada": "For Canada, most institutions require at least 6.0 in each band, but requirements vary.",
    "minimum score for australia": "Australian institutions usually ask for 6.5 overall, with no band less than 6.0.",

    // Vocabulary and Grammar
    "how to improve vocabulary": "Read widely, keep a vocabulary journal, and use new words in context. Use apps like Quizlet for revision.",
    "how to improve grammar": "Practice grammar exercises daily. Focus on common topics like tenses, articles, and sentence structure.",
    "band 9 vocabulary": "Band 9 vocabulary includes advanced and topic-specific terms. Read sample band 9 essays to learn them.",

    // Mock Tests and Practice
    "where to take mock tests": "You can take free mock tests online on IELTS.org, British Council, and EdAcademix (if available).",
    "best books for ielts": "Try Cambridge IELTS books, The Official Cambridge Guide to IELTS, and Barron’s IELTS Superpack.",
    "how many practice tests to take": "Aim to complete 10–15 full-length mock tests before your exam date.",

    // Motivation
    "i am nervous": "It’s normal to feel nervous. Stick to your study plan, practice daily, and you’ll gain confidence.",
    "how long to prepare for ielts": "Most students take 1–3 months to prepare effectively, depending on their current level.",
    "can i get band 8": "Yes! With daily focused practice and the right strategy, you can achieve band 8 or higher.",
    "i failed ielts": "Don’t give up. Review your weaknesses, take feedback seriously, and practice smarter this time.",

    "listening": "IELTS Listening has 4 sections. Make sure to practice with headphones in a quiet space.",
"reading": "IELTS Reading tests your ability to locate, understand, and analyze information in a text. Focus on time management!",
"writing": "Writing is divided into Task 1 and Task 2. Practice regularly and analyze high-band samples.",
"speaking": "Speaking has 3 parts. Practice fluency, pronunciation, and structured responses. You can book a mock speaking test with us!",

"mock test": "You can attempt Listening, Reading, and Writing mock tests on our platform. Speaking tests can be scheduled live.",
"mock tests": "Mock tests simulate the real exam. Take one regularly to track your progress.",
"test result": "Your latest results and performance graph can be viewed on your dashboard.",
"dashboard": "Visit your dashboard to track scores, review feedback, and continue your preparation.",

"speaking questions": "You can practice with common IELTS Part 1, 2, and 3 questions. Try answering with a timer!",
"reading passage tips": "Skim the passage first, then scan for answers. Time is your biggest challenge here.",
"listening audio source": "We use authentic-style recordings. Make sure your environment is quiet when practicing.",
"writing feedback": "After you submit a writing task, you'll receive automated feedback and a band estimate.",
"score analysis": "Your test score analysis includes band scores, feedback, and suggestions for improvement.",

"tips": "Sure! Just tell me which section you're interested in: Listening, Reading, Writing, or Speaking.",
"start test": "Go to your dashboard and select the section you want to begin with.",
"start speaking test": "Please schedule your speaking test through the dashboard. An examiner will call you via Jitsi.",
"video lectures": "You can access video lectures for each test section from the preparation menu on your dashboard.",
"preparation tips": "Consistency is key. Use mock tests, video lectures, and daily practice to improve steadily.",
"reset progress": "You can reset your test attempts from your profile settings. Be sure before you do this.",

"motivate me": "You're doing great! Every bit of practice brings you closer to your goal. Band 8 is within reach!",
"band 9 goal": "With focused practice, top resources, and guidance — yes, band 9 is possible!",
"feeling stuck": "Hit a wall? Take a short break, revisit your weak areas, and come back stronger.",
"how are you": "I’m great and ready to help you ace IELTS. How can I assist today?"

    };



  const lowerMessage = message.toLowerCase();
  for (const key in responses) {
    if (lowerMessage.includes(key)) {
      return responses[key];
    }
  }

  return "I'm sorry, I didn't understand that. Can you ask something else about IELTS?";
}


  function sendMessage(userMessage) {
    if (!userMessage.trim()) return;

    const messagesDiv = document.getElementById("messages");
    const userDiv = document.createElement("div");
    userDiv.className = "message user";
    userDiv.textContent = userMessage;
    messagesDiv.appendChild(userDiv);

    const botResponse = getChatbotResponse(userMessage);
    const botDiv = document.createElement("div");
    botDiv.className = "message bot";
    botDiv.textContent = botResponse;
    messagesDiv.appendChild(botDiv);

    messagesDiv.scrollTop = messagesDiv.scrollHeight;
  }

  function autoReply(event) {
    if (event.key === "Enter") {
      const userMessage = document.getElementById("userMessage").value;
      document.getElementById("userMessage").value = "";
      sendMessage(userMessage);
    }
  }
</script>

<!-- Free Courses Section -->
<div class="test-actions">
  <h3>Try Our Lecture Videos Courses</h3>
  <p>Explore free resources to boost your skills:</p>
  <div class="test-links">
    <a href="courses/listening_tips.php">Listening Course</a>
    <a href="courses/reading_tips.php">Reading Course</a>
    <a href="courses/writing_tips.php">Writing Course</a>
    <a href="courses/speaking_tips.php">Speaking Course</a>
  </div>
</div>

<!-- footer html part start -->      
<div class="footer-container">
  <!-- Address Section -->
  <div class="footer-section address">
    <h2><u><b>ADDRESS</b></u></h2>
    <p><b>United International University, UIU</b></p>
    <p><b>Permanent Campus</b></p>
    <p><b>United City, Madani Avenue</b></p>
    <p><b>Notun Bazar, 100 - Feet, Dhaka - 1212</b></p>

    <!-- Copyright left aligned -->
    <div class="footer-bottom">
      <span style="color: blue; font-weight: bold;">@ Copyright</span>
    </div>
  </div>

  <!-- Useful Links -->
  <div class="footer-section useful-links">
    <h2><u><b>USEFUL LINKS</b></u></h2>
    <p><b><a href="about.php">About EdAcademix</a></b></p>
    <p><b><a href="#">Blogs</a></b></p>
    <p><b><a href="#">Success Stories</a></b></p>
    <p><b><a href="#">Terms & Conditions</a></b></p>
  </div>

  <!-- Follow Us -->
  <div class="footer-section follow-us">
    <h2><u><b>FOLLOW US</b></u></h2>
    <p><b><a href="#"><i class="fab fa-facebook-f"></i> Facebook</a></b></p>
    <p><b><a href="#"><i class="fab fa-linkedin"></i> LinkedIn</a></b></p>
    <p><b><a href="#"><i class="fab fa-twitter"></i> Twitter</a></b></p>
    <p><b><a href="#"><i class="fas fa-envelope"></i> Email</a></b></p>
    <!-- Data Privacy & Cookies Links -->
    <div class="follow-us-links">
      <span>||</span>
      <a href="#">Data Privacy Statement</a>
      <span>||</span>
      <a href="#">Cookies</a>
      <span>||</span>
    </div>
  </div>
</div>
