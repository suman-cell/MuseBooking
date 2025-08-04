<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'vendor/autoload.php'; 

session_start();
require_once 'db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $method = $_POST['method'];

    $query = "SELECT id, email FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['reset_user'] = $user;

        if ($method == 'otp') {
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;

            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'musebook080@gmail.com';
                $mail->Password   = 'gqhw ivij eekn flbz';            
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->SMTPDebug = 3; // Enable detailed debug output
                $mail->Debugoutput = 'html';

                // Recipients
                $mail->setFrom('musebook080@gmail.com', 'MuseBook'); 
                $mail->addAddress($user['email']); // User email from DB

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Your OTP for Password Reset';
                $mail->Body    = "<h3>Your OTP is: <b>$otp</b></h3>";

                $mail->send();

                header("Location: verify_otp.php");
                exit();
            } catch (Exception $e) {
                $message = "OTP sending failed. Mailer Error: " . $mail->ErrorInfo;
            }

        } elseif ($method == 'old_password') {
            header("Location: verify_old_password.php");
            exit();
        }
    } else {
        $message = "Username not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | MuseBook</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color:rgb(16, 67, 255);
            --secondary-color: #a29bfe;
            --accent-color: #fd79a8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --success-color: #00b894;
            --error-color: #d63031;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .password-reset-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            padding: 40px;
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .logo-container {
            margin-bottom: 20px;
        }
        
        .logo {
            max-width: 129px;
            height: auto;
        }
        
        h2 {
            color: var(--dark-color);
            margin-bottom: 30px;
            font-weight: 600;
        }
        
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark-color);
            font-weight: 500;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 60, 255, 0.2);
        }
        
        .method-options {
            margin: 25px 0;
            text-align: left;
        }
        
        .method-options h4 {
            margin-bottom: 15px;
            color: var(--dark-color);
            font-size: 16px;
        }
        
        .radio-option {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            cursor: pointer;
        }
        
        .radio-option input {
            margin-right: 10px;
            width: 18px;
            height: 18px;
            accent-color: var(--primary-color);
            cursor: pointer;
        }
        
        .radio-option label {
            margin-bottom: 0;
            cursor: pointer;
            font-weight: 400;
        }
        
        .btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        .btn:hover {
            background-color:rgb(53, 33, 228);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(191, 201, 209, 0.3);
        }
        
        .message {
            margin-top: 20px;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
        }
        
        .error {
            background-color: rgba(214, 48, 49, 0.1);
            color: var(--error-color);
            border: 1px solid rgba(214, 48, 49, 0.3);
        }
        
        .back-to-login {
            margin-top: 20px;
            color: var(--dark-color);
            font-size: 14px;
        }
        
        .back-to-login a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-to-login a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="password-reset-container">
        <div class="logo-container">
            <img src="favicon.svg" alt="Company Logo" class="logo">
        </div>
        <h2>Reset Your Password</h2>
        
        <?php if ($message): ?>
            <div class="message error"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            
            <div class="method-options">
                <h4>Choose verification method:</h4>
                
                <div class="radio-option">
                    <input type="radio" id="otp-method" name="method" value="otp" required>
                    <label for="otp-method">Send OTP to my email</label>
                </div>
                
                <div class="radio-option">
                    <input type="radio" id="password-method" name="method" value="old_password" required>
                    <label for="password-method">Verify with my old password</label>
                </div>
            </div>
            
            <button type="submit" class="btn">Continue</button>
        </form>
        
        <div class="back-to-login">
            Remember your password? <a href="login.php">Sign in</a>
        </div>
    </div>
</body>
</html>