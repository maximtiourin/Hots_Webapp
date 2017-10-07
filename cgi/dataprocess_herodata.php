<?php

/*
 * Command Line Process
 * args:
 * 0: File path of script being executed (default arg from php)
 *
 * output:
 * JSON string of relevant hero data
 */

set_time_limit(0);

//The json array that holds all of the heroes
$global_json = [];
$global_json['heroes'] = [];

//General QoL constants
const E = PHP_EOL;

//QoL constants for referencing json encoded xml
const ATTR = "@attributes";
const ID = "id";
const V = "value";

/*
 * Data directory path constants
 */
const PATH_DATA = "/data/";
//Stormdata
const PATH_STORMDATA = PATH_DATA . "mods/heroesdata.stormmod/base.stormdata/GameData/";
const PATH_STORMDATA_STRINGS = PATH_DATA . "mods/heroesdata.stormmod/enus.stormdata/LocalizedData/";
const PATH_STORMDATA_HEROES = PATH_STORMDATA . "Heroes/";
const PATHFRAG_STORMDATA_HERO_DIR = "Data/";
const PATHFRAG_STORMDATA_HERO_DATA = "Data.xml";
const FILE_STORMDATA_OLDHEROINDEX = PATH_STORMDATA . "HeroData.xml";
const FILE_STORMDATA_STRINGS_OLDHEROINDEX = PATH_STORMDATA_STRINGS . "GameStrings.txt";
//Heromods
const PATH_HEROMODS = PATH_DATA . "mods/heromods/";
const PATHFRAG_HEROMODS_HERO_DIR = ".stormmod/base.stormdata/GameData/";
const PATHFRAG_HEROMODS_HERO_STRINGS_DIR = ".stormmod/enus.stormdata/LocalizedData/GameStrings.txt";
const PATHFRAG_HEROMODS_HERO_DATA1 = "HeroData.xml";
const PATHFRAG_HEROMODS_HERO_DATA2 = "Data.xml";


//What stormdata heroes to check (Not all *Data.xml files will have <CHero>, but that shouldnt be a problem)
$stormDataNames = [
    "Anubarak" => TRUE,
    "Artanis" => TRUE,
    "Azmodan" => TRUE,
    "Butcher" => TRUE,
    "Chen" => TRUE,
    "Crusader" => TRUE,
    "DemonHunter" => TRUE,
    "Diablo" => TRUE,
    "Dryad" => TRUE,
    "Genn" => TRUE,
    "Jaina" => TRUE,
    "Kaelthas" => TRUE,
    "Leoric" => TRUE,
    "LostVikings" => TRUE,
    "Medic" => TRUE,
    "Monk" => TRUE,
    "Murky" => TRUE,
    "Necromancer" => TRUE,
    "Rexxar" => TRUE,
    "SgtHammer" => TRUE,
    "Stitches" => TRUE,
    "Sylvanas" => TRUE,
    "Tassadar" => TRUE,
    "Thrall" => TRUE,
    "Tinker" => TRUE,
    "Uther" => TRUE,
    "WitchDoctor" => TRUE,
    "Wizard" => TRUE,
    "Zagara" => TRUE,
    "Zeratul" => TRUE
];

//What heromods hero identifiers to use for folders (and hopefully for underlying xml data as well)
$heromodsDataNames = [
    "alarak" => TRUE,
    "amazon" => TRUE,
    "ana" => TRUE,
    "auriel" => TRUE,
    "chogall" => TRUE,
    "chromie" => TRUE,
    "dehaka" => TRUE,
    "dva" => TRUE,
    "garrosh" => TRUE,
    "genji" => TRUE,
    "guldan" => TRUE,
    "kelthuzad" => TRUE,
    "lucio" => TRUE,
    "malthael" => TRUE,
    "medivh" => TRUE,
    "probius" => TRUE,
    "samuro" => TRUE,
    "stukov" => TRUE,
    "thefirelords" => TRUE,
    "tracer" => TRUE,
    "valeera" => TRUE,
    "varian" => TRUE,
    "zarya" => TRUE,
    "zuljin" => TRUE
];

//Blizzard naming conventions are a mess, and a few hero data xml's do not follow any of the established patterns, so must insert special cases for them here
$heromodsDataNamesExceptions = [
    "garrosh" => "Garrosh.xml",
    "kelthuzad" => "KelThuzad.xml",
    "guldan" => "GuldanData.xml"
];

//What internal hero names to ignore from the list
$ignoreNames = [
    "Random" => TRUE,
    "TestHero" => TRUE,
    "GreymaneWorgen" => TRUE,
    "ChoGallBundleProduct" => TRUE
];

// Blizzard really doesn't like consistent patterns when naming their stuff. For the most part I was able to create a pattern match
// for a large variety of egregious edge cases, but there were a few that were so incredibly stupid that it would just be easier
// to explicitly map them.
$talentExceptions = [
    "AbathurMasteryEnvenomedNestsToxicNest" => "AbathurToxicNestEnvenomedNestTalent",
    "AbathurMasteryVileNestsToxicNest" => "AbathurToxicNestVileNestTalent",
    "GenericTalentCalldownMULE" => "GenericCalldownMule",
    "AbathurCombatStyleLocustBrood" => "AbathurLocustSwarm",
    "RehgarMasteryShamanHealingWard" => "RehgarHealingTotem",
    "RehgarMasteryEarthGraspTotem" => "RehgarEarthbindTotemEarthgraspTotemTalent",
    "RaynorMasteryGiveMeMoreAdrenalineRush" => "RaynorAdrenalineRushGiveMeMoreTalent",
    "RaynorMasteryFightorFlightAdrenalineRush" => "RaynorAdrenalineRushFightOrFlightTalent",
    "HeroGenericExecutionerPassive" => "GenericExecutionerTalent",
    "RaynorMasteryClusterRoundPenetratingRound" => "RaynorPenetratingRoundClusterRoundTalent",
    "RaynorMasteryBullseyePenetratingRound" => "RaynorPenetratingRoundBullseyeTalent",
    "RaynorMasteryHelsAngelsRaynorsBanshees" => "RaynorRaynorsRaidersHelsAngelsTalent",
    "TassadarTemplarsWill" => "TassadarOracleTemplarsWillTalent",
    "TassadarKhalasCelerityPlasmaShield" => "TassadarKhalasCelerity",
];

// Experimental map of words that heroes might have as filler for their talent names, adds these words to try to find a talent
$talentHeroWordExceptions = [
    "Tyrande" => [
        "Sentinel", "LightOfElune", "HuntersMark", "LunarFlare"
    ]
];

// Experimental map of words that heroes might have rotating as filler between both ids, so add and remove these words at various points to try to find a talent
$talentHeroRotateExceptions = [
    "Uther" => [
        "DivineStorm", "DivineShield", "HolyRadiance"
    ]
];

// To further add to the Blizzard lunacy, some talents have differing patterns for their proper name compared to their description
$talentNameExceptions = [
    "TassadarHeroicAbilityArchon" => "TassadarArchonSelectButton",
    "TassadarMasteryTwilightArchon" => "TassadarArchonTwilightArchonTalentSelectButton"
];

//What names to convert if encountered
/*$mapNames = [
    "LiLi" => "Li Li",
    "Barbarian" => "Sonya",
    "FaerieDragon" => "Brightwing",
    "L90ETC" => "E.T.C.",
    "Anubarak" => "Anub'arak",
    "Butcher" => "The Butcher",
    "Crusader" => "Johanna",
    "DemonHunter" => "Valla",
    "Dryad" => "Lunara",
    "Kaelthas" => "Kael'thas",
    "LostVikings" => "The Lost Vikings",
    "Medic" => "Lt. Morales",
    "Monk" => "Kharazim",
    "Necromancer" => "Xul",
    "SgtHammer" => "Sgt. Hammer",
    "Tinker" => "Gazlowe",
    "WitchDoctor" => "Nazeebo",
    "Wizard" => "Li-Ming",
    "Amazon" => "Cassia",
    "DVa" => "D.Va",
    "KelThuzad" => "Kel'Thuzad",
    "Lucio" => "Lúcio",
    "Zuljin" => "Zul'jin",
    "Guldan" => "Gul'dan"
];*/

function extractImageString($str) {
    $arr = [];
    $ret = preg_match("/.*\\\\(.*).dds/", $str, $arr);

    if ($ret == 1) {
        return $arr[1];
    }
    else {
        return "";
    }
}

function extractURLFriendlyProperName($name) {
    return preg_replace('/[^a-zA-Z0-9]/', '', $name);
}

/*
 * Extracts the value of the line with the given identifier in the line seperated file
 * Will also remove any html-like brackets inside of the value, leaving only the basic string
 *
 * Can optionally set $isTalent = true so that the function knows to first check for a version of the lineid with a 'Talent'
 * suffix, before just checking for the lineid.
 *
 * If name internal is set to a hero's internal name, then the regex parsing can be sped up while also making sure that
 * hero names are treated as one word.
 *
 * $isTalentNameVariant is a bool that if true means we're extracting specifically a Talent name, made necessary in order to properly
 * map talent name vs. talent desc disparities caused by Blizzard's disparate naming conventions
 */
function extractLine($prefix, $id, $linesepstring, $defaultValue = "", $isTalent = FALSE, $isTalentNameVariant = FALSE, $nameinternal = "") {
    global $talentExceptions, $talentNameExceptions, $talentHeroWordExceptions, $talentHeroRotateExceptions;

    $talent = 'Talent';

    $matchtalent = '@' . $prefix . $id . $talent . '=(.*)$@m';
    $match = '@' . $prefix . $id . '=(.*)$@m';
    $replacebbcode = '/<.*?>/';

    /*
     * Special exception talent match cases
     */
    $mtalent = [];
    $mtvalid = [];
    $mtex = [];
    $mtex2 = [];
    $mtlimit = [];
    $mtwords = [];
    $tid = 0;

    $talentException = FALSE;

    //Talent Exception shortcuts
    if ($isTalentNameVariant && key_exists($id, $talentNameExceptions)) {
        $talentException = TRUE;
        $mtvalid[$tid] = TRUE;
        $mtalent[$tid] = '@' . $prefix . $talentNameExceptions[$id] . '=(.*)$@m';
        $tid++;
    }
    if (key_exists($id, $talentExceptions)) {
        $talentException = TRUE;
        $mtvalid[$tid] = TRUE;
        $mtalent[$tid] = '@' . $prefix . $talentExceptions[$id] . '=(.*)$@m';
        $tid++;
    }
    //$nameinternal shortcuts
    if (strlen($nameinternal) > 0) {
        // {HERONAME}MasteryFooBar = {HERONAME}FooBarTalent (SKIPS 'Mastery'* IGNORES $nameinternal)
        $mtvalid[$tid] = TRUE;
        $mtex[$tid] = "Mastery";
        $mtalent[$tid] = $id;
        $mtalent[$tid] = str_replace($mtex[$tid], '', $mtalent[$tid]);
        $mtalent[$tid] = '@' . $prefix . $mtalent[$tid] . $talent . '=(.*)$@m';
        $tid++;
        // {HERONAME}(HeroicAbility|Mastery)FooBar = {HERONAME}FooBar (SKIPS 'HeroicAbility'* and 'Mastery'* IGNORES $nameinternal)
        $mtvalid[$tid] = TRUE;
        $mtex[$tid] = "HeroicAbility";
        $mtex2[$tid] = "Mastery";
        $mtalent[$tid] = $id;
        $mtalent[$tid] = str_replace($mtex[$tid], '', $mtalent[$tid]);
        $mtalent[$tid] = str_replace($mtex2[$tid], '', $mtalent[$tid]);
        $mtalent[$tid] = '@' . $prefix . $mtalent[$tid] . '=(.*)$@m';
        $tid++;
        // {HERONAME}(HeroicAbility|Mastery)FooBar = {HERONAME}FooBarHotbar (SKIPS 'HeroicAbility'* and 'Mastery' IGNORES $nameinternal)
        $mtvalid[$tid] = TRUE;
        $mtex[$tid] = "HeroicAbility";
        $mtex2[$tid] = "Mastery";
        $mtalent[$tid] = $id;
        $mtalent[$tid] = str_replace($mtex[$tid], '', $mtalent[$tid]);
        $mtalent[$tid] = str_replace($mtex2[$tid], '', $mtalent[$tid]);
        $mtalent[$tid] = '@' . $prefix . $mtalent[$tid] . 'Hotbar' . '=(.*)$@m';
        $tid++;
        // {HERONAME} . Otherwords_Except_Word2_Word3_Word4 . 'Talent' (SKIPS {HeroName} and Word 2 and Word 3 and Word 4, READDS {HeroName})
        $mtvalid[$tid] = FALSE;
        $mtlimit[$tid] = 4;
        $mtalent[$tid] = str_replace($nameinternal, '', $id);
        $mtwords[$tid] = preg_split('/(?=[A-Z])/', $mtalent[$tid], NULL, PREG_SPLIT_NO_EMPTY); //Splits into array of words, where every word past [0] is gauranteed capitalized on the first letter
        if (count($mtwords[$tid]) >= $mtlimit[$tid]) {
            $mtalent[$tid] = '@' . $prefix . $nameinternal;
            for ($i = 3; $i < count($mtwords[$tid]); $i++) {
                $mtalent[$tid] .= $mtwords[$tid][$i];
            }
            $mtalent[$tid] = $mtalent[$tid] . $talent . '=(.*)$@m';
            $mtvalid[$tid] = TRUE;
        }
        $tid++;
        // Looping experimental word map addition based on hero name {HERONAME} . $word . Otherwords . Talent (SKIPS 'Mastery'* READDS {HeroName})
        if (key_exists($nameinternal, $talentHeroWordExceptions)) {
            foreach ($talentHeroWordExceptions[$nameinternal] as $word) {
                $mtvalid[$tid] = TRUE;
                $mtex[$tid] = "Mastery";
                $mtalent[$tid] = $id;
                $mtalent[$tid] = str_replace($nameinternal, '', $mtalent[$tid]);
                $mtalent[$tid] = str_replace($mtex[$tid], '', $mtalent[$tid]);
                $mtalent[$tid] = '@' . $prefix . $nameinternal . $word . $mtalent[$tid] . $talent . '=(.*)$@m';
                $tid++;
            }
        }
        // Looping experimental word map rotation based on hero name {HERONAME} . $word . Otherwords . Talent (SKIPS 'Mastery'* and $word READDS {HeroName})
        if (key_exists($nameinternal, $talentHeroRotateExceptions)) {
            foreach ($talentHeroRotateExceptions[$nameinternal] as $word) {
                $mtvalid[$tid] = TRUE;
                $mtex[$tid] = "Mastery";
                $mtalent[$tid] = $id;
                $mtalent[$tid] = str_replace($nameinternal, '', $mtalent[$tid]);
                $mtalent[$tid] = str_replace($mtex[$tid], '', $mtalent[$tid]);
                $mtalent[$tid] = str_replace($word, '', $mtalent[$tid]);
                $mtalent[$tid] = '@' . $prefix . $nameinternal . $word . $mtalent[$tid] . $talent . '=(.*)$@m';
                $tid++;
            }
        }
    }
    // GenericFooBar = TalentFooBar (SKIPS 'Generic'*)
    $mtvalid[$tid] = TRUE;
    $mtex[$tid] = "Generic";
    $mtalent[$tid] = $id;
    $mtalent[$tid] = str_replace($mtex[$tid], '', $mtalent[$tid]);
    $mtalent[$tid] = '@' . $prefix . $talent . $mtalent[$tid] . '=(.*)$@m';
    $tid++;
    // GenericTalentFooBar = GenericFooBar (SKIPS 'Talent'*)
    $mtvalid[$tid] = TRUE;
    $mtex[$tid] = "Talent";
    $mtalent[$tid] = $id;
    $mtalent[$tid] = str_replace($mtex[$tid], '', $mtalent[$tid]);
    $mtalent[$tid] = '@' . $prefix . $mtalent[$tid] . '=(.*)$@m';
    $tid++;
    // GenericTalentFooBar = GenericFooBarHotbar (SKIPS 'Talent'*)
    $mtvalid[$tid] = TRUE;
    $mtex[$tid] = "Talent";
    $mtalent[$tid] = $id;
    $mtalent[$tid] = str_replace($mtex[$tid], '', $mtalent[$tid]);
    $mtalent[$tid] = '@' . $prefix . $mtalent[$tid] . 'Hotbar' . '=(.*)$@m';
    $tid++;
    // Firstword . Arbitraryword . Otherwords . 'Talent' (SKIPS Word 2)
    $mtvalid[$tid] = FALSE;
    $mtlimit[$tid] = 3;
    $mtalent[$tid] = $id;
    $mtwords[$tid] = preg_split('/(?=[A-Z])/', $mtalent[$tid], NULL, PREG_SPLIT_NO_EMPTY); //Splits into array of words, where every word past [0] is gauranteed capitalized on the first letter
    if (count($mtwords[$tid]) >= $mtlimit[$tid]) {
        $mtalent[$tid] = '@' . $prefix . $mtwords[$tid][0] . '[A-Z]+[a-z0-9]*';
        for ($i = 2; $i < count($mtwords[$tid]); $i++) {
            $mtalent[$tid] .= $mtwords[$tid][$i];
        }
        $mtalent[$tid] = $mtalent[$tid] . $talent . '=(.*)$@m';
        $mtvalid[$tid] = TRUE;
    }
    $tid++;
    // Firstword . Other_words_except_second . 'Talent' (SKIPS Word 2)
    $mtvalid[$tid] = FALSE;
    $mtlimit[$tid] = 3;
    $mtalent[$tid] = $id;
    $mtwords[$tid] = preg_split('/(?=[A-Z])/', $mtalent[$tid], NULL, PREG_SPLIT_NO_EMPTY); //Splits into array of words, where every word past [0] is gauranteed capitalized on the first letter
    if (count($mtwords[$tid]) >= $mtlimit[$tid]) {
        $mtalent[$tid] = '@' . $prefix . $mtwords[$tid][0];
        for ($i = 2; $i < count($mtwords[$tid]); $i++) {
            $mtalent[$tid] .= $mtwords[$tid][$i];
        }
        $mtalent[$tid] = $mtalent[$tid] . $talent . '=(.*)$@m';
        $mtvalid[$tid] = TRUE;
    }
    $tid++;
    // Firstword . Arbitraryword . Otherwords_except_last . 'Talent' (SKIPS Word 2, Last word)
    $mtvalid[$tid] = FALSE;
    $mtlimit[$tid] = 4;
    $mtalent[$tid] = $id;
    $mtwords[$tid] = preg_split('/(?=[A-Z])/', $mtalent[$tid], NULL, PREG_SPLIT_NO_EMPTY); //Splits into array of words, where every word past [0] is gauranteed capitalized on the first letter
    if (count($mtwords[$tid]) >= $mtlimit[$tid]) {
        $mtalent[$tid] = '@' . $prefix . $mtwords[$tid][0] . '[A-Z]+[a-z0-9]*';
        for ($i = 2; $i < count($mtwords[$tid]) - 1; $i++) {
            $mtalent[$tid] .= $mtwords[$tid][$i];
        }
        $mtalent[$tid] = $mtalent[$tid] . $talent . '=(.*)$@m';
        $mtvalid[$tid] = TRUE;
    }
    $tid++;
    // Firstword . 2_Arbitrarywords . Otherwords . 'Talent' (SKIPS Word 2 and Word 3)
    $mtvalid[$tid] = FALSE;
    $mtlimit[$tid] = 4;
    $mtalent[$tid] = $id;
    $mtwords[$tid] = preg_split('/(?=[A-Z])/', $mtalent[$tid], NULL, PREG_SPLIT_NO_EMPTY); //Splits into array of words, where every word past [0] is gauranteed capitalized on the first letter
    if (count($mtwords[$tid]) >= $mtlimit[$tid]) {
        $mtalent[$tid] = '@' . $prefix . $mtwords[$tid][0] . '[A-Z]+[a-z0-9]*[A-Z]+[a-z0-9]*';
        for ($i = 3; $i < count($mtwords[$tid]); $i++) {
            $mtalent[$tid] .= $mtwords[$tid][$i];
        }
        $mtalent[$tid] = $mtalent[$tid] . $talent . '=(.*)$@m';
        $mtvalid[$tid] = TRUE;
    }
    $tid++;
    // Firstword . 2_Arbitrarywords . Otherwords . 'Talent' (SKIPS Nothing)
    $mtvalid[$tid] = FALSE;
    $mtlimit[$tid] = 2;
    $mtalent[$tid] = $id;
    $mtwords[$tid] = preg_split('/(?=[A-Z])/', $mtalent[$tid], NULL, PREG_SPLIT_NO_EMPTY); //Splits into array of words, where every word past [0] is gauranteed capitalized on the first letter
    if (count($mtwords[$tid]) >= $mtlimit[$tid]) {
        $mtalent[$tid] = '@' . $prefix . $mtwords[$tid][0] . '[A-Z]+[a-z0-9]*[A-Z]+[a-z0-9]*';
        for ($i = 1; $i < count($mtwords[$tid]); $i++) {
            $mtalent[$tid] .= $mtwords[$tid][$i];
        }
        $mtalent[$tid] = $mtalent[$tid] . $talent . '=(.*)$@m';
        $mtvalid[$tid] = TRUE;
    }
    $tid++;
    // GenericTalentFooBar = FooBarTalent (SKIPS 'GenericTalent'*)
    $mtvalid[$tid] = TRUE;
    $mtex[$tid] = "GenericTalent";
    $mtalent[$tid] = $id;
    $mtalent[$tid] = str_replace($mtex[$tid], '', $mtalent[$tid]);
    $mtalent[$tid] = '@' . $prefix . $mtalent[$tid] . $talent . '=(.*)$@m';
    $tid++;
    // Firstword . Other_words_except_2_and_3 (SKIPS Word 2 and Word 3)
    $mtvalid[$tid] = FALSE;
    $mtlimit[$tid] = 4;
    $mtalent[$tid] = $id;
    $mtwords[$tid] = preg_split('/(?=[A-Z])/', $mtalent[$tid], NULL, PREG_SPLIT_NO_EMPTY); //Splits into array of words, where every word past [0] is gauranteed capitalized on the first letter
    if (count($mtwords[$tid]) >= $mtlimit[$tid]) {
        $mtalent[$tid] = '@' . $prefix . $mtwords[$tid][0];
        for ($i = 3; $i < count($mtwords[$tid]); $i++) {
            $mtalent[$tid] .= $mtwords[$tid][$i];
        }
        $mtalent[$tid] = $mtalent[$tid] . '=(.*)$@m';
        $mtvalid[$tid] = TRUE;
    }
    $tid++;
    // Firstword . 3Arbitrarywords . Otherwords . 'Talent' (SKIPS Word 2)
    $mtvalid[$tid] = FALSE;
    $mtlimit[$tid] = 3;
    $mtalent[$tid] = $id;
    $mtwords[$tid] = preg_split('/(?=[A-Z])/', $mtalent[$tid], NULL, PREG_SPLIT_NO_EMPTY); //Splits into array of words, where every word past [0] is gauranteed capitalized on the first letter
    if (count($mtwords[$tid]) >= $mtlimit[$tid]) {
        $mtalent[$tid] = '@' . $prefix . $mtwords[$tid][0] . '[A-Z]+[a-z0-9]*[A-Z]+[a-z0-9]*[A-Z]+[a-z0-9]*';
        for ($i = 2; $i < count($mtwords[$tid]); $i++) {
            $mtalent[$tid] .= $mtwords[$tid][$i];
        }
        $mtalent[$tid] = $mtalent[$tid] . $talent . '=(.*)$@m';
        $mtvalid[$tid] = TRUE;
    }
    $tid++;
    // FooBar = FooBarHotbar (SKIPS Nothing)
    $mtvalid[$tid] = TRUE;
    $mtex[$tid] = "Hotbar";
    $mtalent[$tid] = $id;
    $mtalent[$tid] = '@' . $prefix . $mtalent[$tid] . $mtex[$tid] . '=(.*)$@m';
    $tid++;
    // Firstword . 2_Arbitrarywords . Otherwords . 'Talent' (SKIPS Word 2)
    $mtvalid[$tid] = FALSE;
    $mtlimit[$tid] = 3;
    $mtalent[$tid] = $id;
    $mtwords[$tid] = preg_split('/(?=[A-Z])/', $mtalent[$tid], NULL, PREG_SPLIT_NO_EMPTY); //Splits into array of words, where every word past [0] is gauranteed capitalized on the first letter
    if (count($mtwords[$tid]) >= $mtlimit[$tid]) {
        $mtalent[$tid] = '@' . $prefix . $mtwords[$tid][0] . '[A-Z]+[a-z0-9]*[A-Z]+[a-z0-9]*';
        for ($i = 2; $i < count($mtwords[$tid]); $i++) {
            $mtalent[$tid] .= $mtwords[$tid][$i];
        }
        $mtalent[$tid] = $mtalent[$tid] . $talent . '=(.*)$@m';
        $mtvalid[$tid] = TRUE;
    }
    $tid++;
    // 'Generic' . Otherwords_but_remove_plural . 'Talent' (SKIPS Word 1, REMOVES plurality [s, if it exists])
    $mtvalid[$tid] = FALSE;
    $mtex[$tid] = 'Generic';
    $mtlimit[$tid] = 2;
    $mtalent[$tid] = preg_replace('/(.*)s/', '$1', $id);
    $mtwords[$tid] = preg_split('/(?=[A-Z])/', $mtalent[$tid], NULL, PREG_SPLIT_NO_EMPTY); //Splits into array of words, where every word past [0] is gauranteed capitalized on the first letter
    if (count($mtwords[$tid]) >= $mtlimit[$tid]) {
        $mtalent[$tid] = '@' . $prefix . $mtex[$tid];
        for ($i = 1; $i < count($mtwords[$tid]); $i++) {
            $mtalent[$tid] .= $mtwords[$tid][$i];
        }
        $mtalent[$tid] = $mtalent[$tid] . $talent . '=(.*)$@m';
        $mtvalid[$tid] = TRUE;
    }
    $tid++;

    $arr = [];
    $arr2 = [];

    $ret = null;

    if ($isTalent) {
        //matchTalent
        if (!$talentException) $ret = preg_match($matchtalent, $linesepstring, $arr);

        if (!$talentException && $ret == 1) {
            $str = $arr[1];
            return trim(preg_replace($replacebbcode, '', $str));
        }
        else {
            //match
            if (!$talentException) $ret = preg_match($match, $linesepstring, $arr2);

            if (!$talentException && $ret == 1) {
                $str = $arr2[1];
                return trim(preg_replace($replacebbcode, '', $str));
            }
            else {
                //mtalent Talent Exceptions
                for ($i = 0; $i < $tid; $i++) {
                    if ($mtvalid[$i]) $ret = preg_match($mtalent[$i], $linesepstring, $arr);

                    if ($mtvalid[$i] && $ret == 1) {
                        $str = $arr[1];
                        return trim(preg_replace($replacebbcode, '', $str));
                    }
                }

                //Exhausted all possibilities, just return default value
                return $defaultValue;
            }
        }
    }
    else {
        $ret = preg_match($match, $linesepstring, $arr);

        if ($ret == 1) {
            $str = $arr[1];
            return trim(preg_replace($replacebbcode, '', $str));
        }
        else {
            return $defaultValue;
        }
    }
}

function extractHero_xmlToJson($filepath, $file_strings) {
    global $ignoreNames, $mapNames, $global_json;

    $str = file_get_contents($filepath); //Xml string of hero data
    $str2 = $file_strings; //Line seperated hero localization strings

    //Extract relevant xml from larger xml string (Essentially Selects everything between <CHero></CHero> tags, while ignoring <CHero/> tags)
    $res = [];
    $ret = preg_match_all("/<CHero id=[^\/]*>.*<\/CHero>/Ums", $str, $res);

    if ($ret !== FALSE) {
        //Select the overall result array of individual results
        $results = $res[0];

        foreach ($results as $xmlres) {
            //Prep the xml string to be properly used by simple xml
            $s = $xmlres;
            $s = str_replace(array("\n", "\r", "\t"), '', $s);
            $s = trim(str_replace('"', "'", $s));

            //Create simple xml object from xml string
            $xmlobj = simplexml_load_string($s);

            //Decode xml into json
            $j = json_decode(json_encode($xmlobj, JSON_HEX_QUOT), true);

            //Make sure hero even has an id
            if (key_exists(ATTR, $j)) {
                $name_internal = $j[ATTR][ID];

                //Make sure hero isnt on ignore list
                if (!key_exists($name_internal, $ignoreNames) || $ignoreNames[$name_internal] == FALSE) {
                    //Construct hero object from json decoded xml
                    $hero = [];

                    //Proper name
                    $hero['name'] = extractLine("Hero/Name/", $name_internal, $str2, $name_internal);
                    /*if (key_exists($name_internal, $mapNames)) {
                        //Map hero name
                        $hero['name'] = $mapNames[$name_internal];
                    }
                    else {
                        //Use internal name as proper name
                        $hero['name'] = $name_internal;
                    }*/

                    //Internal name
                    $hero['name_internal'] = $name_internal;

                    //Sort name
                    $hero['name_sort'] = extractLine("Hero/SortName/", $name_internal, $str2, extractURLFriendlyProperName($hero['name']));

                    //Attribute name
                    if (key_exists('AttributeId', $j)) {
                        $hero['attribute'] = $j['AttributeId'][ATTR][V];
                    }
                    else {
                        $hero['attribute'] = "NONE";
                    }

                    //Difficulty
                    if (key_exists('Difficulty', $j)) $hero['difficulty'] = $j["Difficulty"][ATTR][V];

                    //Role
                    if (key_exists('Role', $j)) {
                        $hero['role'] = $j['Role'][ATTR][V];
                    }
                    else if (key_exists('RolesMultiClass', $j) && key_exists(ATTR, $j['RolesMultiClass'])) {
                        $hero['role'] = $j['RolesMultiClass'][ATTR][V];
                    }
                    else if (key_exists('CollectionCategory', $j)) {
                        $hero['role'] = $j['CollectionCategory'][ATTR][V];
                    }
                    else {
                        $hero['role'] = "Unknown";
                    }

                    //Universe
                    if (key_exists('Universe', $j)) {
                        $hero['universe'] = $j['Universe'][ATTR][V];
                    }
                    else {
                        $hero['universe'] = "Unknown";
                    }

                    //Description Tagline
                    $hero['desc_tagline'] = extractLine("Hero/Description/", $name_internal, $str2, "None");

                    //Description Bio
                    $hero['desc_bio'] = extractLine("Hero/Info/", $name_internal, $str2, "None");

                    //Image name
                    $hero['image_name'] = extractURLFriendlyProperName($hero['name']);

                    //Ratings
                    $hero['ratings'] = [];
                    if (key_exists('Ratings', $j)) {
                        //Damage
                        if (key_exists('Damage', $j['Ratings'])) {
                            $hero['ratings']['damage'] = intval($j['Ratings']['Damage'][ATTR][V]);
                        }
                        else {
                            $hero['ratings']['damage'] = 0;
                        }
                        //Utility
                        if (key_exists('Utility', $j['Ratings'])) {
                            $hero['ratings']['utility'] = intval($j['Ratings']['Utility'][ATTR][V]);
                        }
                        else {
                            $hero['ratings']['utility'] = 0;
                        }
                        //Survivability
                        if (key_exists('Survivability', $j['Ratings'])) {
                            $hero['ratings']['survivability'] = intval($j['Ratings']['Survivability'][ATTR][V]);
                        }
                        else {
                            $hero['ratings']['survivability'] = 0;
                        }
                        //Complexity
                        if (key_exists('Complexity', $j['Ratings'])) {
                            $hero['ratings']['complexity'] = intval($j['Ratings']['Complexity'][ATTR][V]);
                        }
                        else {
                            $hero['ratings']['complexity'] = 0;
                        }
                    }

                    //Rarity
                    if (key_exists('Rarity', $j)) {
                        $hero['rarity'] = $j['Rarity'][ATTR][V];
                    }
                    else {
                        $hero['rarity'] = "Unknown";
                    }

                    //Talents
                    $hero['talents'] = [];
                    if (key_exists('TalentTreeArray', $j)) {
                        foreach ($j['TalentTreeArray'] as $talent) {
                            $t = [];
                            $tname_internal = $talent[ATTR]['Talent'];
                            $t['name'] = extractLine("Button/Name/", $tname_internal, $str2, $tname_internal, true, true, $name_internal);
                            $t['name_internal'] = $tname_internal;
                            $t['desc'] = extractLine("Button/SimpleDisplayText/", $tname_internal, $str2, "None", true, false, $name_internal);
                            $t['tier'] = $talent[ATTR]['Tier'];
                            $t['column'] = $talent[ATTR]['Column'];
                            $hero['talents'][] = $t;
                        }
                    }

                    $global_json['heroes'][] = $hero;
                }
            }
        }
    }
}

/*
 * Look through Data directories and extract data
 */
//Extract heroes from old hero index
$fp = __DIR__ . FILE_STORMDATA_OLDHEROINDEX;
$strfp = __DIR__ . FILE_STORMDATA_STRINGS_OLDHEROINDEX;
$strfile = null;

if (file_exists($strfp)) {
    $strfile = file_get_contents($strfp);
}
else {
    die("Old Hero Index String File does not exist: " . $strfp);
}

if (file_exists($fp)) {
    extractHero_xmlToJson($fp, $strfile);
}
else {
    die("Old Hero Index File does not exist: " . $fp);
}

//Extract stormdata (use same strings from old hero index)
foreach ($stormDataNames as $heroname => $bool_include) {
    if ($bool_include) {
        $fp = __DIR__ . PATH_STORMDATA_HEROES . $heroname . PATHFRAG_STORMDATA_HERO_DIR . $heroname . PATHFRAG_STORMDATA_HERO_DATA;

        if (file_exists($fp)) {
            extractHero_xmlToJson($fp, $strfile);
        }
        else {
            die("Storm Data File does not exist: " . $fp);
        }
    }
}

$strfile = null;

//Extract heromods
foreach ($heromodsDataNames as $heroname => $bool_include) {
    if ($bool_include) {
        $strfp = __DIR__ . PATH_HEROMODS . $heroname . PATHFRAG_HEROMODS_HERO_STRINGS_DIR;

        $strfile = null;

        if (file_exists($strfp)) {
            $strfile = file_get_contents($strfp);
        }
        else {
            die("Hero Modes String File does not exist: " . $strfp);
        }

        if (key_exists($heroname, $heromodsDataNamesExceptions)) {
            //Special exception hero mods case
            $fp = __DIR__ . PATH_HEROMODS . $heroname . PATHFRAG_HEROMODS_HERO_DIR . $heromodsDataNamesExceptions[$heroname];

            if (file_exists($fp)) {
                extractHero_xmlToJson($fp, $strfile);
            }
            else {
                die("Hero Mods Special Exception File does not exist: " . $fp);
            }
        }
        else {
            //Regular hero mods case
            $fp1 = __DIR__ . PATH_HEROMODS . $heroname . PATHFRAG_HEROMODS_HERO_DIR . PATHFRAG_HEROMODS_HERO_DATA1;
            $fp2 = __DIR__ . PATH_HEROMODS . $heroname . PATHFRAG_HEROMODS_HERO_DIR . ucfirst($heroname) . PATHFRAG_HEROMODS_HERO_DATA2;

            if (file_exists($fp1)) {
                extractHero_xmlToJson($fp1, $strfile);
            }
            else if (file_exists($fp2)) {
                extractHero_xmlToJson($fp2, $strfile);
            }
            else {
                die("Hero Mods Files do not exist at either location: (" . $fp1 . ", " . $fp2 . ")");
            }
        }
    }
}

//Output json
echo json_encode($global_json).E;