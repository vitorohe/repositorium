<div class="input required">
<!--<label for="DocumentFileAttach">Attach some file (at least one)</label>-->
<!--INI-->
<label for="DocumentFileAttach">Attach a file</label>
<!--FIN-->
<?php
	$javascript->link('multifile.js', false);
	$javascript->link('checker.js',false);
?>
</div>

<!--  ensayo -->
<input id="attached_files" type="file" name="attachedfile_1" >
</br>
</br>
<!--Files Attached:-->
<!--INI-->
File Attached:
<!--FIN-->
<!-- This is where the output will appear -->
<div id="files_list"></div>
<script>
	<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->
	
	//var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 3,'MyOutputDiv' );
	/*++++++++++++++INI++++++++++++++*/
	var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 1,'MyOutputDiv' );
	/*++++++++++++++FIN++++++++++++++*/
	<!-- Pass in the file element -->
	multi_selector.addElement( document.getElementById( 'attached_files' ) );
	//Se ejecuta una sola vez
	//document.write('<b>Hello World</b>');
	
		document.write('<div id="MyOutputDiv"></div>');
	
	//var files = document.getElementById('files_list').value;
	//document.write('<pre>'+files+'</pre>');
	//CheckFile("lala");
</script>