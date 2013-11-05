<?php

// Require GD library
if(!extension_loaded('gd')) {
    throw new Exception('Required extension GD is not loaded!');
}

class Lib_Mein_Image
{
    private $src = null;
    
    private $image = null;
    
    public function __construct($src)
    {
        $this->src = $src;
        $info = getimagesize($src);
        
        switch($info['mime'])
        {
            case 'image/gif':
                $this->image = imagecreatefromgif($src);
		break;
            case 'image/jpeg':
                $this->image = imagecreatefromjpeg($src);
		break;
            case 'image/png':
                $this->image = imagecreatefrompng($src);
		break;
            default:
                throw new Exception('Lib_Mein_Image::__construct() Unsupported image type');
        }
    }
    
    public function getResource()
    {
        return $this->image;
    }
    
    public function getWidth()
    {
        return imagesx($this->image);
    }
    
    public function getHeight()
    {
        return imagesy($this->image);
    }
    
    public function getType()
    {
        $info = getimagesize($this->src);
        return $info['mime'];
    }

    public function save($filename, $type = 'image/jpeg', $quality = null)
    {
        switch($type)
        {
            case 'image/gif':
                imagegif($this->image, $filename);
		break;
            case 'image/jpg':
            case 'image/jpeg':
                if($quality == null) {
                    $quality = 85;
                } else if($quality < 0) {
                    $quality = 0;
                } else if($quality > 100) {
                    $quality = 100;
                }
		imagejpeg($this->image, $filename, $quality);
		break;
            case 'image/png':
                if($quality == null) {
                    $quality = 9;
                } else if($quality > 9) {
                    $quality = 9;
                } else if($quality < 1) {
                    $quality = 0;
                }
		imagepng($this->image, $filename, $quality);
                break;
            default:
                // Unsupported image type
                throw new Exception('Unsupported image type');
		#return false;
                break;
        }
        
        return $this;
    }

    // Same as PHP's imagecopymerge() function, except preserves alpha-transparency in 24-bit PNGs
    private function imagecopymergeAlpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct)
    {
        $cut = imagecreatetruecolor($src_w, $src_h);
	imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
	imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
	imagecopymerge($dst_im, $cut, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct);
        
        return $dst_im;
    }

    // Converts a hex color value to its RGB equivalent
    private function hex2rgb($hex_color)
    {
        if($hex_color[0] == '#') {
            $hex_color = substr($hex_color, 1);
        }
	if( strlen($hex_color) == 6 ) {
            list($r, $g, $b) = array(
                $hex_color[0] . $hex_color[1],
		$hex_color[2] . $hex_color[3],
		$hex_color[4] . $hex_color[5]
            );
	} elseif( strlen($hex_color) == 3 ) {
            list($r, $g, $b) = array(
                $hex_color[0] . $hex_color[0],
		$hex_color[1] . $hex_color[1],
		$hex_color[2] . $hex_color[2]
            );
	} else {
            return false;
	}
        
        return array(
            'r' => hexdec($r),
            'g' => hexdec($g),
            'b' => hexdec($b)
	);
    }

    // Flip an image horizontally or vertically
    public function flip($direction)
    {
        $new = imagecreatetruecolor($this->getWidth(), $this->getHeight());

        switch(strtolower($direction))
        {
            case 'v':
            case 'vertical':
            case 'y':
                for($y = 0;$y < $this->getHeight();$y++) {
                    imagecopy($new, $this->image, 0, $y, 0, $this->getHeight() - $y - 1, $this->getWidth(), 1);
                }
		break;
            case 'h':
            case 'horizontal':
            case 'x':
                for ($x = 0;$x < $this->getWidth();$x++) {
                    imagecopy($new, $this->image, $x, 0, $this->getWidth() - $x - 1, 0, 1, $this->getHeight());
                }
		break;
        }
        
        $this->image = $new;
        
        return $this;
    }

    // Rotate an image
    public function rotate($angle = 270, $bg_color = 0)
    {
	// Determine angle
	$angle = strtolower($angle);
	if($angle == 'cw' || $angle == 'clockwise') {
            $angle = 270;
        } else if($angle == 'ccw' || $angle == 'counterclockwise') {
            $angle = 90;
        }

        $rgb = $this->hex2rgb($bg_color);
	$bg_color = imagecolorallocate($this->image, $rgb['r'], $rgb['g'], $rgb['b']);

        $this->image = imagerotate($this->image, $angle, $bg_color);
        
        return $this;
    }


    // Convert an image from color to grayscale ("desaturate")
    public function grayscale()
    {
        imagefilter($this->image, IMG_FILTER_GRAYSCALE);
        return $this;
    }

    // Invert image colors
    public function invert()
    {
        imagefilter($this->image, IMG_FILTER_NEGATE);
        return $this;
    }

    // Adjust image brightness
    public function brightness($level)
    {
        imagefilter($this->image, IMG_FILTER_BRIGHTNESS, $level);
        return $this;
    }

    // Adjust image contrast
    public function contrast($level)
    {
        imagefilter($this->image, IMG_FILTER_CONTRAST, $level);
        return $this;
    }

    // Colorize an image (requires PHP 5.2.5+)
    public function colorize($red, $green, $blue, $alpha)
    {
        imagefilter($this->image, IMG_FILTER_COLORIZE, $red, $green, $blue, $alpha);
        return $this;
    }

    // Highlight image edges
    public function edgedetect()
    {
        imagefilter($this->image, IMG_FILTER_EDGEDETECT);
        return $this;
    }

    // Emboss an image
    public function emboss()
    {
        imagefilter($this->image, IMG_FILTER_EMBOSS);
        return $this;
    }

    // Blur an image
    public function blur($level = 1)
    {
        for($i = 0;$i < $level;$i++) {
            imagefilter($this->image, IMG_FILTER_GAUSSIAN_BLUR);
        }
        return $this;
    }

    // Create a sketch effect
    public function sketch($level = 1)
    {
        for($i = 0;$i < $level;$i++ ) {
            imagefilter($this->image, IMG_FILTER_MEAN_REMOVAL);
        }
        return $this;
    }

    // Make image smoother
    public function smooth($level)
    {
        imagefilter($this->image, IMG_FILTER_SMOOTH, $level);
        return $this;
    }

    // Make image pixelized (requires PHP 5.3+)
    public function pixelate($block_size, $advanced_pix = false)
    {
	imagefilter($this->image, 11, $block_size, $advanced_pix);
        return $this;
    }

    // Produce a sepia-like effect
    public function sepia()
    {
        imagefilter($this->image, IMG_FILTER_GRAYSCALE);
        imagefilter($this->image, IMG_FILTER_COLORIZE, 90, 60, 30);
        return $this;
    }

    // Resize an image to the specified dimensions
    public function resize($new_width, $new_height, $resample = true)
    {
        $new = imagecreatetruecolor($new_width, $new_height);

        // Preserve alphatransparency in PNGs
	imagealphablending($new, false);
	imagesavealpha($new, true);

	if($resample) {
            imagecopyresampled($new, $this->image, 0, 0, 0, 0, $new_width, $new_height, $this->getWidth(), $this->getHeight());
        } else {
            imagecopyresized($new, $this->image, 0, 0, 0, 0, $new_width, $new_height, $this->getWidth(), $this->getHeight());
        }
        
        $this->image = $new;
        
        return $this;
    }

    // Proportionally scale an image to fit the specified width
    public function resizeToWidth($new_width, $resample = true)
    {
        // Determine aspect ratio
	$aspect_ratio = $this->getHeight() / $this->getWidth();

        // Adjust height proportionally to new width
	$new_height = $new_width * $aspect_ratio;

	$new = imagecreatetruecolor($new_width, $new_height);

	// Preserve alphatransparency in PNGs
	imagealphablending($new, false);
	imagesavealpha($new, true);

	if($resample) {
            imagecopyresampled($new, $this->image, 0, 0, 0, 0, $new_width, $new_height, $this->getWidth(), $this->getHeight());
        } else {
            imagecopyresized($new, $this->image, 0, 0, 0, 0, $new_width, $new_height, $this->getWidth(), $this->getHeight());
        }
        
        $this->image = $new;
        
        return $this;
    }

    // Proportionally scale an image to fit the specified height
    public function resizeToHeight($new_height, $resample = true)
    {
        // Determine aspect ratio
	$aspect_ratio = $this->getHeight() / $this->getWidth();

        // Adjust height proportionally to new width
	$new_width = $new_height / $aspect_ratio;

	$new = imagecreatetruecolor($new_width, $new_height);

	// Preserve alphatransparency in PNGs
	imagealphablending($new, false);
	imagesavealpha($new, true);

	if($resample) {
            imagecopyresampled($new, $this->image, 0, 0, 0, 0, $new_width, $new_height, $this->getWidth(), $this->getHeight());
	} else {
            imagecopyresized($new, $this->image, 0, 0, 0, 0, $new_width, $new_height, $this->getWidth(), $this->getHeight());
	}
        
        $this->image = $new;
        
        return $this;
    }

    // Proportionally shrink an image to fit within a specified width/height
    public function shrinkToFit($max_width, $max_height, $resample = true)
    {
        // Determine aspect ratio
	$aspect_ratio = $this->getHeight() / $this->getWidth();

        // Make width fit into new dimensions
	if($this->getWidth() > $max_width)
        {
            $new_width = $max_width;
            $new_height = $new_width * $aspect_ratio;
	}
        else
        {
            $new_width = $this->getWidth();
            $new_height = $this->getHeight();
	}

	// Make height fit into new dimensions
	if($new_height > $max_height)
        {
            $new_height = $max_height;
            $new_width = $new_height / $aspect_ratio;
	}
        
	$new = imagecreatetruecolor($new_width, $new_height);

	// Preserve alphatransparency in PNGs
	imagealphablending($new, false);
	imagesavealpha($new, true);

	if($resample) {
            imagecopyresampled($new, $this->image, 0, 0, 0, 0, $new_width, $new_height, $this->getWidth(), $this->getHeight());
        } else {
            imagecopyresized($new, $this->image, 0, 0, 0, 0, $new_width, $new_height, $this->getWidth(), $this->getHeight());
	}
        
        $this->image = $new;
        
        return $this;
    }

    // Crop an image and optionally resize the resulting piece
    public function crop($x1, $y1, $x2, $y2, $new_width = null, $new_height = null, $resample = true)
    {
        // Crop size
	if($x2 < $x1) {
            list($x1, $x2) = array($x2, $x1);
        }
	if($y2 < $y1) {
            list($y1, $y2) = array($y2, $y1);
        }
        
        $crop_width = $x2 - $x1;
	$crop_height = $y2 - $y1;

	if($new_width == null) {
            $new_width = $crop_width;
        }
	if($new_height == null) {
            $new_height = $crop_height;
        }

	$new = imagecreatetruecolor($new_width, $new_height);

	// Preserve alphatransparency in PNGs
	imagealphablending($new, false);
	imagesavealpha($new, true);

	// Create the new image
	if($resample) {
            imagecopyresampled($new, $this->image, 0, 0, $x1, $y1, $new_width, $new_height, $crop_width, $crop_height);
        } else {
            imagecopyresized($new, $this->image, 0, 0, $x1, $y1, $new_width, $new_height, $crop_width, $crop_height);
	}
        
        $this->image = $new;
        
        return $this;
    }

    // Trim the edges of a portrait or landscape image to make it square and optionally resize the resulting image
    public function squareCrop($new_size = null)
    {
	// Calculate measurements
	if($this->getWidth() > $this->getHeight())
        {
            // For landscape images
            $x_offset = ($this->getWidth() - $this->getHeight()) / 2;
            $y_offset = 0;
            $square_size = $this->getWidth() - ($x_offset * 2);
	} else {
            // For portrait and square images
            $x_offset = 0;
            $y_offset = ($this->getHeight() - $this->getWidth()) / 2;
            $square_size = $this->getHeight() - ($y_offset * 2);
        }

	if($new_size == null) {
            $new_size = $square_size;
        }

	// Resize and crop
	$new = imagecreatetruecolor($new_size, $new_size);

	// Preserve alphatransparency in PNGs
	imagealphablending($new, false);
	imagesavealpha($new, true);

	imagecopyresampled($new, $this->image, 0, 0, $x_offset, $y_offset, $new_size, $new_size, $square_size, $square_size);
        
        $this->image = $new;
        
        return $this;
    }
    
    // scala l'img in base alla sua forma e taglia i bordi che fuoriescono
    public function adaptAndCut($width, $height)
    {
        // se area da ritagliare e area dell'img sono della stessa forma
        if(($width < $height && $this->getWidth() < $this->getHeight()) || ($width > $height && $this->getWidth() > $this->getHeight())) {
            (abs($this->getWidth()-$width) > abs($this->getHeight()-$height)) ? $this->resizeToHeight($height) : $this->resizeToWidth($width);
        }
        else {
            ($width < $height) ? $this->resizeToHeight($height) : $this->resizeToWidth($width);
        }
        
        if($this->getHeight() == $height && $this->getWidth() < $width)
        {
            $this->resizeToWidth($width);
            $y1 = ($this->getHeight()-$height)/2;
            $this->crop(0, $y1, $width, $y1+$height);
        }
        else if($this->getWidth() == $width && $this->getHeight() < $height)
        {
            $this->resizeToHeight($height);
            $x1 = ($this->getWidth()-$width)/2;
            $this->crop($x1, 0, $x1+$width, $height);
        }
        else
        {
            if($this->getHeight() == $height)
            {
                $x1 = ($this->getWidth()-$width)/2;
                $this->crop($x1, 0, $x1+$width, $height);
            }
            else
            {
                $y1 = ($this->getHeight()-$height)/2;
                $this->crop(0, $y1, $width, $y1+$height);
            }
        }
        
        return $this;
    }
    
    public function centre($width, $height)
    {
        $dest = imagecreatetruecolor($width, $height);

        $sfondo = imagecolorallocate($dest, 255, 255, 255);
        imagefill($dest, 0, 0, $sfondo);

        $src_w = $this->getWidth();
        $src_h = $this->getHeight();

        $dst_x = ($src_w < $width) ? ($width-$src_w) / 2 : 0;
        
        $dst_y = ($src_h < $height) ? ($height-$src_h) / 2 : 0;

        imagecopymerge($dest, $this->image, $dst_x, $dst_y, 0, 0, $src_w, $src_h, 100);

        $this->image = $dest;
        
        return $this;
    }

    // Overlay an image on top of another image with opacity; works with 24-big PNG alpha-transparency
    public function watermark($watermark_src, $position = 'center', $opacity = 50, $margin = 0)
    {
        $className = __CLASS__;
	$watermark = new $className($watermark_src);

        switch(strtolower($position))
        {
            case 'top-left':
            case 'left-top':
                $x = 0 + $margin;
		$y = 0 + $margin;
		break;
            case 'top-right':
            case 'right-top':
                $x = $this->getWidth() - $watermark->getWidth() - $margin;
		$y = 0 + $margin;
                break;
            case 'top':
            case 'top-center':
            case 'center-top':
                $x = ($this->getWidth() / 2) - ($watermark->getWidth() / 2);
		$y = 0 + $margin;
		break;
            case 'bottom-left':
            case 'left-bottom':
                $x = 0 + $margin;
                $y = $this->getHeight() - $watermark->getHeight() - $margin;
		break;
            case 'bottom-right':
            case 'right-bottom':
                $x = $this->getWidth() - $watermark->getWidth() - $margin;
		$y = $this->getHeight() - $watermark->getHeight() - $margin;
		break;
            case 'bottom':
            case 'bottom-center':
            case 'center-bottom':
                $x = ($this->getWidth() / 2) - ($watermark->getWidth() / 2);
		$y = $this->getHeight() - $watermark->getHeight() - $margin;
		break;
            case 'left':
            case 'center-left':
            case 'left-center':
                $x = 0 + $margin;
		$y = ($this->getHeight() / 2) - ($watermark->getHeight() / 2);
		break;
            case 'right':
            case 'center-right':
            case 'right-center':
                $x = $this->getWidth() - $watermark->getWidth() - $margin;
		$y = ($this->getHeight() / 2) - ($watermark->getHeight() / 2);
		break;
            case 'center':
            default:
                $x = ($this->getWidth() / 2) - ($watermark->getWidth() / 2);
		$y = ($this->getHeight() / 2) - ($watermark->getHeight() / 2);
		break;
        }

        $res = $this->imagecopymergeAlpha($this->getResource(), $watermark->getResource(), $x, $y, 0, 0, $watermark->getWidth(), $watermark->getHeight(), $opacity);
        
        $this->image = $res;
        
        return $this;
    }

    // Adds text on top of an image with optional shadow
    public function text($text, $font_file, $size = '12', $color = '#000000', $position = 'center', $margin = 0, $shadow_color = null, $shadow_offset_x, $shadow_offset_y)
    {
        // This method could be improved to support the text angle
	$angle = 0;

        $rgb = $this->hex2rgb($color);
	$color = imagecolorallocate($this->image, $rgb['r'], $rgb['g'], $rgb['b']);

        // Determine text size
	$box = imagettfbbox($size, $angle, $font_file, $text);

	// Horizontal
	$text_width = abs($box[6] - $box[2]);
	$text_height = abs($box[7] - $box[3]);


	switch(strtolower($position))
        {
            case 'top-left':
            case 'left-top':
                $x = 0 + $margin;
		$y = 0 + $size + $margin;
                break;
            case 'top-right':
            case 'right-top':
                $x = $this->getWidth() - $text_width - $margin;
		$y = 0 + $size + $margin;
		break;
            case 'top':
            case 'top-center':
            case 'center-top':
                $x = ($this->getWidth() / 2) - ($text_width / 2);
		$y = 0 + $size + $margin;
		break;
            case 'bottom-left':
            case 'left-bottom':
                $x = 0 + $margin;
		$y = $this->getHeight() - $text_height - $margin + $size;
		break;
            case 'bottom-right':
            case 'right-bottom':
                $x = $this->getWidth() - $text_width - $margin;
		$y = $this->getHeight() - $text_height - $margin + $size;
		break;
            case 'bottom':
            case 'bottom-center':
            case 'center-bottom':
                $x = ($this->getWidth() / 2) - ($text_width / 2);
		$y = $this->getHeight() - $text_height - $margin + $size;
		break;
            case 'left':
            case 'center-left':
            case 'left-center':
                $x = 0 + $margin;
		$y = ($this->getHeight() / 2) - (($text_height / 2) - $size);
		break;
            case 'right';
            case 'center-right':
            case 'right-center':
                $x = $this->getWidth() - $text_width - $margin;
		$y = ($this->getHeight() / 2) - (($text_height / 2) - $size);
		break;
            case 'center':
            default:
                $x = ($this->getWidth() / 2) - ($text_width / 2);
		$y = ($this->getHeight() / 2) - (($text_height / 2) - $size);
		break;
	}	

	if($shadow_color)
        {
            $rgb = $this->hex2rgb($shadow_color);
            $shadow_color = imagecolorallocate($this->image, $rgb['r'], $rgb['g'], $rgb['b']);
            imagettftext($this->image, $size, $angle, $x + $shdow_offset_x, $y + $shadow_offset_y, $shadow_color, $font_file, $text);
	}

	imagettftext($this->image, $size, $angle, $x, $y, $color, $font_file, $text);
        
        return $this;
    }
    
    public function roundedCorners($radius, $backgroundHexColor = '#ffffff')
    {
        $rgb = $this->hex2rgb($backgroundHexColor);
        
        $background = imagecolorallocate($this->image, $rgb['r'], $rgb['g'], $rgb['b']); //Colore dello sfondo
        
        //Prende larghezza e altezza dell'immagine
        $w = imagesx($this->image);
        $h = imagesy($this->image);
        
        for($x=0; $x<$radius; $x++)
        {
            for($y=0; $y<$radius; $y++)
            {
                if(sqrt(pow(($x-$radius),2) + pow($y-$radius,2)) > $radius) {
                    imagesetpixel($this->image, $x, $y, $background); //Colora lo sfondo
                }
            }
        }
        
        for($x=$w-$radius; $x<$w; $x++)
        {
            for($y=0; $y<$radius; $y++)
            {
                if(sqrt(pow(($x-($w-$radius)),2) + pow($y-$radius,2)) > $radius) {
                    imagesetpixel($this->image, $x, $y, $background);
                }
            }
        }
        
        for($x=$w-$radius; $x<$w; $x++)
        {
            for($y=$h-$radius; $y<$h; $y++)
            {
                if(sqrt(pow(($x-($w-$radius)),2)+pow($y-($h-$radius),2)) > $radius) {
                    imagesetpixel($this->image, $x, $y, $background);
                }
            }
        }
        
        for($x=0; $x<$radius; $x++)
        {
            for($y=$h-$radius; $y<$h; $y++)
            {
                if(sqrt(pow(($x-$radius),2) + pow($y-($h-$radius),2)) > $radius) {
                    imagesetpixel($this->image, $x, $y, $background);
                }
            }
        }
        
        return $this;
    }
    
    public function __toString()
    {
        switch($this->getType())
        {
            case 'image/gif':
                return imagegif($this->image);
		break;
            case 'image/jpeg':
                return imagejpeg($this->image);
		break;
            case 'image/png':
                return imagepng($this->image);
		break;
            default:
                return null;
        }
    }
}