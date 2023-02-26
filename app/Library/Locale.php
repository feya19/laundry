<?php

namespace App\Library;

class Locale {
    
    public static function numberFormat($number, $decimal = null, $decimalSepartor = null , $thousandSeparator = null) {
        if (!$decimal) {
            $decimal = env('locale.decimal', 2);
        }
        
        if (!$decimalSepartor) {
            $decimalSepartor = env('locale.decimalSeparator', ',');
        }
        
        if (!$thousandSeparator) {
            $thousandSeparator = env('locale.thousandSeparator', '.');
        }
        
		$number = self::numberValue($number);
        if (is_numeric($number)) {
            $parse = explode('.', $number);
	        if (is_null($decimal)) {
	            if (isset($parse[1])) {
		            $decimal = strlen($parse[1]);
	            }
            }
            $result = number_format($number, $decimal, $decimalSepartor, $thousandSeparator);
            return $result;
        } else {
            return 0;
        }
    }
    
    public static function numberValue($number, $decimal = null, $decimalSepartor = null , $thousandSeparator = null) {
        if (!$decimal) {
            $decimal = env('locale.decimal', 2);
        }
        if (!$decimalSepartor) {
            $decimalSepartor = env('locale.decimalSeparator', ',');
        }
        if (!$thousandSeparator) {
            $thousandSeparator = env('locale.thousandSeparator', '.');
        }
		if (!is_numeric($number)) {
            $parse = explode($decimalSepartor, $number);
            $result = str_replace($thousandSeparator, "", $parse[0]);
            if (is_numeric($result)) {
                if (isset($parse[1])) {
                    $result .= '.' . $parse[1];
                }
                return $result;
            } else {
                return 0;
            }
        } else {
            return $number;
        }
    }

	public static function humanDate($timestamp, $format = null) {
		$timestamp = strtotime($timestamp);
		if ($time = $timestamp) {
			$y = date('Y', $time);
			$m = self::listMonth('month_' . date('m', $time));
			$d = date('d', $time);
			if (!$format) {
				$humanDate = '{d} {m} {Y}';
			} else {
				$humanDate = $format;
			}
			$humanDate = str_replace(array('{Y}', '{m}', '{d}'), array($y, $m, $d), $humanDate);
			return $humanDate;
		} else {
			return null;
		}
	}

	public static function humanDateTime($timestamp, $format = null) {
		$timestamp = strtotime($timestamp);
		if ($time = $timestamp) {
			$y = date('Y', $time);
			$m = self::listMonth('month_' . date('m', $time));
			$d = date('d', $time);
			$H = date('H', $time);
			$i = date('i', $time);
			$s = date('s', $time);
			if (!$format) {
				$humanDate = '{d} {m} {Y} {H}:{i}';
			} else {
				$humanDate = $format;
			}
			$humanDate = str_replace(array('{Y}', '{m}', '{d}', '{H}', '{i}', '{s}'), array($y, $m, $d, $H, $i, $s), $humanDate);
			return $humanDate;
		} else {
			return null;
		}
	}

	public static function humanDateDisplay($timestamp){
		$humanDate = self::humanDate($timestamp);
		$day = date('l', strtotime($timestamp));
		return self::listDay($day).', '.$humanDate;
	}

	public static function boolean($boolean, $true = null, $false = null) {
		if ($boolean) {
			if (!$true) {
				$true = '<i class="fa fa-check text-success"></i>';
			}
			return $true;
		} else {
			if (!$false) {
				$false = '<i class="fa fa-times text-danger"></i>';
			}
			return $false;
		}
	}

	public static function listDay($day = null) {
		$data = [
			'Sunday' => 'Minggu',
			'Monday' => 'Senin',
			'Tuesday' => 'Selasa',
			'Wednesday' => 'Rabu',
			'Thursday' => 'Kamis',
			'Friday' => 'Jumat',
			'Saturday' => 'Sabtu'
		];
		if ($day) {
			return $data[$day];
		} else {
			return $data;
		}
	}

	public static function listMonth($month = null) {
		$data = [
			'month_01' => 'Januari',
			'month_02' => 'Februari',
			'month_03' => 'Maret',
			'month_04' => 'April',
			'month_05' => 'Mei',
			'month_06' => 'Juni',
			'month_07' => 'Juli',
			'month_08' => 'Agustus',
			'month_09' => 'September',
			'month_10' => 'Oktober',
			'month_11' => 'November',
			'month_12' => 'Desember',
		];
		if ($month) {
			return $data[$month];
		} else {
			return $data;
		}
	}

	public static function month($month = null) {
		$data = [
			'01' => 'Januari',
			'02' => 'Februari',
			'03' => 'Maret',
			'04' => 'April',
			'05' => 'Mei',
			'06' => 'Juni',
			'07' => 'Juli',
			'08' => 'Agustus',
			'09' => 'September',
			'10' => 'Oktober',
			'11' => 'November',
			'12' => 'Desember',
		];
		if ($month) {
			return $data[$month];
		} else {
			return $data;
		}
	}

	public static function listYear($year = null) {
		$data = [
			'2015' => '2015',
			'2016' => '2016',
			'2017' => '2017',
			'2018' => '2018',
			'2019' => '2019',
			'2020' => '2020',
			'2021' => '2021',
			'2022' => '2022',
			'2023' => '2023',
			'2024' => '2024',
			'2025' => '2025',
		];
		if ($year) {
			return $data[$year];
		} else {
			return $data;
		}
	}
}