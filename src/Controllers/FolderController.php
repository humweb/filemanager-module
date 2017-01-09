<?php

namespace Humweb\Filemanager\Controllers;

use Humweb\Core\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

/**
 * Class FolderController.
 */
class FolderController extends AdminController
{
    protected $file_location;


    public function __construct()
    {
        $this->file_location = config('filemanager.files_dir');
    }


    /**
     * Get list of folders as json to populate treeview.
     *
     * @return mixed
     */
    public function getFolders()
    {
        $directories = File::directories(base_path($this->file_location));
        $final_array = [];

        foreach ($directories as $directory) {
            if (basename($directory) != 'thumbs') {
                $final_array[] = basename($directory);
            }
        }

        return view('filemanager::admin.tree')->with('dirs', $final_array);
    }


    /**
     * Add a new folder.
     *
     * @return mixed
     */
    public function getAddfolder(Request $request)
    {
        $folder_name = Str::slug($request->get('name'));

        $path = base_path($this->file_location);

        if ( ! File::exists($path.'/'.$folder_name)) {
            File::makeDirectory($path.'/'.$folder_name, $mode = 0777, true, true);

            return 'OK';
        } else {
            return 'A folder with this name already exists!';
        }
    }
}
