<?php

$bName = "aaa";
$uploadOk = 0; //0 success
$target_dir = "../upload/";
$target_file = $target_dir.basename($_FILES['file']['name']);
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

//CHECK THE FILE FORMAT MUST BE AN IMAGE
if($imageFileType != "gif" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "jpg"){
    echo "Sorry, only jpeg, jpg, png and gif files are alllowed.";
    $uploadOk = 1;//there is errors
}

//CHECK THE UPLOADED FILE SIZE
if($_FILES['file']['size'] > 2000000){
    echo "Sorry, the file is too huge.";
    $uploadOk = 1;// there is errors
}

if($uploadOk == 1){
    echo "Sorry, your file was not uploaded.";
}else{
    if(move_uploaded_file($_FILES['file']['tmp_name'],$target_file)){
        //insert the info into database
        $bCover = basename($_FILES['file']['name']);
    }else{
        echo "Sorry, there is some error occur while uploading your file.". $_FILES['file']['error'];
    }

}
?>
