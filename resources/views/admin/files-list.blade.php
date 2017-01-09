<div class="container">
    @if((sizeof($file_info) > 0) || (sizeof($directories) > 0))
        <table class="table table-condensed table-striped">
            <thead>
            <tr>
                <th>{{ trans('filemanager::labels.item') }}</th>
                <th>{{ trans('filemanager::labels.size') }}</th>
                <th>{{ trans('filemanager::labels.type') }}</th>
                <th>{{ trans('filemanager::labels.modified') }}</th>
                <th>{{ trans('filemanager::labels.action') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($directories as $key => $dir)
                <tr>
                    <td>
                        <i class="fa fa-folder-o"></i>
                        <a id="large_folder_{{ $key }}" class="action-folder" data-id="{{ $dir }}">
                            {{ basename($dir) }}
                        </a>
                    </td>
                    <td></td>
                    <td>{{ trans('filemanager::labels.folder') }}</td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach

            @foreach($file_info as $file)
                <tr>
                    <td>
                        <i class="fa <?= $file['icon']; ?>"></i>
                        <a href="#" data-select-file="{{ basename($file['name']) }}">
                            {{ basename($file['name']) }}
                        </a>
                        &nbsp;&nbsp;
                        <a href="#" data-rename="{{ basename($file['name']) }}">
                            <i class="fa fa-edit"></i>
                        </a>
                    </td>
                    <td>
                        {{ $file['size'] }}
                    </td>
                    <td>
                        {{ $file['type'] }}
                    </td>
                    <td>
                        {{ date("Y-m-d h:m", $file['created']) }}
                    </td>
                    <td>
                        <a href="#" data-trash="{{ basename($file['name']) }}">
                            <i class="fa fa-trash fa-fw"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    @else
        <div class="col-md-12">
            <p>{{ trans('filemanager::labels.folder') }}</p>
        </div>
    @endif

</div>
