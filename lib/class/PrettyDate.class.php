<?php

/*
 * JavaScript Pretty Date
 * Copyright (c) 2008 John Resig (jquery.com)
 * Licensed under the MIT license.
 */

// Ported to PHP >= 5.1 by Zach Leatherman (zachleat.com)
// Slight modification denoted below to handle months and years.
// Modified by @Kurtextrem.
class Date_Difference
{

	public static function getStringResolved($date, $compareTo = NULL)
	{
		if (!is_null($compareTo)) {
			$compareTo = new DateTime($compareTo);
		}
		return self::getString(new DateTime($date), $compareTo);
	}

	public static function getString(DateTime $date, DateTime $compareTo = NULL)
	{
		global $lang_date_just_now, $lang_date_yesterday, $lang_date_one_minute_ago, $lang_date_one_houre_ago, $lang_date_one_day_ago, $lang_date_one_week_ago, $lang_date_one_month_ago, $lang_date_one_year_ago, $lang_date_minutes_ago, $lang_date_hours_ago, $lang_date_days_ago, $lang_date_weeks_ago, $lang_date_years_ago, $lang_date_months_ago; # IMBA! xD

		if (is_null($compareTo)) {
			$compareTo = new DateTime('now');
		}
		$diff = $compareTo->format('U') - $date->format('U');
		$dayDiff = floor($diff / 86400);

		if (is_nan($dayDiff) || $dayDiff < 0) {
			return '';
		}

		if ($dayDiff == 0) {
			if ($diff < 60) {
				return $lang_date_just_now;
			} elseif ($diff < 120) {
				return $lang_date_one_minute_ago;
			} elseif ($diff < 3600) {
				return sprintf($lang_date_minutes_ago, floor($diff / 60));
			} elseif ($diff < 7200) {
				return $lang_date_one_houre_ago;
			} elseif ($diff < 86400) {
				return sprintf($lang_date_hours_ago, floor($diff / 3600));
			}
		} elseif ($dayDiff == 1) {
			return $lang_date_yesterday;
		} elseif ($dayDiff < 7) {
			return sprintf($lang_date_days_ago, $dayDiff);
		} elseif ($dayDiff == 7) {
			return $lang_date_one_day_ago;
		} elseif ($dayDiff < (7 * 6)) { // Modifications Start Here
			// 6 weeks at most
			$weeks = ceil($dayDiff / 7);
			$text = $lang_date_one_week_ago;
			if ($weeks != 1)
				$text = $lang_date_weeks_ago;
			return sprintf($text, $weeks);
		} elseif ($dayDiff < 365) {
			$months = ceil($dayDiff / (365 / 12));
			$text = $lang_date_one_month_ago;
			if ($months != 1)
				$text = $lang_date_months_ago;
			return sprintf($text, ceil($dayDiff / (365 / 12)));
		} else {
			$years = round($dayDiff / 365);
			$text = $lang_date_one_year_ago;
			if ($years != 1)
				$text = $lang_date_years_ago;
			return sprintf($text, $years);
		}
	}

}

?>