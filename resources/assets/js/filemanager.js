$(function() {

   var FManager = function(el, cfg) {
      // load folders
      $.ajax({
         type: "GET",
         dataType: "text",
         url: "/filemanager/folders",
         data: "base={{ $working_dir }}",
         cache: false
      }).done(function (data) {
         $("#tree1").html(data);
      });
      var $win       = $(document),
          $btnReload = $('.reload-files');

   };

   FManager.prototype.defaultConfig = {
      cwdir: '/',
      images_url: '',
      files_url: '',
      file_mode: 'images'
   };

   $("#upload-btn").click(function () {

      $("#uploadForm").ajaxSubmit({
         beforeSubmit:  function (formData, jqForm, options) {
            $("#upload-btn").html('<i class="fa fa-refresh fa-spin"></i> Uploading...');
            return true;
         },
         success: function(responseText, statusText, xhr, $form)  {
            $("#uploadModal").modal('hide');
            $("#upload-btn").html('Upload File...');
            if (responseText != "OK"){
               notify('File uploaded.');
            }
            $("#file_to_upload").val('');
            $btnReload.trigger('click');
         }
      });
      return false;
   });

   $win
      .on('click', 'action-root', function() {
         $('.folder-item').removeClass('fa-folder-open').addClass('fa-folder');
         $("#working_dir").val('/');
         $btnReload.trigger('click');
      })

      .on('click', '[data-folder]', function(x, y) {
         var $this = $(this),
             id = $this.data('folder'),
             $folder = $('#' + id + ' > i');
         $('.folder-item').addClass('fa-folder');
         $('.folder-item').not("#folder_top > i").removeClass('fa-folder-open');
         if ( ! $this.hasClass('folder-open')) {
            if ($folder.hasClass('fa-folder')) {
               $folder.not("#folder_top > i").removeClass('fa-folder');
               $folder.not("#folder_top > i").addClass('fa-folder-open');
            } else {
               $folder.removeClass('fa-folder-open');
               $folder.addClass('fa-folder');
            }
         }
         $("#working_dir").val($('#' + id).data('id'));
         $btnReload.trigger('click');
      })

      .on('click', '[data-download]', function() {
         location.href = "/filemanager/download?dir="+ $("#working_dir").val() + "&file="+$(this).data('download');
      });

    @if ((Session::has('lfm_type')) && (Session::get('lfm_type') == "Images"))
   .on('click', '.reload-files', function() {
      console.log('REALODING!!!');
      $.ajax({
         type: "GET",
         dataType: "html",
         url: "/filemanager/jsonimages",
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
   .on('click','.load-images', function() {
      console.log('REALODING!!!');
      $.ajax({
         type: "GET",
         dataType: "html",
         url: "/filemanager/jsonfiles",
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
      .on('click', '[data-trash]', function() {
         var x = $(this).data('trash');
         bootbox.confirm("Are you sure you want to delete this item?", function (result) {
            if (result == true) {
               $.ajax({
                  type: "GET",
                  dataType: "text",
                  url: "/filemanager/delete",
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

      .on('click', '[data-rename]', function() {
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
                     url: "/filemanager/rename",
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
      .on('click', '[data-crop]', function() {
         var x = $(this).data('crop');
         $.ajax({
            type: "GET",
            dataType: "text",
            url: "/filemanager/crop",
            data: "img="
            + x
            + "&dir=" + $("#working_dir").val(),
            cache: false
         }).done(function (data) {
            $("#nav-buttons").addClass('hidden');
            $("#content").html(data);
         });
      })
      .on('click', '[data-resize]', function() {
         var x = $(this).data('resize');
         $.ajax({
            type: "GET",
            dataType: "text",
            url: "/filemanager/resize",
            data: "img="
            + x
            + "&dir=" + $("#working_dir").val(),
            cache: false
         }).done(function (data) {
            $("#nav-buttons").addClass('hidden');
            $("#content").html(data);
         });
      })
      .on('click', '[data-view]', function() {
         var x = $(this).data('view');
         var rnd = makeRandom();
         $('#fileview_body').html(
            "<img class='img img-responsive center-block' src='{{ Config::get('lfm.images_url') }}" + $("#working_dir").val() + "/" + x + "?id=" + rnd + "'>"
         );
         $('#fileViewModal').modal();
      })

   function loadFiles() {
      $.ajax({
         type: "GET",
         dataType: "html",
         url: "/filemanager/folders",
         data: {
            base: $("#working_dir").val(),
            show_list: $("#show_list").val()
         },
         cache: false
      }).done(function (data) {
         $("#tree1").html(data);
      });
   }

   function refreshFolders(){
      var wd = $("#working_dir").val();
      if (wd != "/") {
         $('#' + wd + '-folder').removeClass('fa-folder');
         $('#' + wd + '-folder').addClass('fa-folder-open');
      }
   }


   function notImp() {
      bootbox.alert('Not yet implemented!');;
   }

   $("#add-folder").click(function () {
      bootbox.prompt("Folder name:", function (result) {
         if (result === null) {
         } else {
            $.ajax({
               type: "GET",
               dataType: "text",
               url: "/filemanager/newfolder",
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

   function useFile(file) {
      var path = $('#working_dir').val();

      function getUrlParam(paramName) {
         var reParam = new RegExp('(?:[\?&]|&)' + paramName + '=([^&]+)', 'i');
         var match = window.location.search.match(reParam);
         return ( match && match.length > 1 ) ? match[1] : null;
      }

      var funcNum = getUrlParam('CKEditorFuncNum'), fullPath = '{{ \Config::get('lfm.images_url') }}';
      window.opener.CKEDITOR.tools.callFunction(funcNum, path + "/" + file);

        @if ((Session::has('lfm_type')) && (Session::get('lfm_type') == "Images"))
      var fullPath = '{{ \Config::get('lfm.images_url') }}';
      fullPath += (path !== '/')  ? path + "/" + file : file;
        @else
      var fullPath = '{{ \Config::get('lfm.files_url') }}';
      fullPath += (path !== '/')  ? path + "/" + file : file;
   }
        @endif
   window.opener.CKEDITOR.tools.callFunction(funcNum, fullPath);
   window.close();
}


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


function makeRandom()
{
   var text = "";
   var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

   for( var i=0; i < 20; i++ )
      text += possible.charAt(Math.floor(Math.random() * possible.length));
   return text;
}

refreshFolders();
$btnReload.trigger('click');

});
</script>
</body>
</html>
