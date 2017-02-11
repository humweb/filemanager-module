<div class="container">
    <div class="row">

        @if((sizeof($files) > 0) || (sizeof($directories) > 0))

            @foreach($directories as $key => $dir)
                <div class="col-sm-4 col-md-2">
                    <div class="thumbnail text-center" data-id="{{ basename($dir) }}">
                        <a id="large_folder_{{ $key }}" data-id="{{ $dir }}"
                           class="action-folder folder-icon pointer">
                            <img src="/vendor/filemanager/img/folder.jpg">
                        </a>
                    </div>
                    <div class="caption text-center">
                        <div class="btn-group">
                            <button type="button" class="action-folder btn btn-default btn-xs">
                                {{ str_limit(basename($dir), $limit = 10, $end = '...') }}
                            </button>
                            <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown">
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

            @foreach($file_info as $key => $file)

                <div class="col-sm-4 col-md-2 img-row">

                    <div class="thumbnail thumbnail-img text-center" style="border: none;" data-select-file="{{ basename($file['name']) }}" data-id="{{ basename($file['name']) }}" id="img_thumbnail_{{ $key }}">
                        <i class="fa <?= $file['icon']; ?> fa-5x"></i>
                    </div>

                    <div class="caption text-center">
                        <div class="btn-group ">
                            <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown">
                                {{ str_limit(basename($file['name'])) }} <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#" data-rename="{{ basename($file['name']) }}">{{ trans('filemanager::labels.rename') }}</a></li>
                                <li><a href="#" data-view="{{ basename($file['name']) }}">{{ trans('filemanager::labels.view') }}</a></li>
                                <li><a href="#" data-download="{{ basename($file['name']) }}">{{ trans('filemanager::labels.download') }}</a></li>
                                <li class="divider"></li>
                                {{--<li><a href="javascript:notImp()">Rotate</a></li>--}}
                                <li><a href="#" data-resize="{{ basename($file['name']) }}">{{ trans('filemanager::labels.resize') }}</a></li>
                                <li><a href="#" data-crop="{{ basename($file['name']) }}">{{ trans('filemanager::labels.crop') }}</a></li>
                                <li class="divider"></li>
                                <li><a href="#" data-trash="{{ basename($file['name']) }}">{{ trans('filemanager::labels.delete') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            @endforeach

        @else
            <div class="col-md-12">
                <p>{{ trans('filemanager::labels.empty_folder') }}</p>
            </div>
        @endif

    </div>
</div>
