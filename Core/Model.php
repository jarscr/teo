<?php

namespace Core;

use PDO;
use App\Config;

/**
 * Base model
 *
 * Requiere PHP7.3
 * 
 * Desarrolla JARS Costa Rica
 * www.jarscr.com
 * Telefono: 4000-2528
 * 
 * Programador: Alfredo Rodriguez
 * 
 **/

abstract class Model
{

    /**
     * Get the PDO database connection
     *
     * @return mixed
     */
    protected static function getDB()
    {
        static $db = null;

        if ($db === null) {
            $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8';
            $db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);

            // Throw an Exception when an error occurs
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $db;
    }

    protected static function getCredits()
    {
     
        static $creditos = null;
        if ($creditos === null) {
        $creditos = array('year'=>date('Y'),
                'version'=> Config::VERSION,
                'name'=>'TEO Simple PHP Framework',
                'development'=>'JARS Costa Rica',
                'description' =>'TEO Simple PHP Framework for building web applications in PHP',
                'keywords' => 'teo,framework, jarscr, php, free'
            );
        }
        return $creditos;
    }

    protected static function getLang()
    {
        static $lang = null;
        if ($lang === null) {
        $lang = array('lang'=>CONFIG::LANG
            );
        }
        return $lang;
    }

    protected static function getModules()
        {
            static $modules = null;
            if ($modules === null) {
                $modules = array(
                        array(
                    'title'=>'Home',
                    'router'=>'/',
                    'icon'=>'house-fill'
                    ),
                        array(
                    'title'=>'About Us',
                    'router'=>'#',
                    'icon'=>'building'
                        ));
                return $modules;
            }
        }
}
