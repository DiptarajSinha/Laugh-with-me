#!/bin/bash
# This script should set up a CRON job to run cron.php every 24 hours.
# You need to implement the CRON setup logic here.
# Get full path to cron.php
CRON_PATH="$(cd "$(dirname "$0")" && pwd)/cron.php"

# Write cron job entry
# Runs once every 24 hours at 9:00 AM
CRON_JOB="0 9 * * * php $CRON_PATH >> $HOME/xkcd_cron.log 2>&1"

# Add it if not already added
(crontab -l 2>/dev/null | grep -Fv "$CRON_PATH" ; echo "$CRON_JOB") | crontab -

echo "Cron job added to run daily at 9:00 AM"