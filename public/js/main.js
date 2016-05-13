$(document).ready(function(){
    $("#j-select-locations").select2();
    $("#j-select-peoples").select2();

    $(".j-with-images img").each(function (i) {
        $(this).bind('click', function () {
            site.fancyBoxMe(i);
        }); //bind
    });

    $('body .word-definition .word-definition').on('mouseenter mouseleave', function(e) {
        if(e.type === 'mouseenter'){
            $(this).parent().css('background', "none");
        }
        else{
            $(this).parent().attr('style','');
        }

    });

    $('body').on('click', function (e) {
        if($('.fancybox-image').length == 0){
            $('.word-definition').each(function () {
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    $(this).popover('hide');
                    $(this).popover('destroy');
                }
            });
        }
    });

    $('body').on('click','.word-definition',function(e){
        var definitionId = $(this).data('lexid');
        var lexversion = $(this).data('lexversion');
        e.stopPropagation();
        $('.word-definition').each(function(){
            $(this).popover('destroy');
        });
        var that = this;

        $.ajax({
            method: "GET",
            url: "/ajax/lexicon-info",
            dataType: "html",
            data:{lexversion:lexversion,definition_id:definitionId},
            success:function(data){
                $(that).popover('destroy');
                $(that).popover(
                    {
                        html: true,
                        placement:'auto',
                        title: "Lexicon - \""+$(that).html()+'"',
                        content: '<div class="j-lex-content text-center" style="">'+data+'</div>',//loader <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
                    }
                );
                $(that).popover('show');
            }
        });

        $(that).popover('show');
    });

    $('body').on('shown.bs.popover', function () {
        $(".j-with-images img").each(function (i) {
            $(this).bind('click', function () {
                site.fancyBoxMe(i);
            }); //bind
        });
    })

    $('a[data-confirm]').click(function(ev) {
        var href = $(this).attr('href');
        $('#confirm-delete').find('.modal-header .modal-title').text($(this).attr('data-header'));
        $('#confirm-delete').find('.modal-body').text($(this).attr('data-confirm'));
        $('#confirm-delete').find('.btn-ok').attr('href', href);
        $('#confirm-delete').modal({show:true});
        return false;
    });

    $('.j-note-text').click(function(ev) {
        var id = $(this).data('noteid');
        $.ajax({
            method: "GET",
            url: "/ajax/view-note",
            dataType: "html",
            data:{id:id},
            success:function(data){
                $('#popup').find('.modal-header .modal-title').text('Note');
                $('#popup').find('.modal-body').html(data);
                $('#popup').find('.modal-footer').html('<a title="Print note" href="#" data-noteid="'+id+'" class="j-print-note pull-left"><i class="fa fa-print fa-2x"style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>');
                $('#popup').modal({show:true});
            }
        });
    });

    $('.j-journal-text').click(function(ev) {
        var id = $(this).data('journalid');
        $.ajax({
            method: "GET",
            url: "/ajax/view-journal",
            dataType: "html",
            data:{id:id},
            success:function(data){
                $('#popup').find('.modal-header .modal-title').text('Journal Entry');
                $('#popup').find('.modal-body').html(data);
                //$('#popup').find('.modal-footer').html('<a title="Print note" href="#" data-noteid="'+id+'" class="j-print-journal pull-left"><i class="fa fa-print fa-2x"style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>');
                $('#popup').modal({show:true});
            }
        });
    });

    $('.navbar').on('change','select[name=readerMode]',function(){
        location.href = '/reader/mode/'+$(this).val();
    });
    $('.navbar').on('change','input[type=radio][name=readerMode]',function(){
        location.href = '/reader/mode/'+$(this).val();
    });

    $('.j-verses-filters').on('focus','select[name=version]',function(){
        $(this).data('prevval', $(this).val());
    });

    $('.j-verses-filters').on('change','select[name=version]',function(){
        var prevVal = $(this).data('prevval');
        var curVal = $(this).val();
        $.ajax({
            method: "GET",
            url: "/ajax/books-list",
            dataType: "json",
            data:{version:curVal},
            success:function(data){
                if(prevVal == 'berean' || curVal == 'berean'){
                    site.fillSelect('select[name=book]',data);
                    $('select[name=book]').change();
                }
            }
        });
    });

    $('.j-verses-filters').on('change','select[name=book]',function(){
        $.ajax({
            method: "GET",
            url: "/ajax/chapters-list",
            dataType: "json",
            data:{book_id:$(this).val()},
            success:function(data){
                site.fillSelect('select[name=chapter]',data);
                $('select[name=chapter]').change();
            }
        });
    });

    $('.j-admin-verses-filters').on('change','select[name=book]',function(){
        var value = $(this).val();
        $.ajax({
            method: "GET",
            url: "/ajax/chapters-list",
            dataType: "json",
            data:{book_id:value},
            success:function(data){
                $('select[name=verse]').empty().append($("<option></option>").attr("value", 0).text('All Verses')).attr('disabled',true);
                site.fillSelect('select[name=chapter]',data);
                $('select[name=chapter]').prepend($("<option></option>").attr("value", 0).text('All Chapters'));
                if(value == 0){
                    $('select[name=chapter]').attr('disabled',true);
                }
                else{
                    $('select[name=chapter]').removeAttr('disabled');
                }
            }
        });
    });

    $('.j-verses-filters').on('change','select[name=chapter]',function(){
        $.ajax({
            method: "GET",
            url: "/ajax/verses-list",
            dataType: "json",
            data:{book_id:$('select[name=book]').val(),chapter:$('select[name=chapter]').val()},
            success:function(data){
                site.fillSelect('select[name=verse]',data);
            }
        });
    });

    $('.j-admin-verses-filters').on('change','select[name=chapter]',function(){
        var value = $(this).val();
        $.ajax({
            method: "GET",
            url: "/ajax/verses-list",
            dataType: "json",
            data:{book_id:$('select[name=book]').val(),chapter:$('select[name=chapter]').val()},
            success:function(data){
                site.fillSelect('select[name=verse]',data);
                $('select[name=verse]').prepend($("<option></option>").attr("value", 0).text('All Verses'));
                if(value == 0){
                    $('select[name=verse]').attr('disabled',true);
                }
                else{
                    $('select[name=verse]').removeAttr('disabled');
                }
            }
        });
    });

    $('.j-chapter-content').parent().on('click','.j-print-chapter',function(e){
        e.preventDefault();
        $.ajax({
            method: "GET",
            url: "/ajax/print-chapter",
            dataType: "html",
            data:$('.j-verses-filters form').serialize(),
            success:function(data){
                //var printContents = data;
                //var originalContents = $('body').html();
                //$('body').html(printContents);
                //window.print();
                //$('body').html(originalContents);
                //location.reload();

                var printChapter = window.open('', '', 'height='+$(window).height()+',width='+$(window).width());
                printChapter.document.write(data);
                printChapter.print();
                printChapter.close();
            }
        });
    });

    $('.j-my-notes-list').parent().on('click','.j-print-note',function(e){
        e.preventDefault();
        $.ajax({
            method: "GET",
            url: "/ajax/print-note",
            dataType: "html",
            data:{id:$(this).data('noteid')},
            success:function(data){
                var printNote = window.open('', '', 'height='+$(window).height()+',width='+$(window).width());
                printNote.document.write(data);
                printNote.print();
                printNote.close();
            }
        });
    });

    $('.j-locations-list').parent().on('click','.j-view-embed-map',function(e){
        e.preventDefault();
        $.ajax({
            method: "GET",
            url: "/ajax/location-map-embed-code",
            dataType: "html",
            data:{id:$(this).data('locationid')},
            success:function(data){
                if(data){
                    $('#popup').find('.modal-header .modal-title').text('Location Map');
                    $('#popup').find('.modal-body').html(data);
                    $('#popup').find('.modal-footer').html('');
                    $('#popup').modal({show:true});
                }
            }
        });
    });

    $('.j-my-notes-list').parent().on('click','.j-print-all-notes',function(e){
        e.preventDefault();
        var checks = $('input[class="check"]:checked');
        if(checks.length == 0){
            $('#popup').find('.modal-header .modal-title').text('Warning');
            $('#popup').find('.modal-body').html("Please select notes witch you want to print");
            $('#popup').find('.modal-footer').html('');
            $('#popup').modal({show:true});
            return false;
        }
        var noteIds = [];
        $('input:checkbox[class="check"]:checked').each(function(){
            noteIds.push($(this).data('noteid'));
        });

        $.ajax({
            method: "GET",
            url: "/ajax/print-note",
            dataType: "html",
            data:{id:noteIds},
            success:function(data){
                var printNote = window.open('', '', 'height='+$(window).height()+',width='+$(window).width());
                printNote.document.write(data);
                printNote.print();
                printNote.close();
            }
        });
    });

    $("#checkAll").click(function () {
        $(".check").prop('checked', $(this).prop('checked'));
    });

    //$('.edit-images-thumbs').on('mouseover','.img-thumb',function(){
    //    console.log('hover');
    //    $(this).find('img').fadeTo(500, 0.5);
    //});
    //$('.edit-images-thumbs').on('mouseout','.img-thumb',function(){
    //    console.log('out');
    //    $(this).find('img').fadeTo(500, 1);
    //});

    $(".img-thumb").hover(
    function() {
        $(this).find('img').stop().animate({"opacity": "0.5"}, "slow");
        $(this).find('.j-remove-image').stop().animate({"opacity": "1"}, "slow");
    },
    function() {
        $(this).find('img').stop().animate({"opacity": "1"}, "slow");
        $(this).find('.j-remove-image').stop().animate({"opacity": "0"}, "slow");
    });

    $('.edit-images-thumbs.location-images').on('click','.j-remove-image',function(){
        site.deleteImage(this,'/admin/location/delete-image');
    });

    $('.edit-images-thumbs.people-images').on('click','.j-remove-image',function(){
        site.deleteImage(this,'/admin/peoples/delete-image');
    });

    //site.dropzoneInit();
    $("body").mousedown(function(eventObject) {
        if(!$(eventObject.target).hasClass('j-create-note') && !$(eventObject.target).parent().hasClass('j-create-note')){
            $('.j-create-note').remove();
        }
        if(!$(eventObject.target).hasClass('j-create-journal') && !$(eventObject.target).parent().hasClass('j-create-journal')){
            $('.j-create-journal').remove();
        }
    });
    $(".j-bible-text").mouseup(function(eventObject) {
        var selectedObject = site.getSelected();
        var text = selectedObject.toString();
        if(text){
            var startElement = selectedObject.anchorNode.parentElement;
            var endElement = selectedObject.focusNode.parentElement;
            var version = $(startElement).data('version');
            if(!version){
                version = $(startElement).parents('.j-verse-text').data('version');
            }
            if(!version){
                version = $(endElement).data('version');
                if(!version){
                    version = $(endElement).parents('.j-verse-text').data('version');
                }
            }

            var startVerseId = $(startElement).data('verseid');
            if(!startVerseId){
                startVerseId = $(startElement).parents('.j-verse-text').data('verseid');
            }
            var endVerseId = $(endElement).data('verseid');
            if(!endVerseId){
                endVerseId = $(endElement).parents('.j-verse-text').data('verseid');
            }

            var verseId = 0;
            if(!startVerseId || !endVerseId){
                verseId = Math.max((startVerseId || 0), (endVerseId || 0));
            }
            else{
                verseId = Math.min((startVerseId || 0), (endVerseId || 0));
            }

            var menu = '<a href="/notes/create?version='+(version || '')+'&verse_id='+verseId+'&text='+text+'" class="j-create-note" style="position: absolute; width: 32px; height: 32px; background: #367fa9; color:white; font-size: 1.2em; border-radius: 16px; padding: 5px 5px 5px 9px;"><i class="fa fa-btn fa-sticky-note"></i></a>';
            menu += '<a href="/journal/create?version='+(version || '')+'&verse_id='+verseId+'&text='+text+'" class="j-create-journal" style="position: absolute; width: 32px; height: 32px; background: #367fa9; color:white; font-size: 1.2em; border-radius: 16px; padding: 5px 5px 5px 9px;"><i class="fa fa-btn fa-book"></i></a>';
            $('body').append(menu);
            $('.j-create-note').css({
                top: ($(endElement).offset().top-26) + "px",
                left: (eventObject.pageX-15) + "px"
            }).animate( { "opacity": "show", top:($(endElement).offset().top-35)} , 200 );
            $('.j-create-journal').css({
                top: ($(endElement).offset().top-26) + "px",
                left: (eventObject.pageX+20) + "px"
            }).animate( { "opacity": "show", top:($(endElement).offset().top-35)} , 200 );
        }
        else {
            $('.j-create-note').remove();
            $('.j-create-journal').remove();
        }
    });
});