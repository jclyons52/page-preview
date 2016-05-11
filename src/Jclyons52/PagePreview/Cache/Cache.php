<?php

namespace Jclyons52\PagePreview\Cache;

use Jclyons52\PagePreview\Preview;
use Psr\Cache\CacheItemPoolInterface;

class Cache
{
    private $pool;

    public function __construct(CacheItemPoolInterface $pool)
    {
        $this->pool = $pool;
    }

    public function get($key)
    {
        $item = $this->pool->getItem(md5($key));

        $preview = unserialize($item->get());

        if ($preview instanceof Preview) {
            return $preview;
        }
    }

    public function set(Preview $preview, $expiresAt = null)
    {
        $item = $this->pool->getItem(md5($preview->url));

        $item->set(serialize($preview));

        $item->expiresAt($this->getExpireTime($expiresAt));

        $this->pool->save($item);
        
        return $item;
    }


    /**
     * @param $expiresAt
     * @return \DateTime
     */
    private function getExpireTime($expiresAt)
    {
        if ($expiresAt instanceof \DateTime) {
            return $expiresAt;
        }

        $date = new \DateTime();

        if ($expiresAt instanceof \DateInterval) {
            $date->add($expiresAt);
            return $date;
        }

        if (is_numeric($expiresAt)) {
            $dateInterval = \DateInterval::createFromDateString(abs($expiresAt) . ' seconds');
            $date->add($dateInterval);
            return $date;
        }

        return $date->add(new \DateInterval('P1D'));
    }
}
