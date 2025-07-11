# XKCD Comic Email Subscription System 📩

A PHP-based subscription system that allows users to register via email to receive a **daily XKCD comic**. It includes email verification, unsubscription with confirmation, and a scheduled CRON job to deliver comics every 24 hours.

---

## 🚀 Features

✅ Email Verification  
✅ Daily XKCD Comic via Email  
✅ Unsubscribe with Confirmation  
✅ CRON Job Integration  
✅ HTML Email Format  
✅ No database used (uses `registered_emails.txt`)  
✅ Tested locally with [Mailpit](https://github.com/axllent/mailpit)

---

## 📸 Screenshots

> Screenshots of local testing using Mailpit

| Registration Form | Comics | Unsubscribe |
|-------------------|--------------------|-------------|
| ![](Screenshots/register.png) | ![](Screenshots/mailpit.png) | ![](screenshots/unsubscribe.png) |

---

## 📂 File Structure

xkcd-comic-subscriber/
├── index.php # Email registration + verification
├── unsubscribe.php # Unsubscribe via code verification
├── cron.php # Sends XKCD comics to subscribers
├── setup_cron.sh # Adds cron job for daily delivery
├── functions.php # Core business logic
├── registered_emails.txt # Local email storage
├── screenshots/ # (Optional) UI screenshots
└── README.md # This file


---

## 📌 How It Works

### 🔐 Email Verification
1. User enters their email → clicks Submit
2. Receives a 6-digit code via email
3. Enters code to confirm and gets subscribed
4. Email stored in `registered_emails.txt`

### 📤 Daily Comic Delivery
- A CRON job runs `cron.php` every day at 9:00 AM
- Fetches random XKCD comic from https://xkcd.com/
- Sends HTML email with:
  - Comic image
  - Unsubscribe link

### 🚫 Unsubscribe Flow
1. User clicks "Unsubscribe" from the email
2. Enters their email → receives code
3. Enters code to confirm unsubscription
4. Email removed from `registered_emails.txt`

---

## ⚙️ Setup Instructions

### 🛠 Requirements
- PHP 8.3+
- Mailpit (for local email testing)
- Apache or PHP built-in server
- Unix-based OS for CRON (Linux/macOS/WSL)

### 🔧 Installation

1. Clone the repo:
```bash
git clone https://github.com/DiptarajSinha/laugh-with-me.git
cd laugh-with-me
