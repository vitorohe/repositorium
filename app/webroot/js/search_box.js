$(function() {
    var mycounter = 0;
    var amount = 0;
    var total = 0;
    $( "#sortable1, #sortable2" ).sortable({
        connectWith: ".connectedSortable",    
    }).disableSelection();

    $( "#sortable3, #sortable4" ).sortable({
        connectWith: ".connectedSortablec",    
    }).disableSelection();

    $("#sortable2").sortable({
        update: function(event, ui) {
            mycounter = 0;
            amount = 0;
            if($('#amount').val() == "")
                amount = 0;
            else
                amount = parseInt($('#amount').val());
            $('#sortable2 li').each(function(index){
                var start = $(this).text().indexOf('-')+1;
                var end = $(this).text().indexOf('points')-1;
                mycounter +=parseInt($(this).text().substring(start, end));
                $('#counter').val(mycounter);
                total = mycounter*amount;
                $('#total').val(total);
            });
            $('#sortable4 li').each(function(index){
                var start = $(this).text().indexOf('-')+1;
                var end = $(this).text().indexOf('points')-1;
                mycounter +=parseInt($(this).text().substring(start, end));
                $('#counter').val(mycounter);
                total = mycounter*amount;
                $('#total').val(total);
            });
            if(mycounter == 0) {
                $('#counter').val(mycounter);
                total = mycounter*amount;
                $('#total').val(total);
            }
        }
    });

    $("#sortable4").sortable({
        update: function(event, ui) {
            mycounter = 0;
            amount = 0;
            if($('#amount').val() == "")
                amount = 0;
            else
                amount = parseInt($('#amount').val());
            $('#sortable2 li').each(function(index){
                var start = $(this).text().indexOf('-')+1;
                var end = $(this).text().indexOf('points')-1;
                mycounter +=parseInt($(this).text().substring(start, end));
                $('#counter').val(mycounter);
                total = mycounter*amount;
                $('#total').val(total);
            });
            $('#sortable4 li').each(function(index){
                var start = $(this).text().indexOf('-')+1;
                var end = $(this).text().indexOf('points')-1;
                mycounter +=parseInt($(this).text().substring(start, end));
                $('#counter').val(mycounter);
                total = mycounter*amount;
                $('#total').val(total);
            });
            if(mycounter == 0) {
                $('#counter').val(mycounter);
                total = mycounter*amount;
                $('#total').val(total);
            }
        }
    });

    $('#amount').keyup(function() {
        amount = 0;
        if($('#amount').val() == "")
            amount = 0;
        else
            amount = parseInt($('#amount').val());
        total = mycounter*amount;
        $('#total').val(total);
      
    });

    $('form').submit(function(){ 
        $('#thedata').val($( "#sortable2" ).sortable("serialize"));
        $('#thedata2').val($( "#sortable4" ).sortable("serialize"));
    });

});