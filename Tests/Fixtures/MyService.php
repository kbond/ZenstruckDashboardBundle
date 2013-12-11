<?php

namespace Zenstruck\Bundle\DashboardBundle\Tests\Fixtures;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class MyService
{
    public function __toString()
    {
        return '__toString';
    }

    public function bar()
    {
        return 'bar';
    }

    public function getBar()
    {
        return 'getBar';
    }

    public function getBaz()
    {
        return 'getBaz';
    }
}
