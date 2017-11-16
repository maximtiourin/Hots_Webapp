<?php

namespace AppBundle\Controller;

use Fizzik\Credentials;
use Fizzik\Database\MySqlDatabase;
use Fizzik\Database\RedisDatabase;
use Fizzik\HotstatusCache;
use Fizzik\HotstatusPipeline;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Asset;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction() {
        return $this->redirectToRoute("heroes");
    }

    /**
     * @Route("/news", name="news")
     */
    public function newsAction() {
        return $this->render('default/index.html.twig', [
        ]);
    }

    /**
     * @Route("/heroes", name="heroes")
     */
    public function heroesAction() {
        HotstatusPipeline::filter_generate_date();

        return $this->render('default/heroes.html.twig', [
            "filter_gameTypes" => HotstatusPipeline::$filter[HotstatusPipeline::FILTER_KEY_GAMETYPE],
            "filter_maps" => HotstatusPipeline::$filter[HotstatusPipeline::FILTER_KEY_MAP],
            "filter_ranks" => HotstatusPipeline::$filter[HotstatusPipeline::FILTER_KEY_RANK],
            "filter_dates" => HotstatusPipeline::$filter[HotstatusPipeline::FILTER_KEY_DATE]
        ]);
    }

    /**
     * @Route("/heroes/{heroProperName}", options={"expose"=true}, name="hero")
     */
    public function heroAction($heroProperName) {
        if (key_exists($heroProperName, HotstatusPipeline::$filter[HotstatusPipeline::FILTER_KEY_HERO])) {
            HotstatusPipeline::filter_generate_date();

            //Select correct hero in hero filter
            $herofilter = HotstatusPipeline::$filter[HotstatusPipeline::FILTER_KEY_HERO];
            $herofilter[$heroProperName]["selected"] = true;

            return $this->render('default/hero.html.twig', [
                "hero_name" => $heroProperName,
                "filter_heroes" => $herofilter,
                "filter_gameTypes" => HotstatusPipeline::$filter[HotstatusPipeline::FILTER_KEY_GAMETYPE],
                "filter_maps" => HotstatusPipeline::$filter[HotstatusPipeline::FILTER_KEY_MAP],
                "filter_ranks" => HotstatusPipeline::$filter[HotstatusPipeline::FILTER_KEY_RANK],
                "filter_dates" => HotstatusPipeline::$filter[HotstatusPipeline::FILTER_KEY_DATE],
                "average_stats" => HotstatusPipeline::$heropage[HotstatusPipeline::HEROPAGE_KEY_AVERAGE_STATS],
                "average_stats_tooltips" => HotstatusPipeline::$heropage_tooltips[HotstatusPipeline::HEROPAGE_KEY_AVERAGE_STATS]
            ]);
        }
        else {
            return $this->redirectToRoute("heroes");
        }
    }

    /**
     * @Route("/players/search", name="playerSearch")
     * @Method("POST")
     */
    public function playerSearchAction(Request $request) {
        $_TYPE = HotstatusCache::CACHE_REQUEST_TYPE_PAGEDATA;
        $_ID = "playerSearchAction";
        $_VERSION = 0;

        $search = $request->request->get("search");

        //Main Vars
        $pagedata = [];
        $validResponse = FALSE;
        $validSearch = FALSE;
        $r_name = "";
        $r_tag = "";

        //Sanitize and Determine search type and extract parameters
        $search = strip_tags(trim(str_replace('"', '', str_replace("'", "", $search))));
        $nameMatches = [];
        $isJustName = preg_match("@(\w+|[\p{Han}\p{Katakana}\p{Hiragana}\p{Hangul}]+)@u", $search, $nameMatches);
        $namePlusBattletagMatches = [];
        $isNamePlusBattletag = preg_match("@(\w+|[\p{Han}\p{Katakana}\p{Hiragana}\p{Hangul}]+)#(\d+)@u", $search, $namePlusBattletagMatches);

        if ($isNamePlusBattletag) {
            $r_name = $namePlusBattletagMatches[1];
            $r_tag = $namePlusBattletagMatches[2];
            $validSearch = true;
        }
        else if ($isJustName) {
            $r_name = $nameMatches[1];
            $validSearch = true;
        }

        if ($validSearch) {
            //Determine Cache Id
            $CACHE_ID = $_ID . "_" . $search;

            //Get credentials
            $creds = Credentials::getCredentialsForUser(Credentials::USER_HOTSTATUSWEB);

            //Get redis cache
            $redis = new RedisDatabase();
            $connected_redis = $redis->connect($creds[Credentials::KEY_REDIS_URI], HotstatusCache::CACHE_PLAYERSEARCH_DATABASE_INDEX);

            //Try to get cached value
            $cacheval = NULL;
            if ($connected_redis !== FALSE) {
                $cacheval = HotstatusCache::readCacheRequest($redis, $_TYPE, $CACHE_ID, $_VERSION);
            }

            if ($connected_redis !== FALSE && $cacheval !== NULL) {
                //Use cached value
                $pagedata = json_decode($cacheval, true);

                $validResponse = TRUE;
            }
            else {
                //Try to get Mysql value
                $db = new MySqlDatabase();

                $connected_mysql = $db->connect($creds[Credentials::KEY_DB_HOSTNAME], $creds[Credentials::KEY_DB_USER], $creds[Credentials::KEY_DB_PASSWORD], $creds[Credentials::KEY_DB_DATABASE]);

                if ($connected_mysql !== FALSE) {
                    $db->setEncoding(HotstatusPipeline::DATABASE_CHARSET);

                    //Prepare Statement
                    if ($isNamePlusBattletag) {
                        $db->prepare("SearchPlayer",
                            "SELECT `id`, `name`, `tag`, `region` FROM `players` WHERE `name` = ? AND `tag` = ? LIMIT 25");
                        $db->bind("SearchPlayer", "si", $r_name, $r_tag);
                    }
                    else {
                        $db->prepare("SearchPlayer",
                            "SELECT `id`, `name`, `tag`, `region` FROM `players` WHERE `name` = ? LIMIT 25");
                        $db->bind("SearchPlayer", "s", $r_name);
                    }

                    //Search for player
                    $pres = [];

                    $playerResult = $db->execute("SearchPlayer");
                    while ($row = $db->fetchArray($playerResult)) {
                        $region = HotstatusPipeline::$ENUM_REGIONS[$row['region']];

                        if (!key_exists($region, $pres)) {
                            $pres[$region] = [];
                        }

                        $pres[$region][] = [
                            "id" => $row['id'],
                            "name" => $row['name'],
                            "tag" => $row['tag'],
                        ];
                    }
                    $db->freeResult($playerResult);

                    $pagedata = $pres;

                    //Close connection and set valid response
                    $db->close();

                    $validResponse = TRUE;
                }

                //Store mysql value in cache
                if ($validResponse && $connected_redis) {
                    $encoded = json_encode($pagedata);
                    HotstatusCache::writeCacheRequest($redis, $_TYPE, $CACHE_ID, $_VERSION, $encoded, HotstatusCache::getCacheDefaultExpirationTimeInSecondsForToday());
                }
            }

            $redis->close();

            return $this->render(':default:playerSearch.html.twig', [
                "search" => $search,
                "searchResults" => $pagedata,
            ]);
        }
        else {
            return $this->redirectToRoute("playerError");
        }
    }

    /**
     * @Route("/players/{id}", requirements={"id": "\d+"}, name="player")
     */
    public function playerAction($id) {
        //TODO : get player object (name, tag, etc) and pass it to twig,
        //TODO : rest of the player data will be loaded using 'playerloader', if player does not exist redirect error

        return $this->render(':default:player.html.twig', [
        ]);
    }

    /**
     * @Route("/players/{id}", defaults={"id" = "Invalid"}, name="playerError")
     */
    public function playerErrorAction($id) {
        return $this->redirectToRoute("heroes");
    }

    /**
     * @Route("/players/", name="playerError2")
     */
    public function playerError2Action() {
        return $this->redirectToRoute("playerError");
    }

    /**
     * @Route("/talents/{heroProperName}", defaults={"heroProperName" = "Abathur"}, name="talents")
     */
    public function talentsAction($heroProperName) {
        return $this->redirectToRoute("hero", [
            "heroProperName" => $heroProperName
        ]);
    }

    /**
     * @Route("/rankings", name="rankings")
     */
    public function rankingsAction() {
        return $this->render('default/index.html.twig', [
        ]);
    }

    /**
     * @Route("/upload", name="upload")
     */
    public function uploadAction() {
        return $this->render('default/index.html.twig', [
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction() {
        return $this->render('default/index.html.twig', [
        ]);
    }

    /**
     * @Route("/faq", name="faq")
     */
    public function faqAction() {
        return $this->render('default/index.html.twig', [
        ]);
    }
}
