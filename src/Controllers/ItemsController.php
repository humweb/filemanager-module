<?php

namespace Humweb\Filemanager\Controllers;

use Humweb\Core\Http\Controllers\AdminController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;

/**
 * Class ItemsController.
 */
class ItemsController extends AdminController
{
    /**
     * @var
     */
    protected $file_location;
    protected $image_exts = [];


    /**
     * constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->file_location = config('filemanager.files_dir');
        $this->image_exts    = config('filemanager.image_extensions', []);
    }


    /**
     * Return json list of files.
     *
     * @return mixed
     */
    public function getFiles()
    {
        if (Input::has('base')) {
            $basePath = base_path($this->file_location.Input::get('base'));
        } else {
            $basePath = base_path($this->file_location);
        }

        $files           = File::files($basePath);
        $all_directories = File::directories($basePath);

        $directories = [];

        foreach ($all_directories as $directory) {
            if (basename($directory) != 'thumbs') {
                $directories[] = basename($directory);
            }
        }

        $file_info  = [];
        $icon_array = config('filemanager.file_icon_array');
        $type_array = config('filemanager.file_type_array');

        foreach ($files as $file) {
            $file_name = $file;
            $file_size = 1;
            $extension = strtolower(File::extension($file_name));
            $icon      = 'fa-file';
            if ($this->isImage($extension)) {
                $file_type = Image::make($file)->mime();
            } else {
                $file_type = '';
                if (array_key_exists($extension, $icon_array)) {
                    $icon      = $icon_array[$extension];
                    $file_type = $type_array[$extension];
                } else {
                    $icon      = 'fa-file';
                    $file_type = 'File';
                }
            }

            $file_created = filemtime($file);
            $file_type    = '';
            $file_info[]  = [
                'name'      => $file_name,
                'size'      => $file_size,
                'created'   => $file_created,
                'extension' => $extension,
                'icon'      => $icon,
                'type'      => $file_type,
            ];
        }

        if (Input::get('show_list') == 1) {
            return view('filemanager::admin.files-list')
                ->with('directories', $directories)
                ->with('base', Input::get('base'))
                ->with('file_info', $file_info)
                ->with('dir_location', $this->file_location);
        } else {
            return View::make('filemanager::admin.files')
                       ->with('files', $files)
                       ->with('directories', $directories)
                       ->with('base', Input::get('base'))
                       ->with('file_info', $file_info)
                       ->with('dir_location', $this->file_location);
        }
    }


    protected function isImage($ext)
    {
        return in_array($ext, $this->image_exts);
    }


    /**
     * Get the images to load for a selected folder.
     *
     * @return mixed
     */
    public function getImages()
    {
        if (Input::has('base')) {
            $files           = File::files(base_path($this->file_location.Input::get('base')));
            $all_directories = File::directories(base_path($this->file_location.Input::get('base')));
        } else {
            $files           = File::files(base_path($this->file_location));
            $all_directories = File::directories(base_path($this->file_location));
        }

        $directories = [];

        foreach ($all_directories as $directory) {
            if (basename($directory) != 'thumbs') {
                $directories[] = basename($directory);
            }
        }

        $file_info = [];

        foreach ($files as $file) {
            $file_name = $file;
            $file_size = number_format((Image::make($file)->filesize() / 1024), 2, '.', '');
            if ($file_size > 1000) {
                $file_size = number_format((Image::make($file)->filesize() / 1024), 2, '.', '').' Mb';
            } else {
                $file_size = $file_size.' Kb';
            }
            $file_created = filemtime($file);
            $file_type    = Image::make($file)->mime();
            $file_info[]  = [
                'name'    => $file_name,
                'size'    => $file_size,
                'created' => $file_created,
                'type'    => $file_type,
            ];
        }

        if ((Session::has('lfm_type')) && (Session::get('lfm_type') == 'Images')) {
            $dir_location = config('filemanager.images_url');
        } else {
            $dir_location = config('filemanager.files_url');
        }

        if (Input::get('show_list') == 1) {
            return View::make('lfm.images-list')
                       ->with('directories', $directories)
                       ->with('base', Input::get('base'))
                       ->with('file_info', $file_info)
                       ->with('dir_location', $dir_location);
        } else {
            return View::make('lfm.images')
                       ->with('files', $files)
                       ->with('directories', $directories)
                       ->with('base', Input::get('base'))
                       ->with('dir_location', $dir_location);
        }
    }
}
