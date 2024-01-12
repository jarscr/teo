<?php

namespace App;

/**
 * Application configuration
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

class Config
{

    /**
     * Database host
     * @var string
     */
    const DB_HOST = 'localhost';

    /**
     * Database name
     * @var string
     */
    const DB_NAME = 'teo';

    /**
     * Database user
     * @var string
     */
    const DB_USER = 'root';

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = 'root';

    /**
    * Version
     * @var string
    */
    const VERSION = '1.1.1';

    /**
    * Default Lang
     * @var string
    */
    const LANG = 'es';



    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = true;
}
