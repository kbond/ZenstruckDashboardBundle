<?php

namespace Zenstruck\Bundle\DashboardBundle\Tests\Dashboard;

use Knp\Menu\MenuFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Zenstruck\Bundle\DashboardBundle\Dashboard\DashboardManager;
use Zenstruck\Bundle\DashboardBundle\Tests\Fixtures\MyService;
use Zenstruck\Bundle\DashboardBundle\DependencyInjection\Configuration;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class DashboardManagerTest extends TestCase
{
    public function testGetFullTemplateName()
    {
        $manager = $this->getManager();

        $this->assertEquals('@ZenstruckDashboard/Bootstrap2/_foo.html.twig', $manager->getFullTemplateName('_foo.html.twig'));
    }

    public function testGetMenu()
    {
        $manager = $this->getManager();

        $menu = $manager->getMenu();
        $this->assertInstanceOf('Knp\Menu\MenuItem', $menu);
        $this->assertCount(5, $menu->getChildren());

        $menu = $manager->getMenu('primary');
        $this->assertCount(4, $menu);
        $this->assertArrayHasKey('item1', $menu);

        $menu = $manager->getMenu('secondary');
        $this->assertCount(1, $menu);
        $this->assertArrayHasKey('dropdown2', $menu);

        $menu = $manager->getMenuForSection('dropdown2');
        $this->assertCount(2, $menu);
        $this->assertArrayHasKey('item5', $menu);
    }

    public function testCallService()
    {
        $children = $this->getManager()->getMenu()->getChildren();

        $this->assertEquals('foo __toString', $children['item1']->getLabel());
        $this->assertEquals('foo bar', $children['item2']->getLabel());
        $this->assertEquals('foo getBaz', $children['item3']->getLabel());
    }

    protected function getManager()
    {
        $authChecker = $this->createMock(AuthorizationCheckerInterface::class);

        $manager = new DashboardManager($this->getConfig(), new MenuFactory(), $authChecker);
        $manager->registerService('myservice', new MyService());

        return $manager;
    }

    protected function getConfig()
    {
        $menu = array(
            'menu' => array(
                'flat' => array(
                    'nested' => false,
                    'items' => array(
                        'item1' => array(
                            'label' => 'foo {{myservice}}',
                            'uri' => 'http://google.com'
                        ),
                        'item2' => array(
                            'label' => 'foo {{myservice:bar}}',
                            'uri' => 'http://google.com'
                        ),
                        'item3' => array(
                            'label' => 'foo {{myservice:baz}}',
                            'uri' => 'http://google.com'
                        )
                    )
                ),
                'dropdown1' => array(
                    'label' => 'Dropdown1',
                    'items' => array(
                        'item4' => array(
                            'uri' => 'http://google.com'
                        )
                    )
                ),
                'dropdown2' => array(
                    'label' => 'Dropdown2',
                    'group' => 'secondary',
                    'items' => array(
                        'item5' => array(
                            'uri' => 'http://google.com'
                        ),
                        'item6' => array(
                            'uri' => 'http://google.com'
                        )
                    )
                )
            )
        );

        $processor = new Processor();

        return $processor->processConfiguration(new Configuration(), array($menu));
    }
}
