<?php

namespace Zenstruck\Bundle\DashboardBundle\Twig;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Zenstruck\Bundle\DashboardBundle\Dashboard\DashboardManager;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class DashboardExtension extends AbstractExtension
{
    protected $dashboard;

    public function __construct(DashboardManager $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('zenstruck_widget', [$this, 'renderWidget'], array('needs_environment' => true, 'is_safe' => array('html'))),
            new TwigFunction('zenstruck_menu', [$this, 'renderMenu'], array('needs_environment' => true, 'is_safe' => array('html'))),
            new TwigFunction('zenstruck_breadcrumbs', [$this, 'renderBreadcrumbs'], array('needs_environment' => true, 'is_safe' => array('html'))),
        );
    }

    public function renderWidget(Environment $environment, $name)
    {
        if ($widget = $this->dashboard->getWidget($name)) {
            return $environment->render($this->dashboard->getFullTemplateName('_widget.html.twig'), array(
                    'widget' => $widget,
                    'dashboard' => $this->dashboard
                ));
        }

        return null;
    }

    public function renderMenu(Environment $environment)
    {
        return $environment->render($this->dashboard->getFullTemplateName('_menu.html.twig'), array(
                'dashboard' => $this->dashboard
            ));
    }

    public function renderBreadcrumbs(Environment $environment)
    {
        return $environment->render($this->dashboard->getFullTemplateName('_breadcrumbs.html.twig'), array(
                'items' => $this->dashboard->getBreadcrumbs()
            ));
    }
}
