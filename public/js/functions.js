var site = {
    wallCheckInterval: 15000,//ms
    likesAjax: false
};
var reader = {
    highlightMode: false
};
var user = {};

var order = {};

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

    if(site.isAppleMobile()){
        try{
            if(t == ''){
                if (window.getSelection) {
                    t = window.getSelection().getRangeAt(0);
                } else {
                    t = document.getSelection().getRangeAt(0);
                }
            }
        }catch(err){

        }

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
            $('#popup').find('.modal-header .modal-title').html('<div class="pull-left modal-title-text"><i class="bs-note"></i> Note</div>');
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
            $('#popup').find('.modal-header .modal-title').html('<div class="pull-left modal-title-text"><i class="bs-journal"></i> Journal Entry</div>');
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
            $('#popup').find('.modal-header .modal-title').html('<div class="pull-left modal-title-text"><i class="bs-pray"></i> Prayer</div>');
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
    icon.addClass(icon_class + ' cu-print font-size-20 j-status-icon color9');
}

reader.getActionsHtml = function(context){
    var versionId = $(context).data('version');
    var verseId = $(context).data('verseid');
    return '<div class="j-reader-actions" data-verseid="'+verseId+'" style="position: absolute;">' +
                '<a title="Create note" href="#" class="j-create-note btn-create-note btn-reader"><i class="bs-note"></i></a>' +
                '<div class="spliter1"></div>'+
                '<a title="Create Journal Entry" href="#" class="j-create-journal btn-create-journal btn-reader"><i class="bs-journal"></i></a>' +
                '<div class="spliter1"></div>'+
                '<a title="Create prayer" href="#" class="j-create-prayer btn-create-prayer btn-reader"><i class="bs-pray"></i></a>' +
                '<div class="spliter1"></div>'+
                // '<a title="Bookmark verse" href="#" class="j-bookmark-verse btn-bookmark-verse btn-reader"><i class="fa fa-bookmark-o"></i></a>' +
                '<a href="http://bible.local/reader/delete-bookmark/verse/'+versionId+'/'+verseId+'" class="j-bookmark btn-bookmark-verse btn-reader '+($(context).hasClass('j-bookmarked')?"":"hidden")+'"><i title="Remove verse from bookmarks" class="fa fa-bookmark cu-print"></i></a>' +
                '<a href="http://bible.local/reader/bookmark/verse/'+versionId+'/'+verseId+'" class="j-bookmark btn-bookmark-verse btn-reader '+($(context).hasClass('j-bookmarked')?"hidden":"")+'"><i title="Add verse to bookmarks" class="fa fa-bookmark-o cu-print"></i></a>' +
                '<div class="spliter1 hidden"></div>'+
                '<a title="Show definition" href="#" class="hidden j-show-definition btn-show-definition btn-reader"><i class="bs-lexicon"></i></a>' +
                /*'<div class="spliter1"></div>'+
                '<a title="Show definition" href="#" class="j-bookmark btn-add-bookmark btn-reader"><i style="font-size: 1.9rem;" class="fa fa-bookmark-o"></i></a>' +*/
                '<div class="popup-arrow2"></div>'+
            '</div>';
}

reader.getHighlightActionsHtml = function(withRemove){
    // Class for Iphone/Ipad/Ipod
    var imobile = '';
    if(site.isAppleMobile()){
        imobile = 'imobile';
    }

    return '<div class="j-reader-actions highlight-actions '+imobile+'" style="position: absolute;">' +
        '<a title="Highlight selected text" href="#" class="j-highlight-text j-green btn-reader" data-colorclass="j-green"><i class="bs-journal"></i></a>' +
        // '<div class="spliter1"></div>'+
        '<a title="Highlight selected text" href="#" class="j-highlight-text j-yellow btn-reader" data-colorclass="j-yellow"><i class="bs-journal"></i></a>' +
        (withRemove?'<a title=" Remove highlighted text" href="#" class="j-remove-highlighted-text  btn-remove-highlighted-text btn-reader"><i class="fa fa-eraser"></i></a>':'') +
        '<div class="popup-arrow2"></div>'+
        '</div>';
}

reader.highlight = function(keyword,colorClass,markId) {
    // Determine selected options
    var options = {
        acrossElements:true,
        separateWordSearch:false,
        className: colorClass+' j-mark-'+markId
    };
    $(".j-bible-text")/*.unmark()*/.mark(keyword, options);
    $(".j-bible-text").find('.j-mark-'+markId).each(function(){
        // $($(this).context).data('id',markId);
        $(this).data('id',markId);
    });
};

reader.clearSelection = function(){
    if (window.getSelection) {
        if (window.getSelection().empty) {  // Chrome
            window.getSelection().empty();
        } else if (window.getSelection().removeAllRanges) {  // Firefox
            window.getSelection().removeAllRanges();
        }
    } else if (document.selection) {  // IE?
        document.selection.empty();
    }
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
        tokenSeparators: [','],
        selectOnClose: true,
        selectOnBlur: true
    });
}

site.initSelect2 = function(){
    $(".j-select2,.j-select2-country,.j-select2-state").select2({
        placeholder: $(".j-select2").attr('placeholder'),
        width: '100%',
    });
    $('.j-select2-country').on('select2:select', function (evt) {
        if($(this).val() == 226){
            $('.j-state').toggleClass('hidden');
        }
        else{
            $('.j-state').removeClass('hidden');
            $('.j-select2-state').parents('.j-state').addClass('hidden');
        }
    });
    $('.j-select2-state').on('select2:select', function (evt) {
        $('.j-state input[name=state]').val($(this).val());
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
                var formGroup = $(':input[name="'+index+'"],:input[name="'+index+'[]"]').parent('.form-group');
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

reader.clearHighlights = function(definition_obj, definition_word){
    $('.j-reader-actions').remove();
    if(definition_obj){
        definition_obj.parent('.j-lex-content').remove();
    }
    if(definition_word){
        $(definition_word).removeClass('highlight');
        $(definition_word).parent('.j-verse-text').removeClass('clicked');
        $(definition_word).after(' ');
    }

    $('.def-highlight').removeClass('def-highlight');
}

reader.closeDefinition = function(){
    $('.j-lex-content').on('click','.j-btn-reset', function(){

        var definitionId = $(this).parent().data('lexid');
        var definition_word = reader.getDefinitionWord(definitionId);

        reader.clearHighlights($(this), definition_word);
        reader.recalculateAllActiveLexicons(definitionId);
    });
}

reader.getDefinitionWord = function(definitionId){
    return $('.word-definition[data-lexid='+definitionId+']').first();
}

reader.setLexiconArrowOffset = function(definitionId){
    var definition_word = reader.getDefinitionWord(definitionId);
    var definitionWordHeight = $(definition_word).height();

    var definitionWordWidth = $(definition_word).width();
    var definitionStartPosition = $(definition_word).offset().left;
    var definitionEndPosition = definitionStartPosition + definitionWordWidth;

    var lexicon = $('.j-lex-content[data-lexid="'+definitionId+'"]');

    var lexiconPopupStartPosition = $(lexicon).offset().left;

    var leftOffset = 0;

    // Check if definition has 2 lines or more
    if(definitionWordHeight < 40){
        leftOffset = (definitionStartPosition + definitionEndPosition)/2 - 15 - lexiconPopupStartPosition;
    }else{
        leftOffset = definitionStartPosition - lexiconPopupStartPosition + 15 ;
    }

    $(lexicon).children('.popup-arrow3').css({
        left: leftOffset + "px"
    });
}

reader.recalculateAllActiveLexicons = function(){
    $('.j-lex-content').each(function(){
        var definitionId = $(this).data('lexid');

        reader.setLexiconArrowOffset(definitionId);
    })
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
}

site.hideAlert = function(){

    var width = $('.in-inner-container').width();
    var container = $('.alert-container');


    if(location.pathname == '/'){
        width = $('.inner-container').width();
        container = $('.home-alert-container');
    }

     container.width(width);

    setTimeout(function(){
        container.fadeOut();
    }, 3000);
}

site.showAuthWarning = function(text){
    $('#popup-sm').find('.modal-header .modal-title').text('Warning');
    $('#popup-sm').find('.modal-body').html("Log in to your account to use this feature.");
    $('#popup-sm').find('.modal-footer').html('' +
        '<button type="button" class="btn4-kit cu-btn-pad1" data-dismiss="modal">Cancel</button>' +
        '<a href="/auth/login" class="btn2-kit cu-btn-pad1 btn-ok" style="margin-left: 7px;">Login</a>' +
        '');
    $('#popup-sm').modal({show:true});
}

site.showPremiumWarning = function(){
    $('#popup-sm').find('.modal-header .modal-title').text('Premium Feature');
    $('#popup-sm').find('.modal-body').html("Upgrade to a premium membership to use this feature.");
    $('#popup-sm').find('.modal-footer').html('' +
        '<button type="button" class="btn4-kit cu-btn-pad1" data-dismiss="modal">Cancel</button>' +
        '<a href="/user/profile" class="btn2-kit cu-btn-pad1 btn-ok" style="margin-left: 7px;">Upgrade</a>' +
    '');
    $('#popup-sm').modal({show:true});
}

order.fillBillingInfo = function(){

    $('input[name^=shipping_]').each(function(){
        var shippingInput = $(this);
        var name = shippingInput.attr('name').replace('shipping_','');

        var billingInput = $('input[name=billing_'+name+']');
        if(billingInput.val() == ''){
            billingInput.val(shippingInput.val())
        }

    });



    var shippingAddress = $('input[name="shipping_address"]');
    var billingAddress = $('.j-billing-meta input[name="billing_address"]');

    billingAddress.val(shippingAddress.val());
}

reader.getHighlights = function(){
    if($('.j-diff-block').length == 0){
        $.ajax({
            method: "GET",
            url: '/reader/get-highlights',
            dataType:'json',
            data:{
                version:$('select[name=version]').val(),
                book:$('select[name=book]').val(),
                chapter:$('select[name=chapter]').val(),
            },
            success:function(data){
                $.each(data,function (index,item) {
                    reader.highlight(item.highlighted_text,item.color,item.id);
                });
            }
        });
    }
}


/* ToDo: Refactor this function site.isAppleMobile using isMobile.iOS() */
site.isAppleMobile = function()
{
    if (navigator && navigator.userAgent && navigator.userAgent != null)
    {
        var strUserAgent = navigator.userAgent.toLowerCase();
        var arrMatches = strUserAgent.match(/(iphone|ipod|ipad)/);
        if (arrMatches != null)
            return true;
    } // End if (navigator && navigator.userAgent)

    return false;
}

var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};

reader.getSelectedNodes = function(selectedObject){
         reader.startElement = '';
         reader.endElement = '';

        if(selectedObject.startContainer && selectedObject.endContainer){
            reader.startElement = selectedObject.startContainer.parentElement;
            reader.endElement = selectedObject.endContainer.parentElement;
        }else{
            reader.startElement = selectedObject.anchorNode.parentElement;
            reader.endElement = selectedObject.focusNode.parentElement;
        }
    return true;
}
