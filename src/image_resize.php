<?php
/**
 * A function which resizes (and optionally crops) an image to fit a particular size.
 *
 * @param string $source_path The path to the image file
 * @param string $save_path The path to save the image to (or false to output the image)
 * @param int $width The final width of the image, if cropping. Or the maximum width of the image, if resizing
 * @param int $height The final height of the image, if cropping. Or the maximum height of the image, if resizing
 * @param bool $crop Whether to crop the image (true) or resize it (false)
 * @return bool True on success, false on failure
 *
 * @author Edmund Gentle (https://github.com/edmundgentle)
 */
function image_resize($source_path, $save_path=false, $width, $height, $crop=false) {
	if($info=getimagesize($path)) {
		if(substr($info['mime'],0,6)=='image/') {
			$short_type=substr($info['mime'],6);
			$typemaps=array(
				'jpeg'=>'ImageCreateFromJPEG',
				'pjpeg'=>'ImageCreateFromJPEG',
				'png'=>'ImageCreateFromPNG',
				'bmp'=>'ImageCreateFromBMP',
				'x-windows-bmp'=>'ImageCreateFromBMP',
				'vnd.wap.wbmp'=>'ImageCreateFromWBMP',
				'gif'=>'ImageCreateFromGIF',
				'x-xbitmap'=>'ImageCreateFromXBM',
				'x-xbm'=>'ImageCreateFromXBM',
				'xbm'=>'ImageCreateFromXBM',
			);
			$func=$typemaps['jpeg'];
			if(isset($typemaps[$type])) $func=$typemaps[$type];
			
			$sizes=array(
				'final_width'=>0,
				'final_height'=>0,
				'target_width'=>0,
				'target_height'=>0,
				'x_offset'=>0,
				'y_offset'=>0
			);
			
			if($crop) {
				$sizes['final_width']=$width;
				$sizes['final_height']=$height;
				$factor = $width / $info[0];
				if($height<($factor * $info[1])) {
					$sizes['target_height']=$factor*$info[1];
					$sizes['target_width']=$width;
					$sizes['y_offset']=($sizes['target_height']-$height)/2;
				}else{
					$factor = $height / $info[1];
					$sizes['target_height']=$height;
					$sizes['target_width']=$factor*$info[0];
					$sizes['x_offset']=($sizes['target_width']-$width)/2;
				}
			}else{
				if($info[0]>$width or $info[1]>$height) {
					if($info[0]>$info[1]) {
						$sizes['final_width']=$width;
						$sizes['final_height']=($info[1]/$info[0])*$height;
					}else{
						$sizes['final_width']=($info[0]/$info[1])*$width;
						$sizes['final_height']=$height;
					}
				}else{
					$sizes['final_width']=$info[0];
					$sizes['final_height']=$info[1];
				}
				$sizes['target_width']=$sizes['final_width'];
				$sizes['target_height']=$sizes['final_height'];
			}
			
			$thumb=imagecreatetruecolor($sizes['target_width'],$sizes['target_height']);
			$white = imagecolorallocate($thumb, 255, 255, 255);
			imagefill($thumb, 0, 0, $white);
			$source = $func($path);
		    imagecopyresampled($thumb,$source,0,0,0,0,$sizes['target_width'],$sizes['target_height'],$info[0],$info[1]);
		    $dest = imagecreatetruecolor($sizes['final_width'],$sizes['final_height']);
			imagecopy($dest,$thumb, 0, 0, $sizes['x_offset'],$sizes['y_offset'], $sizes['final_width'], $sizes['final_height']);
			if($name) {
				return imagepng($dest,$name,9);
			}else{
				return imagepng($dest);
			}
		}
	}
	return false;
}
?>