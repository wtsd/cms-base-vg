/*jslint browser:true */
/*global $, jQuery, console, CKEDITOR, alert, confirm */

function doCKupdate() {
    'use strict';
    var instance;
    for (instance in CKEDITOR.instances) {
        if (CKEDITOR.instances.hasOwnProperty(instance)) {
            CKEDITOR.instances[instance].updateElement();
        }
    }
}


Date.parseDate = function( input, format ){
      // you code for convert string to date object
      // simplest variant - use native Date.parse, not given format parametr
    return Date.parse(input);
};
Date.prototype.dateFormat = function( format ){
  //you code for convert date object to format string
  //for example
  switch( format ){
    case "Y-m-d": return this.getFullYear()+'-'+(this.getMonth() < 10 ? '0'+(this.getMonth()+1) : (this.getMonth()+1))+'-'+(this.getDate() < 10 ? '0'+this.getDate() : this.getDate());
    case "d":     return this.getDate();
    case "H:i:s": return this.getHours()+':'+this.getMinutes()+':'+this.getSeconds();
    case "h:i a": return ((this.getHours() %12) ? this.getHours() % 12 : 12)+':'+this.getMinutes()+(this.getHours() < 12 ? 'am' : 'pm');
  }
  // or default format
  return this.getDate()+'.'+(this.getMonth()+ 1)+'.'+this.getFullYear();
};

function pasteProcedure() {
    $("body").bind("paste", function(ev) {
        var $this = $(this);
        var original =  ev.originalEvent;
        var file =  original.clipboardData.items[0].getAsFile();
        if (file) {
            var reader = new FileReader();

            var $form = $('form[name="frm-add"]');

            reader.onload = function (evt) {
                var result = evt.target.result,
                    arr = result.split(","),
                    image = arr[1], // raw base64
                    contentType = arr[0].split(";")[0].split(":")[1];

                var tplHidden = '<input type="hidden" name="fname[]" value="{fname}">',
                    tpl = '<div class="single-screenshot">';
                    tpl += '<img src="{path}{fname}" data-img_id="{id}">';
                    tpl += '<a href="javascript:void(0);" data-img_id="{id}" class="img-del text-danger">';
                    tpl += '<i class="fa fa-trash"></i>';
                    tpl += '</a>';
                    tpl += '</div>';

                $.ajax({
                    url: '/api/v1/upload/screenshot/',
                    type: 'post',
                    data: {
                        contentType: contentType,
                        data: {image: image}
                    },
                    success: function (json) {
                        if (json.status == 'ok') {
                            tplHidden.format({
                                fname: json.fname,
                            }).appendTo($form);
                            var html = tpl.format({
                                path: '/temp/',
                                fname: json.fname,
                                id: json.fname,

                            });
                            
                            $('.screenshot').append(html);
                        }
                    },
                });
                
            };
            reader.readAsDataURL(file);
        }
        
        
    });   
}

function copy(e) {

    // find target element
    var
      t = e.target,
      c = t.dataset.copytarget,
      inp = (c ? document.querySelector(c) : null);

    // is element selectable?
    if (inp && inp.select) {

      // select text
      inp.select();

      try {
        // copy text
        document.execCommand('copy');
        inp.blur();
      }
      catch (err) {
        alert('please press Ctrl/Cmd+C to copy');
      }

    }

  }


// Source: StackOverflow
if (!String.prototype.format) {
    String.prototype.format = function() {
        var str = this.toString();
        if (!arguments.length)
            return str;
        var args = typeof arguments[0],
            args = (("string" == args || "number" == args) ? arguments : arguments[0]);
        for (arg in args)
            str = str.replace(RegExp("\\{" + arg + "\\}", "gi"), args[arg]);
        return str;
    }
}

if (!String.prototype.transliterate) {
    String.prototype.transliterate = function () {
        var str = this.toString();
        var mapEn = "a|b|v|g|d|e|yo|j|z|i|iy|k|l|m|n|o|p|r|s|t|u|f|h|ts|ch|sh|sch||y||e|yu|ya|-".split('|'),
            mapRu = "а|б|в|г|д|е|ё|ж|з|и|й|к|л|м|н|о|п|р|с|т|у|ф|х|ц|ч|ш|щ|ъ|ы|ь|э|ю|я| ".split('|');

        for (i = 0; i < mapEn.length; ++i) {
            while (str.indexOf(mapRu[i]) >= 0) {
              str = str.replace(mapRu[i], mapEn[i]);
            }
        }
        str = str.replace(/[^a-zA-Z0-9^\-]+/g, "");

        return str;
    }
}

$(document).on('ready', function () {
    'use strict';


    $(document).on({
        ajaxStart: function() { $('body').addClass("loading");    },
        ajaxStop: function() { $('body').removeClass("loading"); }    
    });

    /* Hash URL stuff */
    /*var hash = window.location.hash;
    if (window.hasOwnProperty('onhashchange')) {
        //window.onhashchange = hashChanged;
    } else {
        setInterval(function () {
            if (window.location.hash !== hash) {
                hash = window.location.hash;
                //hashChanged();
            }
        }, 100);
    }*/
    /* /Hash URL stuff */


    $('body').delegate('form[name="frm_auth"]', 'submit', function (e) {
      e.preventDefault();
      var $form = $(this),
          values = $form.serialize();
          $.ajax({
            url: '/api/v1/authorize/login',
            type: 'post',
            data: values,
            dataType: 'json',
            success: function (json) {
              if (json.status == 'ok') {
                location.href = json.uri;
              } else {
                //$('span[data-msg="msg"]').html(json.msg).parents('.alert').addClass('alert-danger').show();
                alert(json.msg);
              }
            }

          });
    });

    /*$('body').delegate('textarea.editor', 'click', function () {
        CKEDITOR.replace($(this).attr('id'), {
            customConfig: '/web/js/ckeditor-config.js'
        });        
    });*/

    $('textarea.editor').each(function () {
        var uplUrl = $('input[name="image"]').data('url');
        var ctype = $('input[name="c_type_str"]').val();
        var id = $('input[name="id"]').val();

        CKEDITOR.replace($(this).attr('id'), {
            customConfig: '/web/js/ckeditor-config.js',
            uploadUrl: uplUrl,
            filebrowserBrowseUrl : '/'+prefix+'/browsemedia/'+ctype+'/'+id+'/',
            filebrowserUploadUrl : uplUrl
        });
    });


    $('#name').on('keyup', function (e) {
        var name = $(this).val();
        if ($('#id').val() == 0) {
            var trName = name.toLowerCase().transliterate();
            $('#rewrite').val(trName);
            $('input[name="h1"]').val(name);
            $('input[name="title"]').val(name);
        }
    });

    $('#recordTabs a').click(function (e) {
      e.preventDefault()
      $(this).tab('show')
    })

    $('.btn_save').on('click', function (e) {
        e.preventDefault();

        doCKupdate();
        var formData = new FormData($('form#frm')[0]);
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
                    var tpl = '<div class="alert alert-success messagebox">{msg}<a class="close" data-dismiss="alert" href="#">&times;</a></div>';
                    $('#frm').before(tpl.format({msg: result.msg}));

                    /*$('<div></div>')
                        .addClass('alert').addClass('alert-success').addClass('messagebox')
                        .html(result.msg + '<a class="close" data-dismiss="alert" href="#">&times;</a>')
                        .insertBefore('#frm').show();*/
                    //$('.btn_save').attr('disabled', 'disabled');

                    //$('.notification').html(info['msg']).toggle();
                    $('#id').val(result.id);
                    if (history && history.pushState) {
                        var c_type = $('#c_type_str').val();
                        history.pushState(null, null, '/' + prefix + '/' + c_type + '/edit/' + result.id);
                    }

                    var rewrite = $('input[name="rewrite"]').val();

                    $('a[data-action="preview"]').attr('href', '/'+c_type+'/'+rewrite).toggle();
                    
                    $('.single-photo').each(function () {
                        $(this).data('id', result.id);
                    });

                    $('.single-photo a').each(function () {
                        var old_href = $(this).attr('href');
                        var new_href = old_href.replace('/tmp/', '/'+result.id+'/');
                        $(this).attr('href', new_href);
                    });
                    $('.single-photo img').each(function () {
                        var old_src = $(this).attr('src');
                        var new_src = old_src.replace('/tmp/', '/'+result.id+'/');
                        $(this).attr('src', new_src);
                    });
                    $('.attachments a').each(function () {
                        var old_href = $(this).attr('href');
                        var new_href = old_href.replace('/tmp/', '/'+result.id+'/');
                        $(this).attr('href', new_href);
                    });
                } else {
                    alert(result.msg);
                    //$('<div></div>').addClass('alert').addClass('alert-danger').addClass('messagebox').html(result.msg + '<a class="close" data-dismiss="alert" href="#">&times;</a>').insertBefore('#frm').show();
                }

                //setTimeout(function () { $('.notification').fadeOut(); }, 5000);
            },
            error: function () {
                alert('Произошла ошибка при сохранении!');
            },
            data : formData,
            //Options to tell JQuery not to process data or worry about content-type
            cache : false,
            contentType : false,
            processData : false,
            dataType: 'json'
        });
    });

    $('.upload-input').change(function (e) {
        var $this = $(this),
            url = $this.data('url'),
            type = $this.data('type');
        /*
        var file = this.files[0];
        var name = file.name;
        var size = file.size;
        var type = file.type;
        */
        var data = new FormData();
        for (var i = 0; i < $this[0].files.length; i++) {
            data.append(type+'[]', $this[0].files[i]);
        }
        
        //data.append(type, $this[0].files[0]);

        $.ajax({
            url: url,
            type: "post",
            data: data,
            dataType: 'json',
            xhr: function() {  // Custom XMLHttpRequest
              var myXhr = $.ajaxSettings.xhr();
              if(myXhr.upload){ // Check if upload property exists
                  myXhr.upload.addEventListener('progress', function (e) {
                        'use strict';
                        if (e.lengthComputable) {
                            $('progress.'+type).attr({value : e.loaded, max : e.total});
                        }
                    }, false); // For handling the progress of the upload
              }
              return myXhr;
            },
            beforeSend: function (x) {
                if (x && x.overrideMimeType) {
                    x.overrideMimeType("multipart/form-data");
                }
                $('progress.'+type).show();
            },
            error:  function () { alert('Произошла ошибка при загрузке файла'); },
            success: function (result) {
                if (result.status == 'ok') {
                    $this.val('');
                    $('progress.'+type).hide();

                    var html = '',
                        inputs = '';
                    
                    var tpl = '<div class="single-photo" data-id="{id}">';
                        tpl += '<span class="photo-toolbar" style="display:none;">';
                        tpl += '<a href="#" class="delete"><span class="glyphicon glyphicon-trash"></span></a>';
                        tpl += '<a href="{path}{fname}" class="open" target="_blank"><span class="glyphicon glyphicon-zoom-in"></span></a>';
                        tpl += '<a href="#" class="rotate"><span class="glyphicon glyphicon-repeat"></span></a>';
                        tpl += '</span>';
                        tpl += '<a href="{path}{fname}" target="_blank" data-id="{fid}">';
                        tpl += '  <img src="{path}{fname}" class="image uploaded" data-id="{fid}">';
                        tpl += '</a>';
                        tpl += '</div>';
                    
                    if (type == 'image') {
                        for (var i = 0; i < result.files.length; i++) {
                            html += tpl.format({id: result.files[i].id, fname: result.files[i].name, path: result.path});
                            
                            inputs += '<input type="hidden" name="images[]" value="' + result.files[i].id + '">';
                        }
                        $('.photos').append(html);
                    }
                    
                    if (type == 'attachment') {
                        for (var i = 0; i < result.files.length; i++) {
                            html += '<div class="single-attachment" data-id="'+result.files[i].id+'"><a href="'+result.path+result.files[i].name+'" target="_blank" data-id="'+result.files[i].id+'"><div class="filetype '+result.files[i].filetype+'" title="'+result.path+result.files[i].name+'"></div>'+result.files[i].orig_name+'</a></div>';

                            inputs += '<input type="hidden" name="attachments[]" value="' + result.files[i].id + '">';

                        }
                        
                        $('.attachments').append(html);
                    }
                    $('#frm').append(inputs);
                } else {
                    alert(response.msg);
                }
                
                // @todo: Add hidden inputs for images to save them
            },
            enctype: 'multipart/form-data',
            contentType: false,
            processData: false
        });
    });

    $('input[type="file"]').on('change', function () {
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

    $('body').delegate('.delfile', 'click', function (e) {
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
    });


    $('.photos').delegate('.single-photo', 'mouseenter', function (e) {
      var $toolbar = $(this).children('.photo-toolbar');
      $toolbar.show();
    });
    $('.photos').delegate('.single-photo', 'mouseleave', function (e) {
      var $toolbar = $(this).children('.photo-toolbar');
      $toolbar.hide();
    });

    $('.photos').delegate('.delete', 'click', function (e) {
      e.preventDefault();

      if (confirm('Вы уверены, что хотите удалить фотографию? Её уже будет невозможно восстановить.')) {
        var $hr = $(this).parents('.single-photo'),
            id = $hr.attr('data-id'),
            values = {};
        values.action = 'delete';
        values.id = id;

        var model = $('input[name="model"]').val();
        $.ajax({
            url : '/' + prefix + '/ajax/',
            type : 'post',
            data : {
                'act' : 'ajax',
                'model' : model,
                'controller' : 'DeleteImage',
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

    $('.photos').delegate('.rotate', 'click', function (e) {
      e.preventDefault();
      var $hr = $(this).parents('.single-photo'),
          id = $hr.attr('data-id'),
          gId = $('#id').val(),
          values = {},
          $this = $(this);
      values.action = 'rotate';
      values.id = id;
      values.galleryId = gId;

      var model = $('input[name="model"]').val();
      $.ajax({
          url : '/' + prefix + '/ajax/',
          type : 'post',
          data : {
              'act' : 'ajax',
              'model' : model,
              'controller' : 'RotateImage',
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

    })


    $('.photos').delegate('*[data-action="main"]', 'click', function (e) {
        e.preventDefault();
        //alert('there');
      var $hr = $(this).parents('.single-photo'),
          id = $hr.attr('data-id'),
          gId = $('#id').val(),
          values = {},
          $this = $(this);
          values.action = 'setmain';
          values.id = id;
          values.offer_id = gId;

          // @todo: Remove all main photos
          var model = $('input[name="model"]').val();
      $.ajax({
          url : '/' + prefix + '/ajax/',
          type : 'post',
          data : {
              'act' : 'ajax',
              'model' : model,
              'controller' : 'SetMainImage',
              'values' : values
          },
          dataType : 'json',
          success : function (result) {
              if (result.status == 'ok') {
                var $img = $('#img' + id),
                    src = $img.attr("src");
                    // @todo: Set frame for main

                //$img.removeAttr("src").attr("src", src + '?t=' + new Date().getTime());
              }
          }
      });

    });

    $('body').delegate('#pcat_id', 'change', function (e) {
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

    $('body').delegate('.color-val', 'click', function (e) {
        e.preventDefault();
        var input_id = $(this).data('spec'),
            val = $(this).data('val');
        $('input[name="' + input_id + '"]').attr('value', val);
    });
    //////////////////////////////////////////


    /* LISTING */
    $('table').delegate('.delete', 'click', function (e) {
        e.preventDefault();
        var id = $(this).data('id'),
            model = $('form[name="frm_lst"]').data('model'),
            values = {id: id},
            $hr;

        $hr = $(this).parents('tr');
        if (confirm("Вы уверены, что хотите удалить запись?!")) {
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

    $('body').delegate('.toggle-tbody', 'click', function (e) {
        e.preventDefault();
        $(this).parents('table').first().children('tbody').toggle('slideIn');        
    });

    $('[data-toggle="popover"]').popover({
        html : true,
        content: function() {
          var content = $(this).data("popover-content");
          return $(content).children(".popover-body").html();
        },
        title: function() {
          var title = $(this).data("popover-content");
          return $(title).children(".popover-heading").html();
        }
    });



    var $table = $('table[data-table="list"]');
    if ($table.length == 1) {
        var datatype = $table.data('type');
        $.ajax({
            url: '/api/v1/'+datatype+'/columns/',
            type: 'get',
            dataType: 'json',
            cache: false,
            success: function (json) {
              
              var columnType = json;

              var table = $('table#list').DataTable({
                "ajax": '/api/v1/'+datatype+'/table/',
                "columns": columnType,
                "createdRow": function ( row, data, index ) {
                    var html = '<a href="#" data-type="'+datatype+'" data-id="'+data.id+'" data-action="delete" class="btn btn-raised btn-sm btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></a> ';
                        html += '<a href="#" data-type="'+datatype+'" data-id="'+data.id+'" data-action="edit" data-toggle="modal" data-target="#addModal" class="btn btn-raised btn-sm btn-success"><i class="fa fa-pencil" aria-hidden="true"></i></a> ';
                    var i = Object.keys(columnType).length - 1;
                    $('td', row).eq(i).html(html);
                  },
                  "language": {"url": '/web/js/datatable/ru.lang'},
                  "responsive": true,
              });

            }
        });
    }

    $('body').delegate('*[data-action="delete"]', 'click', function () {
        var $this = $(this);
        if (confirm('Вы уверены, что хотите удалить?')) {
          var type = $this.data('type'),
            id = $this.data('id'),
            url = '/api/v1/'+type+'/'+id;

          $.ajax({
            url: url,
            type: 'delete',
            cache: false,
            success: function (json) {
              table.row($this.parents('tr')).remove().draw();
            }
          });
        }
        return false;

    });
    
    $('body').delegate('form[data-form="add"]', 'submit', function (e) {
      e.preventDefault();
      var $form = $(this),
        $btn = $('*[data-action="save-record"]', $form),
        //$form = $btn.parents('form'),
        type = $form.data('type'),
        id = $('input[name="id"]', $form).val(),
        url = '/api/v1/'+type+'/',
        values = $form.serialize();
        //console.log(values);

        $.ajax({
          url: url,
          type: 'post',
          data: values,
          dataType: 'json',
          success: function (json) {
            $btn.removeAttr('disabled');
            table.ajax.reload(null, false);
            if (id == 0) {

              //table.rows.add(json).draw();
            }
            $('#addModal').modal('hide');
            
          },
          beforeSend: function () {
            $('body').addClass("loading");
          },
          complete: function() {
            $btn.removeAttr('disabled');
            $('body').removeClass('loading');
          },
          error: function (data, textStatus, errorThrown) {
            try {
              var json = JSON.parse(data.responseText);
              if (json.errors) {
                alert("Ошибки:\n— "+json.errors.join("\n— "));
              } else {
                alert('Недостаточно прав для сохранения!');
              }
            } catch (e) {
              //console.log(e);
              alert('Невозможно сохранить запись!');
            }
          }
        });

    });

    $('#addModal').on('show.bs.modal', function (event) {
      var $form = $('form[data-form="add"]')[0];
      $form.reset();
      var button = $(event.relatedTarget); // Button that triggered the modal
      var id = button.data('id'),
          type = button.data('type'),
          url = '/api/v1/'+type+'/'+id,
          $modal = $(this);

      $('input[name="id"]').val(id);
      if (id > 0) {
        var record = [];
        $.ajax({
          url: url,
          type: 'get',
          dataType: 'json',
          success: function (json) {
            $.each(json, function (key, value) {
              $modal.find('*[data-field="'+key+'"]').val(value);
              $modal.find('select[data-field="'+key+'"] option[value="'+value+'"]').attr('selected', 'selected');
            });
          }
        });

      }
    });
});
