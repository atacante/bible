
$(document).ready(function(){
    var clipboard = new Clipboard('.copy');
    var hidev = true;
    clipboard.on('success', function(e) {
        e.clearSelection();
    });

    /* Highlight text in reader */
    if($('.j-reader-block').length > 0){
        reader.getHighlights();
    }

    /* "New Posts" functionality for the wall */
    if($('.j-wall-items').length > 0){
        setInterval(function(){
            site.checkNewWallPosts($('.j-wall-items').data('walltype'));
        },site.wallCheckInterval);
    }

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
        width: '100%',
        placeholder: $(".j-select2-ajax").attr('placeholder'),
        ajax:{
            url: $(".j-select2-ajax").data('url'),
            dataType: 'json',
            data: function(term,page){
                return term;
            },
            processResults: function (data, params) {
                return {results: data}
            }
        },
    });

    $(".j-invite-emails").select2({
        tags: true,
        allowClear: true,
        dropdownCssClass: 'hideSearch',
        tokenSeparators: [',',' '],
        selectOnClose: true,
        selectOnBlur: true
    });
    $('.j-invite-emails').on('select2:selecting', function (evt) {
        if(!site.validateEmail(evt.params.args.data.id)){
            $('#popup-sm').find('.modal-header .modal-title').text('Warning');
            $('#popup-sm').find('.modal-body').html("Please enter valid email address");
            $('#popup-sm').find('.modal-footer').html('<button type="button" class="btn2-kit cu-btn-pad1" data-dismiss="modal">Ok</button>');
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
            $('#popup-sm').find('.modal-footer').html('<button type="button" class="btn2-kit cu-btn-pad1" data-dismiss="modal">Ok</button>');
            $('#popup-sm').modal({show:true});
            $(this).val('');
        }
        switch (name){
            case 'date_from':
                break;
            case 'date_to':
                break;
        }
    });

    $(".j-with-images .people-image").each(function (i) {
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

    //New reader logic
    $('body').on('click','.word-definition',function(e) {
        var that = this;
        if(!$(that).hasClass('word-definition')){ // Check if context is not child
            that = $(this).parent('.word-definition');
        }
        var definitionId = $(that).data('lexid');
        var lexversion = $(that).data('lexversion');
        e.stopPropagation();

        $('.j-reader-actions div,.j-reader-actions a').removeClass('hidden');

        $('.j-show-definition').attr('data-lexid',definitionId);
        $('.j-show-definition').attr('data-lexversion',lexversion);

        $('.def-highlight').removeClass('def-highlight');
        $(that).addClass('def-highlight');

    });


     $('body').on('click','.j-show-definition',function(e) {

        var definitionId = $(this).data('lexid');
        var lexversion = $(this).data('lexversion');

        $('.word-definition').each(function(){
            $(this).popover('destroy');
        });

        var definition_word = reader.getDefinitionWord(definitionId);

        var that = $(definition_word).parent();

        var compare = 0;

         if(($('.j-diff-block').length > 0)||($('.related-records').length > 0)){
             compare = 1;
         }

        $.ajax({
            method: "GET",
            url: "/ajax/lexicon-info",
            dataType: "html",
            data:{
                lexversion:lexversion,
                definition_id:definitionId,
                compare:compare
            },
            success:function(data){

                reader.clearHighlights();

                $(definition_word).after(

                    '<div class="j-lex-content lex-content font-size-16" data-lexid="'+definitionId+'">' +
                        data +
                    '</div>'
                );
                $(definition_word).addClass('highlight');

                reader.recalculateAllActiveLexicons();

                reader.closeDefinition();
            }
        });
         return false;
    });



    $('body').on('shown.bs.popover', function () {
        $(".j-with-images .people-image").each(function (i) {
            $(this).bind('click', function () {
                site.fancyBoxMe(i);
            }); //bind
        });
    })

    $('a[data-target="#confirm-delete"]').click(function(ev) {
        var href = $(this).attr('href');
        $('#confirm-delete-sm').find('.modal-header .modal-title').text($(this).attr('data-header'));
        $('#confirm-delete-sm').find('.modal-body').text($(this).attr('data-confirm'));
        $('#confirm-delete-sm').find('.btn-ok').attr('href', href);
        $('#confirm-delete-sm').modal({show:true});
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
        if(!id){
            id = $(this).data('itemid');
        }
        site.getNote(id);
    });

    $('body').on('click','.j-journal-text',function(ev) {
        ev.preventDefault();
        var id = $(this).data('journalid');
        if(!id){
            id = $(this).data('itemid');
        }
        site.getJournal(id);
    });

    $('body').on('click','.j-prayer-text',function(ev) {
        ev.preventDefault();
        if($(this).find('j-custom-sharing')){
            return false;
        }
        var id = $(this).data('prayerid');
        if(!id){
            id = $(this).data('itemid');
        }
        site.getPrayer(id);
    });

    $('body').on('click','.j-status-text',function(ev) {
        ev.preventDefault();
        var id = $(this).data('statusid');
        if(!id){
            id = $(this).data('itemid');
        }
        site.getStatus(id);
    });

    $('body').on('click','.j-friend-item',function(ev) {
        ev.preventDefault();
        var id = $(this).data('userid');
        site.getUser(id);
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
        if(!$(eventObject.target).hasClass('j-reader-actions') && !$(eventObject.target).parent().parent().hasClass('j-reader-actions')){
            $('.j-reader-actions').remove();
        }
    });

// Text selection
    if($('.j-diff-block').length == 0){
        $(".j-bible-text").bind( "mouseup touchend touchcancel",function(eventObject) {
            var selectedObject = site.getSelected();
            var text = selectedObject.toString();

            if(text){
                reader.highlightMode = true;
                reader.getSelectedNodes(selectedObject);
                var startElement = reader.startElement;
                var endElement = reader.endElement;

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
                reader.startVerseId = startVerseId;
                var endVerseId = $(endElement).data('verseid');
                if(!endVerseId){
                    endVerseId = $(endElement).parents('.j-verse-text').data('verseid');
                }
                reader.endVerseId = endVerseId;

                var verseId = 0;
                if(!startVerseId || !endVerseId){
                    verseId = Math.max((startVerseId || 0), (endVerseId || 0));
                }
                else{
                    verseId = Math.min((startVerseId || 0), (endVerseId || 0));
                }

                var startParent = $(startElement).parents('.j-verse-text');
                var endParent = $(endElement).parents('.j-verse-text');
                var hasMark = false;
                if($(startElement).parent().context.localName == 'mark'){
                    hasMark = true;
                }
                if($(endElement).parent().context.localName == 'mark'){
                    hasMark = true;
                }
                if(!hasMark){
                    if($(startElement).index() <= $(endElement).index()){
                        var marks = $(startElement).nextUntil($(endElement).next()).andSelf().find('mark');
                        if(marks.length > 0){
                            hasMark = true;
                        }
                    }
                    else{
                        var marks = $(startElement).prevUntil($(endElement).prev()).andSelf().find('mark');
                        if(marks.length > 0){
                            hasMark = true;
                        }
                    }
                }

                $('body').append(reader.getHighlightActionsHtml(hasMark));


                var pageX = eventObject.pageX;
                if(!pageX){
                    pageX = eventObject.originalEvent.changedTouches[0].pageX;
                }

                var offset = 66;
                var offsetAnimate = 75;

                if(site.isAppleMobile()){
                    offset = -50;
                    offsetAnimate = -30;
                }

                $('.j-reader-actions').css({
                    top: ($(endElement).offset().top-offset) + "px",
                    left: (pageX-(text.length > 3?60:43)) + "px"
                }).animate( { "opacity": "show", top:($(endElement).offset().top-offsetAnimate)} , 200 );
            }
            else {
                $('.j-reader-actions').remove();
            }
        });
    }

    $("body").on('click','.j-highlight-text',function (e) {
        e.preventDefault();
        var text = site.getSelected().toString();
        reader.clearSelection();
        $('.j-reader-actions').remove();
        var color = $(this).data('colorclass');
        $.ajax({
            method: "POST",
            url: '/reader/save-highlight',
            data:{
                bible_version:$('select[name=version]').val(),
                verse_from_id:reader.startVerseId,
                verse_to_id:reader.endVerseId,
                color:color,
                highlighted_text:text,
                _token:$('input[name="_token"]').val()
            },
            success:function(data){
                reader.highlight(text,color,data);
            },
            error:function(e){
                if(e.status == 403){
                    site.showAuthWarning();
                }
            }
        });
    });

    $("body").on('click','.j-remove-highlighted-text',function (e) {
        e.preventDefault();
        var selectedObject = site.getSelected();
        reader.getSelectedNodes(selectedObject);
        var startElement = reader.startElement;
        var endElement = reader.endElement;
        reader.clearSelection();
        $('.j-reader-actions').remove();

        var markIds = [];

        if($(startElement).parent().context.localName == 'mark'){
            markIds.push($($(startElement).parent().context).data('id'));
        }
        if($(endElement).parent().context.localName == 'mark'){
            markIds.push($($(endElement).parent().context).data('id'));
        }

        if($(startElement).index() <= $(endElement).index()){
            var marks = $(startElement).nextUntil($(endElement).next()).andSelf().find('mark');
            if(marks.length > 0){
                marks.each(function(e){
                    markIds.push($(this).data('id'));
                });
            }
        }
        else{
            var marks =  $(startElement).prevUntil($(endElement).prev()).andSelf().find('mark');
            if(marks.length > 0){
                marks.each(function(e){
                    markIds.push($(this).data('id'));
                });
            }
        }

        markIds = $.unique(markIds);

        if(markIds.length > 0){
            for(var i = 0; i < markIds.length; i++){
                $('.j-verse-text').unmark({className:'j-mark-'+markIds[i]});
            }
            $.ajax({
                method: "GET",
                url: '/reader/remove-highlight',
                data:{ids:markIds},
                success:function(data){

                },
                error:function(e){

                }
            });
        }
    });

    // New logic of editing
    $(".j-verse-text").click(function(eventObject) {
        if(reader.highlightMode){
            reader.highlightMode = false;
            return false;
        }
        var selectedObject = $(this);

        if($(eventObject.target)[0].localName == 'span' || $(eventObject.target)[0].localName == 'mark'){
            if($(eventObject.target).hasClass('glyphicon')){
                return true;
            }
            $('.j-verse-text').removeClass('clicked');
            $('.j-reader-actions').remove();
            $('.def-highlight').removeClass('def-highlight');

            selectedObject.addClass('clicked');

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

                $('body').append(reader.getActionsHtml());

                var filteredText = text.replace(/^\s+\d+\s+/,'');

                $('.j-create-note').attr('href','/notes/create?version='+(version || '')+'&verse_id='+verseId+'&text='+filteredText);
                $('.j-create-journal').attr('href','/journal/create?version='+(version || '')+'&verse_id='+verseId+'&text='+filteredText);
                $('.j-create-prayer').attr('href','/prayers/create?version='+(version || '')+'&verse_id='+verseId+'&text='+filteredText);

                var mouseX = eventObject.pageX;
                var mouseY = eventObject.pageY;

                $('.j-reader-actions').css({
                    top: (mouseY-70) + "px",
                    left: (mouseX - 95) + "px"
                }).animate( { "opacity": "show", top:(mouseY-90)} , 200 );
            }
            else {
                $('.j-reader-actions').remove();
            }
        }

    });

    $("body").on('click','.j-create-note',function (e) {
        e.preventDefault();
        var that = this;
        var url = $(this).attr('href');
        var fullScreenLabel = 'Full screen';
        var text = $('.j-verse-text:first').text();
        var filteredText = text.replace(/^\s+\d+\s+/,'');

        if($('.j-my-study-verse').length > 0 || $('input[name="verse_details"]').length > 0 || $('.j-strongs-page').length > 0){
            url += '?version='+$('input[name="bible_version"]').val()
            url += '&verse_id='+$('input[name="verse_id"]').val();
            url += '&text='+filteredText;
        }
        if($('.j-my-study-item').length > 0){
            url += '?rel='+$('input[name="rel"]').val()
        }
        var fullScreenUrl = url;
        if($(this).parent('.j-reader-actions').length > 0 || $('input[name="verse_details"]').length > 0 || $('.j-strongs-page').length > 0){
            url += '&extraFields=1';
            fullScreenLabel = 'My Study Verse';
            fullScreenUrl = $.trim(url.replace('/notes/create','/reader/my-study-verse'));
        }
        $('.j-popup-create-record').hide();
        $.ajax({
            method: "GET",
            url: url,
            data:{},
            success:function(data){
                $('#popup').addClass('create-item');
                $('#popup').find('.modal-header .modal-title').html('');
                $('#popup').find('.modal-header .modal-title').append('<div class="pull-left modal-title-text"><i class="bs-note"></i>'+($(that).attr('title')?$(that).attr('title'):'Create Note')+'</div>');
                $('#popup').find('.modal-header .modal-title').append('<div class="pull-left modal-header-btn"><a href="'+fullScreenUrl+'" data-type="note" class="btn4-kit s-full-screen-btn j-full-screen-btn"><i class="bs-study"></i>'+fullScreenLabel+'</a></div>');
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
        var that = this;
        var url = $(this).attr('href');
        var fullScreenLabel = 'Full screen';

        var text = $('.j-verse-text:first').text();
        var filteredText = text.replace(/^\s+\d+\s+/,'');

        if($('.j-my-study-verse').length > 0 || $('input[name="verse_details"]').length > 0  || $('.j-strongs-page').length > 0){
            url += '?version='+$('input[name="bible_version"]').val()
            url += '&verse_id='+$('input[name="verse_id"]').val();
            url += '&text='+filteredText;
        }
        if($('.j-my-study-item').length > 0){
            url += '?rel='+$('input[name="rel"]').val()
        }
        var fullScreenUrl = url;
        if($(this).parent('.j-reader-actions').length > 0  || $('input[name="verse_details"]').length > 0  || $('.j-strongs-page').length > 0){
            url += '&extraFields=1';
            fullScreenLabel = 'My Study Verse';
            fullScreenUrl = $.trim(url.replace('/journal/create','/reader/my-study-verse'));
        }
        $('.j-popup-create-record').hide();
        $.ajax({
            method: "GET",
            url: url,
            data:{},
            success:function(data){
                $('#popup').addClass('create-item');
                $('#popup').find('.modal-header .modal-title').html('<div class="pull-left modal-title-text"><i class="bs-journal"></i>'+($(that).attr('title')?$(that).attr('title'):'Create Journal Entry')+'</div>');
                $('#popup').find('.modal-header .modal-title').append('<div class="pull-left modal-header-btn"><a href="'+fullScreenUrl+'" data-type="journal" class="btn4-kit s-full-screen-btn j-full-screen-btn">'+fullScreenLabel+'</a></div>');
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
        var that = this;
        var url = $(this).attr('href');
        var fullScreenLabel = 'Full screen';

        var text = $('.j-verse-text:first').text();
        var filteredText = text.replace(/^\s+\d+\s+/,'');

        if($('.j-my-study-verse').length > 0 || $('input[name="verse_details"]').length > 0  || $('.j-strongs-page').length > 0){
            url += '?version='+$('input[name="bible_version"]').val()
            url += '&verse_id='+$('input[name="verse_id"]').val();
            url += '&text='+filteredText;
        }
        if($('.j-my-study-item').length > 0){
            url += '?rel='+$('input[name="rel"]').val()
        }
        var fullScreenUrl = url;
        if($(this).parent('.j-reader-actions').length > 0  || $('input[name="verse_details"]').length > 0  || $('.j-strongs-page').length > 0){
            url += '&extraFields=1';
            fullScreenLabel = 'My Study Verse';
            fullScreenUrl = $.trim(url.replace('/prayers/create','/reader/my-study-verse'));
        }
        $('.j-popup-create-record').hide();
        $.ajax({
            method: "GET",
            url: url,
            data:{},
            success:function(data){
                $('#popup').addClass('create-item');
                $('#popup').find('.modal-header .modal-title').html('<div class="pull-left modal-title-text"><i class="bs-pray"></i>'+($(that).attr('title')?$(that).attr('title'):'Create Prayer')+'</div>');
                $('#popup').find('.modal-header .modal-title').append('<div class="pull-left modal-header-btn"><a href="'+fullScreenUrl+'" data-type="prayer" class="btn4-kit s-full-screen-btn j-full-screen-btn">'+fullScreenLabel+'</a></div>');
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

    if($('.j-reader-block.j-bible-text').length > 0){
        var hash = window.location.hash;
        var param = hash.replace(/[0-9]/g, '');
        var value = hash.replace( /^\D+/g, '');
        if(param == '#verse'){
            var target = $(".j-verse-text[data-verseid="+value+"]");
            $('body').scrollTo(target,500,{offset:-100});
            target.effect("highlight", {color:"#00B9F7"}, 5000);
        }
    }

    $('.j-analysis-read-more').click(function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var hash = url.substring(url.indexOf('#'));
        var param = hash.replace(/[0-9]/g, '');
        var value = hash.replace( /^\D+/g, '');

        if(param == '#location'){
            var target = $(".j-location-item[data-locationid="+value+"]");
        }
        else if(param == '#people'){
            var target = $(".j-people-item[data-peopleid="+value+"]");
        }
        $('body').scrollTo(target,500,{offset:-100});
    });

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
        $('.related-item').removeClass('highlight');
        var target = $(".j-verse-text[data-verseid="+verseId+"]");
        $('body').scrollTo(target,500,{offset:-100});
        $('.j-verse-text').removeClass('highlight');
        target.addClass('highlight');
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

    $('.public-wall,.group-wall,.j-members-list,.j-friends-items,.j-group-items,.group-block .g-body,#popup').on('click','.load-more',function(e){
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
                    $('#popup .modal-body .load-more-block').remove();
                    $('#popup .modal-body').append(data);
                }
                else{
                    var parent = $(that).parents('.g-body');
                    var parent2 = $(that).parents('.j-group-items');
                    $(that).parents('.load-more-block').remove();
                    $('.j-wall-items,.j-friends-items,.j-members-list .row').append(data);
                    parent2.append(data);
                    parent.append(data);
                    if($('.j-wall-items').length){
                        var loadMoreBlock = $('.load-more-block').clone();
                        $('.load-more-block').remove();
                        $('.j-wall-items').after(loadMoreBlock);
                    }
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

    $('#cancel-request-sm').on('click','.j-remove-friend,.j-reject-friend-request,.j-cancel-friend-request,.j-ignore-friend-request',function(e){
        e.preventDefault();
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
            thumbnailWidth:140,
            thumbnailHeight:140,
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

    $('#avatar').on('click','.j-remove-image',function(){
        site.deleteImage(this,'/user/delete-avatar');
    });

    $('.j-groups-list,.group-details').on('click','.j-join-group',function(e){
        e.preventDefault();

        if($(this).hasClass('disabled')){
            site.showPremiumWarning();
            return false;
        }

        var url = $(this).attr('href');
        var that = this;
        $.ajax({
            method: "GET",
            url: url,
            success:function(data){
                if(data == 'requested'){
                    $(that).parent().children('.j-cancel-request').toggleClass('hidden');
                    $(that).toggleClass('hidden');
                }
                else if(data == 'joined'){
                    $(that).parent().children('.j-leave-group').toggleClass('hidden');
                    $(that).toggleClass('hidden');
                }
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

    $('#popup, #note-form,#journal-form,#prayer-form').on('change','input[name="access_level"]',function(e){
        var level = $(e.target).val();
        if(level == 'public_for_groups' || level == 1 || level == 0){
            $('.j-all-groups').removeClass('disabled');
            $('.j-all-groups input[type="radio"]').attr('disabled',false);
            $('.j-specific-groups').removeClass('disabled');
            $('.j-specific-groups input[type="radio"]').attr('disabled',false);
            $('.j-only-show-group-owner input[name="only_show_group_owner"]').attr('disabled',false);
            $('.j-only-show-group-owner').removeClass('disabled');
            $('input[name="share_for_groups"][value="public_for_groups"]').attr('checked',true);
        }
        else{
            $('.j-all-groups').addClass('disabled');
            $('.j-all-groups input[type="radio"]').attr('disabled',true);
            $('.j-specific-groups').addClass('disabled');
            $('.j-specific-groups input[type="radio"]').attr('disabled',true);
            $('.j-only-show-group-owner input[name="only_show_group_owner"]').attr('disabled',true);
            $('.j-only-show-group-owner').addClass('disabled');
        }
    });
    $('#popup, .entry-form').on('change','input[name="share_for_groups"]',function(e){
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
                                    var elem = $(that).parent().find('.j-likes-count');
                                    var curLikesCount = parseInt(elem.html());
                                    var count = 0;
                                    if($(that).hasClass('liked')){
                                        count = curLikesCount-1;
                                    }
                                    else{
                                        count = curLikesCount+1;
                                    }
                                    if(count == 0){
                                        elem.addClass('hidden');
                                    }
                                    else{
                                        elem.removeClass('hidden');
                                    }
                                    elem.html(count);
                                }
                            });
    });

    $('.j-wall-items').on('mouseout','.j-wall-like-btn,.popover',function(e){
        var that = this;
        $(that).removeClass('hovered');
        setTimeout(function(){
            if(!($('.popover:hover').length/* || $('.j-wall-like-btn:hover').length*/)){
                $(that).parent().find('.j-wall-like-btn').popover('destroy');
            }
        },1000);
    });

    $('.j-wall-items').on('mouseover','.j-wall-like-btn',function(e){
        var that = this;
        $(that).addClass('hovered');
        setTimeout(function(){
            if($(that).hasClass('hovered') && !site.likesAjax){
                var url = $(that).data('likeslink');
                if($(that).parent().find('.popover').length == 0){
                    if(site.likesAjax){
                        site.likesAjax.abort();
                    }
                    site.likesAjax = $.ajax({
                        method: "GET",
                        url: url,
                        success:function(data){
                            $(that).popover(
                                {
                                    html: true,
                                    placement:'auto',
                                    title: '',
                                    content: data
                                }
                            );
                            $(that).popover('show');
                            site.likesAjax = false;
                        }
                    });
                }
            }
        },1000);
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
                $('#popup').data('itemtype',$(that).parents('.j-wall-item').find('.j-item-body').data('itemtype'));
                $('#popup').data('itemid',$(that).parents('.j-wall-item').find('.j-item-body').data('itemid'));
                $('#popup').find('.modal-header .modal-title').html('Report inappropriate content');
                $('#popup').find('.modal-body').html(data);
                $('#popup').find('.modal-footer').html('');//<a href="'+url+'" class="j-send-report"></a>
                $('#popup').modal({show:true});
            }
        });
    });

    $('#popup,#popup-sm').on('click','.j-send-report',function(e){
        e.preventDefault();
        var that = this;
        var form = $(that).parents('.j-report-form');
        site.ajaxForm(form,function(data){
            form[0].reset();
            var reportBtn = $('.j-item-body[data-itemtype='+$('#popup').data('itemtype')+'][data-itemid='+$('#popup').data('itemid')+']').parents('.j-wall-item').find('.j-item-report');
            reportBtn.addClass('disabled');
            reportBtn.addClass('reported');
            $('#popup-sm').modal('hide');
        });
    });

    $('body').on('click','.j-show-more',function(e){
        e.preventDefault();

        var parent = $(e.target).parent();
        var button = $(e.target);

        var field = $(parent).find('.j-hidden');

        field.toggleClass('hidden');

        var text = button.text();

        button.html(
            (text == 'See More') ? "See Less" : "See More");

    });

    $('body').on('click','.j-show-billing',function(e){
        order.fillBillingInfo();

        $('.j-billing-meta').toggleClass('hidden');
    });

    $(".j-create-record").on("click", function(e){
        var top = 140;

        var btnCoordTop = $(this).offset().top+top;
        var btnCoordLeft = $(this).offset().left;

        $(".j-popup-create-record").toggle();
        $(".j-popup-create-record").offset({top:btnCoordTop, left:btnCoordLeft});
        e.preventDefault();
    });

    $(".j-btn-settings").on("click", function(e){
        var left = 320;
        var top = 25;
        if($(this).parents('.j-parallel-verses').length > 0){
            left = 235;
            top = 35;
        }
        var btnCoordTop = $(this).offset().top+top;
        var btnCoordLeft = $(this).offset().left-left;
        $(".j-popup-settings").toggle();
        $(".j-popup-settings").offset({top:btnCoordTop, left:btnCoordLeft});
        e.preventDefault();
    });

    $(".j-btn-should-see").on("click", function(e){
        var btnCoordTop = $(this).offset().top+35;
        var btnCoordLeft = $(this).offset().left-150;
        $(".j-popup-should-see").toggle();
        $(".j-popup-should-see").offset({top:btnCoordTop, left:btnCoordLeft});

        $(".j-status-list li a").removeClass('active');
        var currentLevel = $('.j-access-level').val();
        $('.j-status-list li a[data-val="'+currentLevel+'"]').addClass('active');
        e.preventDefault();
    });

    $(".j-status-list li a").on('click', function(e){
        var newLevel = $(this).data("val");
        $('.j-access-level').val(newLevel);

        $(".j-status-list li a").removeClass('active');
        $(this).addClass('active');
        $(".j-status-list li a i.bs-checkmark").addClass('hidden');
        $(".j-status-list li a.active i.bs-checkmark").removeClass('hidden');

        $(".j-popup-should-see").toggle();

        site.changeStatusIcon(newLevel);
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
        var curSelVal = $(this).data("val");
        var curSelText = $(this).html();
        $(".j-select-version").val(curSelVal);
        $(".j-sel-version-text").html(curSelText);
        $(".j-choose-version-pop").hide();
        $(".j-nav-sel").show();
        $("#j-sel-book-form").submit();
        e.preventDefault();
    });
    $(".j-sel-version-label").on("click", function(e){
        $(".j-nav-sel").hide();
        $(".j-choose-version-pop").show();
        e.preventDefault();
    });

    /* ----------------- BOOK ----------------- */
    $(".j-close-choose-book").on("click", function(e){
        $(".j-choose-book-pop").hide();
        $(".j-nav-sel2").show();
        e.preventDefault();
    });
    $(".j-book-list a").on("click", function(e){
        $(".j-book-list a").removeClass("active");
        $(this).addClass("active");
        var curSelVal = $(this).data("val");
        var curSelText = $(this).html()
        $(".j-select-book").val(curSelVal);
        $(".j-sel-book-text").html(curSelText);
        $(".j-select-chapter").val(1);
        $(".j-select-verse").val(1);
        $(".j-choose-book-pop").hide();
        $(".j-nav-sel2").show();
        $("#j-sel-book-form").submit();

        e.preventDefault();
    });
    $(".j-sel-book-label").on("click", function(e){
        $(".j-nav-sel2").hide();
        $(".j-choose-book-pop").show();
        if ($("body").width()<751){
            $(".j-choose-desctop").hide();
            $(".j-choose-mobile").show();
        } else {
            $(".j-choose-mobile").hide();
            $(".j-choose-desctop").show();
        }

        e.preventDefault();
    });
    $(".j-btn-related-rec").on("click", function (e) {
        $(".j-mobile-rel-rec").toggle();
        e.preventDefault();
    });
    $(window).resize(function() {
        if ($("body").width() < 768) {
            $(".j-choose-desctop").hide();
            $(".j-choose-mobile").show();
        } else {
            $(".j-choose-mobile").hide();
            $(".j-choose-desctop").show();
        }

        dinamicArrows();
    });

    /* ----------------- CHAPTER ----------------- */
    $(".j-close-choose-chapter").on("click", function(e){
        $(".j-choose-chapter-pop").hide();
        $(".j-nav-sel3").show();
        e.preventDefault();
    });
    $(".j-chapter-list a").on("click", function(e){
        $(".j-chapter-list a").removeClass("active");
        $(this).addClass("active");
        var curSelVal = $(this).data("val");
        var curSelText = $(this).html();
        $(".j-select-chapter").val(curSelVal);
        $(".j-sel-chapter-text").html(curSelText);
        $(".j-select-verse").val(1);
        $(".j-choose-chapter-pop").hide();
        $(".j-nav-sel3").show();
        $("#j-sel-book-form").submit();
        e.preventDefault();
    });
    $(".j-sel-chapter-label").on("click", function(e){
        $(".j-nav-sel3").hide();
        $(".j-choose-chapter-pop").show();
        e.preventDefault();
    });

    /* ----------------- VERSE ----------------- */
    $(".j-close-choose-verse").on("click", function(e){
        $(".j-choose-verse-pop").hide();
        $(".j-nav-sel3").show();
        e.preventDefault();
    });
    $(".j-verse-list a").on("click", function(e){
        $(".j-verse-list a").removeClass("active");
        $(this).addClass("active");
        var curSelVal = $(this).data("val");
        var curSelText = $(this).html();
        $(".j-select-verse").val(curSelVal);
        $(".j-sel-verse-text").html(curSelText);
        $(".j-choose-verse-pop").hide();
        $(".j-nav-sel3").show();
        $("#j-sel-book-form").submit();
        e.preventDefault();
    });
    $(".j-sel-verse-label").on("click", function(e){
        $(".j-nav-sel3").hide();
        $(".j-choose-verse-pop").show();
        e.preventDefault();
    });


    // Hide Popups
    $(document).mouseup(function (e){
        var popSettings = $(".j-popup-settings");
        var popSettings2 = $(".j-btn-settings");
        if (!popSettings.is(e.target) && popSettings.has(e.target).length === 0 && !popSettings2.is(e.target) && popSettings2.has(e.target).length === 0) {
            popSettings.hide();
        }

        var popCreate = $(".j-popup-create-record");
        var popCreate2 = $(".j-create-record");
        if (!popCreate.is(e.target) && popCreate.has(e.target).length === 0 && !popCreate2.is(e.target) && popCreate2.has(e.target).length === 0) {
            popCreate.hide();
        }

        var popCompare = $(".j-popup-compare");
        var popCompare2 = $(".j-btn-compare");
        var popCompare3 = $(".j-compare-versions");
        var popCompare4 = $(".select2-container");

        var leftMenu = $(".j-hide-menu");

        if (!popCompare.is(e.target) && popCompare.has(e.target).length === 0 && !popCompare2.is(e.target) && popCompare2.has(e.target).length === 0 && !popCompare3.is(e.target) && popCompare3.has(e.target).length === 0 && !popCompare4.is(e.target) && popCompare4.has(e.target).length === 0 && hidev==true) {
            popCompare.hide();
            hidev=true;
        }
        if (!leftMenu.is(e.target) && leftMenu.has(e.target).length === 0) {
            leftMenu.addClass("hide-menu");
        }
    });

    $('.j-live-search-list li').each(function(){
        $(this).attr('data-search-term', $(this).text().toLowerCase());
    });

    $('.j-live-search-box').on('keyup', function(){
        var searchTerm = $(this).val().toLowerCase();
        $('.j-live-search-list li').each(function(){
            if ($(this).filter('[data-search-term *= ' + searchTerm + ']').length > 0 || searchTerm.length < 1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    $('.j-live-search-list').on('click','.j-versions-to-compare-list',function(){
        if($("#compare-versions-select").val() && $("#compare-versions-select").val().length >= 2){
            $(this).after('<div class="j-compare-error" style="color: red; font-size: 12px; margin-left: 5px;">You can only select 2 items</div>');
            setTimeout(function(){
                $('.j-compare-error').remove();
            },3000);
            //$('#popup-sm').find('.modal-header .modal-title').text('Warning');
            //$('#popup-sm').find('.modal-body').html("You can only select 2 items");
            //$('#popup-sm').find('.modal-footer').html('<button type="button" class="btn2-kit cu-btn-pad1" data-dismiss="modal">Ok</button>');
            //$('#popup-sm').modal({show:true});
            return false;
        }
        $("#compare-versions-select option[value='" + $(this).data('versioncode') + "']").attr('selected', 'selected');
        $('.j-compare-list').append($(this));
        $(this).find('.btn-reset').removeClass('hidden');
    });

    $('.j-remove-compare').click(function(e){
        e.preventDefault();
        $("#compare-versions-select option[value='" + $(this).parents('li').data('versioncode') + "']").removeAttr('selected');
        $(this).addClass('hidden');
        $('.live-search-list').append($(this).parents('li'));
    });

    /* ----------------- My Journey ----------------- */
    $('.j-show-adv-search').click(function(e){
        $('.j-admin-verses-filters').toggleClass('hidden');
        $('.j-short-verses-filters').toggleClass('hidden');
    });

    function dinamicArrows() {
        if($('.j-chapter-content,.j-dynamic-arrows').length > 0){
            var eTop = $('.j-nav-sel2').offset().top;
            var arrowsTop = eTop - $(window).scrollTop();
            var paginationPos = $(window).height() - ($('.j-reader-pagination').offset().top  - $(window).scrollTop());
            $('.j-dynamic-arrows').width($('.j-chapter-content').width());
            $('.j-dynamic-arrows').css('top',($(window).height()/2)-20);
            if((arrowsTop < -45 && paginationPos < 70) || (paginationPos > 70 && arrowsTop > -45)){
                $('.j-dynamic-arrows').fadeIn();
            }
            $(window).scroll(function() {
                var arrowsTop = eTop - $(window).scrollTop();
                var paginationPos = $(window).height() - ($('.j-reader-pagination').offset().top  - $(window).scrollTop());
                if((arrowsTop < -45 && paginationPos < 70) || (paginationPos > 70 && arrowsTop > -45)){
                    $('.j-dynamic-arrows').fadeIn();
                }
                if((arrowsTop > -45 || paginationPos > 70) || (paginationPos > 70 && arrowsTop < -45)){
                    $('.j-dynamic-arrows').fadeOut();
                }

            });
        }
    }
    dinamicArrows();

    if($('.j-compare-verses').length > 0){
        $('body').scrollTo($('.j-compare-verses'),0,{offset:0});
    }
    $('#popup, .entry-form').on('click','.j-collapse',function(){
        if($(this).hasClass('collapsed')){
            $(this).find('.j-arrow-up-down').addClass('arrow-up');
        }
        else{
            $(this).find('.j-arrow-up-down').removeClass('arrow-up');
        }
    });
    site.hideAlert();

    $('body').on('click','.j-submit-order', function(e){
        e.preventDefault();
        order.fillBillingInfo();
        $('.j-create-order-form').submit();
        return false;
    });

    $('body').on('click','.j-quote-rate', function(e){
        var zip = $('input[name=shipping_postcode]').val();

        if(!zip){
            $('.j-quote-result').text('Please fill Shipping Postcode');
            return false;
        }

        $('.spinner').show();
        $.ajax({
            method: "GET",
            url: '/order/usps-rate-ajax/'+zip,
            success:function(data){
                $('.j-quote-result').text(data);
                $('.spinner').hide();
            }
        });

        return false;
    });

    $('body').on('click','a.disabled',function(e){
        e.preventDefault();
        site.showPremiumWarning();
    });

    $('body').on('click','.j-my-bookmarks',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var that = this;
        $.ajax({
            method: "GET",
            url: url,
            success:function(data){
                $('#popup-sm').find('.modal-header .modal-title').html('<i class="fa fa-bookmark-o" style="margin-left: 15px;" aria-hidden="true"></i> My Bookmarks');
                $('#popup-sm').find('.modal-body').css('padding','20px').html('<div class="my-bookmarks-list j-my-bookmarks-list">'+data+'</div>');
                $('#popup-sm').find('.modal-footer').remove();
                $('#popup-sm').modal({show:true});
            }
        });
    });
    $('body').on('click','.j-bookmark',function(e){
        e.preventDefault();
        $(that).toggleClass('hidden');
        var url = $(this).attr('href');
        var that = this;
        $.ajax({
            method: "GET",
            url: url,
            success:function(data){
                if(data){
                    $('.j-bookmark').toggleClass('hidden');
                }
            },
            error:function(err){
                if(err.status){
                    site.showAuthWarning();
                }
            }
        });
    });
    $('body').on('click','.j-my-bookmarks-list .j-remove-bookmark',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var that = this;
        $.ajax({
            method: "GET",
            url: url,
            success:function(data){
                $(that).parent().remove();
            }
        });
    });

    $('body').on('click','.j-my-bookmarks-list .load-more',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var that = this;
        $.ajax({
            method: "GET",
            url: url,
            success:function(data){
                $('#popup-sm .modal-body .load-more-block').remove();
                $('#popup-sm .modal-body .j-my-bookmarks-list').append(data);
            }
        });
    });

    $('.j-hide-menu li.active a').on("click", function (e) {
        $('.j-hide-menu').toggleClass("hide-menu");
        e.preventDefault();
    });
    $('.j-hide-menu li.active .caret').on("click", function (e) {
        $('.j-hide-menu').toggleClass("hide-menu");
        e.preventDefault();
    });
});