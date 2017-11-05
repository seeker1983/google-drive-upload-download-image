<?php

/* IMPORTANT: first time you must run the script from command-line like
 * php -f drive.php
 * or in browser
 * Follow the instructions(open link, paste code)
 * to generate refresh token
 * CAUTION: Please make sure script has WRITE access to REFRESH_TOKEN_PATH
 * /

/* This file will be automatically generated on first run from command-line */
define('REFRESH_TOKEN_PATH', __DIR__ . '/../credentials/google_drive.token.json');

/* Here you put client secret 
 * Follow the instructions here: 
 * https://developers.google.com/drive/v3/web/quickstart/php
 * Save downloaded file from step1-g as CLIENT_SECRET_PATH */
define('CLIENT_SECRET_PATH', __DIR__ . '/../credentials/client_secret.json');

/* Timezone for logging and else */
define('TIMEZONE', 'Europe/Kiev');

/* Log file name */
define('LOG_FILE', __DIR__ . '/../../log.txt');

/* Set upload permissions to "Anyone with link" */
define('UPLOAD_ANYONE_WITH_LINK', TRUE);


















