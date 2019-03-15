<?php

namespace Humweb\Filemanager\Controllers;

use Humweb\Core\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

/**
 * Class RenameController.
 */
class RenameController extends AdminController
{
    protected $file_location;


    public function __construct()
    {
        $this->file_location = Config::get('lfm.files_dir');
    }


    /**
     * @return string
     */
    public function getRename()
    {
        $file_to_rename = Input::get('file');
        $dir            = Input::get('dir');
        $new_name       = Str::slug(Input::get('new_name'));

        if ($dir == '/') {
            if (File::exists(base_path().'/'.$this->file_location.$new_name)) {
                return 'File name already in use!';
            } else {
                if (File::isDirectory(base_path().'/'.$this->file_location.$file_to_rename)) {
                    File::move(base_path().'/'.$this->file_location.$file_to_rename,
                        base_path().'/'.$this->file_location.$new_name);

                    return 'OK';
                } else {
                    $extension = File::extension(base_path().'/'.$this->file_location.$file_to_rename);
                    $new_name  = Str::slug(str_replace($extension, '', $new_name)).'.'.$extension;

                    File::move(base_path().'/'.$this->file_location.$file_to_rename,
                        base_path().'/'.$this->file_location.$new_name);

                    if (Session::get('lfm_type') == 'Images') {
                        // rename thumbnail
                        File::move(base_path().'/'.$this->file_location.'thumbs/'.$file_to_rename,
                            base_path().'/'.$this->file_location.'thumbs/'.$new_name);
                    }

                    return 'OK';
                }
            }
        } else {
            if (File::exists(base_path().'/'.$this->file_location.$dir.'/'.$new_name)) {
                return 'File name already in use!';
            } else {
                if (File::isDirectory(base_path().'/'.$this->file_location.$dir.'/'.$file_to_rename)) {
                    File::move(base_path().'/'.$this->file_location.$dir.'/'.$file_to_rename,
                        base_path().'/'.$this->file_location.$dir.'/'.$new_name);
                } else {
                    $extension = File::extension(base_path().'/'.$this->file_location.$dir.'/'.$file_to_rename);
                    $new_name  = Str::slug(str_replace($extension, '', $new_name)).'.'.$extension;

                    File::move(base_path().'/'.$this->file_location.$dir.'/'.$file_to_rename,
                        base_path().'/'.$this->file_location.$dir.'/'.$new_name);

                    if (Session::get('lfm_type') == 'Images') {
                        File::move(base_path().'/'.$this->file_location.$dir.'/thumbs/'.$file_to_rename,
                            base_path().'/'.$this->file_location.$dir.'/thumbs/'.$new_name);
                    }

                    return 'OK';
                }
            }
        }
    }
}
