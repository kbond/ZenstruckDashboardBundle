<?php

namespace Zenstruck\Bundle\DashboardBundle\Dashboard\Service;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
interface MenuProviderInterface
{
    /**
     * @return \Knp\Menu\MenuItem
     */
    public function getMenu();
}
