<?php
namespace App\Utils;
class RandomUtil
{
    public static function randomString($prefix)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$string = '';
		$max = strlen($characters) - 1;
		for ($i = 0; $i < 11; $i++) {
			$string .= $characters[mt_rand(0, $max)];
		}
		$code = $prefix . $string;
		return $code;
    }
} 