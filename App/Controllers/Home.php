<?php

namespace App\Controllers;

use \Core\View;
use App\Models\User as Users;

/**
 * Home controller
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

class Home extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        $Users = new Users();
        $Modules =  $Users->getAllModules();
        $allUsers = $Users->getAll()();
        $Credits = $Users->getAllCredits();
        $Language =  $Users->getLangAll();

        $Parameters = array('lang'=>$Language,
        'modules'=> $Modules,
        'users'=>$allUsers,
        'credits'=>$Credits
        );
        View::renderTemplate('Home/index.html',$Parameters );
    }
}
