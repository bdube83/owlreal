<?php
class Session {
    public static function start() {
        self::$haveSession = true;
        session_start();
    }
    public static function finish() {
        session_write_close();
        self::$haveSession = false;
    }
    public static function clear() {
        if (self::$haveSession) {
            session_unset();
            session_destroy();
        }
    }
}
?>