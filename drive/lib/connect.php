<?php
if(file_exists(__DIR__ . '/../../vendor/autoload.php'))
  require_once __DIR__ . '/../../vendor/autoload.php';
else
  require_once __DIR__ . '/../vendor/autoload.php';  

function get_client() {
  $client = new Google_Client();
  $client->setApplicationName('Spreadsheets API');
  $client->setScopes(['https://www.googleapis.com/auth/drive']);
  $client->setAuthConfig(CLIENT_SECRET_PATH);
  $client->setAccessType('offline');

  $credentialsPath = expand_home_directory(REFRESH_TOKEN_PATH);
  if (file_exists($credentialsPath)) {
    $accessToken = json_decode(file_get_contents($credentialsPath), true);
  } else {
    $authUrl = $client->createAuthUrl();

    if (php_sapi_name() == 'cli') {
      printf("Open the following link in your browser:\n%s\n", $authUrl);
      print 'Enter verification code: ';
      $authCode = trim(fgets(STDIN));
    } else {
      if(!empty($_GET['code'])) {
        $authCode = urldecode($_GET['code']);
      } else {
        include(__DIR__ . '/../templates/connect-template.php');
        exit();
      }

    }

    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

    if( file_put_contents($credentialsPath, json_encode($accessToken)) ){
      printf("Credentials saved to %s\n", $credentialsPath);
    } else {
      if (php_sapi_name() !== 'cli')echo("<pre>");
      echo("Access denied?\n");
      echo("Create file " . realpath(dirname($credentialsPath)) . '/' . basename($credentialsPath) . " with data:\n");

      if (php_sapi_name() !== 'cli') echo("<textarea rows=10>");

      echo(json_encode($accessToken) . "\n");

      if (php_sapi_name() !== 'cli') echo("</textarea><br>");


      throw new Exception("Cannot create credentials file(access denied?)", 1);      
    }
  }
  $client->setAccessToken($accessToken);

  if ($client->isAccessTokenExpired()) {
    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
  }
  return $client;
}

function expand_home_directory($path) {
    $homeDirectory = getenv('HOME');
    if (empty($homeDirectory)) {
      $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
    }
    return str_replace('~', realpath($homeDirectory), $path);
}

function create_drive_service() {
    $client = get_client();

    /* Install curl certificate, windows-only */
//    $client->setHttpClient(new \GuzzleHttp\Client(array(
//          'verify' => 'C:\Windows\curl-ca-bundle.crt',
//    )));


    return new Google_Service_Drive($client);
}


