<?php
//connect to db
$con=mysqli_connect("host","user","pass","db");
include 'inc/con.php';
//get the pallette id
$id = $_GET['id'];
//create a random key
$length = 10;
$session = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ"), 0, $length);
//call the api include file
require('ColourLovers.php');
//set a new request
$c = new ColourLovers;
$results = $c->palette($id);    
//cut the array into the needed data-name only
$paletteName=($results['palette']['title']);
//cut the array into hex codes
$hex=($results['palette']['colors']['hex']);
//did we get a name?
echo $paletteName;
echo '<hr>'; //deleteme
//do we have a random key?
echo $session;
echo '<hr>'; //deleteme
//loop and echo the hex codes
foreach($hex as $value){ 
    print $value;
    echo '<br>';
    }
echo '<hr>'; //deleteme

//echo out the hex codes with sqaure swatches
foreach($hex as $value){ 
    print '<div id ="brick" style="background-color: #'.$value .';width: 100px;height: 100px"></div><u>'.$value.'</u></br>';  
    $cycle++;
    }

echo '<hr>';//deleteme

//insert the array into the db
foreach($hex as $value){ 
mysqli_query($con,"INSERT INTO hexInbound (paletteId, hexId, paletteName, randomId)
VALUES ('$id', '$value', '$paletteName', '$session')");
echo "success";
  }
echo '<hr>';//deleteme

//pull the just written hex form the db using the session key
$result = mysqli_query($con,"SELECT hexId, paletteName FROM hexInbound where randomId = '$session'");
while($row = mysqli_fetch_array($result)){
    echo $row['hexId'].' '.$row['paletteName']. '<br />';
}
echo '<hr>';//deleteme

//echo the css asset types
$result = mysqli_query($con,"SELECT * FROM cssAssets");
while($row = mysqli_fetch_array($result)){
    echo $row['cssAsset'].' '.$row['cssCode']. '<br />';
}









?>
