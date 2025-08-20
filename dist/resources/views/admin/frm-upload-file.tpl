<script>var prefix = '{$prefix}';</script>
<link rel="stylesheet" type="text/css" href="/web/css/upload.css" />

<form enctype="multipart/form-data" class="upload-form">
    <input type="hidden" name="controller" value="uploadfile" />
    <input type="hidden" name="model" value="{$model|htmlspecialchars}" />
    <input type="hidden" name="id" value="{$id|htmlspecialchars}" id="id" />

    <div class="form-input">
      <label for="upload">Загрузить ещё файлы:</label>
      <input type="file" name="attachment[]" multiple id="file-upload" class="upload-input"><br>
      <small>Изображения должны быть в формате jpg.</small><br>
      <progress style="display:none;"></progress>
      <div class="preview"></div>
    </div>
</form>

{literal}
<script src="/resources/assets/jquery/dist/jquery.min.js" type="text/javascript"></script>
<script>
function progressHandlingFunction(e){
    if(e.lengthComputable){
        $('progress').attr({value:e.loaded,max:e.total});
    }
}

(function(){
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
        $.ajax({
            url: "/" + prefix + "/ajax/",
            type: "post",
            xhr: function() {  // Custom XMLHttpRequest
              var myXhr = $.ajaxSettings.xhr();
              if(myXhr.upload){ // Check if upload property exists
                  myXhr.upload.addEventListener('progress', progressHandlingFunction, false); // For handling the progress of the upload
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
                    html += '<div class="single-attachment" data-id="'+result.files[i].id+'"><a href="'+result.path+result.files[i].name+'" target="_blank" data-id="'+result.files[i].id+'"><div class="filetype '+result.files[i].filetype+'" title="'+result.path+result.files[i].name+'"></div>'+result.files[i].orig_name+'</a></div>';

                    inputs += '<input type="hidden" name="attachments[]" value="' + result.files[i].id + '">';

                }
                
                $('.attachments', window.parent.document).append(html);
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
})();
</script>
{/literal}
