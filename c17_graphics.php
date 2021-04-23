<?php
$image = imagecreatetruecolor(200, 500);
$grey = 0XCCCCCC;
imagefilledrectangle($image, 0, 0, 200-1, 50-1, $grey);
$white = 0XFFFFFF;
imagefilledrectangle($image, 50, 10, 150, 40, $white);
header('Content-type: image/png');
imagepng($image);
imagedestroy($image);