<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $filename; ?></title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<?php 
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
 ?>
</head>
<body>
	<?php 
	echo $table;
	?>
</body>
</html>