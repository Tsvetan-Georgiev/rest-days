<?php
function restDaysYear($getyear){
	$hdays[$getyear."-01-01"] = "Нова година";
	$arr_weekds = array();
		function velikDen($G,$petilipon){
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
					$Razppetak="0".$RC."-"."0".$m."-".$G;
				// записва датата в секунди по линукският формат
					$Razppetak=strtotime($Razppetak);
				// добавя или изважда дни според втория зададен параметър на функцията
					$Razppetak2=strtotime(date("d-m-Y",$Razppetak)."-"."$petilipon"." day");
				// превръща датата във формат МесецДен-Година за да е по-лесно да бъде сортиран с останалите дати
					$Razppetak2=date("Y-m-d",$Razppetak2);
				}
				else{
					$Razppetak=$RC."-"."0".$m."-".$G;
					$Razppetak=strtotime($Razppetak);
					$Razppetak2=strtotime(date("d-m-Y",$Razppetak)."-"."$petilipon"." day");
					$Razppetak2=date("Y-m-d",$Razppetak2);
				}
			return $Razppetak2;
		}
		/**
		 *  Функция връщаща броя на дните във февруари
		 *
		 *  @param int $G Година
		 *
		 *	@return int Брой дни
		 */
		function getDaysFev($G){
			$RC= date("L",strtotime("$G"."-02-02"));
			if ($RC==1) {
				$RC=29;
				return $RC;
			} else {
				$RC=28;
				return $RC;
			}
		}
		// високосна ли е годината
		function leap_year(){
			global $getyear;
			if(getDaysFev($getyear)==29){
				return 266;
			}
			else{
				return 265;
			}
		}
		//Намиране на съботите и неделите в дадена година
		function getWeekendsOfYear($year){
			$now ="01.01.".$year;
			$end_date ="01.01.".($year+1);
			$now = strtotime($now);
			$end_date = strtotime($end_date);
			global $hdays;
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
		// функция за сливане на почивните дни през година с празниците, които не се включват в тях, а са през седмицата
		function allinone($getyear){
			global $hdays,$getyear;
			$all_rest_days=0;
			$leap_year=leap_year();
			ksort($hdays);
		}
		getWeekendsOfYear($getyear);
		$fevruari_days=getDaysFev($getyear);
		//$Razppetak=velikDen($getyear,"2");
		//$Ponedlk=velikDen($getyear,"-1");
		$hdays[velikDen($getyear,"2")] = "Великден";
		$hdays[velikDen($getyear,"1")] = "Великден";
		$hdays[velikDen($getyear,"0")] = "Великден";
		$hdays[velikDen($getyear,"-1")] = "Великден";
		$hdays[$getyear."-03-03"] = "Национален празник";
		$hdays[$getyear."-05-01"] = "Ден на труда";
		$hdays[$getyear."-05-06"] = "Гергьовден";
		$hdays[$getyear."-05-24"] = "Ден на писмеността";
		$hdays[$getyear."-09-06"] = "Ден на съединението";
		$hdays[$getyear."-09-22"] = "Независимостта на България";
		$hdays[$getyear."-12-24"] = "Коледа";
		$hdays[$getyear."-12-25"] = "Коледа";
		$hdays[$getyear."-12-26"] = "Коледа";
		foreach ($hdays as $key => $value) {
			$za_bazata=strtotime($key);
			$hdaysUnix[$za_bazata] = $value;
		}
	return $hdaysUnix;
}
?>
