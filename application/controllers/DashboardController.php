<?php

namespace Icinga\Module\Businessprocess\Controllers;

use Icinga\Module\Businessprocess\Web\Controller;
use Icinga\Module\Businessprocess\Web\Component\DashboardFullScreen;

class DashboardController extends Controller
{
    /**
     * Show a dashboard page
     */
    public function indexAction()
    {
        $this->setTitle('Dashboard');
        //$this->controls()->add($this->dashboardTab());
        $this->content()->add(DashboardFullScreen::create($this->Auth(), $this->storage()));
	$this->setAutorefreshInterval(120);
    } 
}
