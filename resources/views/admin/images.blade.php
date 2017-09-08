<div class="container">
    <div class="row">

        @if((sizeof($files) > 0) || (sizeof($directories) > 0))

            @foreach($directories as $key => $dir)
                <div class="col-sm-6 col-md-2">
                    <div class="thumbnail text-center" data-id="{{ basename($dir) }}">
                        <a id="large_folder_{{ $key }}" data-folder="large_folder_{{ $key }}" data-id="{{ $dir }}"
                           class="folder-icon pointer">
                            <img src="/img/folder.jpg">
                        </a>
                    </div>
                    <div class="caption text-center">
                        <div class="btn-group">
                            <button type="button" data-folder="large_folder_{{ $key }}"
                                    class="btn btn-secondary btn-xs folder-open">
                                {{ str_limit(basename($dir), $limit = 10, $end = '...') }}
                            </button>
                            <button type="button" class="btn btn-secondary dropdown-toggle btn-xs" data-toggle="dropdown"
                                    aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="javascript:rename('{{ basename($dir) }}')">{{ trans('filemanager::labels.rename') }}</a></li>
                                <li><a href="javascript:trash('{{ basename($dir) }}')">{{ trans('filemanager::labels.delete') }}</a></li>
                            </ul>
                        </div>

                    </div>
                </div>
            @endforeach

            @foreach($files as $key => $file)

                <div class="col-sm-6 col-md-2 img-row">

                    <div class="thumbnail thumbnail-img" data-id="{{ basename($file) }}" id="img_thumbnail_{{ $key }}">
                        <img id="{{ $file }}" data-select-file="{{ basename($file) }}"
                             src="{{ $dir_location }}/thumbs/{{ basename($file) }}?r={{ str_random(40) }}"
                             alt="">
                    </div>

                    <div class="caption text-center">
                        <div class="btn-group ">
                            <button type="button" class="btn btn-secondary dropdown-toggle btn-xs" data-toggle="dropdown"
                                    aria-expanded="false">
                                Actions
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#" data-rename="{{ basename($file) }}">{{ trans('filemanager::labels.rename') }}</a></li>
                                <li><a href="#" data-view="{{ basename($file) }}">{{ trans('filemanager::labels.view') }}</a></li>
                                <li><a href="#" data-download="{{ basename($file) }}">{{ trans('filemanager::labels.download') }}</a></li>
                                <li class="divider"></li>
                                {{--<li><a href="javascript:notImp()">Rotate</a></li>--}}
                                <li><a href="#" data-resize="{{ basename($file) }}">{{ trans('filemanager::labels.resize') }}</a></li>
                                <li><a href="#" data-crop="{{ basename($file) }}">{{ trans('filemanager::labels.crop') }}</a></li>
                                <li class="divider"></li>
                                <li><a href="#" data-trash="{{ basename($file) }}">{{ trans('filemanager::labels.delete') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            @endforeach

        @else
            <div class="col-md-12">
                <p>Folder is empty.</p>
            </div>
        @endif

    </div>
</div>
