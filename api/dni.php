<?php

require_once 'routines.php';

/**
*	Клас съдържащ функции за откриване на почивни дни
*/
class RestDays {

    private $year;
    private $hdays = [];
    private $onlySetText = false;

    function __construct($year, $hdays = null, $onlySetText = false) {
        $this->year = $year;
        if (isset($hdays)) {
            $this->hdays = $hdays;
        } else {
            $this->hdays = [];
        }
        $this->onlySetText = $onlySetText;
    }

    private function setDay($day, $text) {
        if (!$this->onlySetText
            || isset($this->hdays[$day])) {

            $this->hdays[$day] = $text;
        }
    }

	/**
	*	Намиране на съботите и неделите в дадена година
	*
	*	@return void
	*/
	private function getWeekendsOfYear(){
		$now ="01.01.".$this->year;
		$end_date ="01.01.".($this->year+1);
		$now = strtotime($now);
		$end_date = strtotime($end_date);
		while (date("Y-m-d", $now) != date("Y-m-d", $end_date)) {
		    $day_index = date("w", $now);
		    //проверка дали е събота или неделя
		    if ($day_index == 0 || $day_index == 6) {
		    	if ($day_index == 0) {
		    		$forpush = date("Y-m-d", $now);
                    self::setDay($forpush, "Неделя");
		    	}
		    	else{
		    		$forpush = date("Y-m-d", $now);
                    self::setDay($forpush, "Събота");
		    	}
		    }
		    $now = strtotime(date("Y-m-d", $now) . "+1 day");
		}
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

        // +/- дни от великден
        $okoloVelikden = strtotime(isoDate($G, $m, $RC)) + $petilipon;

		return date("Y-m-d", $okoloVelikden);
	}

	/**
	*	Откриване на всички почивни дни в дадена година
	*
	*	@return array Почивни дни
	*/
	public function get() {
		self::setDay($this->year."-01-01", "Нова година");
		self::getWeekendsOfYear();
		self::setDay($this->year."-03-03", "Национален празник");
		self::setDay($this->year."-05-01", "Ден на труда");
		self::setDay($this->year."-05-06", "Гергьовден");
		self::setDay($this->year."-05-24", "Ден на писмеността");
		self::setDay($this->year."-09-06", "Ден на съединението");
		self::setDay($this->year."-09-22", "Независимостта на България");
		self::setDay($this->year."-12-24", "Коледа");
		self::setDay($this->year."-12-25", "Коледа");
		self::setDay($this->year."-12-26", "Коледа");
		self::setDay(self::velikDen($this->year,"2"), "Велики петък");
		self::setDay(self::velikDen($this->year,"1"), "Великден");
		self::setDay(self::velikDen($this->year,"0"), "Великден");
		self::setDay(self::velikDen($this->year,"-1"), "Велики понеделник");

        return $this->hdays;
	}
}

/**
*	Връща масив с всички почивни дни
*
*	@param int $year Година
*   @param array|null $hdays Почивни дни
*	@return array Списък с почивните дни
*/
function restDaysYear($year, $hdays = null, $onlySetText = false){
    return (new RestDays($year, $hdays, $onlySetText))->get();
}
?>
