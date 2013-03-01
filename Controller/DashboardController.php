<?php

namespace Zenstruck\Bundle\DashboardBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;
use Zenstruck\Bundle\DashboardBundle\Dashboard\DashboardManager;

class DashboardController
{
    protected $templating;
    protected $dashboardManager;

    public function __construct(EngineInterface $templating, DashboardManager $dashboardManager)
    {
        $this->templating = $templating;
        $this->dashboardManager = $dashboardManager;
    }

    public function dashboardAction()
    {
        $content = $this->templating->render($this->dashboardManager->getDashboardTemplate(), array(
                'manager' => $this->dashboardManager
            ));

        return new Response($content);
    }

    public function menuWidgetAction($group = null, $section = null)
    {
        if ($group) {
            $menu = $this->dashboardManager->getMenu($group);
        } elseif ($section) {
            $menu = $this->dashboardManager->getMenuForSection($section);
        } else {
            $menu = $this->dashboardManager->getMenu();
        }

        $content = $this->templating->render($this->dashboardManager->getFullTemplateName('_menu_widget.html.twig'), array(
                'menu' => $menu
            ));

        return new Response($content);
    }
}
