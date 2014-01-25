Image Resizer
=============

A PHP function which resizes an image quickly and easily.

Usage
=====

Using this function is very easy. You simply include the file with the function in, then pass in the relevant variables.

    require '../src/image_resize.php';
    
    header('Content-Type: image/png');
    
    image_resize('my_image.jpg', false, 500, 300, false);
    

