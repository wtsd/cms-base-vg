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
    
    /* End */
};