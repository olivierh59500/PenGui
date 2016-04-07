<?php
session_start();
/**
 * Created by PhpStorm.
 * User: root
 * Date: 03/04/16
 * Time: 23:01
 */

Class Session
{
    public static function manageSession($setSession)
    {

        $_SESSION['loginUser'] = $setSession;
        //echo "available";
        return $setSession;
        
    }

}