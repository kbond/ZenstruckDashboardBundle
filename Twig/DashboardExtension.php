<?php

namespace Zenstruck\Bundle\DashboardBundle\Twig;

use Zenstruck\Bundle\DashboardBundle\Dashboard\DashboardManager;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class DashboardExtension extends \Twig_Extension
{
    protected $dashboard;

    public function __construct(DashboardManager $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('zenstruck_widget', [$this, 'renderWidget'], array('needs_environment' => true, 'is_safe' => array('html'))),
            new \Twig_SimpleFunction('zenstruck_menu', [$this, 'renderMenu'], array('needs_environment' => true, 'is_safe' => array('html'))),
            new \Twig_SimpleFunction('zenstruck_breadcrumbs', [$this, 'renderBreadcrumbs'], array('needs_environment' => true, 'is_safe' => array('html'))),
        );
    }

    public function renderWidget(\Twig_Environment $environment, $name)
    {
        if ($widget = $this->dashboard->getWidget($name)) {
            return $environment->render($this->dashboard->getFullTemplateName('_widget.html.twig'), array(
                    'widget' => $widget,
                    'dashboard' => $this->dashboard
                ));
        }

        return null;
    }

    public function renderMenu(\Twig_Environment $environment)
    {
        return $environment->render($this->dashboard->getFullTemplateName('_menu.html.twig'), array(
                'dashboard' => $this->dashboard
            ));
    }

    public function renderBreadcrumbs(\Twig_Environment $environment)
    {
        return $environment->render($this->dashboard->getFullTemplateName('_breadcrumbs.html.twig'), array(
                'items' => $this->dashboard->getBreadcrumbs()
            ));
    }
}
