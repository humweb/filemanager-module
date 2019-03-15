<?php

namespace Humweb\Filemanager\Controllers;

use Humweb\Core\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

/**
 * Class LfmController.
 */
class MainController extends AdminController
{
    /**
     * @var
     */
    protected $file_location;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->file_location = Config::get('filemanager.files_dir');
    }


    /**
     * Show the filemanager.
     *
     * @return mixed
     */
    public function getIndex(Request $request)
    {
        if ($request->has('base')) {
            $working_dir = $request->get('base');
            $base        = $this->file_location.$request->get('base').'/';
        } else {
            $working_dir = '/';
            $base        = $this->file_location;
        }

        return view('filemanager::admin.index', [
            'file_type'   => $request->get('type', 'images'),
            'base'        => $base,
            'working_dir' => $working_dir
        ]);
    }
}
