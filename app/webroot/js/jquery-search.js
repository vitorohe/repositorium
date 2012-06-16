    $(document).ready(function(){
        /*
         Set the inner html of the table, tell the user to enter a search term to get started.
         We could place this anywhere in the document. I chose to place it
         in the table.
        */
        $('#msg').html('<p style="padding:5px;">Enter a search term to start filtering.</p>');
        $('#msgc').html('<p style="padding:5px;">Enter a search term to start filtering.</p>');


        /* When the user enters a value such as "j" in the search box
         * we want to run the .get() function. */
        $('#searchData').keyup(function() {

            /* Get the value of the search input each time the keyup() method fires so we
             * can pass the value to our .get() method to retrieve the data from the database */
            var searchVal = $(this).val();

            /* If the searchVal var is NOT empty then check the database for possible results
             * else display message to user */
            /* Fire the .get() method for and pass the searchVal data to the
             * search-data.php file for retrieval */
            $.get('/repositorium/index.php/criterias/autocomplete/?searchData='+searchVal+'&criterias='+$('#sortable2').text()+'&categories='+$('#sortable4').text(), function(returnData) {
                    /* If the returnData is empty then display message to user
                     * else our returned data results in the table.  */
	            if (!returnData) {
	            	$('#msg').html('<p style="padding:5px;">Search term entered does not return any data.</p>');
	            	$('#sortable1').html('');
	            	} else {
	            	$('#sortable1').html(returnData);
	            	$('#msg').html('<p style="padding:5px;">Enter a search term to start filtering.</p>');
	            }
            });


            /* Get the value of the search input each time the keyup() method fires so we
             * can pass the value to our .get() method to retrieve the data from the database */
            //var searchVal = $(this).val();

            /* If the searchVal var is NOT empty then check the database for possible results
             * else display message to user */
                /* Fire the .get() method for and pass the searchVal data to the
                 * search-data.php file for retrieval */
            $.get('/repositorium/index.php/categories/autocomplete/?searchData='+searchVal+'&categories='+$('#sortable4').text()+'&criterias='+$('#sortable2').text(), function(returnData) {
                    /* If the returnData is empty then display message to user
                     * else our returned data results in the table.  */
            	if (!returnData) {
            		$('#msgc').html('<p style="padding:5px;">Search term entered does not return any data.</p>');
                    $('#sortable3').html('');
            	} else {
                    $('#sortable3').html(returnData);
                    $('#msgc').html('<p style="padding:5px;">Enter a search term to start filtering.</p>');
            	}
            });
        });

    });