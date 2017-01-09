<ul class="list-unstyled">
    <li style="margin-left: -5px;">
        <a class="pointer action-root" id="folder_top" data-id="/">
            <i class="fa fa-folder-open" data-id="/"></i> Files
        </a>
    </li>
    @foreach($dirs as $key => $dir)
        <li>
            <a class="pointer action-folder" data-folder="large_folder_{{ $key }}" id="folder_{{ $key }}" data-id="{{ $dir }}">
                <i class="fa fa-folder folder-item" data-id="{{ $dir }}" id="{{ $dir }}-folder"></i> {{ $dir }}
            </a>
        </li>
    @endforeach
</ul>
