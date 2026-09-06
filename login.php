<?php 
session_start();
include('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT UserID, PasswordHash, FullName FROM Users WHERE Email = ?");
    
    if (!$stmt) {
        die("Prepare failed: " . $conn->error); // DEBUGGING HELP
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['PasswordHash'])) {
            session_regenerate_id(true);
            $_SESSION['UserID'] = $row['UserID'];
            $_SESSION['user_name'] = $row['FullName']; // Store the name
            header("Location: index.php");
            exit;
        } else {
            $errorMessage = "Invalid password. Please try again.";
        }
    } else {
        $errorMessage = "No account found with this email.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>EdAcademix Login</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
      height: 100vh;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      perspective: 1500px;
    }

    .container {
      background: rgba(255, 255, 255, 0.2);
      border-radius: 20px;
      box-shadow: 0 25px 75px rgba(31, 38, 135, 0.37);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.18);
      padding: 50px;
      width: 380px;
      transform-style: preserve-3d;
      animation: floatCard 6s ease-in-out infinite, glowPulse 2s ease-in-out infinite alternate;
      transform-origin: center;
    }

    @keyframes floatCard {
      0%, 100% {
        transform: rotateY(0deg) rotateX(0deg);
      }
      50% {
        transform: rotateY(15deg) rotateX(12deg);
      }
    }

    @keyframes glowPulse {
      0% {
        box-shadow: 0 0 20px rgba(0, 123, 255, 0.6), 0 0 50px rgba(0, 123, 255, 0.5);
      }
      100% {
        box-shadow: 0 0 40px rgba(0, 123, 255, 0.8), 0 0 100px rgba(0, 123, 255, 0.7);
      }
    }

    .logo {
      display: block;
      margin: 0 auto 20px;
      width: 250px;
      border-radius: 50px;
      animation: fadeIn 2s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    h2 {
      text-align: center;
      color: #2d3b73;
      margin-bottom: 30px;
      font-size: 2em;
      text-transform: uppercase;
      letter-spacing: 2px;
      animation: slideIn 1s ease-out;
    }

    @keyframes slideIn {
      from { opacity: 0; transform: translateY(-50px); }
      to { opacity: 1; transform: translateY(0); }
    }

    label {
      display: block;
      margin-bottom: 8px;
      color: #2d3b73;
      font-weight: 600;
    }

    input {
      width: 92%;
      padding: 16px;
      margin-bottom: 20px;
      border: none;
      border-radius: 12px;
      outline: none;
      background: #e0f2ff;
      box-shadow: inset 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: box-shadow 0.3s ease, transform 0.3s ease;
    }

    input:focus {
      box-shadow: 0 0 12px rgba(0, 123, 255, 0.7);
      transform: scale(1.05);
    }

    button {
      width: 100%;
      padding: 16px;
      background: linear-gradient(90deg, #0062cc, #004095);
      color: white;
      border: none;
      border-radius: 12px;
      font-size: 1.4em;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    }

    button:hover {
      background: linear-gradient(90deg, #004095, #0062cc);
      transform: scale(1.1);
      box-shadow: 0 0 12px rgba(0, 123, 255, 0.8);
    }

    .footer {
      text-align: center;
      margin-top: 25px;
      font-size: 1em;
      color: #2d3b73;
      animation: fadeIn 2s ease-in-out;
    }

    .footer a {
      color: #0062cc;
      text-decoration: none;
      font-weight: bold;
      position: relative;
      display: inline-block;
      transition: color 0.3s ease, transform 0.3s ease;
    }

    .footer a:hover {
      color: #004095;
      transform: translateY(-3px);
    }

    .error-message {
      color: #e74c3c;
      text-align: center;
      margin-bottom: 20px;
      font-weight: bold;
      animation: fadeIn 1s ease-in-out;
    }
  </style>
</head>
<body>
  <div class="container">
    <img src="img/EDLOGO.png" alt="Logo" class="logo">
    <h2>Login</h2>

    <?php if (!empty($errorMessage)) : ?>
      <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required autocomplete="email">

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required autocomplete="current-password" minlength="3">

      <button type="submit">Login</button>
    </form>

    <div class="footer">
      Don't have an account? <a href="signup.php">Sign up</a>
    </div>
  </div>
</body>
</html>

