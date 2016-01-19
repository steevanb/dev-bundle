<?php

namespace steevanb\DevBundle\Cache;

use Doctrine\Common\Cache\CacheProvider;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class SessionCache extends CacheProvider
{
    /** @var SessionInterface */
    protected $session;

    /** @var KernelInterface */
    protected $kernel;

    /** @var array */
    protected $mappingPaths = array();

    /**
     * @param SessionInterface $session
     * @param KernelInterface $kernel
     */
    public function __construct(SessionInterface $session, KernelInterface $kernel)
    {
        $this->session = $session;
        $this->kernel = $kernel;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function addPathToScan($path)
    {
        if (in_array($path, $this->mappingPaths) === false) {
            $this->mappingPaths[] = $path;
        }

        return $this;
    }

    /**
     * @param string $bundle
     * @return $this
     */
    public function addBundleToScan($bundle)
    {
        $path = $this->kernel->getBundle($bundle)->getPath();
        $path .= DIRECTORY_SEPARATOR . 'Resources';
        $path .= DIRECTORY_SEPARATOR . 'config';
        $path .= DIRECTORY_SEPARATOR . 'doctrine';
        $this->addPathToScan($path);

        return $this;
    }

    /**
     * @return bool
     */
    public function refresh()
    {
        $lastRefresh = $this->getLastRefresh();
        if ($lastRefresh === null) {
            $this->flushAll();

            return true;
        }
        $lastRefreshTimestamp = $lastRefresh->format('U');

        foreach ($this->mappingPaths as $path) {
            if (is_dir($path)) {
                $finder = new Finder();
                foreach ($finder->in($path)->files() as $file) {
                    if (filemtime($file) >= $lastRefreshTimestamp) {
                        $this->flushAll();

                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @return $this
     */
    public function defineLastRefresh()
    {
        $this->session->set('session-cache-last-refresh', new \DateTime());

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastRefresh()
    {
        return $this->session->get('session-cache-last-refresh');
    }

    /**
     * @param string $id
     * @return string
     */
    protected function getSessionId($id)
    {
        return 'session-cache_' . $id;
    }

    /**
     * {@inheritdoc}
     */
    protected function doFetch($id)
    {
        return $this->session->get($this->getSessionId($id), false);
    }

    /**
     * {@inheritdoc}
     */
    protected function doContains($id)
    {
        return $this->session->has($this->getSessionId($id));
    }

    /**
     * {@inheritdoc}
     */
    protected function doSave($id, $data, $lifeTime = 0)
    {
        $this->session->set($this->getSessionId($id), $data);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function doDelete($id)
    {
        $this->session->remove($this->getSessionId($id));

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function doFlush()
    {
        $sessionPrefix = $this->getSessionId(null);
        $sessionPrefixLength = strlen($sessionPrefix);
        foreach (array_keys($this->session->all()) as $name) {
            if (substr($name, 0, $sessionPrefixLength) == $sessionPrefix) {
                $this->session->remove($name);
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function doGetStats()
    {
        return null;
    }
}

