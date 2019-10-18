<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Guestbook
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class PhocaguestbookHelperCaptcha
{
	function createImageData()
	{
		$rand_char 			 = PhocaguestbookHelperCaptcha::generateRandomChar(6);
		$rand_char_array 	 = array (			$rand_char[0]."          ",
										   "  ".$rand_char[1]."        "	,
										 "    ".$rand_char[2]."      "	,
									   "      ".$rand_char[3]."    "   ,
									 "        ".$rand_char[4]."  "	,
								   "          ".$rand_char[5]);

		$image_name 		= PhocaguestbookHelperCaptcha::getRandomImage();
		$image 				= @imagecreatefromjpeg($image_name);
		 
		foreach ($rand_char_array as $key => $value)
		{
			$font_color 	= PhocaguestbookHelperCaptcha::getRandomFontColor();
			$position_x 	= PhocaguestbookHelperCaptcha::getRandomPositionX();
			$position_y 	= PhocaguestbookHelperCaptcha::getRandomPositionY();
			$font_size 		= PhocaguestbookHelperCaptcha::getRandomFontSize();
			
			ImageString($image, $font_size, $position_x, $position_y, $value, ImageColorAllocate ($image, $font_color[0], $font_color[1], $font_color[2]));
		}

		$image_data['outcome'] 		= $rand_char;
		$image_data['image'] 		= $image;
		
		return $image_data;
	}
	
	function generateRandomChar($length=6)
	{	
	
		global $mainframe;
		$params	= &$mainframe->getParams();
		
		//$charGroup = strtoupper  ('a,b,c,d,e,f,g,h,j,k,m,n,p,q,r,s,t,u,v,w,x,y,z,2,3,4,5,6,7,8,9');
		$charGroup = 'O,X,K,U,L,T,U,R';
		/*if ($params->get( 'standard_captcha_chars' ) != '') {
			$charGroup = str_replace(" ", "", trim( $params->get( 'standard_captcha_chars' ) ));
		}*/
		
		$charGroup = explode( ',', $charGroup );
	
		
		srand ((double) microtime() * 1000000);
		
		$random_string = "";
		
		for($i=0;$i<$length;$i++)
		{
			$random_char_group = rand(0,sizeof($charGroup)-1);
			
			$random_string .= $charGroup[$random_char_group];
		}
		return $random_string;
	}

	function getRandomImage()
	{
		$rand = mt_rand(1,6);
		$image = '0'.$rand.'.jpg';
		$image = JPATH_ROOT.DS.'components'.DS.'com_phocaguestbook'.DS.'assets'.DS.'captcha'.DS . $image;
		return $image;
	}

	function getRandomPositionX()
	{
		$rand = mt_rand(2,3);
		return $rand;
	}

	function getRandomPositionY()
	{
		$rand = mt_rand(1,4);
		return $rand;
	}

	function getRandomFontSize()
	{
		$rand = mt_rand(4,5);
		return $rand;
	}

	function getRandomFontColor()
	{
		/*$rand = mt_rand(1,6);
		if ($rand == 1) {$font_color[0] = 0; $font_color[1] = 0; $font_color[2] = 0;}
		if ($rand == 2) {$font_color[0] = 0; $font_color[1] = 0; $font_color[2] = 153;}
		if ($rand == 3) {$font_color[0] = 0; $font_color[1] = 102; $font_color[2] = 0;}
		if ($rand == 4) {$font_color[0] = 102; $font_color[1] = 51; $font_color[2] = 0;}
		if ($rand == 5) {$font_color[0] = 163; $font_color[1] = 0; $font_color[2] = 0;}
		if ($rand == 6) {$font_color[0] = 0; $font_color[1] = 82; $font_color[2] = 163;}*/
		$rand = mt_rand(1,3);
		if ($rand == 1) {$font_color[0] = 255; $font_color[1] = 255; $font_color[2] = 255;}
		if ($rand == 2) {$font_color[0] = 205; $font_color[1] = 205; $font_color[2] = 205;}
		if ($rand == 3) {$font_color[0] = 245; $font_color[1] = 150; $font_color[2] = 0;}
		
		return $font_color;
	}
}


// ===============================================================================================================


class PhocaguestbookHelperCaptchaMath
{	
	function createImageItem($item)
	{
		switch ($item)
		{
			// 1 ---------------------------------------------------------------------
			case 1:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(10);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (	
"   ".$ch[0]." ",
"  ".$ch[1].$ch[2]." ",
" ".$ch[3]." ".$ch[4]." ",
$ch[5]."  ".$ch[6]." ",
"   ".$ch[7]." ",
"   ".$ch[8]." ",
"   ".$ch[9]." ",
);
			break;
			
			// 2 ---------------------------------------------------------------------
			case 2:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(14);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (	
" ".$ch[0].$ch[1].$ch[2]." ",
$ch[3]."   ".$ch[4],
"   ".$ch[5]." ",
"  ".$ch[6]."  ",
" ".$ch[7]."   ",
$ch[8]."    ",
$ch[9].$ch[10].$ch[11].$ch[12].$ch[13]
);
			break;
			
			// 3 ---------------------------------------------------------------------
			case 3:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(14);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (		
" ".$ch[0].$ch[1].$ch[2]." ",
$ch[3]."   ".$ch[4],
"    ".$ch[5],
"  ".$ch[6].$ch[7]." ",
"    ".$ch[8],
$ch[9]."   ".$ch[10],
" ".$ch[11].$ch[12].$ch[13]." "
);
			break;
			
			// 4 ---------------------------------------------------------------------
			case 4:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(12);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (		
"   ".$ch[0]." ",
"  ".$ch[1]."  ",
" ".$ch[2]."   ",
$ch[3]."  ".$ch[4]." ",
$ch[5].$ch[6].$ch[7].$ch[8].$ch[9],
"   ".$ch[10]." ",
"   ".$ch[11]." "
);
			break;
			
			// 5 ---------------------------------------------------------------------
			case 5:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(17);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (
$ch[0].$ch[1].$ch[2].$ch[3].$ch[4],
$ch[5]."    ",
$ch[6].$ch[7].$ch[8].$ch[9]." ",
"    ".$ch[10],
"    ".$ch[11],
$ch[12]."   ".$ch[13],
" ".$ch[14].$ch[15].$ch[16]." "
);
			break;
			
			// 6 ---------------------------------------------------------------------
			case 6:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(16);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (
" ".$ch[0].$ch[1].$ch[2]." ",
$ch[3]."    ",
$ch[4]."    ",
$ch[5].$ch[6].$ch[7].$ch[8]." ",
$ch[9]."   ".$ch[10],
$ch[11]."   ".$ch[12],
" ".$ch[13].$ch[14].$ch[15]." "
);
			break;
			
			// 7 ---------------------------------------------------------------------
			case 7:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(11);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (
			
$ch[0].$ch[1].$ch[2].$ch[3].$ch[4],
"    ".$ch[5],
"    ".$ch[6],
"   ".$ch[7]." ",
"  ".$ch[8]."  ",
" ".$ch[9]."   ",
$ch[10]."    "
);
			break;
			
			// 8 ---------------------------------------------------------------------
			case 8:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(17);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (
			
" ".$ch[0].$ch[1].$ch[2]." ",
$ch[3]."   ".$ch[4],
$ch[5]."   ".$ch[6],
" ".$ch[7].$ch[8].$ch[9]." ",
$ch[10]."   ".$ch[11],
$ch[12]."   ".$ch[13],
" ".$ch[14].$ch[15].$ch[16]." "
);
			break;
			
			// 9 ---------------------------------------------------------------------
			case 9:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(16);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (	
" ".$ch[0].$ch[1].$ch[2]." ",
$ch[3]."   ".$ch[4],
$ch[5]."   ".$ch[6],
" ".$ch[7].$ch[8].$ch[9].$ch[10],
"    ".$ch[11],
"    ".$ch[12],
" ".$ch[13].$ch[14].$ch[15]." "
);
			break;
			
			// 10 + ------------------------------------------------------------------
			case 10:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(9);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (	
"     ",
"  ".$ch[0]."  ",
"  ".$ch[1]."  ",
$ch[2].$ch[3].$ch[4].$ch[5].$ch[6],
"  ".$ch[7]."  ",
"  ".$ch[8]."  ",
"     "
);
			break;
			
			// 11 - ------------------------------------------------------------------
			case 11:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(5);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (	
"     ",
"     ",
"     ",
$ch[0].$ch[1].$ch[2].$ch[3].$ch[4],
"     ",
"     ",
"     "
);
			break;
			
			// 12 x ------------------------------------------------------------------
			case 12:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(9);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (
"     ",			
$ch[0]."   ".$ch[1],
" ".$ch[2]." ".$ch[3]." ",
"  ".$ch[4]."  ",
" ".$ch[5]." ".$ch[6]." ",
$ch[7]."   ".$ch[8],
"     "
);	
			break;
			
			// 13 : ------------------------------------------------------------------
			case 13:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(8);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (
"     ",			
"  ".$ch[0].$ch[1]." ",
"  ".$ch[2].$ch[3]." ",
"     ",
"     ",
"  ".$ch[4].$ch[5]." ",
"  ".$ch[6].$ch[7]." "
);	
			break;
			
			// 15 = ------------------------------------------------------------------
			case 15:
			$randChar['char'] 	= PhocaguestbookHelperCaptchaMath::generateRandomChar(10);
			$ch					= $randChar['char'];
			$randChar['array'] 	= array (
			
"     ",
"     ",
$ch[0].$ch[1].$ch[2].$ch[3].$ch[4],
"     ",
$ch[5].$ch[6].$ch[7].$ch[8].$ch[9],
"     ",
"     "
);
			break;
		}
		return $randChar;
	}


	function createImageData()
	{
		$image_name 		= PhocaguestbookHelperCaptchaMath::getRandomImage();
		$image 				= @imagecreatefromjpeg($image_name);
			
		$math = PhocaguestbookHelperCaptchaMath::getMath();

		$items = array( 0 => $math['first'], 1 => $math['operation'], 2 => $math['second'], 3 => 15);
		
		$x = 12;//Position X (first)
		for ($i=0;$i<4;$i++)
		{		
			$randChar = PhocaguestbookHelperCaptchaMath::createImageItem($items[$i]);
			// Position Y (first) ---
			if ($i == 1) {
				$y = 18;
			} else {
				$y = 7;
			}
			// -----------------------
			foreach ($randChar['array'] as $key => $value)
			{
				$font_color 	= PhocaguestbookHelperCaptchaMath::getRandomFontColor();
				
				if ($i == 1 || $i == 3) {
					$font_size 	= 2;
				} else {
					$font_size	= 5;
				}
				
				$position_x 	= $x;
				$position_y		= $y;
				
				ImageString($image, $font_size, $position_x, $position_y, $value, ImageColorAllocate ($image, $font_color[0], $font_color[1], $font_color[2]));
				if ($i == 1) {
					$y = $y + 7;
				} else {
					$y = $y + 11;
				}
			}
			if ($i == 0 || $i == 2) {
				$x = $x + 70;
			} else {
				$x = $x + 50;
			}
		}
		// Here is not the rand char but the matematical outcome
		$image_data['outcome'] 		= $math['outcome'];
		$image_data['image'] 		= $image;
		
		return $image_data;
	}
	
	function generateRandomChar($length=6)
	{	
	
		global $mainframe;
		$params	= &$mainframe->getParams();
		
		$charGroup = 'a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z';
		if ($params->get( 'math_captcha_chars' ) != '') {
			$charGroup = str_replace(" ", "", trim( $params->get( 'math_captcha_chars' ) ));
		}
		$charGroup = explode( ',', $charGroup );
	
		
		srand ((double) microtime() * 1000000);
		
		$random_string = "";
		
		for($i=0;$i<$length;$i++)
		{
			$random_char_group = rand(0,sizeof($charGroup)-1);
			
			$random_string .= $charGroup[$random_char_group];
		}
		return $random_string;
	}

	function getRandomImage()
	{
		$rand = mt_rand(7,9);
		$image = '0'.$rand.'.jpg';
		$image = JPATH_ROOT.DS.'components'.DS.'com_phocaguestbook'.DS.'assets'.DS.'captcha'.DS . $image;
		return $image;
	}


	function getRandomFontColor()
	{
		$rand = mt_rand(1,6);
		if ($rand == 1) {$font_color[0] = 0; $font_color[1] = 0; $font_color[2] = 0;}
		if ($rand == 2) {$font_color[0] = 0; $font_color[1] = 0; $font_color[2] = 153;}
		if ($rand == 3) {$font_color[0] = 0; $font_color[1] = 102; $font_color[2] = 0;}
		if ($rand == 4) {$font_color[0] = 102; $font_color[1] = 51; $font_color[2] = 0;}
		if ($rand == 5) {$font_color[0] = 163; $font_color[1] = 0; $font_color[2] = 0;}
		if ($rand == 6) {$font_color[0] = 0; $font_color[1] = 82; $font_color[2] = 163;}
		return $font_color;
	}
	
	function getMath()
	{
		$math['first'] 		= mt_rand(1,9);
		$math['second']		= mt_rand(1,9);
		$math['operation']	= mt_rand(10,13);

		switch ($math['operation'])
		{
			case 10;
				$math['outcome']	=  (int)$math['first'] + (int)$math['second'];
			break;
			
			case 11;
				if ((int)$math['first'] < (int)$math['second']) {
					$prevFirst		= $math['first'];
					$math['first'] 	= $math['second'];
					$math['second'] = $prevFirst;
				}
				
				$outcome = (int)$math['first'] - (int)$math['second'];
				if ($outcome == 0) {
					$math['second'] = $math['second'] - 1;
				}
				$math['outcome']	=  (int)$math['first'] - (int)$math['second'];
			break;
			
			case 12;
				$math['outcome']	=  (int)$math['first'] * (int)$math['second'];
			break;
			
			case 13;
				switch ($math['first'])
				{
					case 9:
						$second	= array(1,3,9,9);
					break;
					case 8:
						$second	= array(1,2,4,8);
					break;
					case 7:
						$second	= array(1,7,7,7);
					break;
					case 6:
						$second	= array(1,2,3,6);
					break;
					case 5:
						$second	= array(1,5,5,5);
					break;
					case 4:
						$second	= array(1,2,4,4);
					break;
					case 3:
						$second	= array(1,3,3,3);
					break;
					case 2:
						$second	= array(1,2,2,2);
					break;
					case 1:
					default:
						$second	= array(1,1,1,1);
					break;
				}
				$randSecond = mt_rand(0,3);
				$math['outcome']	= (int)$math['first'] / (int)$second[$randSecond];
				$math['second']		= (int)$second[$randSecond];// We must define the second new
			break;
		}
		return $math;
	}
}
?>