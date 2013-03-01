<?php

namespace Zenstruck\Bundle\DashboardBundle\Twig;

use Symfony\Component\Templating\EngineInterface;
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
            'zenstruck_widget' => new \Twig_Function_Method($this, 'renderWidget', array('is_safe' => array('html')))
        );
    }

    public function renderWidget($name)
    {
        if ($widget = $this->dashboard->getWidget($name)) {
            return $this->environment->render('ZenstruckDashboardBundle:Twitter:_widget.html.twig', array(
                    'widget' => $widget,
                    'dashboard' => $this->dashboard
                ));
        }

        return null;
    }
}