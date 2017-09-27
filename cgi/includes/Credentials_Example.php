<?php
/*
 * Example credentials configuration file for things such as cgi script connection strings.
 * To create own, rename to Credentials.php and enter relevant info.
 */
class Credentials {
    //Replay Process Database Connections
    const KEY_DB_HOSTNAME = "db_host";
    const KEY_DB_USER = "db_user";
    const KEY_DB_PASSWORD = "db_password";
    const KEY_DB_DATABASE = "db_database";
    private static $replayProcess_db_hostname = "%HOSTNAME%";
    private static $replayProcess_db_user = "%USER%";
    private static $replayProcess_db_password = "%PASSWORD%";
    private static $replayProcess_db_database = "%DATABASE%";
    //Replay Process AWS credentials
    const KEY_AWS_KEY = "aws_key";
    const KEY_AWS_SECRET = "aws_secret";
    const KEY_AWS_REPLAYREGION = 'aws_replayregion';
    private static $replayProcess_aws_key = "%KEY%";
    private static $replayProcess_aws_secret = "%SECRET%";
    private static $replayProcess_aws_replayregion = "%REPLAYREGION%"; //Ex: eu-west-1

    public static function getReplayProcessCredentials() {
        $a = [];

        $a[self::KEY_DB_HOSTNAME] = self::$replayProcess_db_hostname;
        $a[self::KEY_DB_USER] = self::$replayProcess_db_user;
        $a[self::KEY_DB_PASSWORD] = self::$replayProcess_db_password;
        $a[self::KEY_DB_DATABASE] = self::$replayProcess_db_database;

        $a[self::KEY_AWS_KEY] = self::$replayProcess_aws_key;
        $a[self::KEY_AWS_SECRET] = self::$replayProcess_aws_secret;
        $a[self::KEY_AWS_REPLAYREGION] = self::$replayProcess_aws_replayregion;

        return $a;
    }
}
?>