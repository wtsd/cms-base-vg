/*jslint browser:true */
/*global $, jQuery, console, CKEDITOR, alert, confirm */
var CKEDITOR_BASEPATH = '/resources/assets/ckeditor/';


function makeEditor(id) {
    'use strict';
    CKEDITOR.replace(id, {toolbar : 'VG'});

    //CKEDITOR.instances[id].change(e);
}

$(document).ready(function(){
  'use strict';

  var hash = window.location.hash,
        hashChanged = function () {
            console.log('Changed hash! "' + window.location.hash + '"');
            //window.location.hash = "#nothing";
        };
    if (window.hasOwnProperty('onhashchange')) {
        window.onhashchange = hashChanged;
    } else {
        setInterval(function () {
            if (window.location.hash !== hash) {
                hash = window.location.hash;
                hashChanged();
            }
        }, 100);
    }
    /* /Hash URL stuff */

    /*    
    $('.menu li').on('hover', function(){
        $(this).children('.submenu').toggle();
    });
    */

    $('.btn-logout').on('click', function (e) {
        e.preventDefault();
        $('.logoutmodal').modal();
    });

    $('.logout').on('click', function (e) {
        e.preventDefault();
        $('.logoutmodal').modal();
    });

    // List
    $('.btn_addanother').on('click', function (e) {
        e.preventDefault();
        var c_type = $('.listing').attr('data-ctype');
        location.href = '/' + prefix + '/' + c_type + '/add/';
    });

    /* LISTING */
    $('.listing tr:even').addClass("even");
    $('.delete').click(function (e) {
        e.preventDefault();
        var id = $(this).attr('data-id'),
            model = $('.listing').attr('model'),
            values = {},
            $hr;

        values.action = 'delete';
        values.id = id;
        $hr = $(this).parents('tr');
        if (confirm("Are you sure?!")) {
            $.ajax({
                url : '/' + prefix + '/ajax/',
                type : 'post',
                data : {
                    'act' : 'ajax',
                    'model' : model,
                    'controller' : 'Delete',
                    'values' : values
                },
                dataType : 'json',
                success : function (result) {
                    if (result.ok !== '0') {
                        $hr.hide();
                    } else {
                        alert('An error occured.');
                    }
                }
            });

        } else {
            return false;
        }
    });
    $('.toggle-tbody').on('click', function (e) {
        e.preventDefault();
        $(this).parents('table').first().children('tbody').toggle('slideIn');
    });

    // FRM
    var $frm;

    $('textarea.editor').each(function (item) {
        CKEDITOR.editorConfig = function( config ) {
            config.toolbar = 'VG';
            config.allowedContent = true; 
            config.language = 'ru';

            config.toolbar_VG =
            [
                { name: 'document', items : [ 'Source' ] },
                { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord' ] },
                { name: 'editing', items : [ 'Find','Replace','-','SelectAll' ] },
                { name: 'links', items : [ 'Link','Unlink' ] },
                { name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','SpecialChar' ] },
                { name: 'colors', items : [ 'TextColor','BGColor' ] },
                '/',
                { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
                { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
                { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote',
                '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
                
            ];
        };
        CKEDITOR.replace(item.attr('id'));
    });

    $frm = $('frm');


    $('.btn_save').on('click', function (e) {
        e.preventDefault();

        //doCKupdate();

        var formData = new FormData($('form')[0]);
        $.ajax({
            url : '/' + prefix + '/ajax/',  //server script to process data
            type : 'POST',
            xhr : function () {  // custom xhr
                var myXhr = $.ajaxSettings.xhr();
                /*
                if (myXhr.upload) { // check if upload property exists
                    myXhr.upload.addEventListener('progress', progressHandlingFunction, false); // for handling the progress of the upload
                }
                */
                return myXhr;
            },
            //Ajax events
            //beforeSend: beforeSendHandler,
            success : function (result) {
                //console.log(result);
                var $alobj = $('.alert'),
                    $newobj;
                //console.log($alobj[0]);
                if (result.status == 'ok') {
                    $('<div></div>').addClass('alert').addClass('alert-success').addClass('messagebox').html(result.msg + '<a class="close" data-dismiss="alert" href="#">&times;</a>').insertBefore('#frm').show();
                    //$('.btn_save').attr('disabled', 'disabled');

                    //$('.notification').html(info['msg']).toggle();
                    $('#id').val(result.id);
                    if (history && history.pushState) {
                        var c_type = $('#c_type_str').val();
                        history.pushState(null, null, '/' + prefix + '/' + c_type + '/edit/' + result.id);
                    }
                } else {
                    $('<div></div>').addClass('alert').addClass('alert-danger').addClass('messagebox').html(result.msg + '<a class="close" data-dismiss="alert" href="#">&times;</a>').insertBefore('#frm').show();
                }

                //setTimeout(function () { $('.notification').fadeOut(); }, 5000);
            },
            //error: errorHandler,
            data : formData,
            //Options to tell JQuery not to process data or worry about content-type
            cache : false,
            contentType : false,
            processData : false,
            dataType: 'json'
        });

    });

    $('.btn_tolst').on('click', function (e) {
        e.preventDefault();
        var c_type = $('#c_type_str').val();
        location.href = '/' + prefix + '/' + c_type + '/browse/';
    });

    $('.btn_addanother').on('click', function (e) {
        e.preventDefault();
        var c_type = $('#c_type_str').val();
        location.href = '/' + prefix + '/' + c_type + '/add/';
    });

    $('input[type="file"]').on('change', function () {
        alert('there'); 
        /*
        var tmp_img = $(this).val();
        $(this).insertAfter('<img src="' + tmp_img + '" />');
        */
        var $container = $(this).parents('.filecontainer'),
            $newcont = $container.clone(true);
        $newcont.insertAfter($container);
    });

    $('.addfile').on('click', function (e) {
        e.preventDefault();
        var $container = $(this).parents('.filecontainer'),
            $newcont = $container.clone(true);
        $newcont.insertAfter($container);
        //$(this).parents('.filecontainer').children('.ctrls').hide();
    });
    $('.delfilebox').on('click', function (e) {
        e.preventDefault();
        var $container = $(this).parents('.filecontainer');
        //console.log($container.siblings('.filecontainer').length);
        if ($container.siblings('.filecontainer').length >= 1) {
            $container.remove();
        }
    });

    $('.delfile').on('click', function (e) {
        e.preventDefault();
        var $hr = $(this).parents('div.fileinfo'),
            values = {};
        values.action = 'delete';
        values.fpath = $(this).attr('fpath');

        if (confirm("Вы уверены, что хотите безвозвратно удалить этот файл?!")) {
            $.ajax({
                url : '/' + prefix + '/ajax/',
                type : 'post',
                data : {
                    'act' : 'ajax',
                    'model' : 'File',
                    'controller' : 'Delete',
                    'values' : values
                },
                dataType : 'json',
                success : function (result) {
                    //console.log(result);
                    if (result.ok !== '0') {
                        $hr.hide();
                    } else {
                        alert('error');
                    }
                }
            });
        } else {
            return false;
        }
        console.log($(this).attr('fpath'));
    });



    $('.photos').delegate('.single-photo', 'mouseenter', function (e) {
      var $toolbar = $(this).children('.photo-toolbar');
      $toolbar.show();
      console.log('over!');
    });
    
    /*$('.photos .single-photo').on('mouseenter', function (e) {
      var $toolbar = $(this).children('.photo-toolbar');
      $toolbar.show();
    });*/
    $('.photos .single-photo').on('mouseleave', function (e) {
      var $toolbar = $(this).children('.photo-toolbar');
      $toolbar.hide();
    });

    $('.photos .single-photo .edit').on('click', function (e) {
      //e.preventDefault();
    })

    $('.single-photo').delegate('.delete', 'click', function (e) {
      e.preventDefault();

      var submodel = $('#submodel').val();
      if (confirm('Вы уверены, что хотите удалить фотографию? Её уже будет невозможно восстановить.')) {
        var $hr = $(this).parents('.single-photo'),
            id = $hr.attr('data-id'),
            values = {};
        values.action = 'delete';
        values.id = id;
        $.ajax({
            url : '/' + prefix + '/ajax/',
            type : 'post',
            data : {
                'act' : 'ajax',
                'model' : submodel,
                'controller' : 'Delete',
                'values' : values
            },
            dataType : 'json',
            success : function (result) {
                if (result.ok !== '0') {
                    $hr.hide();
                } else {
                    alert('An error occured.');
                }
            }
        });
      }
    });

    //$('.photos .single-photo .delete').on('click', );

    $('.photos .single-photo .rotate').on('click', function (e) {
      e.preventDefault();
      var $hr = $(this).parents('.single-photo'),
          id = $hr.attr('data-id'),
          gId = $('#id').val();
          values = {},
          $this = $(this);
      values.action = 'rotate';
      values.id = id;
      values.galleryId = gId;


      var submodel = $('#submodel').val();
      $.ajax({
          url : '/' + prefix + '/ajax/',
          type : 'post',
          data : {
              'act' : 'ajax',
              'model' : submodel,
              'controller' : 'Rotate',
              'values' : values
          },
          dataType : 'json',
          success : function (result) {
              if (result.status == 'ok') {
                var $img = $('#img' + id),
                    src = $img.attr("src");
                $img.removeAttr("src").attr("src", src + '?t=' + new Date().getTime());
              }
          }
      });

    });

    $('#pcat_id').on('change', function (e) {
        console.log('there');
        var pcat_id = $(this).val();

          $.ajax({
              url : '/' + prefix + '/ajax/spec/getByPCat/',
              type : 'post',
              data : {
                  'pcat_id' : pcat_id,
              },
              dataType : 'json',
              success : function (result) {
                  if (result.status == 'ok') {
                    $('.frm-specifications').html(result.spec_html);
                  }
              }
          });

    });

    $('.color-val').on('click', function (e) {
        e.preventDefault();
        var input_id = $(this).attr('data-spec'),
            val = $(this).attr('data-val');
        $('input[name="' + input_id + '"]').attr('value', val);
    });

});

function doTransliterate(str) {
  var mapEn = "a|b|v|g|d|e|yo|j|z|i|iy|k|l|m|n|o|p|r|s|t|u|f|h|ts|ch|sh|sch||y||e|yu|ya|-".split('|');
  var mapRu = "а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я| ".split('|');

  for(i = 0; i < mapEn.length; ++i) {
    while (str.indexOf(mapRu[i]) >= 0) {
      str = str.replace(mapRu[i], mapEn[i]);
    }
  }
  str = str.replace(/[^a-zA-Z0-9^\-]+/g, "");

  return str;
}


function doCKupdate() {
    'use strict';
    var instance;
    for (instance in CKEDITOR.instances) {
        if (CKEDITOR.instances.hasOwnProperty(instance)) {
            CKEDITOR.instances[instance].updateElement();
        }
    }
}



function startEverythingProfile() {
    'use strict';
    var $frm = $('frm_profile');

    $('#frm_profile').on('change', function () {
        //console.log('changing');
        var $btns = $(this).children('input.btn_save');

        //console.log($btns);
    });

    $('.btn_save').on('click', function (e) {
        e.preventDefault();

        /* CKEDITOR */
        //CKupdate();

        var formData = new FormData($('form')[0]);
        $.ajax({
            url: '/' + prefix + '/ajax/saveProfile/',  //server script to process data
            type: 'POST',
            xhr: function () {  // custom xhr
                var myXhr = $.ajaxSettings.xhr();
/*
                if (myXhr.upload) { // check if upload property exists
                    myXhr.upload.addEventListener('progress', progressHandlingFunction, false); // for handling the progress of the upload
                }
*/
                return myXhr;
            },
            //Ajax events
            //beforeSend: beforeSendHandler,
            success: function (result) {
//                console.log(result);
                var info = jQuery.parseJSON(result),
                    $alobj = $('.alert'),
                    $newobj;
                if ($alobj[0]) {
                    $alobj.html(info.msg + '<a class="close" data-dismiss="alert" href="#">&times;</a>').toggle();
                } else {
                    $newobj = $('<div></div>').addClass('alert').html(info.msg + '<a class="close" data-dismiss="alert" href="#">&times;</a>').insertBefore('#frm_profile');
                }

                //$('.notification').html(info['msg']).toggle();
                $('#id').val(info.id);
                if (history && history.pushState) {
                    var c_type = $('#c_type_str').val();
                    history.pushState(null, null, '/' + prefix + '/' + c_type + '/edit/' + info.id);
                }
                //setTimeout(function () { $('.notification').fadeOut(); }, 5000);
            },
            //error: errorHandler,
            data: formData,
            //Options to tell JQuery not to process data or worry about content-type
            cache: false,
            contentType: false,
            processData: false
        });

    });
}


function startEverythingFrm() {
    'use strict';
    //$(".alert").alert();
    
    
}

function fileUploadRoutine() {
    $('#file-upload').change(function (e) {
        $this = $(this);
        //console.log('Changed!');
        //var img = document.getElementById("blah");
        //img.src = event.target.result;
        //var filename = $(this).val();
        /*
        var file = this.files[0];
        var name = file.name;
        var size = file.size;
        var type = file.type;
        */

        var formData = new FormData($('.upload-form')[0]);
        console.log(formData);
        $.ajax({
            url: "/" + prefix + "/ajax/",
            type: "post",
            xhr: function() {  // Custom XMLHttpRequest
              var myXhr = $.ajaxSettings.xhr();
              if(myXhr.upload){ // Check if upload property exists 
                  myXhr.upload.addEventListener('progress', function (e) {if (e.lengthComputable) { $('progress').attr({value : e.loaded, max : e.total}); }, false); // For handling the progress of the upload
              }
              return myXhr;
            },
            beforeSend: function () {
                $('progress').show();
            },
            error:  function () { alert('An error occured while file upload procedure'); },
            success: function (response) {
                //alert("Data Uploaded: ");
                $this.val('');
                $('progress').hide();
                var result = JSON.parse(response);

                var html = inputs = '';
                for (var i = 0; i < result.files.length; i++) {
                    html += '<div class="single-photo" data-id="'+result.files[i].id+'">';
                    console.log('Over there!');
                    html += '<span class="photo-toolbar" style="display:none;"><a href="#" class="delete"><span class="glyphicon glyphicon-trash"></span></a><a href="/img/offer/{$id}/full/{$photo.fname}" class="open" target="_blank"><span class="glyphicon glyphicon-zoom-in"></span></a><a href="#" class="rotate"><span class="glyphicon glyphicon-repeat"></span></a></span>';
                    html += '<a href="#"><img src="' + result.path + result.files[i].name + '" data-id="' + result.files[i].id + '" class="uploaded"></a></div>';
                    inputs += '<input type="hidden" name="images[]" value="' + result.files[i].id + '">';
                }
                
                $('.photos', window.parent.document).append(html);
                $('#frm', window.parent.document).append(inputs);
                
                // @todo: Add hidden inputs for images to save them
            },
            enctype: 'multipart/form-data',
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        });
    });
}