<?php

namespace Zenstruck\Bundle\DashboardBundle\Dashboard;

use Knp\Menu\MenuItem;
use Knp\Menu\Silex\RouterAwareFactory;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class DashboardManager
{
    protected $config;
    protected $urlGenerator;
    protected $securityContext;

    /** @var MenuItem */
    protected $menu;

    public function __construct($config, UrlGeneratorInterface $urlGenerator, SecurityContextInterface $securityContext)
    {
        $this->config = $config;
        $this->urlGenerator = $urlGenerator;
        $this->securityContext = $securityContext;
    }

    public function getLayout()
    {
        return $this->config['layout'];
    }

    public function getTitle()
    {
        return $this->config['title'];
    }

    public function getWidgets($group = null)
    {
        if (!$group) {
            return $this->config['widgets'];
        }

        return array_filter($this->config['widgets'], function($item) use ($group) {
                return $group === $item['group'];
            });
    }

    /**
     * @param $group
     *
     * @return \Knp\Menu\MenuItem
     */
    public function getMenu($group = null)
    {
        $menu = $this->buildMenu();

        if (!$group) {
            return $menu;
        }

        $menu = array_filter($menu->getChildren(), function(MenuItem $item) use ($group) {
                return $group === $item->getExtra('group');
            });

        return $menu;
    }

    public function getMenuForSection($section)
    {
        $menu = $this->buildMenu()->getChild($section);

        if ($menu) {
            return $menu->getChildren();
        }

        return array();
    }

    /**
     * @return \Knp\Menu\MenuItem
     */
    protected function buildMenu()
    {
        if ($this->menu) {
            return $this->menu;
        }

        $menu = new MenuItem('root', new RouterAwareFactory($this->urlGenerator));

        foreach ($this->config['menu'] as $sectionName => $section) {
            if ($label = $section['label']) {
                $sectionName = $label;
            }

            $nested = true;

            if ($section['nested']) {
                $subMenu = $menu->addChild($sectionName);
                $subMenu->setLabel($this->parseText($sectionName));
                $subMenu->setExtra('group', $section['group']);

                if ($icon = $section['icon']) {
                    $subMenu->setExtra('icon', $icon);
                }
            } else {
                $nested = false;
                $subMenu = $menu;
            }

            foreach ($section['items'] as $itemName => $item) {
                if ($label = $item['label']) {
                    $itemName = $label;
                }

                // security check
                if ($item['role'] && $this->securityContext->getToken() && !$this->securityContext->isGranted($item['role'])) {
                    continue;
                } else {
                    $menuItem = $subMenu->addChild($itemName, $item);
                    $menuItem->setExtra('group', $section['group']);
                    $menuItem->setLabel($this->parseText($itemName));

                    if (!$nested) {
                        $menuItem->setExtra('flat', true);
                    }

                    if ($icon = $item['icon']) {
                        $menuItem->setExtra('icon', $icon);
                    }
                }
            }

            // remove empty sections
            if ($nested && !count($subMenu->getChildren())) {
                $menu->removeChild($sectionName);
            }
        }

        return $this->menu = $menu;
    }

    protected function parseText($text)
    {
        $context = $this;

        // check for {{foo}} or {{foo:bar}} syntax
        $text = preg_replace_callback('/{{(\w+)(:(\w+))?}}/', function($matches) use ($context) {
                // create getter
                $method = 'get'.ucfirst($matches[1]);

                $ret = $context->$method();

                // check for {{foo:bar}} syntax
                if (isset($matches[3])) {
                    $method = $matches[3];

                    // if {{foo:bar}} syntax is used, call bar method
                    if (is_object($ret) && method_exists($ret, $method)) {
                        $ret = $ret->$method();
                    }
                }

                return (string) $ret;
            }, $text);

        return $text;
    }

    protected function getUser()
    {
        return $this->securityContext->getToken()->getUser();
    }
}