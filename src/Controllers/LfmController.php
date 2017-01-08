<?php

namespace Humweb\Filemanager\Controllers;

use Humweb\Core\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;

/**
 * Class LfmController.
 */
class LfmController extends AdminController
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
        $this->file_location = Config::get('lfm.files_dir');
    }


    /**
     * Show the filemanager.
     *
     * @return mixed
     */
    public function show()
    {
        if (Input::has('base')) {
            $working_dir = Input::get('base');
            $base        = $this->file_location.Input::get('base').'/';
        } else {
            $working_dir = '/';
            $base        = $this->file_location;
        }

        return view('lfm.index')->with('base', $base)->with('working_dir', $working_dir);
    }
}
