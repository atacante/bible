var site = {
    wallCheckInterval: 15000,//ms
    likesAjax: false
};
var reader = {};
var user = {};

site.fillSelect = function (selector,items){
    $(selector).empty();
    $.each(items, function(value,key) {
        $(selector).append($("<option></option>")
            .attr("value", value).text(key));
    })
}

site.fancyBoxMe = function (e) {
    var numElemets = $(".j-with-images img").size();
    if ((e + 1) == numElemets) {
        nexT = 0
    } else {
        nexT = e + 1
    }
    if (e == 0) {
        preV = (numElemets - 1)
    } else {
        preV = e - 1
    }
    //var tarGet = $('.j-with-images img').eq(e).data('href');
    var tarGet = $('.j-with-images .people-image').eq(e).data('image');
    tarGet = tarGet.replace('thumbs/', "");
    tarGet = tarGet.replace('\\', "");
    $.fancybox({
        href: tarGet,
        padding : 0,
        //openEffect	: 'elastic',
        //closeEffect	: 'elastic',
        helpers: {
            title: {
                type: 'inside'
            }
        },
        afterLoad: function () {
            //this.title = 'Image ' + (e + 1) + ' of ' + numElemets + ' :: <a href="javascript:;" onclick="site.fancyBoxMe(' + preV + ')">prev</a>&nbsp;&nbsp;&nbsp;<a href="javascript:;" onclick="site.fancyBoxMe(' + nexT + ')">next</a>'
        }
    }); // fancybox
} // fancyBoxMe

reader.showDiff = function(first,second){
    var diff = JsDiff.diffWords(first, second);
    var display = $('.j-diff-block');
    var text = '';
    diff.forEach(function(part){
        if(part.added){
            text = text+'<ins>'+part.value+'</ins>';
        }
        else if(part.removed){
            text = text+'<del>'+part.value+'</del>';
        }
        else{
            text = text+part.value;
        }
    });
    display.html(text);
}

site.dropzoneInit = function(){
    //Dropzone.js Options - Upload an image via AJAX.
    var fileList = new Array;
    var i =0;
    Dropzone.options.myDropzone = {
        uploadMultiple: false,
        previewsContainer: '#img-thumb-preview',
        previewTemplate: $('#preview-template').html(),
        addRemoveLinks: false,
        dictDefaultMessage: '',
        autoProcessQueue: true,
        init: function() {
            this.on("addedfile", function(file) {
            });
            this.on("thumbnail", function(file, dataUrl) {
            });
            this.on("success", function(file, res) {
                fileList[i] = {"serverFileName" : res.filename, "fileName" : file.name,"fileId" : i };
                i++;
            });
            this.on("removedfile", function(file) {
                var rmvFile = "";
                if(fileList.length > 0){
                    var f;
                    for(f in fileList){
                        if(fileList[f].fileName == file.name)
                        {
                            rmvFile = fileList[f].serverFileName;
                            fileList.splice(f,1);
                        }
                    }
                }

                $('input[name="images"]').val();

                if (rmvFile){
                    $.ajax({
                        type: 'POST',
                        url: '/admin/location/delete-image',
                        data: {filename: rmvFile,'_token':$('input[name="_token"]').val()},
                        dataType: 'html',
                        success: function(data){
                            /*var rep = JSON.parse(data);
                             if(rep.code == 200)
                             {

                             }*/
                        }
                    });
                }
            } );
        }
    };
    //myDropzone = new Dropzone("#my-dropzone",{
    //    url: "/admin/location/upload-image",
    //    headers: {
    //        'X-CSRF-Token': $('input[name="_token"]').val()
    //    }
    //});

    $('.j-select-image').on('click', function(e) {
        //trigger file upload select
        $("#my-dropzone").trigger('click');
    });

    Dropzone.autoDiscover = false;
}

site.deleteImage = function(element,url){
    var that = element;
    $.ajax({
        type: 'POST',
        url: url,
        data: {user_id: $('input[name="user_id"]').val(),filename: $(element).data('filename'),'_token':$('input[name="_token"]').val()},
        dataType: 'html',
        success: function(data){
            var rep = JSON.parse(data);
            $(that).parent().remove();
            $('.j-avatar-preview').remove();
        }
    });
}

site.getSelected = function(){
    var t = '';
    if(window.getSelection) {
        t = window.getSelection();
    } else if(document.getSelection) {
        t = document.getSelection();
    } else if(document.selection) {
        t = document.selection.createRange().text;
    }
    return t;
}

site.getNote = function(id){
    $.ajax({
        method: "GET",
        url: "/ajax/view-note",
        dataType: "html",
        data:{id:id},
        success:function(data){
            $('#popup').find('.modal-header .modal-title').text('Note');
            $('#popup').find('.modal-body').html(data);
            //$('#popup').find('.modal-footer').html('<a title="Print note" href="#" data-noteid="'+id+'" class="j-print-note pull-left"><i class="fa fa-print fa-2x"style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>');
            $('#popup').find('.modal-footer').html('');
            $('#popup').modal({show:true});
        }
    });
}

site.getJournal = function(id){
    $.ajax({
        method: "GET",
        url: "/ajax/view-journal",
        dataType: "html",
        data:{id:id},
        success:function(data){
            $('#popup').find('.modal-header .modal-title').text('Journal Entry');
            $('#popup').find('.modal-body').html(data);
            //$('#popup').find('.modal-footer').html('<a title="Print note" href="#" data-noteid="'+id+'" class="j-print-journal pull-left"><i class="fa fa-print fa-2x"style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>');
            $('#popup').find('.modal-footer').html('');
            $('#popup').modal({show:true});
        }
    });
}
site.getPrayer = function(id){
    $.ajax({
        method: "GET",
        url: "/ajax/view-prayer",
        dataType: "html",
        data:{id:id},
        success:function(data){
            $('#popup').find('.modal-header .modal-title').text('Prayer');
            $('#popup').find('.modal-body').html(data);
            //$('#popup').find('.modal-footer').html('<a title="Print note" href="#" data-noteid="'+id+'" class="j-print-journal pull-left"><i class="fa fa-print fa-2x"style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>');
            $('#popup').find('.modal-footer').html('');
            $('#popup').modal({show:true});
        }
    });
}

site.getStatus = function(id){
    $.ajax({
        method: "GET",
        url: "/ajax/view-status",
        dataType: "html",
        data:{id:id},
        success:function(data){
            $('#popup').find('.modal-header .modal-title').text('Status');
            $('#popup').find('.modal-body').html(data);
            $('#popup').find('.modal-footer').html('');
            $('#popup').modal({show:true});
        }
    });
}

site.getUser = function(id){
    $.ajax({
        method: "GET",
        url: "/user/view/"+id,
        dataType: "html",
        data:{id:id},
        success:function(data){
            $('#popup').find('.modal-header .modal-title').text('User Info');
            $('#popup').find('.modal-body').html(data);
            $('#popup').find('.modal-footer').html('');
            $('#popup').modal({show:true});
        }
    });
}

/**
* @status = 'public_all'|'public_friends'|'private'
* */
site.changeStatusIcon = function(status){

    var icon_class = 'bs-s-public';

    switch(status){
        case 'public_all':
            icon_class = 'bs-s-public';
            break;
        case 'public_friends':
            icon_class = 'bs-friends';
            break;
        case 'private':
            icon_class = 'bs-s-onlyme';
            break;
    }

    var icon = $(".j-status-icon");
    icon.removeClass();
    icon.addClass(icon_class + ' cu-print font-size-20 j-status-icon');
}

reader.getActionsHtml = function(){
    return '<div class="j-reader-actions" style="position: absolute;">' +
                '<a title="Create note" href="#" class="j-create-note btn-create-note btn-reader"><i class="bs-note"></i></a>' +
                '<div class="spliter1"></div>'+
                '<a title="Create Journal Entry" href="#" class="j-create-journal btn-create-journal btn-reader"><i class="bs-journal"></i></a>' +
                '<div class="spliter1"></div>'+
                '<a title="Create prayer" href="#" class="j-create-prayer btn-create-prayer btn-reader"><i class="bs-pray"></i></a>' +
                '<div class="spliter1 hidden"></div>'+
                '<a title="Show definition" href="#" class="hidden j-show-definition btn-show-definition btn-reader"><i class="bs-lexicon"></i></a>' +
                '<div class="popup-arrow2"></div>'+
            '</div>';
}

site.initCkeditors = function(){
    if($("#note-text,#journal-text,#prayer-text,.ckeditor").length > 0){
        $('#note-text,#journal-text,#prayer-text,.ckeditor').ckeditor({
            customConfig: '/js/ckeditor/config.js',
            filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
            filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()}}',
            filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
            filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token={{csrf_token()}}'
        });
    }
}

site.initTagging = function(){
    $(".j-tags").select2({
        width: '100%',
        tags: true,
        tokenSeparators: [',']
    });
}

site.initSelect2 = function(){
    $(".j-select2").select2({
        placeholder: $(".j-select2").attr('placeholder'),
        width: '100%',
    });
}

site.ajaxForm = function(form,callback){
    $('.form-group').removeClass('has-error');
    $('.help-block').remove();
    var url = form.attr('action');
    var formData = new FormData(form[0]);

    $('textarea').each(function(){
        var value;
        if(value = $(this).val()){
            formData.append($(this).attr('name'), value);
        }
    });

    $.ajax({
        method: "POST",
        url: url,
        data: formData,
        contentType : false,
        processData : false,
        success:function(data){
            $('#popup').modal('hide');
            if(callback){
                callback(data);
            }
            else{
                location.reload();
            }
        },
        error:function(data){
            $.each(data.responseJSON, function(index,value){
                var formGroup = $(':input[name="'+index+'"]').parent('.form-group');
                console.log(formGroup);
                formGroup.addClass('has-error');
                formGroup.append('<span class="help-block">'+value[0]+'</span>');
            });
        }
    });
}

site.loadComments = function(elem){
    var url = $(elem).attr('href');
    var that = elem;
    $.ajax({
        method: "GET",
        url: url,
        success:function(data){
            $(that).parents('.item-footer').find('.j-item-comments').html(data);
        }
    });
}

site.validateEmail = function (email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

site.FacebookInviteFriends = function ()
{
    FB.ui({
        method: 'share',
        href: 'http://bible.local/invite'
    });
}

reader.clearHighlights = function(){
    $('.j-reader-actions').remove();
    $('.j-lex-content').remove();
    $('.highlight').removeClass('highlight');
    $('.clicked').removeClass('clicked');
    $('.def-highlight').removeClass('def-highlight');
}

site.checkNewWallPosts = function(wallType){
    var url = false;
    switch (wallType){
        case 'public':
            url = location.href;
            break;
        case 'group':
            url = location.href;
            break;
    }
    $.ajax({
        method: "GET",
        url: url,
        data:{
            checkPosts:1,
            lastNoteId:$('#j-last-note-id').val(),
            lastJournalId:$('#j-last-journal-id').val(),
            lastPrayerId:$('#j-last-prayer-id').val(),
            lastStatusId:$('#j-last-status-id').val()
        },
        success:function(data){
            if(data > 0){
                $('.j-new-posts span').html(data);
                $('.j-new-posts').removeClass('hidden');
            }
        }
    });
    console.log('checkNewPosts for wall ' + wallType);
}

site.hideAlert = function(){

    var width = $('.in-inner-container').width();
     $('.alert-container').width(width);

    setTimeout(function(){
        $('.alert-container').fadeOut();
    }, 3000);
}
