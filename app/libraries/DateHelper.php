<?php
namespace App\libraries;



class DateHelper
{

	public static function dateFormat()
	{
		return 'd/m/Y';
	}

	public static function dateTimeFormat()
	{
		return 'd/m/Y H:i';
	}

	public static function dateFormatDB()
	{
		return 'Y-m-d';
	}

	public static function timeFormatDB()
	{
		return 'H:i';
	}

	public static function dateTimeFormatDB()
	{
		return 'Y-m-d H:i';
	}

	public static function currentDate()
	{
		return date('Y-m-d');
	}

	public static function currentDateTime()
	{
		return date('Y-m-d H:i');
	}

	public static function validateDate($date)
	{
			$format1 = 'j-m-Y';
			$format2 = 'd-m-Y';
			$format3 = 'j-n-Y';
			$format4 = 'd-n-Y';

			$date=  str_replace('/','-',$date);

			$d = DateTime::createFromFormat($format1, $date);
			$r = $d && $d->format($format1) == $date;
			if ($r !== false)
			{return $d->format( self::dateFormat() );}

			$d = DateTime::createFromFormat($format2, $date);
			$r = $d && $d->format($format2) == $date;
			if ($r !== false)
			{return $d->format( self::dateFormat() );}

			$d = DateTime::createFromFormat($format3, $date);
			$r = $d && $d->format($format3) == $date;
			if ($r !== false)
			{return $d->format( self::dateFormat() );}

			$d = DateTime::createFromFormat($format4, $date);
			$r = $d && $d->format($format4) == $date;
			if ($r !== false)
			{return $d->format( self::dateFormat() );}

			return  false;

	}

	public static function validateDateTime($dateTime)
	{
			$format1 = 'Y-m-j H:i';
			$format2 = 'Y-m-d H:i';
			$format3 = 'Y-n-j H:i';
			$format4 = 'Y-n-d H:i';
			$format5 = 'Y-d-m H:i';

			// $dateTime=  str_replace('/','-',$dateTime);

			$d = DateTime::createFromFormat($format1, $dateTime);
			$r = $d && $d->format($format1) == $dateTime;
			if ($r !== false)
			{return $d->format( self::dateTimeFormatDB() );}

			$d = DateTime::createFromFormat($format2, $dateTime);
			$r = $d && $d->format($format2) == $dateTime;
			if ($r !== false)
			{return $d->format( self::dateTimeFormatDB() );}

			$d = DateTime::createFromFormat($format3, $dateTime);
			$r = $d && $d->format($format3) == $dateTime;
			if ($r !== false)
			{return $d->format( self::dateTimeFormatDB() );}

			$d = DateTime::createFromFormat($format4, $dateTime);
			$r = $d && $d->format($format4) == $dateTime;
			if ($r !== false)
			{return $d->format( self::dateTimeFormatDB() );}

			$d = DateTime::createFromFormat($format5, $dateTime);
			$r = $d && $d->format($format5) == $dateTime;
			if ($r !== false)
			{return $d->format( self::dateTimeFormatDB() );}

			return  false;

	}

	public static function DateToDb($date)
	{
		return date(self::dateFormatDB(), strtotime(str_replace('/','-', $date ) ));
	}

	public static function DateTimeToDb($dateTime)
	{
		return date(self::dateTimeFormatDB(), strtotime($dateTime));
	}




	public static function DateToShow($date)
	{
	    return date( self::dateFormat() , strtotime($date));
	}

	public static function dateAdd($date,$days)
	{
			$date = DateTime::createFromFormat(self::dateFormatDB(), $date);
			date_add($date,date_interval_create_from_date_string ($days.' days'));
			return date_format($date, self::dateFormatDB());
	}

	public static function dateDiff($date1, $date2)
	{
			return date_diff(date_create($date1), date_create($date2))->days;
	}

	public static function isDateBeforeToday($date, $field, $orEqual = false)
	{
			if ($orEqual) {
				if ( $date >= static::currentDate() ) {
					throw ValidationException::withMessages([ $field => __( 'messages.error_date_before_today' ) ]);
				}
			} else {
				if ( $date > static::currentDate() ) {
					throw ValidationException::withMessages([ $field => __( 'messages.error_date_before_today' ) ]);
				}
			}

	}




	//-------------- hijry
	public function get_date(){
			return $this->hijri[1] . ' ' . $this->get_month_name($this->hijri[0]) . ' ' . $this->hijri[2] . 'H';
	}

	public function get_day(){
			return $this->hijri[1];
	}

	public function get_month(){
			return $this->hijri[0];
	}

	public function get_year(){
			return $this->hijri[2];
	}

	public function get_month_name($i){
			static $month  = array(
					"muharram", "safar", "rabiulawal", "rabiulakhir",
					"jamadilawal", "jamadilakhir", "rejab", "syaaban",
					"ramadhan", "syawal", "zulkaedah", "zulhijjah"
			);
			return $month[$i-1];
	}

	public static function GregorianToHijri($time = null){
			if ($time === null) $time = time();
			$m = date('m', $time);
			$d = date('d', $time);
			$y = date('Y', $time);

			return static::JDToHijri(cal_to_jd(CAL_GREGORIAN, $m, $d, $y));
	}

	public static function DateToShowHijri( $hijriDate = [] )
	{
		if (empty($hijriDate)) {return '';}
		return $hijriDate[2].'/'.$hijriDate[0].'/'.$hijriDate[1];
	}

	public static function HijriToGregorian($m, $d, $y){
			return jd_to_cal(CAL_GREGORIAN, $this->HijriToJD($m, $d, $y));
	}

	# Julian Day Count To Hijri
	private static function JDToHijri($jd){
			$jd = $jd - 1948440 + 10632;
			$n  = (int)(($jd - 1) / 10631);
			$jd = $jd - 10631 * $n + 354;
			$j  = ((int)((10985 - $jd) / 5316)) *
					((int)(50 * $jd / 17719)) +
					((int)($jd / 5670)) *
					((int)(43 * $jd / 15238));
			$jd = $jd - ((int)((30 - $j) / 15)) *
					((int)((17719 * $j) / 50)) -
					((int)($j / 16)) *
					((int)((15238 * $j) / 43)) + 29;
			$m  = (int)(24 * $jd / 709);
			$d  = $jd - (int)(709 * $m / 24);
			$y  = 30*$n + $j - 30;

			return array($m, $d, $y);
	}

	# Hijri To Julian Day Count
	private function HijriToJD($m, $d, $y){
			return (int)((11 * $y + 3) / 30) +
					354 * $y + 30 * $m -
					(int)(($m - 1) / 2) + $d + 1948440 - 385;
	}
	//-------------- End hijry



}
