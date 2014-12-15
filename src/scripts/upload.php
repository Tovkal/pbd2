<?php
/**
 * Created by PhpStorm.
 * User: tovkal
 * Date: 4/12/14
 * Time: 22:14
 */
// Output JSON
function outputJSON($msg, $status = 'error'){
    header('Content-Type: application/json');
    die(json_encode(array(
        'data' => $msg,
        'status' => $status
    )));
}

// Check for errors
if($_FILES['SelectedFile']['error'] > 0){
    outputJSON('An error ocurred when uploading.');
}

if(!getimagesize($_FILES['SelectedFile']['tmp_name'])){
    outputJSON('Please ensure you are uploading an image.');
}

// Check filetype
if($_FILES['SelectedFile']['type'] == 'image/png' || $_FILES['SelectedFile']['type'] == 'image/jpeg'
    || $_FILES['SelectedFile']['type'] == 'image/jpg'){

    // Check filesize
    if($_FILES['SelectedFile']['size'] > 50000000){
        outputJSON('File uploaded exceeds maximum upload size.');
    }

    // Check if the file exists
    if(file_exists('../img/anuncis/' . $_FILES['SelectedFile']['name'])){
        outputJSON('File with that name already exists.', 'success');
    }

    // Upload file
    if(!move_uploaded_file($_FILES['SelectedFile']['tmp_name'], '../img/anuncis/' . $_FILES['SelectedFile']['name'])) {
        outputJSON('Error uploading file - check destination is writeable.');
    }

    // Success!
    outputJSON('File uploaded successfully to "' . '../img/anuncis/' . $_FILES['SelectedFile']['name'] . '".', 'success');

} else {
    outputJSON('Unsupported filetype uploaded.');
}