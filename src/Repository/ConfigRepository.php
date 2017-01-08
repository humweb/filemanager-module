<?php

namespace Humweb\Filemanager\Repository;

/**
 * Config.
 */
class ConfigRepository implements ConfigRepositoryContract
{
    protected $disks = [];
    protected $default;
    protected $cloud;


    /**
     * ConfigRepository constructor.
     *
     * @param array  $disks
     * @param string $default
     * @param string $cloud
     */
    public function __construct(array $disks = [], $default = 'local', $cloud = 's3')
    {
        $this->disks   = $disks;
        $this->default = $default;
        $this->cloud   = $cloud;
    }


    /**
     * @return array
     */
    public function getDisks()
    {
        return $this->disks;
    }


    /**
     * @param      $name
     * @param bool $useDefault
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getDisk($name, $useDefault = true)
    {
        if ( ! $this->hasDisk($name)) {
            if ($useDefault) {
                return $this->disks[$this->getDefault()];
            }

            throw new \Exception('Disk '.$name.' not found.');
        }

        return $this->disks[$name];
    }


    public function hasDisk($name)
    {
        return isset($this->disks[$name]);
    }


    /**
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }


    /**
     * @return string
     */
    public function getCloud()
    {
        return $this->cloud;
    }
}
