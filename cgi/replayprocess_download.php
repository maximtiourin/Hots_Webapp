<?php
/*
 * Replay Process Download
 * In charge of looking through cataloged replays and downloading them to a temporary location for use with processing further
 * down the pipeline. Can be made to only ever download a fixed amount of replays before waiting for them to be processed to prevent
 * storing too much at once.
 */

include 'includes/Credentials.php';
include 'includes/Database.php';
include 'includes/Hotsapi.php';

set_time_limit(0);

$db = new Database();
$creds = Credentials::getReplayProcessCredentials();
$db->connect($creds[Credentials::KEY_HOSTNAME], $creds[Credentials::KEY_USER], $creds[Credentials::KEY_PASSWORD], $creds[Credentials::KEY_DATABASE]);

//Prepare statements

//Constants and qol
const SLEEP_DURATION = 5; //seconds
const MINI_SLEEP_DURATION = 1; //seconds
$e = PHP_EOL;
$dosleep = false;

//Helper functions
function smartSleep($duration, $mainsleep = false, $mainsleepDuration = SLEEP_DURATION) {
    global $dosleep;

    if ($mainsleep) {
        if ($dosleep) {
            sleep($duration);

            $dosleep = false;
        }
    }
    else {
        sleep($duration - $mainsleepDuration);

        $dosleep = true;
    }
}

//Begin main script
echo 'Replay process <<DOWNLOAD>> has started'.$e
    .'--------------------------------------'.$e;

//Look for replays to download and handle
while (true) {


    if ($dosleep) {
        smartSleep(SLEEP_DURATION, true);
    }
}

?>