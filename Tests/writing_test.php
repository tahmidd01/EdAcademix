<?php
session_start();
require_once "../connect.php";

$user_id = $_SESSION['UserID'];
$current_set = null;

// STEP 1: Check which writing TaskIDs the user has already completed
$sql = "SELECT DISTINCT TaskID FROM writingresponses WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$completed_task_ids = [];
while ($row = $result->fetch_assoc()) {
    $completed_task_ids[] = $row['TaskID'];
}

// STEP 2: Define sets and their corresponding TaskIDs
$sets = [
    1 => [1, 2],
    2 => [3, 4],
    3 => [5, 6]
];

// STEP 3: Determine which writing set to offer next
foreach ($sets as $set_num => $task_ids) {
    $completed_count = 0;
    foreach ($task_ids as $task_id) {
        if (in_array($task_id, $completed_task_ids)) {
            $completed_count++;
        }
    }
    if ($completed_count < count($task_ids)) {
        $current_set = $set_num;
        break;
    }
}

if ($current_set === null) {
    echo "<h3>You have already completed all Writing Test Sets.</h3>";
    exit();
}

// STEP 4: Fetch writing tasks for this set
$sql_tasks = "SELECT * FROM writingtasks WHERE TaskID IN (?, ?) ORDER BY TaskID ASC";
$stmt_tasks = $conn->prepare($sql_tasks);
$task_ids = $sets[$current_set];
$stmt_tasks->bind_param("ii", $task_ids[0], $task_ids[1]);
$stmt_tasks->execute();
$tasks_result = $stmt_tasks->get_result();

$tasks = [];
while ($row = $tasks_result->fetch_assoc()) {
    $tasks[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Writing Test - Set <?= $current_set ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
    --main-bg: linear-gradient(to right, #e0eafc, #cfdef3);
    --card-bg: rgba(255, 255, 255, 0.9);
    --primary: #007bff;
    --primary-dark: #0056b3;
    --text-color: #333;
    --border-radius: 16px;
    --shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
    --blur: blur(10px);
}

body {
    font-family: 'Inter', sans-serif;
    background: var(--main-bg);
    margin: 0;
    padding: 40px 20px;
    color: var(--text-color);
    overflow-x: hidden;
}

        /* Quit button on top right */
        #quitBtn {
            position: fixed;
            top: 50px;
            right: 20px;
            background: var(--primary);
            color: white;
            border: none;
            padding: 15px 20px;
            font-size: 16px;
            font-weight: 600;
            border-radius: var(--border-radius);
            cursor: pointer;
            box-shadow: 0 6px 14px rgba(0, 123, 255, 0.4);
            transition: background-color 0.3s ease;
            z-index: 1001;
        }

        #quitBtn:hover {
            background: var(--primary-dark);
        }

        /* Modal overlay */
        #quitModal {
            display: none; /* Hidden by default */
            position: fixed;
            top: 0; left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        /* Modal content box */
        #quitModalContent {
            background: white;
            padding: 30px 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            text-align: center;
            max-width: 400px;
            width: 90%;
            font-size: 18px;
        }

        #quitModalContent p {
            margin-bottom: 30px;
            font-weight: 600;
            color: var(--primary-dark);
        }

        /* Modal buttons */
        .modal-btn {
            padding: 12px 28px;
            font-size: 16px;
            font-weight: 600;
            border-radius: var(--border-radius);
            border: none;
            cursor: pointer;
            margin: 0 12px;
            transition: background-color 0.3s ease;
            min-width: 120px;
        }

        #confirmQuitBtn {
            background: #dc3545;
            color: white;
            box-shadow: 0 6px 14px rgba(220, 53, 69, 0.5);
        }

        #confirmQuitBtn:hover {
            background: #a71d2a;
        }

        #cancelQuitBtn {
            background: var(--primary);
            color: white;
            box-shadow: 0 6px 14px rgba(0, 123, 255, 0.5);
        }

        #cancelQuitBtn:hover {
            background: var(--primary-dark);
        }

        /* Existing styles below... */
.test-header {
    background: linear-gradient(to right, #007bff, #0056b3);
    color: white;
    padding: 30px 0;
    text-align: center;
    border-radius: var(--border-radius);
    margin: 0 auto 35px auto;  /* Center horizontally, keep bottom margin */
    box-shadow: var(--shadow);
    animation: fadeIn 1s ease-in-out;
    max-width: 900px;  /* Adjust width to reduce length */
}

.test-header h1 {
    font-size: 40px;
    font-weight: bold;
    margin: 0;
    letter-spacing: 2px;
    text-shadow: 1px 2px 4px rgba(0,0,0,0.4);
}

.timer-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: #ffffff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    margin-bottom: 30px;
    gap: 16px;
    animation: fadeIn 1s ease-in-out;
}

#timer {
    font-size: 24px;
    font-weight: bold;
    background-color: #ffeded;
    color: #cc0000;
    padding: 12px 24px;
    border-radius: 8px;
    border: 2px solid #cc0000;
    box-shadow: 0 2px 6px rgba(204, 0, 0, 0.15);
    text-align: center;
}
.timer-wrapper.scrolled .timer-container {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1000;
    width: auto;
    border-radius: 50px;
    padding: 12px 24px;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
    animation: floatIn 0.3s ease-out;
}

/* Animation for floating in */
@keyframes floatIn {
    from {
        opacity: 0;
        transform: translateX(-50%) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
}


.question-block {
    background: var(--card-bg);
    padding: 30px;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    margin-bottom: 40px;
    animation: fadeInUp 0.7s ease-in-out;
    transition: transform 0.3s ease;
}

.question-block:hover {
    transform: scale(1.01);
}

.question-block h3 {
    margin-bottom: 20px;
    font-size: 24px;
    color: var(--primary-dark);
}

.question-block p {
    font-size: 17px;
    margin-bottom: 12px;
}

select,
input[type="text"] {
    width: 80%;
    max-width: 400px;
    padding: 8px 12px;
    font-size: 15px;
    border-radius: 6px;
    border: 1px solid #ccc;
    margin-top: 6px;
    margin-bottom: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

select:focus, input[type="text"]:focus {
    border-color: var(--primary);
    outline: none;
    box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
}

.options label {
    display: block;
    margin: 6px 0;
    font-size: 16px;
    cursor: pointer;
}

.submit-btn {
    background: var(--primary);
    color: #fff;
    border: none;
    padding: 15px 35px;
    font-size: 18px;
    font-weight: 600;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 10px 20px rgba(0, 123, 255, 0.25);
}

.submit-btn:hover {
    background: var(--primary-dark);
    transform: translateY(-3px);
    box-shadow: 0 12px 28px rgba(0, 86, 179, 0.35);
}

hr {
    border: 0;
    height: 1px;
    background: #ddd;
    margin: 30px 0;
}

@media (max-width: 768px) {
    .timer-container {
        flex-direction: column;
        align-items: flex-start;
    }

    #timer {
        margin-left: 0;
        margin-top: 15px;
    }

    .submit-btn {
        width: 100%;
        text-align: center;
    }
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
.answer-textarea {
    width: 100%;
    max-width: 800px;
    padding: 15px 20px;
    font-size: 16px;
    line-height: 1.5;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #f9f9f9;
    color: #333;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    resize: vertical; /* Allows users to resize the height */
}

.answer-textarea:focus {
    border-color: var(--primary);
    background-color: #fff;
    box-shadow: 0 4px 14px rgba(0, 123, 255, 0.3);
    outline: none;
}

.answer-textarea::placeholder {
    color: #aaa;
    font-style: italic;
}

.answer-textarea:focus::placeholder {
    color: transparent; /* Hides placeholder text when focused */
}
.word-counter {
    min-width: 140px;
    padding: 12px 16px;
    background-color: #eaf4ff;
    color: #007bff;
    font-weight: 600;
    font-size: 15px;
    border: 1.5px solid #b3daff;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 123, 255, 0.15);
    white-space: nowrap;
    margin-top: 4px;
    transition: all 0.3s ease;
}
.switch {
    position: fixed;
    top: 60px;
    right: 100px;
    display: inline-block;
    width: 60px;
    height: 40px;
    margin-right: 10px;
    vertical-align: middle;
    z-index: 999;
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
    border-radius: 40px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 32px;
    width: 32px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: 0.4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: #2196F3;
}

input:checked + .slider:before {
    transform: translateX(20px); /* (60 - 32 - 2x4 = 20px move) */
}
.dark-mode {
    --main-bg: linear-gradient(to right, #0f2027, #203a43, #2c5364);
    --card-bg: rgba(20, 20, 20, 0.95);
    --primary: #1e90ff;
    --primary-dark: #1c6fb5;
    --text-color: #f5f5f5;
    --border-radius: 16px;
    --shadow: 0 12px 32px rgba(0, 0, 0, 0.3);
    --blur: blur(10px);
}

/* General page */
.dark-mode body {
    background: var(--main-bg);
    color: var(--text-color);
}

/* Quit Button */
.dark-mode #quitBtn {
    background: var(--primary);
    color: white;
    box-shadow: 0 6px 14px rgba(30, 144, 255, 0.4);
}

.dark-mode #quitBtn:hover {
    background: var(--primary-dark);
}

/* Modal overlay remains same */
.dark-mode #quitModalContent {
    background: #1e1e1e;
    color: var(--text-color);
}

/* Modal text */
.dark-mode #quitModalContent p {
    color: var(--primary);
}

/* Modal buttons */
.dark-mode #confirmQuitBtn {
    background: #dc3545;
    color: white;
    box-shadow: 0 6px 14px rgba(220, 53, 69, 0.4);
}

.dark-mode #confirmQuitBtn:hover {
    background: #a71d2a;
}

.dark-mode #cancelQuitBtn {
    background: var(--primary);
    color: white;
    box-shadow: 0 6px 14px rgba(30, 144, 255, 0.5);
}

.dark-mode #cancelQuitBtn:hover {
    background: var(--primary-dark);
}

/* Test Header */
.dark-mode .test-header {
    background: linear-gradient(to right, #1e90ff, #1c6fb5);
    color: white;
}

/* Audio section */
.dark-mode .audio-timer-container {
    background: #121212;
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.05);
}

.dark-mode #timer {
    background-color: #ffeded;
    color: #cc0000;
    border-color: #cc0000;
}

/* Question Block */
.dark-mode .question-block {
    background: var(--card-bg);
    color: var(--text-color);
}

.dark-mode .question-block h3 {
    color: var(--primary);
}
/* Submit Button */
.dark-mode .submit-btn {
    background: var(--primary);
    box-shadow: 0 10px 20px rgba(30, 144, 255, 0.25);
}

.dark-mode .submit-btn:hover {
    background: var(--primary-dark);
    box-shadow: 0 12px 28px rgba(28, 111, 181, 0.35);
}

/* Horizontal rule */
.dark-mode hr {
    background: #444;
}
.dark-mode #timer {
    background-color:rgb(18, 17, 17);
    color: #ff6666;
    border-color: #ff6666;
    box-shadow: 0 2px 6px rgba(255, 102, 102, 0.5);
}
.dark-mode .timer-container {
    background-color: #121212;
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.05);
    border-radius: var(--border-radius);
    padding: 12px 24px;
    color: #ff6666;
    border: 2px solid #ff6666;
    font-weight: bold;
    font-size: 24px;
    text-align: center;
    user-select: none;
}
.dark-mode .answer-textarea {
    background-color: #1e1e1e;
    color: #ffffff;
    border: 1px solid #444;
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.05);
}

.dark-mode .answer-textarea:focus {
    background-color: #2a2a2a;
    border-color: var(--primary);
    box-shadow: 0 4px 14px rgba(0, 123, 255, 0.4);
}

.dark-mode .answer-textarea::placeholder {
    color: #bbb;
}

.dark-mode .answer-textarea:focus::placeholder {
    color: transparent;
}

.dark-mode .word-counter {
    background-color: #2d3b50;
    color: #66bfff;
    border: 1.5px solid #4a90e2;
    box-shadow: 0 4px 10px rgba(102, 191, 255, 0.2);
}

    </style>
    <script>
        let timerSeconds = 3600; // 60 minutes
           function startTimer() {
            const timerDisplay = document.getElementById("timer");
            const interval = setInterval(() => {
                const mins = Math.floor(timerSeconds / 60);
                const secs = timerSeconds % 60;
                timerDisplay.textContent = `Time Left: ${mins}:${secs < 10 ? '0' : ''}${secs}`;
                if (timerSeconds <= 0) {
                    clearInterval(interval);
                    alert("Time's up! Submitting your test.");
                    document.getElementById("testForm").submit();
                }
                timerSeconds--;
            }, 1000);
        }

        function showQuitModal() {
            document.getElementById('quitModal').style.display = 'flex';
        }

        function hideQuitModal() {
            document.getElementById('quitModal').style.display = 'none';
        }

        function confirmQuit() {
            // Redirect to dashboard
            window.location.href = "../dashboard.php"; // Adjust the path as needed
        }

        window.onload = () => {
            startTimer();
            document.getElementById('quitBtn').addEventListener('click', showQuitModal);
            document.getElementById('cancelQuitBtn').addEventListener('click', hideQuitModal);
            document.getElementById('confirmQuitBtn').addEventListener('click', confirmQuit);
        }
    </script>
    <script>
function updateWordCount(textarea, counterId) {
    const text = textarea.value.trim();
    const wordCount = text === "" ? 0 : text.split(/\s+/).length;
    document.getElementById(counterId).textContent = `Word Count: ${wordCount}`;

}
</script>

<script>
  window.addEventListener("scroll", function () {
    const wrapper = document.querySelector(".timer-wrapper");
    if (window.scrollY > 200) {
      wrapper.classList.add("scrolled");
    } else {
      wrapper.classList.remove("scrolled");
    }
  });
</script>


</head>
<body>
     <button id="quitBtn" type="button" title="Quit Test">Quit</button>

    <div id="quitModal">
        <div id="quitModalContent">
            <p>Are you sure you want to quit the test?</p>
            <button id="confirmQuitBtn" class="modal-btn">Yes, Quit</button>
            <button id="cancelQuitBtn" class="modal-btn">No, Stay</button>
        </div>
    </div>
<div class="test-header">
    <h1>WRITING TEST</h1>
</div>
<div>
    <label class="switch">
        <input type="checkbox" id="darkModeToggle" onchange="toggleDarkMode()">
        <span class="slider round"></span>
    </label>
</div>

<div class="timer-wrapper">
  <div class="timer-container">
    <div id="timer">Time Left: 60:00</div>
  </div>
</div>


<form id="testForm" method="POST" action="submit_writing.php">
    <input type="hidden" name="SetNumber" value="<?= $current_set ?>">
    <input type="hidden" name="TestType" value="writing">

    <?php 
    $task_number = 1;
    foreach ($tasks as $index => $task): ?>
        <div class="question-block">
        <h3>Task <?= $task_number ?> - <?= htmlspecialchars($task['TaskType']) ?></h3>
            <p><?= nl2br(htmlspecialchars($task['Prompt'])) ?></p>
            <div style="display: flex; gap: 20px; align-items: flex-start;">
    <textarea 
        name="answers[<?= $task['TaskID'] ?>]" 
        rows="10" 
        class="answer-textarea" 
        required 
        placeholder="Write your answer here..." 
        oninput="updateWordCount(this, 'word-count-<?= $index ?>')"
        id="textarea-<?= $index ?>"
    ></textarea>
    <div class="word-counter" id="word-count-<?= $index ?>">
    Word Count: 0
</div>

</div>


        </div>
        <hr>
    <?php 
     $task_number++;
     endforeach; ?>

    <button type="submit" class="submit-btn">Submit Writing</button>
</form>

</body>
</html>
 <script>
    function updateBDTime() {
        const timeElement = document.getElementById("time");
        const nowUTC = new Date();
        const bdTime = new Date(nowUTC.getTime() + (6 * 60 * 60 * 1000)); // UTC+6

        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: '2-digit',
        };

        const datePart = bdTime.toLocaleDateString('en-GB', options);
        const timePart = bdTime.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        });

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