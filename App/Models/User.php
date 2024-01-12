<?php

namespace App\Models;

use PDO;

/**
 * Example user model
 *
 * Requiere PHP8.2
 * 
 * Desarrolla JARS Costa Rica
 * www.jarscr.com
 * Telefono: 4000-2528
 * 
 * Programador: Alfredo Rodriguez
 * 
 **/

class User extends \Core\Model
{

    /**
     * Get all the users as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        // Se comenta para que Travis Compile sin DB
        //$db = static::getDB();
        //$stmt = $db->query('SELECT user_id, username FROM users');
        //return $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array('Usuario'=>'Demo','Email'=>'demo@example.com');
    }

    public static function getLangAll()
    {
        $lang = static::getLang();
        return $lang;
    }

   
    public static function getAllModules()
    {
        $modules = static::getModules();
        return $modules;
    }


    public static function getAllCredits()
    {
        $credits = static::getCredits();
        return $credits;
    }
}
