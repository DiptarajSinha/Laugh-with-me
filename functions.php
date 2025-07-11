<?php

/**
 * Generate a 6-digit numeric verification code.
 */
function generateVerificationCode(): string {
    // Generate a 6-digit numeric code
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

/**
 * Send a verification code to an email.
 */
function sendVerificationEmail(string $email, string $code): bool {
    $subject = '';
    $message = '';

    if (isset($_SESSION['email_to_unsubscribe']) && $_SESSION['email_to_unsubscribe'] === $email) {
        $subject = "Confirm Un-subscription";
        $message = "<p>To confirm un-subscription, use this code: <strong>$code</strong></p>";
    } else {
        $subject = "Your Verification Code";
        $message = "<p>Your verification code is: <strong>$code</strong></p>";
    }

    $headers = "From: no-reply@example.com\r\n";
    $headers .= "Content-type: text/html\r\n";

    return mail($email, $subject, $message, $headers);
}

/**
 * Register an email by storing it in a file.
 */
function registerEmail(string $email): bool {
  $file = __DIR__ . '/registered_emails.txt';
    // TODO: Implement this function
  $emails = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];

  if (!in_array($email, $emails)) {
      return file_put_contents($file, $email . PHP_EOL, FILE_APPEND) !== false;
  }

  return true; // Email already registered, still considered success
}

/**
 * Unsubscribe an email by removing it from the list.
 */
function unsubscribeEmail(string $email): bool {
  $file = __DIR__ . '/registered_emails.txt';
    // TODO: Implement this function
    if (!file_exists($file)) return false;

    $emails = file($file, FILE_IGNORE_NEW_LINES);
    $emails = array_filter($emails, fn($e) => trim($e) !== trim($email));

    return file_put_contents($file, implode(PHP_EOL, $emails) . PHP_EOL) !== false;
}

/**
 * Fetch random XKCD comic and format data as HTML.
 */
function fetchAndFormatXKCDData(): string {
    // XKCD has comics up to ~2800; pick one randomly
    $randomId = rand(1, 2800);
    $url = "https://xkcd.com/$randomId/info.0.json";

    $json = @file_get_contents($url);
    if ($json === false) {
        return "<p>Failed to fetch XKCD comic.</p>";
    }

    $data = json_decode($json, true);
    if (!$data || !isset($data['img'])) {
        return "<p>Invalid XKCD response.</p>";
    }

    $img = htmlspecialchars($data['img']);
    $title = htmlspecialchars($data['title']);

    return "<h2>XKCD Comic</h2><img src=\"$img\" alt=\"XKCD Comic\">";
}


/**
 * Send the formatted XKCD updates to registered emails.
 */
function sendXKCDUpdatesToSubscribers(): void {
  $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) {
        return;
    }

    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $comic = fetchAndFormatXKCDData();
    if (!$comic) {
        return;
    }

    $subject = "Your XKCD Comic";
    $headers = "From: no-reply@example.com\r\n";
    $headers .= "Content-type: text/html\r\n";

    foreach ($emails as $email) {
        $message = $comic;
        $message .= '<p><a href="http://localhost:8000/unsubscribe.php" id="unsubscribe-button">Unsubscribe</a></p>';
        mail(trim($email), $subject, $message, $headers);
    }
}