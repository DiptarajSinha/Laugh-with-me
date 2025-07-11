<?php
require_once 'functions.php';
session_start();

$message = '';

// Step 1: Handle email submission to send code
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unsubscribe_email'])) {
    $email = $_POST['unsubscribe_email'];
    $code = generateVerificationCode();
    $_SESSION['unsubscribe_code'] = $code;
    $_SESSION['email_to_unsubscribe'] = $email;

    if (sendVerificationEmail($email, $code)) {
        $message = "Verification code sent to $email for unsubscription.";
    } else {
        $message = "Failed to send unsubscription email.";
    }
}

// Step 2: Handle code verification to unsubscribe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verification_code']) && isset($_SESSION['unsubscribe_code'])) {
    $enteredCode = $_POST['verification_code'];
    $email = $_SESSION['email_to_unsubscribe'] ?? '';

    if ($email && $enteredCode === $_SESSION['unsubscribe_code']) {
        if (unsubscribeEmail($email)) {
            $message = "Successfully unsubscribed $email.";
        } else {
            $message = "Unsubscription failed. Email not found.";
        }
        unset($_SESSION['unsubscribe_code']);
        unset($_SESSION['email_to_unsubscribe']);
    } else {
        $message = "Invalid verification code for unsubscription.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Unsubscribe from XKCD</title>
</head>
<body>
    <h1>Unsubscribe</h1>
    <p><?= htmlspecialchars($message) ?></p>

    <form method="post">
        <input type="email" name="unsubscribe_email" required>
        <button id="submit-unsubscribe">Unsubscribe</button>
    </form>

    <br>

    <form method="post">
        <input type="text" name="verification_code" maxlength="6" required>
        <button id="submit-verification">Verify</button>
    </form>
</body>
</html>s