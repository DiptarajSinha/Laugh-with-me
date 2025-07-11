<?php
require_once 'functions.php';

// Initialize session to store code
session_start();

$message = '';

// Handle email submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];
    $code = generateVerificationCode();
    $_SESSION['verification_code'] = $code;
    $_SESSION['email_to_verify'] = $email;

    if (sendVerificationEmail($email, $code)) {
        $message = "Verification code sent to $email";
    } else {
        $message = "Failed to send verification email.";
    }
}

// Handle code verification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verification_code'])) {
    $enteredCode = $_POST['verification_code'];
    $email = $_SESSION['email_to_verify'] ?? '';

    if ($email && $enteredCode === ($_SESSION['verification_code'] ?? '')) {
        registerEmail($email);
        $message = "Email $email successfully verified and registered!";
        unset($_SESSION['verification_code']);
        unset($_SESSION['email_to_verify']);
    } else {
        $message = "Invalid verification code.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>XKCD Email Registration</title>
</head>
<body>
    <h1>Register for XKCD Comics</h1>

    <p><?= htmlspecialchars($message) ?></p>

    <form method="post">
        <input type="email" name="email" required>
        <button id="submit-email">Submit</button>
    </form>

    <br>

    <form method="post">
        <input type="text" name="verification_code" maxlength="6" required>
        <button id="submit-verification">Verify</button>
    </form>
</body>
</html>