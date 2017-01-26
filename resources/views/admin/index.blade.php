<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Manager</title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/vendor/filemanager/css/cropper.min.css">
    <link rel="stylesheet" href="/vendor/filemanager/css/filemanager.css">
    {{--<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.css">--}}
</head>
<body>
<div class="container">
    <div class="row fill">
        <div class="panel panel-default fill">
            <div class="panel-heading">
                <h3 class="panel-title">FileManager</h3>
            </div>
            <div class="panel-body fill">
                <div class="row fill">
                    <div class="wrapper fill">
                        <div class="col-md-2 col-lg-2 col-sm-2 col-xs-2 left-nav fill" id="lfm-leftcol">
                            <div id="tree1">
                            </div>
                            <a href="#!" id="add-folder" class="add-folder btn btn-default btn-xs"><i class="fa fa-plus"></i> {{ trans('filemanager::labels.new_folder') }}
                            </a>
                        </div>
                        <div class="col-md-10 col-lg-10 col-sm-10 col-xs-10 right-nav" id="right-nav">
                            <nav class="navbar navbar-default">
                                <div class="container-fluid">
                                    <div class="navbar-header">
                                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                                data-target="#bs-example-navbar-collapse-1">
                                            <span class="sr-only">Toggle navigation</span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                        </button>
                                    </div>
                                    <div class="collapse navbar-collapse">
                                        <ul class="nav navbar-nav" id="nav-buttons">
                                            <li>
                                                <a href="#!" id="upload" data-toggle="modal" data-target="#uploadModal"><i
                                                            class="fa fa-upload"></i> {{ trans('filemanager::labels.upload') }}</a>
                                            </li>
                                            <li>
                                                <a href="#!" class="thumbnail-display" id="thumbnail-display"><i
                                                            class="fa fa-picture-o"></i> {{ trans('filemanager::labels.thumbnails') }}</a>
                                            </li>
                                            <li>
                                                <a href="#!" class="list-display" id="list-display"><i
                                                            class="fa fa-list"></i> {{ trans('filemanager::labels.list') }}</a>
                                            </li>
                                            <li>
                                                <a href="#!" class="reload-files" id="list-display"><i
                                                            class="fa fa-refresh"></i> Reload</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </nav>

                            @if ($errors->any())
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
                                            </button>
                                            <ul>
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div id="content" class="row fill">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Upload File</h4>
            </div>
            <div class="modal-body">
                {{ Form::open(array('url' => '/admin/filemanager/upload', 'role' => 'form', 'name' => 'uploadForm',
                'id' => 'uploadForm', 'method' => 'post', 'enctype' => 'multipart/form-data')) }}
                <div class="form-group" id="attachment">
                    {{ Form::label('file_to_upload', 'Choose File', array('class' => 'control-label')) }}
                    <div class="controls">
                        <div class="input-group" style="width: 100%">
                            <input type="file" id="file_to_upload" name="file_to_upload">
                        </div>
                    </div>
                </div>
                {{ Form::hidden('working_dir', $working_dir, ['id' => 'working_dir']) }}
                {{ Form::hidden('show_list', 0, ['id' => 'show_list']) }}
                {{ Form::close() }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="upload-btn">Upload File</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="fileViewModal" tabindex="-1" role="dialog" aria-labelledby="fileLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="fileLabel">View File</h4>
            </div>
            <div class="modal-body" id="fileview_body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.3.0/bootbox.js"></script>
{{--<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>--}}
<script src="{{ asset('vendor/filemanager/js/cropper.min.js') }}"></script>
<script src="/vendor/filemanager/js/jquery.form.min.js"></script>
<script>
    $(document).ready(function () {
        // load folders
        $.ajax({
            type: "GET",
            dataType: "text",
            url: "/admin/filemanager/folders",
            data: "base={{ $working_dir }}",
            cache: false
        }).done(function (data) {
            $("#tree1").html(data);
        });
        var $win = $(document),
            $btnReload = $('.reload-files');


        $("#upload-btn").click(function () {

            $("#uploadForm").ajaxSubmit({
                beforeSubmit: function (formData, jqForm, options) {
                    $("#upload-btn").html('<i class="fa fa-refresh fa-spin"></i> Uploading...');
                    return true;
                },
                success: function (responseText, statusText, xhr, $form) {
                    $("#uploadModal").modal('hide');
                    $("#upload-btn").html('Upload File...');
                    if (responseText != "OK") {
                        notify('File uploaded.');
                    }
                    $("#file_to_upload").val('');
                    $btnReload.trigger('click');
                }
            });
            return false;
        });

        $win
            .on('click', 'action-root', function () {
                $('.folder-item').removeClass('fa-folder-open').addClass('fa-folder');
                $("#working_dir").val('/');
                $btnReload.trigger('click');
            })

            .on('click', '[data-folder]', function (x, y) {
                var $this = $(this),
                    id = $this.data('folder');
                console.log(id);
                $('.folder-item').addClass('fa-folder');
                $('.folder-item').not("#folder_top > i").removeClass('fa-folder-open');
                if (!$this.hasClass('folder-open')) {
                    if ($('#' + id + ' > i').hasClass('fa-folder')) {
                        $('#' + id + ' > i').not("#folder_top > i").removeClass('fa-folder');
                        $('#' + id + ' > i').not("#folder_top > i").addClass('fa-folder-open');
                    } else {
                        $('#' + id + ' > i').removeClass('fa-folder-open');
                        $('#' + id + ' > i').addClass('fa-folder');
                    }
                }
                $("#working_dir").val($('#' + id).data('id'));
                $btnReload.trigger('click');
            })

            .on('click', '[data-download]', function () {
                location.href = "/admin/filemanager/download?dir=" + $("#working_dir").val() + "&file=" + $(this).data('download');
            })

            @if ((Session::has('lfm_type')) && (Session::get('lfm_type') == "Images"))
                .on('click', '.reload-files', function () {
            console.log('REALODING!!!');
            $.ajax({
                type: "GET",
                dataType: "html",
                url: "/admin/filemanager/jsonimages",
                data: {
                    base: $("#working_dir").val(),
                    show_list: $("#show_list").val()
                },
                cache: false
            }).done(function (data) {
                $("#content").html(data);
                $("#nav-buttons").removeClass("hidden");
                $(".dropdown-toggle").dropdown();
                refreshFolders();
            });
        })
            @else
                .on('click', '.reload-files', function () {
            console.log('REALODING!!!');
            $.ajax({
                type: "GET",
                dataType: "html",
                url: "/admin/filemanager/jsonfiles",
                data: {
                    base: $("#working_dir").val(),
                    show_list: $("#show_list").val()
                },
                cache: false
            }).done(function (data) {
                $("#content").html(data);
                $("#nav-buttons").removeClass("hidden");
                $(".dropdown-toggle").dropdown();
                refreshFolders();
            });
        })
            @endif
            .on('click', '[data-trash]', function () {
            var x = $(this).data('trash');
            bootbox.confirm("Are you sure you want to delete this item?", function (result) {
                if (result == true) {
                    $.ajax({
                        type: "GET",
                        dataType: "text",
                        url: "/admin/filemanager/delete",
                        data: {
                            base: $("#working_dir").val(),
                            items: x
                        },
                        cache: false
                    }).done(function (data) {
                        if (data != "OK") {
                            notify(data);
                        } else {
                            loadFiles();
                            $btnReload.trigger('click');
                        }
                    });
                }
            });
        })

            .on('click', '[data-rename]', function () {
                var x = $(this).data('rename');
                bootbox.prompt({
                    title: "Rename to:",
                    value: x,
                    callback: function (result) {
                        if (result === null) {
                        } else {
                            $.ajax({
                                type: "GET",
                                dataType: "text",
                                url: "/admin/filemanager/rename",
                                data: {
                                    file: x,
                                    dir: $("#working_dir").val(),
                                    new_name: result
                                },
                                cache: false
                            }).done(function (data) {
                                if (data == "OK") {
                                    $btnReload.trigger('click');
                                    loadFiles();
                                } else {
                                    notify(data);
                                }
                            });
                        }
                    }
                });
            })
            .on('click', '[data-crop]', function () {
                var x = $(this).data('crop');
                $.ajax({
                    type: "GET",
                    dataType: "text",
                    url: "/admin/filemanager/crop",
                    data: "img="
                    + x
                    + "&dir=" + $("#working_dir").val(),
                    cache: false
                }).done(function (data) {
                    $("#nav-buttons").addClass('hidden');
                    $("#content").html(data);
                });
            })
            .on('click', '[data-resize]', function () {
                var x = $(this).data('resize');
                $.ajax({
                    type: "GET",
                    dataType: "text",
                    url: "/admin/filemanager/resize",
                    data: "img="
                    + x
                    + "&dir=" + $("#working_dir").val(),
                    cache: false
                }).done(function (data) {
                    $("#nav-buttons").addClass('hidden');
                    $("#content").html(data);
                });
            })
            .on('click', '[data-view]', function () {
                var x = $(this).data('view');
                var rnd = makeRandom();
                $('#fileview_body').html(
                    "<img class='img img-responsive center-block' src='{{ Config::get('lfm.images_url') }}" + $("#working_dir").val() + "/" + x + "?id=" + rnd + "'>"
                );
                $('#fileViewModal').modal();
            })
            .on('click', '[data-select-file]', function () {
                var file = $(this).data('select-file'),
                    path = $('#working_dir').val(),
                    reParam = new RegExp('(?:[\?&]|&)CKEditorFuncNum=([^&]+)', 'i'),
                    match = window.location.search.match(reParam),
                    funcNum = ( match && match.length > 1 ) ? match[1] : null,
                    fullPath = '{{ config('filemanager.images_url') }}';

                        @if ((Session::has('lfm_type')) && (Session::get('lfm_type') == "Images"))
                var fullPath = '{{ \Config::get('lfm.images_url') }}';
                fullPath += (path !== '/') ? path + "/" + file : file;
                        @else
                var fullPath = '{{ config('filemanager.files_url') }}';
                fullPath += (path !== '/') ? path + "/" + file : file;
                @endif
                window.opener.CKEDITOR.tools.callFunction(funcNum, fullPath);
                window.close();
            });

        function loadFiles() {
            $.ajax({
                type: "GET",
                dataType: "html",
                url: "/admin/filemanager/folders",
                data: {
                    base: $("#working_dir").val(),
                    show_list: $("#show_list").val()
                },
                cache: false
            }).done(function (data) {
                $("#tree1").html(data);
            });
        }

        function refreshFolders() {
            var wd = $("#working_dir").val();
            if (wd != "/") {
                $('#' + wd + '-folder').removeClass('fa-folder');
                $('#' + wd + '-folder').addClass('fa-folder-open');
            }
        }


        function notImp() {
            bootbox.alert('Not yet implemented!');
        }

        $("#add-folder").click(function () {
            bootbox.prompt("Folder name:", function (result) {
                if (result === null) {
                } else {
                    $.ajax({
                        type: "GET",
                        dataType: "text",
                        url: "/admin/filemanager/newfolder",
                        data: {
                            name: result,
                            dir: $("#working_dir").val()
                        },
                        cache: false
                    }).done(function (data) {
                        if (data == "OK") {
                            loadFiles();
                            $btnReload.trigger('click');
                            refreshFolders();
                        } else {
                            notify(data);
                        }
                    });
                }
            });
        });


        function notify(x) {
            bootbox.alert(x);
        }

        $("#thumbnail-display").click(function () {
            $("#show_list").val(0);
            $btnReload.trigger('click');
        });

        $("#list-display").click(function () {
            $("#show_list").val(1);
            $btnReload.trigger('click');
        });


        function makeRandom() {
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

            for (var i = 0; i < 20; i++)
                text += possible.charAt(Math.floor(Math.random() * possible.length));
            return text;
        }

        refreshFolders();
        $btnReload.trigger('click');

    })
</script>
</body>
</html>
