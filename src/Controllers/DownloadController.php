<?php

namespace Humweb\Filemanager\Controllers;

use Humweb\Core\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

/**
 * Class DownloadController.
 */
class DownloadController extends AdminController
{
    /**
     * @var
     */
    protected $file_location;


    /**
     * constructor.
     */
    public function __construct()
    {
        $this->file_location = Config::get('lfm.files_dir');
    }


    /**
     * Download a file.
     *
     * @return mixed
     */
    public function getDownload()
    {
        $file_to_download = Input::get('file');
        $dir              = Input::get('dir');

        return Response::download(base_path().'/'.$this->file_location.$dir.'/'.$file_to_download);
    }
}
