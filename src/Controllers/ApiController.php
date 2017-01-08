<?php

namespace Humweb\Filemanager\Controllers;

use Humweb\Core\Http\Controllers\AdminController;
use Humweb\Filemanager\Manager as FileManager;
use Humweb\Filemanager\Repository\ConfigRepositoryContract;
use Humweb\Filemanager\Response;
use Illuminate\Http\Request;

/**
 * Class ItemsController.
 */
class ApiController extends AdminController
{
    /**
     * @var
     */
    protected $config;
    protected $disk;

    /**
     * @var Response
     */
    protected $response;


    /**
     * constructor.
     *
     * @param ConfigRepositoryContract $config
     * @param Request                  $request
     */
    public function __construct(ConfigRepositoryContract $config, Request $request, Response $response)
    {
        $this->config = $config;
        $disk         = $this->config->getDisk($request->get('disk', 'local'));

        $this->disk = new FileManager($disk['driver']);

        $this->response = $response;
    }


    /**
     * Return json list of files.
     *
     * @param Request $req
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getListing(Request $req)
    {
        $path = $req->get('dir');

        if ($this->disk->exists($path)) {
            $metadata = $this->disk->getMetadata($path);

            if ($metadata['type'] === 'file') {
                return $this->response->download($path, $this->disk);
            }

            return $this->response->ok($this->disk->ls($req->get('dir')));
        } else {
            return $this->response->error('Path does not exists.');
        }
    }


    public function missingMethod($path = '')
    {
        if ($this->disk->exists($path)) {
            $metadata = $this->disk->getMetadata($path);

            if ($metadata['type'] === 'file') {
                return $this->response->download($path, $this->disk);
            }

            return $this->response->ok($this->disk->ls($path));
        } else {
            return $this->response->error('Path does not exists.');
        }
    }
}
