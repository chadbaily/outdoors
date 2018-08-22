<?php

$maxChars = 40;
$strings = explode("\n", wordwrap($_GET['text'], $maxChars));
$Y = imagefontwidth(2) * $maxChars;
$lineHeight = imagefontheight(2) + 1;
$X = $lineHeight * count($strings);

$im = imagecreatetruecolor($X, $Y);
$bg = imagecolorallocate($im, 255, 255, 255);
imagefilledrectangle($im, 0, 0, $X, $Y, $bg);
$fg = imagecolorallocate($im, 0, 0, 0);
for ($i = 0; $i < count($strings); ++$i) {
    imagestringup($im, 2, $lineHeight * $i, $Y - 2, $strings[$i], $fg);
}
header("Content-type: image/png");
imagepng($im);
imagedestroy($im);
?>
