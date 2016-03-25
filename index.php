<?php

/************* UPLOADING FILE *******************/
$pdf_file   = 'image.pdf';
$dir = 'images/';

if(isset($_POST["submit"])) {
$target_file = basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check file size
if ($_FILES["fileToUpload"]["size"] > 5000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "pdf" ) {
    echo "Sorry, only PDF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $dir.$pdf_file)) {
        //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

/********** CONVERTING PDF TO JPG ***************/
if ($uploadOk == 1) {
$imgCounter = new imagick($dir.$pdf_file);
$pages = $imgCounter ->getNumberImages(); 

for($i=0;$i<$pages;$i++)
{
  $save_to  = 'image_'.rand().'.jpg';
  $img = new imagick($dir.$pdf_file.'['.$i.']');
  //set new format
  $img->setImageFormat('jpg');
  //save image file
  $img->writeImage($dir.$save_to);
  ?><img src="<?php echo $dir.$save_to;?>"><?php



}

unlink($dir.$pdf_file);
//unlink($save_to);

        
        
}

}

?>
<form action="" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload PDF" name="submit">
</form>
<?php
/********* Delete All Files After 1 Minute **********/
/*
$files = glob('images/*');

foreach($files as $file) { // iterate files
    // if file creation time is more than 5 minutes
    if ((time() - filectime($file)) > 60) {  // 86400 = 60*60*24
        unlink($file);
    }
}
*/
?>