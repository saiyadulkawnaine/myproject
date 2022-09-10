<?php
namespace App\Library;
class Numbertowords {

	// 2st modified Function 
    // From $words array remove zero from 0 index.;
    // Add Line 52-55;

	public static function ntow($number,$currency='USD', $hundred_name='cents'){
		$number = $number;
		$number=explode(".",$number);
        $no = round($number[0]);
        $point = round($number[1]);
        $hundred = null;
        $digits_1 = strlen($no);
		$i = 0;
		$str = array();
		$words = array('0' => '', '1' => 'one', '2' => 'two',
		'3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
		'7' => 'seven', '8' => 'eight', '9' => 'nine',
		'10' => 'ten', '11' => 'eleven', '12' => 'twelve',
		'13' => 'thirteen', '14' => 'fourteen',
		'15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
		'18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
		'30' => 'thirty', '40' => 'forty', '50' => 'fifty',
		'60' => 'sixty', '70' => 'seventy',
		'80' => 'eighty', '90' => 'ninety');
		$digits = array('', 'hundred', 'thousand', 'lac', 'crore','hundred');
		while ($i < $digits_1) {
			$divider = ($i == 2) ? 10 : 100;
			$number = floor($no % $divider);
			$no = floor($no / $divider);
			$i += ($divider == 10) ? 1 : 2;
			if ($number) {
				$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
				$hundred = ($counter == 1 && $str[0]) ? '  ' : null;//and
				$str [] = ($number < 21) ? $words[$number] .
				" " . $digits[$counter] . $plural . " " . $hundred
				:
				$words[floor($number / 10) * 10]
				. " " . $words[$number % 10] . " "
				. $digits[$counter] . $plural . " " . $hundred;
			} else $str[] = null;
		}
		$str = array_reverse($str);
		$result = implode('', $str);
		$pointIt=$point / 10;
		$pointMo=$point % 10;
		$points = ($point) ?
		" " . $words[$pointIt] . " " . 
		$words[$pointMo] : '';
		$zeroinpoint=substr($point,-1);
		if($zeroinpoint==0){
			$points.=" zero";
		}
		$out=$result. " " . $currency;
		if($points){
			$out.=" and " . $points . " " . $hundred_name;
		}
		return ucfirst($out);
    }

	// 1st modified Function 
    // in $words array adding zero in 0 index; 
    //Drawback: It make problem in big Number like 99999999999999990.20;
	/*public static function ntow($number,$currency='USD', $hundred_name='cents'){
		$number = $number;
		$number=explode(".",$number);
        $no = round($number[0]);
        $point = round($number[1]);
        $hundred = null;
        $digits_1 = strlen($no);
		$i = 0;
		$str = array();
		$words = array('0' => 'zero', '1' => 'one', '2' => 'two',
		'3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
		'7' => 'seven', '8' => 'eight', '9' => 'nine',
		'10' => 'ten', '11' => 'eleven', '12' => 'twelve',
		'13' => 'thirteen', '14' => 'fourteen',
		'15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
		'18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
		'30' => 'thirty', '40' => 'forty', '50' => 'fifty',
		'60' => 'sixty', '70' => 'seventy',
		'80' => 'eighty', '90' => 'ninety');
		$digits = array('', 'hundred', 'thousand', 'lac', 'crore');
		while ($i < $digits_1) {
			$divider = ($i == 2) ? 10 : 100;
			$number = floor($no % $divider);
			$no = floor($no / $divider);
			$i += ($divider == 10) ? 1 : 2;
			if ($number) {
				$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
				$hundred = ($counter == 1 && $str[0]) ? '  ' : null;//and
				$str [] = ($number < 21) ? $words[$number] .
				" " . $digits[$counter] . $plural . " " . $hundred
				:
				$words[floor($number / 10) * 10]
				. " " . $words[$number % 10] . " "
				. $digits[$counter] . $plural . " " . $hundred;
			} else $str[] = null;
		}
		$str = array_reverse($str);
		$result = implode('', $str);
		$pointIt=$point / 10;
		$pointMo=$point % 10;
		$points = ($point) ?
		" " . $words[$pointIt] . " " . 
		$words[$pointMo] : '';
		$out=$result. " " . $currency;
		if($points){
			$out.=" and " . $points . " " . $hundred_name;
		}
		return ucfirst($out);
    }*/
	
    // Original Function do not delete it 
    //Drawback: do not show zero after point 2,903.20;
	/*public static function ntow($number,$currency='USD', $hundred_name='cents'){
		$number = $number;
		$number=explode(".",$number);
        $no = round($number[0]);
        $point = round($number[1]);
        $hundred = null;
        $digits_1 = strlen($no);
		$i = 0;
		$str = array();
		$words = array('0' => '', '1' => 'one', '2' => 'two',
		'3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
		'7' => 'seven', '8' => 'eight', '9' => 'nine',
		'10' => 'ten', '11' => 'eleven', '12' => 'twelve',
		'13' => 'thirteen', '14' => 'fourteen',
		'15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
		'18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
		'30' => 'thirty', '40' => 'forty', '50' => 'fifty',
		'60' => 'sixty', '70' => 'seventy',
		'80' => 'eighty', '90' => 'ninety');
		$digits = array('', 'hundred', 'thousand', 'lac', 'crore');
		while ($i < $digits_1) {
			$divider = ($i == 2) ? 10 : 100;
			$number = floor($no % $divider);
			$no = floor($no / $divider);
			$i += ($divider == 10) ? 1 : 2;
			if ($number) {
				$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
				$hundred = ($counter == 1 && $str[0]) ? '  ' : null;//and
				$str [] = ($number < 21) ? $words[$number] .
				" " . $digits[$counter] . $plural . " " . $hundred
				:
				$words[floor($number / 10) * 10]
				. " " . $words[$number % 10] . " "
				. $digits[$counter] . $plural . " " . $hundred;
			} else $str[] = null;
		}
		$str = array_reverse($str);
		$result = implode('', $str);
		$pointIt=$point / 10;
		$pointMo=$point % 10;
		$points = ($point) ?
		" " . $words[$pointIt] . " " . 
		$words[$pointMo] : '';
		$out=$result. " " . $currency;
		if($points){
			$out.=" and " . $points . " " . $hundred_name;
		}
		return ucfirst($out);
    }*/
}