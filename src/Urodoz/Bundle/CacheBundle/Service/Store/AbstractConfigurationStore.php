<?php

/*
 * This file is part of the UrodozCacheManager bundle.
 *
 * (c) Albert Lacarta <urodoz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Urodoz\Bundle\CacheBundle\Service\Store;

use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractConfigurationStore
{

    /**
     * @Assert\Type("string")
     * @Assert\Ip()
     * @Assert\NotBlank()
     */
    protected $host;

    /**
     * @Assert\Type("integer")
     * @Assert\NotBlank()
     * @Assert\Range(min=1,max=65535)
     */
    protected $port;

    /**
     * Inits the configuration object
     *
     * @param string  $host
     * @param integer $port
     */
    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * Returns the host
     *
     * @return string
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Returns the port
     *
     * @return integer
     */
    public function getHost()
    {
        return $this->host;
    }

}
