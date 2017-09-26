<?php
/*
 * Replay Process Find
 * In charge of checking hotsapi for unseen replays and inserting initial entries into the 'replays' table then queueing them.
 */

include 'includes/database.php';
include 'includes/hotsapi.php';

set_time_limit(0);

$db = new Database();
$db->connectDefaultReplayProcess();

//Prepare statements
$db->prepare("SelectNewestReplay", "SELECT * FROM replays ORDER BY hotsapi_page DESC, hotsapi_idinpage DESC LIMIT 1");
$db->prepare("InsertNewReplay", "INSERT INTO replays (hotsapi_id, hotsapi_page, hotsapi_idinpage, fingerprint, hotsapi_url, status) VALUES (?, ?, ?, ?, ?, ?)");
$db->bind("InsertNewReplay", "iiisss", $r_id, $r_page, $r_idinpage, $r_fingerprint, $r_s3url, $r_status);

//Constants and qol
const OUT_OF_REPLAYS_SLEEP_DURATION = 3600; //seconds
const UNKNOWN_ERROR_CODE = 300; //seconds
const TOO_MANY_REQUEST_SLEEP_DURATION = 60; //seconds
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

function addToPageIndex($amount) {
    global $pageindex;
    setPageIndex($pageindex + $amount);
}

function setPageIndex($amount) {
    global $pagenum, $pageindex;

    $pageindex = $amount;
    if ($pageindex > Hotsapi::REPLAYS_PER_PAGE) {
        $pagenum++;
        $pageindex = 1;
    }
}

//Begin main script
echo 'Replay process <<FIND>> has started'.$e
    .'--------------------------------------'.$e;

//Get newest replay if there is one to determine where to start looking in hotsapi
$result = $db->execute("SelectNewestReplay");
$resrows = $db->countResultRows($result);
$pagenum = 1; //Default start at page 1
$pageindex = 1; //Default replay page id

if ($resrows > 0) {
    $row = $db->fetchArray($result);

    $pagenum = $row['hotsapi_page'];
    setPageIndex($row['hotsapi_idinpage'] + 1);
}

$db->freeResult($result);

//Look for replays to download and handle
while (true) {
    echo 'Requesting page '.$pagenum.' from hotsapi, starting at page index '.$pageindex.'...'.$e;

    $api = Hotsapi::getPagedReplays($pagenum);

    $prevpage = $pagenum;

    if ($api['code'] == Hotsapi::HTTP_OK) {
        //Process json data and put it in the database
        $replays = $api['json']['replays'];
        $replaylen = count($replays);
        if ($replaylen > 0) {
            $relevant_replays = Hotsapi::getReplaysGreaterThanEqualToId($replays, $pageindex);
            if (count($relevant_replays) > 0) {
                foreach ($relevant_replays as $replay) {
                    $r_id = $replay['id'];
                    $r_page = $pagenum;
                    $r_idinpage = $replay['page_index'];
                    $r_fingerprint = $replay['fingerprint'];
                    $r_s3url = $replay['url'];
                    $r_status = "queued";

                    $db->execute("InsertNewReplay");
                }
                addToPageIndex($replaylen); //Finished with page, rollover page index
                echo 'Page #' . $prevpage . ' processed (' . count($relevant_replays) . ' relevant replays).'.$e;
            }
            else {
                //No relevant replays found here, set next replayid to be greater than the highest id in the replayset
                addToPageIndex($replaylen); //Finished with page, rollover page index
                echo 'Page #' . $prevpage . ' had no more relevant replays.'.$e;
            }
        }
        else {
            //No more replay pages to process! Long sleep
            echo 'Out of replays to process! Waiting for new hotsapi replay at page index #' . $pageindex . '...'.$e;
            smartSleep(OUT_OF_REPLAYS_SLEEP_DURATION);
        }
    }
    else if ($api['code'] == Hotsapi::HTTP_RATELIMITED) {
        //Error too many requests, wait awhile before trying again
        echo 'Error: HTTP Code ' . $api['code'] . '. Rate limited.'.$e;
        smartSleep(TOO_MANY_REQUEST_SLEEP_DURATION);
    }
    else {
        echo 'Error: HTTP Code ' . $api['code'].'.'.$e;
        smartSleep(UNKNOWN_ERROR_CODE);
    }

    if ($dosleep) {
        smartSleep(SLEEP_DURATION, true);
    }
}

?>