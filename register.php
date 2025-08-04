<?php
session_start();
require_once "db.php"; // Database connection

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action']; // Check if it's signup or login
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Server-side validation
    if (!ctype_alpha($username)) {
        $error = "Username must contain only letters!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif (!str_ends_with($email, '@gmail.com')) {
        $error = "Email must be a Gmail address (e.g., example@gmail.com)!";
    } else {
        // Extract the username part of the email (before @gmail.com)
        $emailUsername = explode('@', $email)[0];
        if (strlen($emailUsername) < 5 || strlen($emailUsername) > 30) {
            $error = "Gmail username must be between 5 and 30 characters long!";
        } elseif (!preg_match('/^[a-zA-Z]+[0-9]*$/', $emailUsername)) {
            $error = "Gmail username must contain only letters or letters followed by numbers at the end!";
        } elseif (ctype_digit($emailUsername)) {
            $error = "Gmail username cannot contain only numbers!";
        } elseif (strlen($password) < 8) {
            $error = "Password must be at least 8 characters long!";
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
            $error = "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character!";
        } else {
            if ($action == "signup") {
                // SIGNUP LOGIC
                if (!empty($username) && !empty($email) && !empty($password)) {
                    // ✅ Check if Username OR Email already exists
                    $checkQuery = "SELECT * FROM users WHERE username = ? OR email = ?";
                    $stmt = $conn->prepare($checkQuery);
                    $stmt->bind_param("ss", $username, $email);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $error = "Username or Email already exists! Try logging in.";
                    } else {
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                        $insertQuery = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
                        $stmt = $conn->prepare($insertQuery);
                        $stmt->bind_param("sss", $username, $email, $hashedPassword);

                        if ($stmt->execute()) {
                            echo "<script>
                                alert('Registration successful! Redirecting to login...');
                                window.location.href = 'login.php';
                            </script>";
                            exit();
                        } else {
                            $error = "Signup failed! Please try again.";
                        }
                    }
                } else {
                    $error = "All fields are required!";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register/Login</title>
    <link rel="stylesheet" href="login.css"><!-- Add your CSS file -->
    <script>
        // Client-side validation
        function validateForm() {
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();

            if (!/^[a-zA-Z]+$/.test(username)) {
                alert("Username must contain only letters!");
                return false;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert("Invalid email format!");
                return false;
            }

            if (!email.endsWith("@gmail.com")) {
                alert("Email must be a Gmail address (e.g., example@gmail.com)!");
                return false;
            }

            // Extract the username part of the email (before @gmail.com)
            const emailUsername = email.split('@')[0];
            if (emailUsername.length < 5 || emailUsername.length > 30) {
                alert("Gmail username must be between 5 and 30 characters long!");
                return false;
            }

            // Updated regex: letters only or letters followed by numbers at the end
            const emailUsernameRegex = /^[a-zA-Z]+[0-9]*$/;
            if (!emailUsernameRegex.test(emailUsername)) {
                alert("Gmail username must contain only letters or letters followed by numbers at the end!");
                return false;
            }

            if (/^\d+$/.test(emailUsername)) {
                alert("Gmail username cannot contain only numbers!");
                return false;
            }

           // Password validation: at least 8 characters, one uppercase, one lowercase, one number, one special character
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        if (!passwordRegex.test(password)) {
            alert("Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character!");
            return false;
        }

            return true;
        }
    </script>
</head>
<body>

<div class="main">
<h1><img src="favicon.svg" alt="Logo"></h1>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="register.php" method="POST" onsubmit="return validateForm();">
        <h3>Enter your sign up credentials</h3>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter your Username" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter your Email" required>                                  

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter your Password" required>

        <input type="hidden" name="action" value="signup">
        <button type="submit">Sign Up</button>
        <p>Already have an account? <a href="login.php" style="color: blue;">Login here</a></p>
    </form>
</div>

</body>
</html>