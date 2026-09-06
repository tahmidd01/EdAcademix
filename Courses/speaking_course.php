<?php
$conn = new mysqli("localhost", "root", "", "edacademix");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$sql = "SELECT video_number, video_path FROM sectionvideos 
        WHERE section = 'speaking' 
        ORDER BY video_number ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Speaking Course</title>
  <style>
    body {
      margin: 0;
      background-color:rgb(11, 72, 0);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      color: white;
      min-height: 100vh;
      overflow-x: hidden;
      perspective: 1200px;
    }

    h1 {
      margin-top: 40px;
      font-size: 3rem;
      font-weight: 900;
      color: #00ffcc;
      text-transform: uppercase;
      letter-spacing: 2px;
      user-select: none;
      position: relative;
      display: inline-block;
      animation: subtle3DFloat 6s ease-in-out infinite, glowPulse 3s ease-in-out infinite;
      text-shadow:
        0 0 8px #00ffcc,
        0 0 15px #00cc99,
        0 0 20px #00ffcc,
        0 0 35px #00cc99;
      z-index: 1;
      transform-style: preserve-3d;
      transform-origin: center;
    }

    @keyframes subtle3DFloat {
      0%, 100% {
        transform: rotateX(2deg) rotateY(-3deg) translateZ(5px);
      }
      50% {
        transform: rotateX(-2deg) rotateY(3deg) translateZ(10px);
      }
    }

    @keyframes glowPulse {
      0%, 100% {
        text-shadow:
          0 0 8px #00ffcc,
          0 0 15px #00cc99,
          0 0 20px #00ffcc,
          0 0 35px #00cc99;
      }
      50% {
        text-shadow:
          0 0 12px #00ffe0,
          0 0 25px #00b386,
          0 0 35px #00ffe0,
          0 0 50px #00b386;
      }
    }

    @keyframes entrance3D {
      0% {
        opacity: 0;
        transform: rotateY(80deg) scale(0.5) translateZ(-200px);
      }
      100% {
        opacity: 1;
        transform: rotateY(0deg) scale(1) translateZ(0);
      }
    }

    .video-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 30px;
      width: 90%;
      margin: 40px auto;
      z-index: 1;
      transform-style: preserve-3d;
    }

    .video-card {
      background: rgba(255, 255, 255, 0.12);
      backdrop-filter: blur(14px);
      border-radius: 24px;
      overflow: hidden;
      box-shadow:
        0 8px 15px rgba(0, 0, 0, 0.2),
        0 15px 40px rgba(0, 0, 0, 0.35);
      transition: transform 0.5s ease, box-shadow 0.5s ease;
      transform-style: preserve-3d;
      border: 1px solid rgba(255, 255, 255, 0.15);
      cursor: pointer;
      position: relative;
      opacity: 0;
      transform: rotateY(90deg);
      animation: entrance3D 1.2s ease forwards;
    }

    .video-card:nth-child(even) {
      animation-delay: 0.2s;
    }
    .video-card:nth-child(odd) {
      animation-delay: 0.4s;
    }

    .video-card::before {
      content: "";
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(120deg, rgba(0,255,204,0.3), rgba(0,179,134,0));
      opacity: 0;
      transition: opacity 0.5s ease;
      pointer-events: none;
      border-radius: 24px;
      transform: translateZ(60px);
    }

    .video-card:hover::before {
      opacity: 1;
    }

    .video-card:hover {
      transform: rotateY(10deg) scale(1.1) translateZ(30px);
      box-shadow:
        0 25px 45px rgba(0, 0, 0, 0.6),
        0 40px 60px rgba(0, 0, 0, 0.8);
      z-index: 10;
    }

    video {
      width: 100%;
      height: auto;
      border-bottom: 1px solid rgba(255, 255, 255, 0.25);
      display: block;
      backface-visibility: hidden;
    }

    .label {
      padding: 18px 15px;
      font-size: 1.15rem;
      font-weight: 700;
      text-align: center;
      background: rgba(0, 0, 0, 0.35);
      color: #e0eaff;
      letter-spacing: 1.2px;
      text-transform: uppercase;
      user-select: none;
    }

    @media (max-width: 500px) {
      h1 {
        font-size: 2rem;
      }
      .video-container {
        gap: 20px;
      }
      .video-card {
        border-radius: 18px;
      }
      .label {
        font-size: 1rem;
        padding: 12px 10px;
      }
    }

        .dashboard-btn {
  position: absolute;
  top: 20px;
  left: 20px;
  padding: 12px 22px;
  font-size: 1rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 1px;
  text-decoration: none;
  color: #fff;
  background: linear-gradient(135deg,rgb(0, 4, 41),rgb(212, 212, 212));
  border: none;
  border-radius: 30px;
  box-shadow: 0 0 12px rgba(255, 102, 178, 0.5), 0 0 20px rgba(204, 51, 153, 0.3);
  transition: all 0.35s ease;
  z-index: 999;
  backdrop-filter: blur(6px);
  text-shadow: 0 0 4px rgba(0, 0, 0, 0.4);
}

.dashboard-btn:hover {
  background: linear-gradient(135deg,rgb(255, 0, 0),rgb(83, 0, 0));
  box-shadow: 0 0 20px rgba(255, 77, 166, 0.7), 0 0 30px rgba(179, 0, 134, 0.5);
  transform: scale(1.08) rotate(-1deg);
}
  </style>
</head>
<body>

<h1>🗣️ Speaking Course Playlist</h1>

<div class="video-container">
<?php
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $num = htmlspecialchars($row['video_number']);
        $path = htmlspecialchars($row['video_path']);
        $adjusted_path = "../" . $path;
        echo "
<div class='video-card'>
    <video controls controlsList='nodownload'>
        <source src='$adjusted_path' type='video/mp4'>
        Your browser does not support the video tag.
    </video>
    <div class='label'>Speaking Video $num</div>
</div>";
    }
} else {
    echo "<p style='color: red;'>No videos found for Speaking section.</p>";
}
$conn->close();
?>
</div>

<script>
  document.querySelectorAll('video').forEach(video => {
    function fixCardSizeOnExit() {
      const isFullscreen = document.fullscreenElement || document.webkitFullscreenElement;
      if (!isFullscreen) {
        const card = video.closest('.video-card');
        if (!card) return;

        const originalWidth = card.offsetWidth + 'px';
        const originalHeight = card.offsetHeight + 'px';

        card.style.transition = 'none';
        card.style.width = originalWidth;
        card.style.height = originalHeight;

        void card.offsetWidth;

        setTimeout(() => {
          card.style.transition = '';
          card.style.width = '';
          card.style.height = '';
        }, 150);
      }
    }

    video.addEventListener('fullscreenchange', fixCardSizeOnExit);
    video.addEventListener('webkitfullscreenchange', fixCardSizeOnExit);
  });

  window.addEventListener("load", () => {
    document.querySelectorAll(".video-card").forEach((card, index) => {
      card.style.animationDelay = (index * 0.15) + "s";
      card.classList.add("animate-in");
    });
  });
</script>

</body>
<a href="../dashboard.php" class="dashboard-btn">🏠 Dashboard</a>
</html>
