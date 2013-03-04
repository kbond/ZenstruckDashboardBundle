<?php

namespace Zenstruck\Bundle\DashboardBundle\Twig;

use Zenstruck\Bundle\DashboardBundle\Dashboard\DashboardManager;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class DashboardExtension extends \Twig_Extension
{
    protected $dashboard;

    /** @var \Twig_Environment */
    protected $environment;

    public function __construct(DashboardManager $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getName()
    {
        return 'zenstruck_dashboard';
    }

    public function getFunctions()
    {
        return array(
            'zenstruck_widget' => new \Twig_Function_Method($this, 'renderWidget', array('is_safe' => array('html'))),
            'zenstruck_menu' => new \Twig_Function_Method($this, 'renderMenu', array('is_safe' => array('html'))),
            'zenstruck_breadcrumbs' => new \Twig_Function_Method($this, 'renderBreadcrumbs', array('is_safe' => array('html')))
        );
    }

    public function renderWidget($name)
    {
        if ($widget = $this->dashboard->getWidget($name)) {
            return $this->environment->render($this->dashboard->getFullTemplateName('_widget.html.twig'), array(
                    'widget' => $widget,
                    'dashboard' => $this->dashboard
                ));
        }

        return null;
    }

    public function renderMenu()
    {
        return $this->environment->render($this->dashboard->getFullTemplateName('_menu.html.twig'), array(
                'dashboard' => $this->dashboard
            ));
    }

    public function renderBreadcrumbs()
    {
        return $this->environment->render($this->dashboard->getFullTemplateName('_breadcrumbs.html.twig'), array(
                'items' => $this->dashboard->getBreadcrumbs()
            ));
    }
}