<?php

/*
 * This file is part of the UrodozCacheManager bundle.
 *
 * (c) Albert Lacarta <urodoz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Urodoz\Bundle\CacheBundle\Service\CacheItem;

use Urodoz\Bundle\CacheBundle\Exception\CorruptedDataException;
use Urodoz\Bundle\CacheBundle\Service\CacheItem\CacheItemStoreInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * This class represents an item to be stored on memcache
 * or retrieved. It contains the information, the expiration needed
 * for the persistent implementations to be expired with CacheManager
 * a hash of the real data an index information
 *
 * @author Albert Lacarta <urodoz@gmail.com>
 */
class CacheItemStore implements CacheItemStoreInterface
{

    /**
     * @Assert\Type("string")
     * @Assert\NotBlank()
     */
    protected $data;

    /**
     * @Assert\Type("integer")
     * @Assert\NotBlank()
     */
    protected $expirationStamp;

    /**
     * @Assert\Type("string")
     * @Assert\NotBlank()
     */
    protected $hash;

    /**
     * @Assert\Type("string")
     * @Assert\NotBlank()
     */
    protected $type;

    /**
     * {@inheritDoc}
     */
    public function createFromData($data, $expirationInSeconds)
    {
        $this->data = $data;
        $this->expirationStamp = time()+$expirationInSeconds;
        $this->hash = sha1($data);
        $this->type = gettype($this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function hydrateFromCacheData($cacheData)
    {
        //Decoding and checking
        $decodedData = @json_decode($cacheData, true);
        if(!$decodedData
                || !array_key_exists("data", $decodedData)
                || !array_key_exists("hash", $decodedData)
                || !array_key_exists("type", $decodedData)
                || !array_key_exists("expirationStamp", $decodedData)
                )
        {
            $errorMessage = "The data fetched from implementation its not valid."
                    . "JSON decode cannot be performed"
                    ;
            throw new CorruptedDataException($errorMessage);
        }

        $this->data = base64_decode($decodedData["data"]);
        $this->hash = $decodedData["hash"];
        $this->expirationStamp = $decodedData["expirationStamp"];

        //Hash and expiration check to nullate data
        if(sha1($this->data)!=$this->hash) $this->data = null;
        if(time()>=$this->expirationStamp) $this->data = null;

        settype($this->data, $decodedData["type"]);
    }

    /**
     * {@inheritDoc}
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritDoc}
     */
    public function getCacheData()
    {
        return json_encode(array(
            /*
             * Encoding the information in base64 allows
             * to store any kind on information , even binary
             * as safe information but with more memory occupied
             * TODO : Make it optional (or detect binary strings to encode it)
             */
            "data" => base64_encode($this->data),
            /*
             * The expiration is stored on the implementation due to
             * the finally target to use multiple sources as cache systems
             * (more than only Memcache and Redis), this sources could be
             * not volatile, making it necessary to expirate the information
             * with the Cache Manager service after fetch
             */
            "expirationStamp" => $this->expirationStamp,
            /*
             * The hash is needed to verify the information
             */
            "hash" => $this->hash,
            "type" => $this->type,
        ));
    }

}
