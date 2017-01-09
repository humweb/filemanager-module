<?php

namespace Humweb\Filemanager;

use Illuminate\Support\Facades\Storage;
use League\Flysystem\Plugin\ListWith;

/**
 * Manager.
 */
class Manager
{
    /**
     * @var \Illuminate\Support\Facades\Storage
     */
    protected $storage;

    protected $excludedDirectories = ['thumbs'];
    protected $excludedFiles = [];
    protected $imageExtensions = ['png', 'jpg', 'gif', 'jpeg'];

    /**
     * Manager constructor.
     *
     * @param string $disk
     */
    public function __construct($disk = null)
    {
        //$this->imageExtensions = Config::get('lfm.images_exts', $this->imageExtensions);
        $this->storage = Storage::disk($disk);
        $this->storage->addPlugin(new ListWith());
    }

    public function ls($dir = '/', $recursive = false)
    {
        $items = $this->storage->listWith(['mimetype', 'size', 'timestamp'], $dir, $recursive);

        $files = [];
        $directories = [];

        foreach ($items as $item) {
            // Directories
            if ($item['type'] === 'dir' && !$this->shouldExcludeDir($item['basename'])) {
                $directories[] = $item;
            }
            // Files
            else {
                $files[] = $item;
            }
        }

        unset($items);

        return compact('files', 'directories');
    }

    public function mkdir($dir)
    {
        return $this->storage->makeDirectory($dir);
    }

    public function rmdir($dir)
    {
        return $this->storage->deleteDirectory($dir);
    }

    /**
     * @param string|array $file
     *
     * @return mixed
     */
    public function rm($file)
    {
        return $this->storage->delete($file);
    }

    public function cp($from, $to)
    {
        return $this->storage->copy($from, $to);
    }

    public function mv($from, $to)
    {
        return $this->storage->move($from, $to);
    }

    public function write($file, $contents = '')
    {
        return $this->storage->put($file, $contents);
    }

    public function read($file)
    {
        return $this->storage->get($file);
    }

    public function exists($file)
    {
        return $this->storage->exists($file);
    }

    public function has($file)
    {
        return $this->storage->has($file);
    }

    public function size($file)
    {
        return $this->storage->size($file);
    }

    public function lastModified($file)
    {
        return $this->storage->lastModified($file);
    }

    protected function isImage($ext)
    {
        return in_array($ext, $this->imageExtensions);
    }

    protected function shouldExcludeDir($dir = '')
    {
        if (!empty($this->excludedDirectories)) {
            return in_array($dir, $this->excludedDirectories);
        }

        return false;
    }

    public function __call($method, array $arguments)
    {
        return call_user_func_array([$this->storage, $method], $arguments);
    }
}
