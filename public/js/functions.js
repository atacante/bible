var site = {};
var reader = {};

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
    var tarGet = $('.j-with-images img').eq(e).attr('src');
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
                console.log(fileList);
                i++;
            });
            this.on("removedfile", function(file) {
                var rmvFile = "";
                if(fileList.length > 0){
                    var f;
                    for(f in fileList){
                        console.log(fileList[f].fileName);
                        if(fileList[f].fileName == file.name)
                        {
                            rmvFile = fileList[f].serverFileName;
                            fileList.splice(f,1);
                            console.log(fileList);
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
        data: {filename: $(element).data('filename'),'_token':$('input[name="_token"]').val()},
        dataType: 'html',
        success: function(data){
            var rep = JSON.parse(data);
            $(that).parent().remove();
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
            $('#popup').find('.modal-footer').html('<a title="Print note" href="#" data-noteid="'+id+'" class="j-print-note pull-left"><i class="fa fa-print fa-2x"style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>');
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

