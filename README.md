<h1> Google drive image Download/Upload script </h1>

This is a simple demo script that allows you upload file into required google drive folder,
creating all parent folders in the way, if required.

Or download file from specified path on your google drive.

<h3> Instructions </h3>

Install dependencies via composer:

	php composer.phar install

<h4> Create client secret </h4>

Follow the steps in config.php to create client_secret.json for you google drive project.
Download it and put in credentials folder.

<h4> First run </h4>

Run index.php via command-line or in browser. 

On first run, script will ask you to authorize access to your google drive account.
Paste the code, script will create google_drive.token.json in credentials.
This token will be used to access your google drive for all subsequent runs.
Token doesn't expire, unless not used for 6 months.
Make sure script have write access to credentials folder.

<h4> Example </h4>

On success, index.php will upload image.jpg into file '/images/nature/mountains/river.jpg' on your google drive,
and download it back as local river-new.jpg


<h3> Usage </h3>

<h4> Upload </h4>
    $link = upload_file_gd('/images/nature/mountains', 'image.jpg', 'river.jpg');

<h4> Download </h4>
    download_file_gd('/images/nature/mountains/river.jpg', 'local-river.jpg');


