<?php

// default dimensions 
//height : 524px 
//width: 700px

$source_image = imagecreatefromjpeg("jietou01.jpg"); 
$source_imagex = imageSX($source_image); 
$source_imagey = imagesy($source_image);
echo '<pre>';
print_r($source_image);
echo '</pre>';
echo '<pre>';
print_r($source_imagex);
echo '</pre>';
echo '<pre>';
print_r($source_imagey);
echo '</pre>';
//destination image size calculation, should not exceed the target

//check if height and width are larger than required and then scale 
if($source_imagey>524 || $source_imagex > 700) {
if($source_imagey>$source_imagex){
   $new_height = 524;       
       $new_width = 524/$source_imagey*$source_imagex;
}else{
   $new_width = 700;
       $new_height = 700/$source_imagex*$source_imagey;     
    } 
 }

 $dest_image = imagecreatetruecolor($new_width, $new_height);

 //poor quality but fast 
 imagecopyresized($dest_image, $source_image, 0, 0, 0, 0, $new_width,
 $new_height, $source_imagex, $source_imagey);
 imagejpeg($dest_image,"final.jpg", 80);


 //better quality but slow 
 imagecopyresampled($dest_image, $source_image, 0, 0, 0, 0, $new_width,
 $new_height, $source_imagex, $source_imagey); 
 imagejpeg($dest_image, 'final2.jpg', 80);

 //create square thumbnail 
 // resize image to scale shorter side to 90px 
 if($new_width>90 || $new_height>90) {  
    if($new_height>$new_width)  
    {       
       $thumb_height = (90/$new_width)*$new_height;             
       $thumb_width = 90;
       $top = ($tHeight - 90)/2;
       $left = 0;   
    }else{
   $thumb_width = (90/$new_height)*$new_width;
   $thumb_height = 90;
       $left = ($thumb_width-90)/2;         
       $top = 0;    
    } 
 }
 $image_t = imagecreatetruecolor($thumb_width,$thumb_height);   
 imagecopyresampled($image_t, $dest_image, 0, 0, 0, 0, $thumb_width,
 $thumb_height, $new_width, $new_height);

 $thumb_image = imagecreatetruecolor(90,90); 
 imagecopy($thumb_image, $image_t, 0, 0, $left, $top,
 $thumb_width,$thumb_height); 
 imagejpeg($thumb_image, 'thumb.jpg', 80);

 imagedestroy($thumb_image); 
 imagedestroy($dest_image);    
 imagedestroy($image_t);