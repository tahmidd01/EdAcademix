<?php
session_start();
require_once "../connect.php";


$userID = $_SESSION['UserID'];
$roomName = "EdAcademixIELTS_SpeakingTest_User" . $userID;

// Insert or update the speaking session
$sql = "INSERT INTO speaking_sessions (user_id, room_name, status, started_at)
        VALUES (?, ?, 'ongoing', NOW())
        ON DUPLICATE KEY UPDATE status='ongoing', started_at=NOW(), ended_at=NULL";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $userID, $roomName);
$stmt->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Speaking Test - EdAcademixIELTS</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            height: 100vh;
            background: radial-gradient(ellipse at center, #1e3c72 0%, #2a5298 100%);
            font-family: 'Segoe UI', sans-serif;
            color: white;
            overflow: hidden;
            position: relative;
            perspective: 1500px;
        }

        /* Particle layer */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            background: url('https://www.transparenttextures.com/patterns/stardust.png');
            opacity: 0.06;
            z-index: 1;
            animation: moveParticles 60s linear infinite;
        }

        @keyframes moveParticles {
            0% { background-position: 0 0; }
            100% { background-position: 1000px 1000px; }
        }

        .content {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            transform-style: preserve-3d;
        }

        h1 {
            font-size: 3.5rem;
            margin-bottom: 10px;
            animation: shimmer 5s infinite linear;
            text-shadow: 0 0 15px #00f0ff, 0 0 30px #00f0ff;
        }

        @keyframes shimmer {
            0% { color: #00f0ff; text-shadow: 0 0 10px #00f0ff; }
            50% { color: #00ffd5; text-shadow: 0 0 20px #00ffd5; }
            100% { color: #00f0ff; text-shadow: 0 0 10px #00f0ff; }
        }

        p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            text-shadow: 0 0 5px rgba(255,255,255,0.3);
        }

        #jitsi-container {
    width: 90%;
    max-width: 1000px;
    height: 600px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 20px;
    backdrop-filter: blur(20px);
    border: 2px solid rgba(0, 255, 255, 0.2);
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.7),
                0 0 30px rgba(0, 255, 255, 0.5);
    animation: float3D 12s ease-in-out infinite;
    overflow: hidden;
    transform-style: preserve-3d;
}
@keyframes float3D {
    0% { transform: rotateX(5deg) rotateY(5deg); }
    25% { transform: rotateX(6deg) rotateY(-5deg); }
    50% { transform: rotateX(-4deg) rotateY(4deg); }
    75% { transform: rotateX(3deg) rotateY(-6deg); }
    100% { transform: rotateX(5deg) rotateY(5deg); }
}



        .floating-icons {
            position: absolute;
            bottom: 30px;
            left: 30px;
            z-index: 5;
            animation: floatIcons 6s ease-in-out infinite;
            opacity: 0.12;
        }

        .icon {
            font-size: 70px;
            margin: 10px;
            color: #0ff;
            text-shadow: 0 0 15px #0ff;
        }

        @keyframes floatIcons {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>
<body>
    <div class="particles"></div>
    <div class="content" id="tilt-container">
        <h1>Live Speaking Test with Examiner</h1>
        <p>Please make sure your microphone and camera are enabled.</p>
        <div id="jitsi-container"></div>
    </div>

    <div class="floating-icons">
        <div class="icon">🎤</div>
        <div class="icon">📹</div>
    </div>

    <script src='https://meet.jit.si/external_api.js'></script>
    <script>
        const domain = "meet.jit.si";
        const roomName = "EdAcademixIELTS_SpeakingTest_User<?= $userID ?>";
        const options = {
            roomName: roomName,
            width: "100%",
            height: 600,
            parentNode: document.querySelector('#jitsi-container'),
            configOverwrite: {
                startWithVideoMuted: false,
                startWithAudioMuted: false,
            },
            interfaceConfigOverwrite: {
                SHOW_JITSI_WATERMARK: false,
                DEFAULT_REMOTE_DISPLAY_NAME: 'Examiner',
            }
        };
        const api = new JitsiMeetExternalAPI(domain, options);

        // 3D tilt effect based on mouse position
        const tiltContainer = document.getElementById("tilt-container");
        const jitsiContainer = document.getElementById("jitsi-container");

       
    </script>

<!-- Quit Button -->
<button id="quitBtn" style="
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 12px 24px;
    background: linear-gradient(145deg, #ff4b4b, #c33232);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: bold;
    cursor: pointer;
    z-index: 1000;
    box-shadow: 0 5px 0 #991f1f;
    transition: all 0.2s ease-in-out;
">
    Quit
</button>

<!-- Quit Confirmation Modal -->
<div id="quitModal" style="
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.6);
    justify-content: center;
    align-items: center;
    z-index: 1001;
    font-family: Arial, sans-serif;
    color: #000;
    flex-direction: column;
">
    <div style="
        background-color: white;
        padding: 30px;
        border-radius: 10px;
        text-align: center;
        max-width: 400px;
        width: 90%;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    ">
        <h2 style="margin-bottom: 15px;">Quit Test?</h2>
        <p style="margin-bottom: 25px;">Are you sure you want to quit the speaking test?</p>
        
        <button id="confirmQuitBtn" style="
            background: linear-gradient(145deg, #f44336, #d32f2f);
            color: white;
            margin-right: 10px;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 5px 0 #992222;
            transition: all 0.2s ease-in-out;
        ">
            Yes
        </button>

        <button id="cancelQuitBtn" style="
            background: linear-gradient(145deg, #e0e0e0, #bdbdbd);
            color: #333;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 5px 0 #888;
            transition: all 0.2s ease-in-out;
        ">
            No
        </button>
    </div>
</div>

<!-- Script -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const quitBtn = document.getElementById('quitBtn');
        const quitModal = document.getElementById('quitModal');
        const confirmQuitBtn = document.getElementById('confirmQuitBtn');
        const cancelQuitBtn = document.getElementById('cancelQuitBtn');

        quitBtn.addEventListener('click', function (e) {
            e.preventDefault();
            quitModal.style.display = 'flex';
        });

        cancelQuitBtn.addEventListener('click', function (e) {
            e.preventDefault();
            quitModal.style.display = 'none';
        });

        confirmQuitBtn.addEventListener('click', function (e) {
            e.preventDefault();
            window.location.href = "../dashboard.php"; // Modify path as necessary
        });
    });
</script>


</body>
</html>
