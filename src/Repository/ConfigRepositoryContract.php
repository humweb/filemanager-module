<?php

namespace Humweb\Filemanager\Repository;

/**
 * Config.
 */
interface ConfigRepositoryContract
{
    /**
     * ConfigRepository constructor.
     *
     * @param array  $disks
     * @param string $default
     * @param string $cloud
     */
    public function __construct(array $disks, $default, $cloud);


    /**
     * @return array
     */
    public function getDisks();


    /**
     * @param $name
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getDisk($name);


    /**
     * @return string
     */
    public function getDefault();


    /**
     * @return string
     */
    public function getCloud();


    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasDisk($name);
}
