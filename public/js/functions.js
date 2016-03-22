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