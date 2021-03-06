<?php
/*
Session Class
*/
class session{
    public static function init(){
        session_start();
    }

    public static function set($key, $val){
        $_SESSION[$key] = $val;
    }

    public static function get($key){
        if(isset($_SESSION[$key])){
            return $_SESSION[$key];
        }else{
            return FALSE;
        }
    }
    public static function checkSession(){
        self::init();
        if(self::get('adminlogin')== false){
            self::destroy();
            header('Location:index.php');
        }
    }
    public static function checkLogin(){
        self::init();
        if(self::get('adminlogin')== true){
            header('Location:dashboard.php');
        }
    }
    public static function destroy(){
        session_destroy();
        header('Location:index.php');
    }
}

?>