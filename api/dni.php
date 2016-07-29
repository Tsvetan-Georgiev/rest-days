<?php
/**
*	Клас съдържащ функции за откриване на почивни дни
*/
class RestDays {

	/**
	*	Високосна ли е годината
	*
	*	@param int $year Година
	*	@return bool Дали е високосна година
	*/
	private static function isLeapYear($year)
	{
		return ((($year % 4) == 0) && ((($year % 100) != 0) || (($year %400) == 0)));
	}

	/**
	*	Намиране на съботите и неделите в дадена година
	*
	*	@param int $year Година
	*	@param array &$hdays Масив с почивни дни
	*	@return void
	*/
	private static function getWeekendsOfYear($year, &$hdays){
		$now ="01.01.".$year;
		$end_date ="01.01.".($year+1);
		$now = strtotime($now);
		$end_date = strtotime($end_date);
		while (date("Y-m-d", $now) != date("Y-m-d", $end_date)) {
		    $day_index = date("w", $now);
		    //проверка дали е събота или неделя
		    if ($day_index == 0 || $day_index == 6) {
		    	if ($day_index == 0) {
		    		$forpush = date("Y-m-d", $now);
		    		$hdays[$forpush] = "Неделя";
		    	}
		    	else{
		    		$forpush = date("Y-m-d", $now);
		    		$hdays[$forpush] = "Събота";
		    	}
		    }
		    $now = strtotime(date("Y-m-d", $now) . "+1 day");
		}
		return $hdays;
	}

	/**
	*	Откриване на дните, когато е Великден
	*
	*	@param int $G Година
	*	@param int $petilipon +/-дни от великден
	*	@return string Датата на великден +/-$petilipon
	*/
	private static function velikDen($G, $petilipon) {
		$m = 0;
		$R1 = $G % 19;
		$R2 = $G % 4;
		$R3 = $G % 7;
		$RA = 19*$R1 + 16;
		$R4 = $RA % 30;
		$RB = 2*$R2 + 4*$R3 + 6*$R4;
		$R5 = $RB % 7;
		$RC = $R4 + $R5;

		if ($RC<28){
			$RC=$RC+3;
			$m=4;
		}
		else if($RC>=28){
			$RC=$RC-27;
		    $m=5;
		}

		// ако великден е май или денят е преди 10-ти слага нула на деня
		if ($m==5 or $RC<10) {
			// стринг от точната дата на великден
			$razpPetak="0".$RC."-"."0".$m."-".$G;
			// записва датата в секунди по линукският формат
			$razpPetak=strtotime($razpPetak);
			// добавя или изважда дни според втория зададен параметър на функцията
			$razpPetak2=strtotime(date("d-m-Y",$razpPetak)."-"."$petilipon"." day");
			// превръща датата във формат МесецДен-Година за да е по-лесно да бъде сортиран с останалите дати
			$razpPetak2=date("Y-m-d",$razpPetak2);
		}
		else{
			$razpPetak=$RC."-"."0".$m."-".$G;
			$razpPetak=strtotime($razpPetak);
			$razpPetak2=strtotime(date("d-m-Y",$razpPetak)."-"."$petilipon"." day");
			$razpPetak2=date("Y-m-d",$razpPetak2);
		}

		return $razpPetak2;
	}

	/**
	*	Откриване на всички почивни дни в дадена година
	*
	*	@param int $year
	*	@return array Списък с почивни дни
	*/
	public static function get($year) {
		$hdays[$year."-01-01"] = "Нова година";
		self::getWeekendsOfYear($year, $hdays);
		$hdays[self::velikDen($year,2)] = "Великден";
		$hdays[self::velikDen($year,1)] = "Великден";
		$hdays[self::velikDen($year,0)] = "Великден";
		$hdays[self::velikDen($year,-1)] = "Великден";
		$hdays[$year."-03-03"] = "Национален празник";
		$hdays[$year."-05-01"] = "Ден на труда";
		$hdays[$year."-05-06"] = "Гергьовден";
		$hdays[$year."-05-24"] = "Ден на писмеността";
		$hdays[$year."-09-06"] = "Ден на съединението";
		$hdays[$year."-09-22"] = "Независимостта на България";
		$hdays[$year."-12-24"] = "Коледа";
		$hdays[$year."-12-25"] = "Коледа";
		$hdays[$year."-12-26"] = "Коледа";
		// сортиране по дати
		ksort($hdays);
		foreach ($hdays as $key => $value) {
			$za_bazata=strtotime($key);
			$hdaysUnix[$za_bazata] = $value;
		}

		return $hdaysUnix;
	}
}

/**
*	Връща масив с всички почивни дни
*
*	@param int $year Година
*	@return array Списък с почивните дни
*/
function restDaysYear($year){
	return RestDays::get($year);
}
?>
