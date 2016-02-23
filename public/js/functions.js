var site = {};
var reader = {};

site.fillSelect = function (selector,items){
    $(selector).empty();
    $.each(items, function(value,key) {
        $(selector).append($("<option></option>")
            .attr("value", value).text(key));
    })
}

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