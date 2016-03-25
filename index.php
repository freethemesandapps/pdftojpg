<?php

/************* UPLOADING FILE *******************/
$pdf_file   = 'image.pdf';
$dir = 'images/';

/********* DELETE ALL IMAGES AFTER FEW MINUTES **********/
$files = glob($dir.'*');

foreach($files as $file) { // iterate files
    // if file creation time is more than 2 minutes
    if ((time() - filectime($file)) > 120) {  
        unlink($file);
    }
}


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
  
  $outImage[] = $save_to;

}
//Delete Original PDF File
unlink($dir.$pdf_file);
//unlink($save_to);

        
        
}

}

?>
<html
<head>
<title>PDF to JPG</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</head>
<body>
<div class="well text-center">
</div>

<div class="container">

    <div class="row">

        <div class="col-lg-12">
            <h1 class="page-header"><span class="glyphicon glyphicon-file" aria-hidden="true"></span>
            						<span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
                                    <span class="glyphicon glyphicon-picture" aria-hidden="true"></span> <br>
                                    PDF to JPG
                <small>Convert your file right now!</small>
            </h1>
            <ol class="breadcrumb">
                <li>&nbsp;</li>
            </ol>
        </div>

    </div>

    <div class="row">

        <div class="col-md-6">
			<?php
            if($outImage){
            foreach($outImage as $singleImage)
            {
               ?><div class="col-lg-4">
               		<img class="img-responsive" src="<?php echo $dir.$singleImage;?>">
				</div><?php
            }
            }
            ?>
        </div>
        <div class="col-md-6">
            <h2>Follow these steps:</h2>
			<ol>
            	<li>Upload your PDF file: 
                <form action="" method="post" enctype="multipart/form-data">
                <label class="file">
                  <input type="file" name="fileToUpload" id="fileToUpload" required>
                  <span class="file-custom"></span>
                </label>
                  <input type="submit" value="Upload PDF" name="submit">
                </form>
                </li>
                <li>Only pdf files less than 5Mb allowed</li>
                <li>Once the file is converted the thumbnails will appear on the left panel</li>
                <li>Just <b>right-click</b> the image and select <b>"save image as..."</b></li>
                <li>All images will be removed from server after 2 minutes</li>
            </ol>
        </div>

        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li>Author: <a href="http://andreandrade.us" target="_blank">Andre Andrade</a></li>
            </ol>
        </div>

    </div>
  </div>


    
</body>
</html>