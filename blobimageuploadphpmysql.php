<?php

//This code shows how to save image im mysql database using php, sql and html.
//The image is uploaded using php and sql.
//It's a web-based application that can be accessed by using a browser.
//This is for educational purposes only, Use it at your own risk.

//Connect to server
$servername = "localhost";
$username = "root";
$password = "";
$conn = mysqli_connect($servername, $username, $password);
if ($conn) {
echo "Connected to server successfully";
} else {
die( "Failed To Connect to server ". mysqli_connect_error() );
}

$selectalreadycreateddatabase = mysqli_select_db($conn, "PhpMysqlDatabaseBlobImageUpload"); 
if ($selectalreadycreateddatabase) {
echo "<br /> Exixting database selected successfully";
} else {
echo "<br /> Selected Database Not Found";
$createNewDb = "CREATE DATABASE IF NOT EXISTS `PhpMysqlDatabaseBlobImageUpload`";
if (mysqli_query($conn, $createNewDb)) {
echo "<br />New Database Created Successfullly";
$selectCreatedDatabase = mysqli_select_db($conn, "PhpMysqlDatabaseBlobImageUpload");
if ($selectCreatedDatabase) {
echo "<br />Created Database Selected Successfullly";
// Creating new table 
$sqlcreatetable = "
CREATE TABLE IF NOT EXISTS `imageuploadphpmysqlblob` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`name` varchar(100) NOT NULL,
`image` longblob NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
";

if (mysqli_query($conn, $sqlcreatetable)) {
echo "<br />New table Created";
} else {
echo "<br /> Unable to create new table.";
}

}
} else {
echo "Unable to create database";

}
}

if (isset($_POST['submit'])) {
if (getimagesize($_FILES['imagefile']['tmp_name']) == false) {
echo "<br />Please Select An Image.";
} else {

//declare variables
$image = $_FILES['imagefile']['tmp_name'];
$name = $_FILES['imagefile']['name'];
$image = base64_encode(file_get_contents(addslashes($image)));

$sqlInsertimageintodb = "INSERT INTO `imageuploadphpmysqlblob`(`name`, `image`) VALUES ('$name','$image')";
if (mysqli_query($conn, $sqlInsertimageintodb)) {
echo "<br />Image uploaded successfully.";
} else {
echo "<br />Image Failed to upload.";
}

}

} else {
# code...
}

//Retrieve image from database and display it on html webpage
function displayImageFromDatabase(){
//use global keyword to declare conn inside a function
global $conn;
$sqlselectimageFromDb = "SELECT * FROM `imageuploadphpmysqlblob` ";
$dataFromDb = mysqli_query($conn, $sqlselectimageFromDb);
while ($row = mysqli_fetch_assoc($dataFromDb)) {
echo '<img height="250px" width="250px" src=data:image;base64,'.$row['image'].'/>';
}


}
//calling the function to display image
displayImageFromDatabase();

//Finnaly close connection
if (mysqli_close($conn)) {
echo "<br />Connection Closed.......";
}


?>
<!DOCTYPE html>
<html>
<head>
<title>How To upload BLOB Image To Mysql Database Using PHP,SQL And HTML.</title>
</head>
<body>
<form action="" method="post" enctype="multipart/form-data">
<input type="file" name="imagefile">
<br />
<input type="submit" name="submit" value="Upload">

</form>
</body>
</html>