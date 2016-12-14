<?php
include("class.importer.php");
/*
$newImport = new Importer("localhost", "username", "password");
*/
$mysqlImport = new Importer("localhost", "user", "pass");

/* Put file in sqlfiles directory and replace the name here.
write name of the databse that you created.
*/
$mysqlImport->doImport("./sqlfiles/test.sql", "databsename", true);

if ($mysqlImport->importerErrors){
	echo "<pre>\n";
	print_r($mysqlImport->errors);
	echo "\n</pre>";
} else {
	echo "<strong>File imported successfully</strong>";
}
?>
