#!/usr/bin/php -q
<?php
require __DIR__ . '/vendor/autoload.php';


include ('/var/www/sql.php');



if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Gmail API PHP Quickstart');
    $client->setScopes(Google_Service_Gmail::GMAIL_MODIFY);
    $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = 'token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}




if (php_sapi_name() != 'cli') {
  throw new Exception('This application must be run on the command line.');
}

/**
 * Get list of Messages in user's mailbox.
 *
 * @param  Google_Service_Gmail $service Authorized Gmail API instance.
 * @param  string $userId User's email address. The special value 'me'
 * can be used to indicate the authenticated user.
 * @return array Array of Messages.
 */
function modifyThread($service, $userId, $threadId, $labelsToAdd, $labelsToRemove) {
  $mods = new Google_Service_Gmail_ModifyThreadRequest();
  $mods->setAddLabelIds($labelsToAdd);
  $mods->setRemoveLabelIds($labelsToRemove);
  try {
    $thread = $service->users_threads->modify($userId, $threadId, $mods);
    print 'Thread with ID: ' . $threadId . ' successfully modified.';
    return $thread;
  } catch (Exception $e) {
    print 'An error occurred: ' . $e->getMessage();
  }
}

function modifyMessage($service, $userId, $messageId, $labelsToAdd, $labelsToRemove) {
  $mods = new Google_Service_Gmail_ModifyMessageRequest();
  $mods->setAddLabelIds($labelsToAdd);
  $mods->setRemoveLabelIds($labelsToRemove);
  try {
    $message = $service->users_messages->modify($userId, $messageId, $mods);
    print 'Message with ID: ' . $messageId . ' successfully modified.';
    return $message;
  } catch (Exception $e) {
    print 'An error occurred: ' . $e->getMessage();
  }
}


function listMessages($service, $userId, $optArr = []) {
  $pageToken = NULL;
  $messages = array();
  do {
    try {
      if ($pageToken) {
        $optArr['pageToken'] = $pageToken;
      }
      $messagesResponse = $service->users_messages->listUsersMessages($userId, $optArr);
      if ($messagesResponse->getMessages()) {
        $messages = array_merge($messages, $messagesResponse->getMessages());
        $pageToken = $messagesResponse->getNextPageToken();
      }
    } catch (Exception $e) {
      print 'An error occurred: ' . $e->getMessage();
    }
  } while ($pageToken);

  return $messages;
}

function getHeaderArr($dataArr) {
    $outArr = [];
    foreach ($dataArr as $key => $val) {
        $outArr[$val->name] = $val->value;
    }
    return $outArr;
}

function getBody($dataArr) {
    $outArr = [];
    foreach ($dataArr as $key => $val) {
        $outArr[] = base64url_decode($val->getBody()->getData());
        break; // we are only interested in $dataArr[0]. Because $dataArr[1] is in HTML.
    }
    return $outArr;
}

function base64url_decode($data) {
  //return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
  return base64_decode(str_replace(array('-', '_'), array('+', '/'), $data));
}

function getMessage($service, $userId, $messageId) {
  try {
    $message = $service->users_messages->get($userId, $messageId);
    #print 'Message with ID: ' . $message->getId() . ' retrieved.' . "\n";
    return $message;
  } catch (Exception $e) {
    #print 'An error occurred: ' . $e->getMessage();
  }
}

function listLabels($service, $userId, $optArr = []) {
    $results = $service->users_labels->listUsersLabels($userId);

    if (count($results->getLabels()) == 0) {
      #print "No labels found.\n";
    } else {
      #print "Labels:\n";
      foreach ($results->getLabels() as $label) {
        printf("- %s\n", $label->getName());
      }
    }
}

// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Gmail($client);
$user = 'me';

$user = 'me';
$strSubject = 'Test mail using GMail API' . date('M d, Y h:i:s A');
$strRawMessage = "From: myAddress<leaks@bidermann.com>\r\n";
$strRawMessage .= "To: toAddress <bobbiannpierce@gmail.com>\r\n";
$strRawMessage .= 'Subject: =?utf-8?B?' . base64_encode($strSubject) . "?=\r\n";
$strRawMessage .= "MIME-Version: 1.0\r\n";
$strRawMessage .= "Content-Type: text/html; charset=utf-8\r\n";
$strRawMessage .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n\r\n";
$strRawMessage .= "this <b>is a test message!\r\n";
// The message needs to be encoded in Base64URL
$mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');
$msg = new Google_Service_Gmail_Message();
$msg->setRaw($mime);
//The special value **me** can be used to indicate the authenticated user.
$service->users_messages->send("me", $msg);
