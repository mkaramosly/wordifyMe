


<!DOCTYPE html>
<head>

</head>
<body>

<!--<div>-->
<!--<form -->
<!---->
<!--	<button id="""upload" name="upload" ty-->
<!--</div>-->


<form enctype="multipart/form-data" action="upload.php" method="POST">
	<!-- MAX_FILE_SIZE must precede the file input field -->
	<input type="hidden" name="MAX_FILE_SIZE" value="300000" />
	<!-- Name of input element determines name in $_FILES array -->
	<label for="upfile">Upload Audio File:</label>
	<input name="upfile" type="file" />
	<input type="submit" value="Send File" />
</form>

</body>
</html>

