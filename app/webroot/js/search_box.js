$(function() {
    var mycounter = 0;
    var amount = 0;
    $( "#sortable1, #sortable2" ).sortable({
        connectWith: ".connectedSortable",    
    }).disableSelection();

    $( "#sortable3, #sortable4" ).sortable({
        connectWith: ".connectedSortablec",    
    }).disableSelection();

    $("#sortable2").sortable({
        update: function(event, ui) {
	
	        /* If the searchVal var is NOT empty then check the database for possible results
	         * else display message to user */
	            /* Fire the .get() method for and pass the searchVal data to the
	             * search-data.php file for retrieval */
	        $.get('/repositorium/index.php/categories/autocomplete/?searchData='+''+'&categories='+$('#sortable4').text()+'&criterias='+$('#sortable2').text(), function(returnData) {
	                /* If the returnData is empty then display message to user
	                 * else our returned data results in the table.  */
	        	if (!returnData) {
	                $('#sortable3').html('');
	        	} else {
	                $('#sortable3').html(returnData);
	                $('#msgc').html('<p style="padding:5px;">Enter a search term to start filtering.</p>');
	        	}
	        });
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
            });
            if(mycounter == 0) {
                $('#counter').val(mycounter);
            }
        }
    });

    $("#sortable4").sortable({
        update: function(event, ui) {
        	$.get('/repositorium/index.php/criterias/autocomplete/?searchData='+''+'&criterias='+$('#sortable2').text()+'&categories='+$('#sortable4').text(), function(returnData) {
                /* If the returnData is empty then display message to user
                 * else our returned data results in the table.  */
	            if (!returnData) {
	            	$('#sortable1').html('');
	            } else {
	            	$('#sortable1').html(returnData);
	            	$('#msg').html('<p style="padding:5px;">Enter a search term to start filtering.</p>');
	            }
	        });
	        /* Get the value of the search input each time the keyup() method fires so we
	         * can pass the value to our .get() method to retrieve the data from the database */
	        //var searchVal = $(this).val();
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
            });
            $('#sortable4 li').each(function(index){
                var start = $(this).text().indexOf('-')+1;
                var end = $(this).text().indexOf('points')-1;
                mycounter +=parseInt($(this).text().substring(start, end));
                $('#counter').val(mycounter);
            });
            if(mycounter == 0) {
                $('#counter').val(mycounter);
            }
        }
    });

    $('form').submit(function(){ 
        $('#thedata').val($( "#sortable2" ).sortable("serialize"));
        $('#thedata2').val($( "#sortable4" ).sortable("serialize"));
    });

});