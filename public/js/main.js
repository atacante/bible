$(document).ready(function(){
    var clipboard = new Clipboard('.copy');
    var hidev = true;
    clipboard.on('success', function(e) {
        e.clearSelection();
    });

    $("#j-select-locations,#j-select-peoples").select2();
    $(".j-entry-types").select2({
        placeholder: "All types",
    });
    $(".j-select-user").select2({
        placeholder: "Select user...",
    });
    $(".j-compare-versions").select2({
        maximumSelectionLength: 2,
        placeholder: "Start Typing Version Name (or Language)",
    });
    $('.j-compare-versions').on('select2:selecting', function (evt) {
        hidev = false;
        setTimeout(function(){
            hidev = true;
        },500);
        evt.stopPropagation();
    });

    $(".j-select2-ajax").select2({
        minimumInputLength: 2,
        placeholder: $(".j-select2-ajax").attr('placeholder'),
        ajax:{
            url: $(".j-select2-ajax").data('url'),
            dataType: 'json',
            data: function(term,page){
                return term;
            },
            processResults: function (data, params) {
                //console.log(data);
                return {results: data}
            }
        },
    });

    $(".j-invite-emails").select2({
        tags: true,
        allowClear: true,
        dropdownCssClass: 'hideSearch',
        tokenSeparators: [',',' ']
    });
    $('.j-invite-emails').on('select2:selecting', function (evt) {
        if(!site.validateEmail(evt.params.args.data.id)){
            $('#popup-sm').find('.modal-header .modal-title').text('Warning');
            $('#popup-sm').find('.modal-body').html("Please enter valid email address");
            $('#popup-sm').find('.modal-footer').html('<a class="btn btn-danger btn-ok" data-dismiss="modal">Ok</a>');
            $('#popup-sm').modal({show:true});
            return false;
        }
    });

    site.initSelect2();
    site.initTagging();

    $('.coupon-datepicker').datepicker(
        {
            autoclose:true,
            startDate: '+1d'
        }
    );

    $('.datepicker').datepicker(
        {
            autoclose:true,
            //format: 'mm/dd/yyyy'
        }
    ).on('changeDate', function(e) {
        var name = $(this).attr('name');
        var dateFromField = $('input[name="date_from"]');
        var dateToField = $('input[name="date_to"]');
        var timeFrom = (new Date(dateFromField.val()).getTime());
        var timeTo = (new Date(dateToField.val()).getTime());
        if(dateFromField.val() && dateToField.val() && timeFrom > timeTo){
            $('#popup-sm').find('.modal-header .modal-title').text('Warning');
            $('#popup-sm').find('.modal-body').html("End date must be larger than start date");
            $('#popup-sm').find('.modal-footer').html('<a class="btn btn-danger btn-ok" data-dismiss="modal">Ok</a>');
            $('#popup-sm').modal({show:true});
            $(this).val('');
        }
        switch (name){
            case 'date_from':
                console.log(e.timeStamp);
                break;
            case 'date_to':
                console.log(e.timeStamp);
                break;
        }
    });

    $(".j-with-images img").each(function (i) {
        $(this).bind('click', function () {
            site.fancyBoxMe(i);
        }); //bind
    });

    $('body .word-definition .word-definition').on('mouseenter mouseleave', function(e) {
        if(e.type === 'mouseenter'){
            $(this).parent().css('background', "none");
            $(this).parent().css('color', "#333");
        }
        else{
            $(this).parent().attr('style','');
        }
    });

    $('body').on('click', function (e) {
        if(!$(e.target).hasClass('j-highlight-verse') && $(e.target).parents('.related-item').length == 0 && !$(e.target).hasClass('modal') && $(e.target).parents('.modal').length == 0){
            $('.j-verse-text').removeClass('highlight');
            $('.related-item').removeClass('highlight');
            $('.related-item').removeClass('blur');
        }
        if($('.fancybox-image').length == 0){
            $('.word-definition').each(function () {
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    $(this).popover('hide');
                    $(this).popover('destroy');
                }
            });
        }
    });

    //Old reader logic
/*    $('body').on('click','.word-definition',function(e){
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
    });*/

    //New reader logic
    $('body').on('click','.word-definition',function(e) {
        var definitionId = $(this).data('lexid');
        var lexversion = $(this).data('lexversion');
        e.stopPropagation();

        $('.j-reader-actions div,a').removeClass('hidden');

        $('.j-show-definition').attr('data-lexid',definitionId);
        $('.j-show-definition').attr('data-lexversion',lexversion);



    });


     $('body').on('click','.j-show-definition',function(e) {

         var definitionId = $(this).data('lexid');
         var lexversion = $(this).data('lexversion');

        $('.word-definition').each(function(){
            $(this).popover('destroy');
        });

        var definition_word =  $('.word-definition[data-lexid='+definitionId+']');

        var that = $(definition_word).parent();

        $.ajax({
            method: "GET",
            url: "/ajax/lexicon-info",
            dataType: "html",
            data:{lexversion:lexversion,definition_id:definitionId},
            success:function(data){
                $('.j-reader-actions').remove();
                $('.j-lex-content').remove();
                $('.word-definition').removeClass('highlight');

                $(that).append(
                    '<div class="j-lex-content text-center" style="">'+data+'</div>'
                );
                $(definition_word).addClass('highlight');
            }
        });

         return false;

    });

    $('body').on('shown.bs.popover', function () {
        $(".j-with-images img").each(function (i) {
            $(this).bind('click', function () {
                site.fancyBoxMe(i);
            }); //bind
        });
    })

    $('a[data-target="#confirm-delete"]').click(function(ev) {
        var href = $(this).attr('href');
        $('#confirm-delete').find('.modal-header .modal-title').text($(this).attr('data-header'));
        $('#confirm-delete').find('.modal-body').text($(this).attr('data-confirm'));
        $('#confirm-delete').find('.btn-ok').attr('href', href);
        $('#confirm-delete').modal({show:true});
        return false;
    });

    $('body').on('click','a[data-target="#cancel-request-sm"]', function(ev) {
        var href = $(this).attr('href');
        $('#cancel-request-sm').attr('data-itemid',$(this).attr('data-itemid'));
        $('#cancel-request-sm').find('.modal-header .modal-title').text($(this).attr('data-header'));
        $('#cancel-request-sm').find('.modal-body').text($(this).attr('data-confirm'));
        $('#cancel-request-sm').find('.btn-ok').attr('href', href).addClass($(this).attr('data-callclass')?$(this).attr('data-callclass'):'j-cancel-request');
        $('#cancel-request-sm').modal({show:true});
        return false;
    });

    $('body').on('click','.j-note-text',function(ev) {
        ev.preventDefault();
        var id = $(this).data('noteid');
        site.getNote(id);
    });

    $('body').on('click','.j-item-body',function(ev) {
        var id = $(this).data('itemid');
        var type = $(this).data('itemtype');
        if($(ev.target).hasClass('j-image-thumb')){
            return false;
        }
        switch (type){
            case 'note':
                site.getNote(id);
                break;
            case 'journal':
                site.getJournal(id);
                break;
            case 'prayer':
                site.getPrayer(id);
                break;
            case 'status':
                site.getStatus(id);
                break;
        }
    });

    $('body').on('click','.j-journal-text',function(ev) {
        ev.preventDefault();
        var id = $(this).data('journalid');
        site.getJournal(id);
    });

    $('body').on('click','.j-prayer-text',function(ev) {
        ev.preventDefault();
        var id = $(this).data('prayerid');
        site.getPrayer(id);
    });

    $('body').on('click','.j-status-text',function(ev) {
        ev.preventDefault();
        var id = $(this).data('statusid');
        site.getStatus(id);
    });

    $('body').on('click','.friend-item .j-friend-item',function(ev) {
        ev.preventDefault();
        var id = $(this).data('userid');
        site.getUser(id);
    });

    $('body').on('change','select[name=readerMode]',function(){
        location.href = '/reader/mode/'+$(this).val();
    });
    $('body').on('change','input[type=radio][name=readerMode]',function(){
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
                $('select[name=chapter]').val(0);
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
                $('select[name=verse]').val(0);
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

    $('.j-my-notes-list,.j-my-entries-list').parent().on('click','.j-print-note',function(e){
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

    $('.j-my-journal-list,.j-my-entries-list').parent().on('click','.j-print-journal',function(e){
        e.preventDefault();
        $.ajax({
            method: "GET",
            url: "/ajax/print-journal",
            dataType: "html",
            data:{id:$(this).data('journalid')},
            success:function(data){
                var printNote = window.open('', '', 'height='+$(window).height()+',width='+$(window).width());
                printNote.document.write(data);
                printNote.print();
                printNote.close();
            }
        });
    });

    $('.j-my-prayers-list,.j-my-entries-list').parent().on('click','.j-print-prayer',function(e){
        e.preventDefault();
        $.ajax({
            method: "GET",
            url: "/ajax/print-prayer",
            dataType: "html",
            data:{id:$(this).data('prayerid')},
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

    $('.j-my-journal-list').parent().on('click','.j-print-all-journal',function(e){
        e.preventDefault();
        var checks = $('input[class="check"]:checked');
        if(checks.length == 0){
            $('#popup').find('.modal-header .modal-title').text('Warning');
            $('#popup').find('.modal-body').html("Please select journal entries witch you want to print");
            $('#popup').find('.modal-footer').html('');
            $('#popup').modal({show:true});
            return false;
        }
        var journalIds = [];
        $('input:checkbox[class="check"]:checked').each(function(){
            journalIds.push($(this).data('journalid'));
        });

        $.ajax({
            method: "GET",
            url: "/ajax/print-journal",
            dataType: "html",
            data:{id:journalIds},
            success:function(data){
                var printNote = window.open('', '', 'height='+$(window).height()+',width='+$(window).width());
                printNote.document.write(data);
                printNote.print();
                printNote.close();
            }
        });
    });

    $('.j-my-prayers-list').parent().on('click','.j-print-all-prayers',function(e){
        e.preventDefault();
        var checks = $('input[class="check"]:checked');
        if(checks.length == 0){
            $('#popup').find('.modal-header .modal-title').text('Warning');
            $('#popup').find('.modal-body').html("Please select prayers witch you want to print");
            $('#popup').find('.modal-footer').html('');
            $('#popup').modal({show:true});
            return false;
        }
        var prayersIds = [];
        $('input:checkbox[class="check"]:checked').each(function(){
            prayersIds.push($(this).data('prayerid'));
        });

        $.ajax({
            method: "GET",
            url: "/ajax/print-prayer",
            dataType: "html",
            data:{id:prayersIds},
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

    $(document).on({
        mouseenter: function () {
            $(this).find('img').stop().animate({"opacity": "0.5"}, "slow");
            $(this).find('.j-remove-image').stop().animate({"opacity": "1"}, "slow");
        },
        mouseleave: function () {
            $(this).find('img').stop().animate({"opacity": "1"}, "slow");
            $(this).find('.j-remove-image').stop().animate({"opacity": "0"}, "slow");
        }
    }, ".img-thumb");

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

    $('.edit-images-thumbs.group-images').on('click','.j-remove-image',function(){
        site.deleteImage(this,'/groups/delete-image');
    });

    $('#popup,.edit-images-thumbs.notes-images,.edit-images-thumbs.journal-images,.edit-images-thumbs.prayers-images').on('click','.j-remove-image',function(){
        site.deleteImage(this,$(this).data('url'));
    });

    $('.edit-images-thumbs.product-images').on('click','.j-remove-image',function(){
        site.deleteImage(this,'/admin/shop-products/delete-image');
    });

    //site.dropzoneInit();
    $("body").mousedown(function(eventObject) {
        /*if(!$(eventObject.target).hasClass('j-create-note') && !$(eventObject.target).parent().hasClass('j-create-note')){
            $('.j-create-note').remove();
        }
        if(!$(eventObject.target).hasClass('j-create-journal') && !$(eventObject.target).parent().hasClass('j-create-journal')){
            $('.j-create-journal').remove();
        }
        if(!$(eventObject.target).hasClass('j-create-prayer') && !$(eventObject.target).parent().hasClass('j-create-prayer')){
            $('.j-create-prayer').remove();
        }*/
        if(!$(eventObject.target).hasClass('j-reader-actions') && !$(eventObject.target).parent().parent().hasClass('j-reader-actions')){
            $('.j-reader-actions').remove();
        }
    });

// Old logic of editing
/*    $(".j-bible-text").mouseup(function(eventObject) {
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

            /!*var menu = '<a title="Create note" href="/notes/create?version='+(version || '')+'&verse_id='+verseId+'&text='+text+'" class="j-create-note" style="position: absolute; width: 32px; height: 32px; background: #367fa9; color:white; font-size: 1.2em; border-radius: 16px; padding: 5px 5px 5px 9px;"><i class="fa fa-btn fa-sticky-note"></i></a>';
            menu += '<a title="Create Journal Entry" href="/journal/create?version='+(version || '')+'&verse_id='+verseId+'&text='+text+'" class="j-create-journal" style="position: absolute; width: 32px; height: 32px; background: #367fa9; color:white; font-size: 1.2em; border-radius: 16px; padding: 5px 5px 5px 9px;"><i class="fa fa-btn fa-book"></i></a>';
            menu += '<a title="Create prayer" href="/prayers/create?version='+(version || '')+'&verse_id='+verseId+'&text='+text+'" class="j-create-prayer" style="position: absolute; width: 32px; height: 32px; background: #367fa9; color:white; font-size: 1.2em; border-radius: 16px; padding: 5px 5px 5px 8px;"><i class="fa fa-btn fa-hand-paper-o"></i></a>';*!/

            $('body').append(reader.getActionsHtml());
            $('.j-create-note').attr('href','/notes/create?version='+(version || '')+'&verse_id='+verseId+'&text='+text);
            $('.j-create-journal').attr('href','/journal/create?version='+(version || '')+'&verse_id='+verseId+'&text='+text);
            $('.j-create-prayer').attr('href','/prayers/create?version='+(version || '')+'&verse_id='+verseId+'&text='+text);

            $('.j-reader-actions').css({
                top: ($(endElement).offset().top-66) + "px",
                left: (eventObject.pageX-105) + "px"
            }).animate( { "opacity": "show", top:($(endElement).offset().top-75)} , 200 );
            /!*$('.j-create-note').css({
                top: ($(endElement).offset().top-26) + "px",
                left: (eventObject.pageX-15) + "px"
            }).animate( { "opacity": "show", top:($(endElement).offset().top-35)} , 200 );
            $('.j-create-journal').css({
                top: ($(endElement).offset().top-26) + "px",
                left: (eventObject.pageX+20) + "px"
            }).animate( { "opacity": "show", top:($(endElement).offset().top-35)} , 200 );
            $('.j-create-prayer').css({
                top: ($(endElement).offset().top-26) + "px",
                left: (eventObject.pageX+55) + "px"
            }).animate( { "opacity": "show", top:($(endElement).offset().top-35)} , 200 );*!/
        }
        else {
            /!*$('.j-create-note').remove();
            $('.j-create-journal').remove();
            $('.j-create-prayer').remove();*!/
            $('.j-reader-actions').remove();
        }
    });*/

    // New logic of editing
    $(".j-verse-text").click(function(eventObject) {
        var selectedObject = $(this);

        var text = selectedObject.text();
        if(text){
            var startElement = selectedObject;
            var endElement = selectedObject;
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

            /*var menu = '<a title="Create note" href="/notes/create?version='+(version || '')+'&verse_id='+verseId+'&text='+text+'" class="j-create-note" style="position: absolute; width: 32px; height: 32px; background: #367fa9; color:white; font-size: 1.2em; border-radius: 16px; padding: 5px 5px 5px 9px;"><i class="fa fa-btn fa-sticky-note"></i></a>';
            menu += '<a title="Create Journal Entry" href="/journal/create?version='+(version || '')+'&verse_id='+verseId+'&text='+text+'" class="j-create-journal" style="position: absolute; width: 32px; height: 32px; background: #367fa9; color:white; font-size: 1.2em; border-radius: 16px; padding: 5px 5px 5px 9px;"><i class="fa fa-btn fa-book"></i></a>';
            menu += '<a title="Create prayer" href="/prayers/create?version='+(version || '')+'&verse_id='+verseId+'&text='+text+'" class="j-create-prayer" style="position: absolute; width: 32px; height: 32px; background: #367fa9; color:white; font-size: 1.2em; border-radius: 16px; padding: 5px 5px 5px 8px;"><i class="fa fa-btn fa-hand-paper-o"></i></a>';*/

            $('body').append(reader.getActionsHtml());
            $('.j-create-note').attr('href','/notes/create?version='+(version || '')+'&verse_id='+verseId+'&text='+text);
            $('.j-create-journal').attr('href','/journal/create?version='+(version || '')+'&verse_id='+verseId+'&text='+text);
            $('.j-create-prayer').attr('href','/prayers/create?version='+(version || '')+'&verse_id='+verseId+'&text='+text);

            $('.j-reader-actions').css({
                top: ($(endElement).offset().top-66) + "px",
                left: (eventObject.pageX-105) + "px"
            }).animate( { "opacity": "show", top:($(endElement).offset().top-75)} , 200 );
        }
        else {
            $('.j-reader-actions').remove();
        }
    });

    $("body").on('click','.j-create-note',function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var fullScreenLabel = 'Full screen';
        if($('.j-my-study-verse').length > 0){
            url += '?version='+$('input[name="bible_version"]').val()
            url += '&verse_id='+$('input[name="verse_id"]').val();
            url += '&text='+$('.j-verse-text').text();
        }
        if($('.j-my-study-item').length > 0){
            url += '?rel='+$('input[name="rel"]').val()
        }
        var fullScreenUrl = url;
        if($(this).parent('.j-reader-actions').length > 0){
            url += '&extraFields=1';
            fullScreenLabel = 'My Study Verse';
            fullScreenUrl = $.trim(url.replace('/notes/create','/reader/my-study-verse'));
        }

        $.ajax({
            method: "GET",
            url: url,
            data:{},
            success:function(data){
                $('#popup').find('.modal-header .modal-title').html('');
                $('#popup').find('.modal-header .modal-title').append('<div class="pull-left">Create Note</div>');
                $('#popup').find('.modal-header .modal-title').append('<div class="pull-left"><a href="'+fullScreenUrl+'" data-type="note" class="label label-primary full-screen-btn j-full-screen-btn">'+fullScreenLabel+'</a></div>');
                //$('#popup').find('.modal-header .modal-title').append('<div class="pull-left"><button type="submit" class="btn btn-primary full-screen-btn j-full-screen-btn">Full screen</button></div>');
                $('#popup').find('.modal-body').html(data);
                $('#popup').find('.modal-footer').html('');
                //$('#popup input[name="verse_id"]').val('');
                $('#popup').modal({show:true});
                site.initCkeditors();
                site.initTagging();
                site.initSelect2();
            },
            error:function(e){
                if(e.status = 401){
                    location.href = url;
                }
            }
        });
        return false;
    });

    $("body").on('click','.j-create-journal',function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var fullScreenLabel = 'Full screen';
        if($('.j-my-study-verse').length > 0){
            url += '?version='+$('input[name="bible_version"]').val()
            url += '&verse_id='+$('input[name="verse_id"]').val();
            url += '&text='+$('.j-verse-text').text();
        }
        if($('.j-my-study-item').length > 0){
            url += '?rel='+$('input[name="rel"]').val()
        }
        var fullScreenUrl = url;
        if($(this).parent('.j-reader-actions').length > 0){
            url += '&extraFields=1';
            fullScreenLabel = 'My Study Verse';
            fullScreenUrl = $.trim(url.replace('/journal/create','/reader/my-study-verse'));
        }
        $.ajax({
            method: "GET",
            url: url,
            data:{},
            success:function(data){
                $('#popup').find('.modal-header .modal-title').html('<div class="pull-left">Create Journal Entry</div>');
                $('#popup').find('.modal-header .modal-title').append('<div class="pull-left"><a href="'+fullScreenUrl+'" data-type="journal" class="label label-primary full-screen-btn j-full-screen-btn">'+fullScreenLabel+'</a></div>');
                $('#popup').find('.modal-body').html(data);
                $('#popup').find('.modal-footer').html('');
                $('#popup').modal({show:true});
                site.initCkeditors();
                site.initTagging();
                site.initSelect2();
            },
            error:function(e){
                if(e.status = 401){
                    location.href = url;
                }
            }
        });
        return false;
    });

    $("body").on('click','.j-create-prayer',function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var fullScreenLabel = 'Full screen';
        if($('.j-my-study-verse').length > 0){
            url += '?version='+$('input[name="bible_version"]').val()
            url += '&verse_id='+$('input[name="verse_id"]').val();
            url += '&text='+$('.j-verse-text').text();
        }
        if($('.j-my-study-item').length > 0){
            url += '?rel='+$('input[name="rel"]').val()
        }
        var fullScreenUrl = url;
        if($(this).parent('.j-reader-actions').length > 0){
            url += '&extraFields=1';
            fullScreenLabel = 'My Study Verse';
            fullScreenUrl = $.trim(url.replace('/prayers/create','/reader/my-study-verse'));
        }
        $.ajax({
            method: "GET",
            url: url,
            data:{},
            success:function(data){
                $('#popup').find('.modal-header .modal-title').html('<div class="pull-left">Create Prayer</div>');
                $('#popup').find('.modal-header .modal-title').append('<div class="pull-left"><a href="'+fullScreenUrl+'" data-type="prayer" class="label label-primary full-screen-btn j-full-screen-btn">'+fullScreenLabel+'</a></div>');
                $('#popup').find('.modal-body').html(data);
                $('#popup').find('.modal-footer').html('');
                $('#popup').modal({show:true});
                site.initCkeditors();
                site.initTagging();
                site.initSelect2();
            },
            error:function(e){
                if(e.status = 401){
                    location.href = url;
                }
            }
        });
        return false;
    });

    $("body").on('click','.j-full-screen-btn',function (e) {
        if($('.j-my-study-verse').length > 0 || $('.j-my-study-item').length > 0){
            e.preventDefault();
            var url = $(this).attr('href');
            var type = $(this).data('type');
            $('#'+type+'-form input[name="full_screen"]').val(1);
            $('#'+type+'-form').attr('action',url);
            $('#'+type+'-form').submit();
        }

        //var noteText = $('textarea[name="note_text"]').val();
        //var journalText = $('textarea[name="journal_text"]').val();
        //var prayerText = $('textarea[name="prayer_text"]').val();
        //
        //alert(noteText);
        //
        //var url = $(this).attr('href');
        //
        //if(noteText){
        //    url = url+'&note_text='+noteText;
        //}
        //if(journalText){
        //    url = url+'&journal_text='+journalText;
        //}
        //if(prayerText){
        //    url = url+'&prayer_text='+prayerText;
        //}
        //alert(url);
        //location.href = url;
    });

    if($('.j-reader-block.j-bible-text').length > 0){
        var hash = window.location.hash;
        var param = hash.replace(/[0-9]/g, '');
        var value = hash.replace( /^\D+/g, '');
        if(param == '#verse'){
            var target = $(".j-verse-text[data-verseid="+value+"]");
            $('body').scrollTo(target,500,{offset:-100});
            target.effect( "highlight", {color:"#669966"}, 5000);
        }
    }

    $('.j-version-status').change(function(){
        var data = {};
        data.version_code = $(this).data('version');
        data[$(this).attr('name')] = $(this).is(':checked');

        $.ajax({
            method: "GET",
            url: "/ajax/update-version",
            data:data,
            success:function(data){

            }
        });
    });

    $('.j-subscribed-status').change(function(event){
        var data = {};
        var checkBox = $(this);
        data.id = $(this).data('userid');
        if($(this).is(':checked')){
            data[$(this).attr('name')] = 1;
        } else {
            data[$(this).attr('name')] = 0;
        }

        $.ajax({
            method: "GET",
            url: "/admin/subscription/update-subscribed",
            data:data,
            success:function(data){
                if(data.e!=null) {
                    alert(data.e);
                    if(checkBox.is(':checked')){
                        checkBox.attr('checked', false);
                    } else {
                        checkBox.attr('checked', true);
                    }
                }
            }
        });
    });

    $('.j-generate-coupon-code').click(function(e){
        e.preventDefault();
        $.ajax({
            method: "GET",
            url: "/admin/coupons/get-code",
            success:function(data){
                $('#coupon_code').val(data);
            }
        });
    });

    $('input[name="plan_type"]').change(function(){
        switch ($(this).val()){
            case "free":
                $('.premium-only').addClass('hidden');
                $('#coupon_code').val('');
                break;
            case "premium":
                $('.premium-only').removeClass('hidden');
                break;
        }
    });

    $("body").on('click','.j-show-article',function (e){
        location.href = $(this).data('link');
    });

    $("body").on('click','.j-show-product',function (e){
        window.open($(this).data('link'));
        return false;
    });

    $('.j-popup-form').click(function(e){
        e.preventDefault();
        $.ajax({
            method: "GET",
            url: $(this).attr('href'),
            success:function(data){
                $('#popup').find('.modal-header .modal-title').html('<div class="pull-left">'+$(this).text()+'</div>');
                //$('#popup').find('.modal-header .modal-title').append('<div class="pull-left"><a href="'+url+'" data-type="prayer" class="label label-primary full-screen-btn j-full-screen-btn">Full screen</a></div>');
                $('#popup').find('.modal-body').html(data);
                $('#popup').find('.modal-footer').html('');
                $('#popup').modal({show:true});
            }
        });
    });

    $('#popup').on('click','#note-form button[type="submit"], #journal-form button[type="submit"],#prayer-form button[type="submit"]',function(e){
        e.preventDefault();
        var form = $(this).parents('form');
        site.ajaxForm(form);
    });

    $('.related-records').on('click','.j-highlight-verse',function(e){
        e.preventDefault();
        var verseId = $(this).data('verseid');
        $('.related-item').addClass('blur');
        $('.related-item').removeClass('highlight');
        $(this).parents('.related-item').addClass('highlight');
        var target = $(".j-verse-text[data-verseid="+verseId+"]");
        $('body').scrollTo(target,500,{offset:-100});
        $('.j-verse-text').removeClass('highlight');
        target.addClass('highlight');
        //target.effect( "highlight", {color:"#e1e1e8"});
    });

    $('.my-study-verse .star-link,.my-study-item .star-link').click(function(e){
        e.preventDefault();
        var type = $(this).data('type')
        $('body').scrollTo($('#'+type),500,{offset:-20});
    });

    $('.j-select-role').change(function(e){
        var role = $(this).val();
        $.ajax({
            method: "GET",
            url: '/admin/user/get-users-by-role?role='+role,
            dataType:'json',
            success:function(data){
                site.fillSelect('.j-select-user',data);
                $('.j-select-user').prepend($('<option selected="selected"></option>').attr("value", 0).text('All Users'));
                $(".j-select-user").select2("destroy");
                $(".j-select-user").select2();
            }
        });
    });

    $('.public-wall,.j-members-list,.j-friends-items,.group-block .g-body,#popup').on('click','.load-more',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var that = this;
        $.ajax({
            method: "GET",
            url: url,
            dataType:'html',
            success:function(data){
                if($(that).hasClass('j-load-more-comments')){
                    var commentsParent = $(that).parents('.j-comments-list');
                    commentsParent.find('.load-more-block').remove();
                    commentsParent.append(data);
                }
                else if($('#popup').is(':visible')){
                    $('#popup .modal-body').append(data);
                    $('#popup .modal-body .load-more-block').remove();
                }
                else{
                    var parent = $(that).parents('.g-body');
                    $('.public-wall,.j-friends-items,.j-members-list .row').append(data);
                    $('.load-more-block').remove();
                    parent.append(data);
                }
            }
        });
    });

    $('.j-friends-list,.j-members-list,#popup').on('click','.j-follow-friend,.j-approve-friend-request',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var that = this;
        $.ajax({
            method: "GET",
            url: url,
            success:function(data){
                if($(that).hasClass('j-approve-friend-request')){
                    $(that).parent().children('.j-reject-friend-request').toggleClass('hidden');
                    $(that).parent().children('.j-ignore-friend-request').toggleClass('hidden');
                    $(that).parent().children('.j-friends-btn').toggleClass('hidden');

                    $(that).parent().children('.j-remove-friend').toggleClass('hidden');

                    $(that).toggleClass('hidden');
                }
                else{
                    if($(that).hasClass('j-follow-friend')){
                        $(that).parent().children('.j-cancel-friend-request').toggleClass('hidden');
                    }
                    else{
                        $(that).parent().children('.j-remove-friend').toggleClass('hidden');
                    }

                    $(that).toggleClass('hidden');
                }
            },
            error:function(data){
                location.href = url;
            }
        });
    });

    $('/*.j-friends-list,.j-members-list,*/#cancel-request-sm').on('click','.j-remove-friend,.j-reject-friend-request,.j-cancel-friend-request,.j-ignore-friend-request',function(e){
        e.preventDefault();
        console.log('j-cancel-friend-request');
        var url = $(this).attr('href');
        var that = this;
        $.ajax({
            method: "GET",
            url: url,
            success:function(data){
                if($(that).hasClass('j-reject-friend-request')){
                    location.reload();
                    $(that).parents('.friend-item').remove();
                }
                else if($(that).hasClass('j-cancel-friend-request')){
                    $('#cancel-request-sm').modal('hide');
                    location.reload();
                }
                else if($(that).hasClass('j-ignore-friend-request')){
                    $('#cancel-request-sm').modal('hide');
                    location.reload();
                }
                else if($(that).hasClass('j-remove-friend')){
                    $('#cancel-request-sm').modal('hide');
                    location.reload();
                }
                else{
                    $(that).parent().children('.j-follow-friend').toggleClass('hidden');
                    $(that).toggleClass('hidden');
                }
            }
        });
    });

    $('.j-members-list').on('click','.j-ban-member,.j-unban-member',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var that = this;
        $.ajax({
            method: "GET",
            url: url,
            success:function(data){
                location.reload();
                /*var childClass = $(that).hasClass('j-ban-member')?'.j-unban-member':'.j-ban-member';
                $(that).parent().children(childClass).toggleClass('hidden');
                $(that).toggleClass('hidden');*/
            }
        });
    });


    Dropzone.autoDiscover = false;
    $("#avatar").dropzone(
        {
            url: "/user/upload-avatar",
            maxFilesize: 2,
            maxFiles:1,
            parallelUploads:1,
            previewsContainer: '#img-thumb-preview',
            previewTemplate: $('#preview-template').html(),
            headers: {
                'X-CSRF-Token': $('input[name="_token"]').val()
            },
            uploadMultiple: false,
            //thumbnailWidth:500,
            //thumbnailHeight:500,
            init: function() {
                this.on("maxfilesexceeded", function(file) {
                    this.removeAllFiles();
                    this.addFile(file);
                });
                this.on('sending', function(file, xhr, formData){
                    formData.append('user_id', $('input[name="user_id"]').val());
                });
                this.on("success", function(file, res) {
                    site.file = {"serverFileName" : res.filename, "fileName" : file.name};
                });
                this.on("removedfile", function(file) {
                    var rmvFile = site.file.serverFileName;
                    $('input[name="images"]').val();

                    if (rmvFile){
                        $.ajax({
                            type: 'POST',
                            url: '/user/delete-avatar',
                            data: {user_id: $('input[name="user_id"]').val(),filename: rmvFile,'_token':$('input[name="_token"]').val()},
                            dataType: 'html',
                            success: function(data){

                            }
                        });
                    }
                });
            },
        }
    );

    $('.j-select-image').on('click', function(e) {
        //trigger file upload select
        $("#avatar").trigger('click');
    });

    $('#avatar.user-image').on('click','.j-remove-image',function(){
        site.deleteImage(this,'/user/delete-avatar');
    });

    $('.j-groups-list,.group-details').on('click','.j-join-group',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var that = this;
        $.ajax({
            method: "GET",
            url: url,
            success:function(data){
                $(that).parent().children('.j-leave-group').toggleClass('hidden');
                $(that).toggleClass('hidden');
            },
            error:function(data){
                location.href = url;
            }
        });
    });

    $('.j-groups-list,.group-details').on('click','.j-leave-group',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var that = this;
        $.ajax({
            method: "GET",
            url: url,
            success:function(data){
                $(that).parent().children('.j-join-group').toggleClass('hidden');
                $(that).toggleClass('hidden');
            }
        });
    });

    $('.j-groups-list').on('click','.j-accept-request',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var that = this;
        $.ajax({
            method: "GET",
            url: url,
            success:function(data){
                location.reload();
            }
        });
    });

    $('.j-requests-list,#cancel-request-sm').on('click','.j-cancel-request',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var that = this;
        $.ajax({
            method: "GET",
            url: url,
            success:function(data){
                $('#cancel-request-sm').modal('hide');
                location.reload();
            }
        });
    });

    $('.community-menu').on('click','a.disabled',function(e){
        e.preventDefault();
    });

    $('#popup, #note-form,#journal-form,#prayer-form').on('change','input[name="access_level"]',function(e){
        var level = $(e.target).val();
        if(level == 'public_for_groups' || level == 1 || level == 0){
            $('.j-all-groups').removeClass('disabled');
            $('.j-all-groups input[type="radio"]').attr('disabled',false);
            $('.j-specific-groups').removeClass('disabled');
            $('.j-specific-groups input[type="radio"]').attr('disabled',false);
            $('input[name="share_for_groups"][value="public_for_groups"]').attr('checked',true);
        }
        else{
            $('.j-all-groups').addClass('disabled');
            $('.j-all-groups input[type="radio"]').attr('disabled',true);
            $('.j-specific-groups').addClass('disabled');
            $('.j-specific-groups input[type="radio"]').attr('disabled',true);
        }
    });
    $('#popup, #note-form').on('change','input[name="share_for_groups"]',function(e){
        var level = $(e.target).val();
        if(level == 'public_for_groups'){
            $('select.j-groups').attr('disabled',true);
        }
        else{
            $('select.j-groups').attr('disabled',false);
        }
    });

    $('.j-wall-items').on('click','.j-wall-item-comments',function(e){
        e.preventDefault();
        //$('.j-item-comments').html('');
        site.loadComments(this);
    });

    $('.j-wall-items').on('click','.j-save-comment',function(e){
        e.preventDefault();
        var that = this;
        var form = $(that).parents('.j-comment-form');
        site.ajaxForm(form,function(data){
            form[0].reset();
            form.parents('.j-item-comments').find('.j-comments-list').prepend(data);

            var curCommentsCount = parseInt(form.parents('.j-item-footer').find('.j-comments-count').html());
            if($(that).hasClass('liked')){
                form.parents('.j-item-footer').find('.j-comments-count').html(curCommentsCount-1);
            }
            else{
                form.parents('.j-item-footer').find('.j-comments-count').html(curCommentsCount+1);
            }
        });
    });

    $('.j-wall-items').on('click','.j-wall-like-btn',function(e){
        e.preventDefault();
        $(this).popover('destroy');
        var url = $(this).attr('href');
        var that = this;
        if(site.ajaxProcess && site.ajaxUrl == url){
            site.ajaxProcess.abort();
        }
        site.ajaxUrl = url;
        site.ajaxProcess = $.ajax({
                                method: "GET",
                                url: url,
                                success:function(data){
                                    $(that).parent().find('.j-wall-like-btn').toggleClass('hidden');

                                    var curLikesCount = parseInt($(that).parent().find('.j-likes-count').html());
                                    if($(that).hasClass('liked')){
                                        $(that).parent().find('.j-likes-count').html(curLikesCount-1);
                                    }
                                    else{
                                        $(that).parent().find('.j-likes-count').html(curLikesCount+1);
                                    }
                                }
                            });
    });

    $('.j-wall-items').on('mouseout','.j-wall-like-btn,.j-wall-item,.popover',function(e){
        if(!($('.popover:hover').length || $('.j-wall-item:hover').length)){
            $('.j-wall-like-btn').each(function () {
                $(this).popover('destroy');
            });
        }
    });

    $('.j-wall-items').on('mouseover','.j-wall-like-btn',function(e){
        var url = $(this).data('likeslink');
        var that = this;
        if($(that).parent().find('.popover').length == 0){
            $.ajax({
                method: "GET",
                url: url,
                success:function(data){
                    $(that).popover(
                        {
                            html: true,
                            placement:'auto',
                            title: '',
                            content: data,
                        }
                    );
                    $(that).popover('show');
                }
            });
        }
    });

    $('.j-wall-items').on('click','.j-show-all-likes',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var that = this;
        $.ajax({
            method: "GET",
            url: url,
            success:function(data){
                $('#popup').find('.modal-header .modal-title').html('All item likes');
                $('#popup').find('.modal-body').html(data);
                $('#popup').find('.modal-footer').html('');
                $('#popup').modal({show:true});
            }
        });
    });

    $('.j-wall-items').on('click','.j-item-report',function(e){
        e.preventDefault();

        if($(this).hasClass('disabled')){
            return false;
        }

        var url = $(this).attr('href');
        var that = this;
        $.ajax({
            method: "GET",
            url: url,
            success:function(data){
                $('#popup-sm').data('itemtype',$(that).parents('.j-wall-item').find('.j-item-body').data('itemtype'));
                $('#popup-sm').data('itemid',$(that).parents('.j-wall-item').find('.j-item-body').data('itemid'));
                $('#popup-sm').find('.modal-header .modal-title').html('Report inappropriate content');
                $('#popup-sm').find('.modal-body').html(data);
                $('#popup-sm').find('.modal-footer').html('');//<a href="'+url+'" class="j-send-report"></a>
                $('#popup-sm').modal({show:true});
            }
        });
    });

    $('#popup-sm').on('click','.j-send-report',function(e){
        e.preventDefault();
        var that = this;
        var form = $(that).parents('.j-report-form');
        site.ajaxForm(form,function(data){
            form[0].reset();
            console.log($('#popup-sm').data('itemtype'));
            console.log($('#popup-sm').data('itemid'));
            var reportBtn = $('.j-item-body[data-itemtype='+$('#popup-sm').data('itemtype')+'][data-itemid='+$('#popup-sm').data('itemid')+']').parents('.j-wall-item').find('.j-item-report');
            reportBtn.addClass('disabled');
            reportBtn.addClass('reported');
            $('#popup-sm').modal('hide');
        });
    });

    $('ul.community-menu').on('click','.j-show-more',function(e){
        e.preventDefault();
        $('ul.community-menu .j-hidden').toggleClass('hidden');

        var text = $('ul.community-menu .j-show-more').text();

        $('ul.community-menu .j-show-more').html(
            (text == 'show more ') ? "show less <i class='fa fa-angle-up' aria-hidden='true'></i>" : "show more <i class='fa fa-angle-down' aria-hidden='true'></i>");

    });

    $(".j-btn-settings").on("click", function(e){
        var btnCoordTop = $(this).offset().top+25;
        var btnCoordLeft = $(this).offset().left-290;
        $(".j-popup-settings").toggle();
        $(".j-popup-settings").offset({top:btnCoordTop, left:btnCoordLeft});
        e.preventDefault();
    });
    $(".j-popup-settings .j-btn-ok").on("click", function(e){
        $(".j-popup-settings").hide();
    });
    $(".j-btn-compare").on("click", function(e){
        var btnCoordTop = $(this).offset().top+25;
        var btnCoordLeft = $(this).offset().left-315;
        $(".j-popup-compare").toggle();
        $(".j-popup-compare").offset({top:btnCoordTop, left:btnCoordLeft});
        e.preventDefault();
    });

    $(".j-check-diff").on("change", function(e){
        location.href=$(this).data('link');
        $(".spinner").show();
    });


    $(".j-close-choose-version").on("click", function(e){
       $(".j-choose-version-pop").hide();
        $(".j-nav-sel").show();
        e.preventDefault();
    });
    $(".j-version-list li a").on("click", function(e){
        $(".j-version-list li a").removeClass("active");
        $(this).addClass("active");
       /* alert($(this).data("val"));*/
        var curSelVal = $(this).data("val");
        var curSelText = $(this).html()
        $(".j-select-version").val(curSelVal);
        $(".j-sel-version-text").html(curSelText);
        $(".j-choose-version-pop").hide();
        $(".j-nav-sel").show();
        e.preventDefault();
    });
    $(".j-sel-version-label").on("click", function(e){
        $(".j-nav-sel").hide();
        $(".j-choose-version-pop").show();
        e.preventDefault();
    });
/*    $(".j-popup-settings .j-btn-ok").on("click", function(e){
        $(".j-popup-settings").hide();
    });*/

    // Hide Popups
    $(document).mouseup(function (e){
        var popSettings = $(".j-popup-settings");
        var popSettings2 = $(".j-btn-settings");
        if (!popSettings.is(e.target) && popSettings.has(e.target).length === 0 && !popSettings2.is(e.target) && popSettings2.has(e.target).length === 0) {
            popSettings.hide();
        }

        var popCompare = $(".j-popup-compare");
        var popCompare2 = $(".j-btn-compare");
        var popCompare3 = $(".j-compare-versions");
        var popCompare4 = $(".select2-container");


        if (!popCompare.is(e.target) && popCompare.has(e.target).length === 0 && !popCompare2.is(e.target) && popCompare2.has(e.target).length === 0 && !popCompare3.is(e.target) && popCompare3.has(e.target).length === 0 && !popCompare4.is(e.target) && popCompare4.has(e.target).length === 0 && hidev==true) {
            popCompare.hide();
            hidev=true;
        }
    });
});