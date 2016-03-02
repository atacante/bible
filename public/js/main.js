$(document).ready(function(){
    $('.j-verses-filters').on('change','select[name=book]',function(){
        $.ajax({
            method: "GET",
            url: "/ajax/chapters-list",
            dataType: "json",
            data:{book_id:$(this).val()},
            success:function(data){
                site.fillSelect('select[name=chapter]',data);
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
});