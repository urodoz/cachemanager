<?php

/*
 * This file is part of the UrodozCacheManager bundle.
 *
 * (c) Albert Lacarta <urodoz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Urodoz\Bundle\CacheBundle\Tests\Service\Mocks;

use Urodoz\Bundle\CacheBundle\Event\UpdateCacheKeyEvent;

/**
 * Class used with test purposes by CacheManagerTest
 *
 * This class allows to have the prefix of the keys
 * injected directly on the class and used by the cacheManager
 * implementation test the misspoint to keys adter a prefix
 * change
 *
 * @author Albert Lacarta <urodoz@gmail.com>
 */
class PrefixGenerator
{

    /**
     * @var string
     */
    private $prefix="foo";

    public function onCacheKeyUpdate(UpdateCacheKeyEvent $event)
    {
        $event->addPrefix($this->prefix);
    }

    public function setPrefixUsed($newPrefix)
    {
        $this->prefix = $newPrefix;
    }

}
