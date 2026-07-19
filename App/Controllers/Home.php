<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use Core\Controller;
use Core\View;

/**
 * Home controller
 */
class Home extends Controller
{
    /**
     * Show the index page
     */
    public function indexAction(): void
    {
        View::renderTemplate('Home/index.html', [
            'lang' => User::getLangAll(),
            'modules' => User::getAllModules(),
            'users' => User::getAll(),
            'credits' => User::getAllCredits(),
        ], User::getLangAll()['lang']);
    }
}
