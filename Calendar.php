<?php

require_once 'DateHelpers.php';

class CurrentDate extends DateTimeImmutable
{
    use DateHelpers;

    public function __construct()
    {
        parent::__construct();
    }
}

class CalendarDate extends DateTime
{
    use DateHelpers;

    public function __construct()
    {
        parent::__construct();
        $this->modify('first day of this month');
    }

    public function getMonthStartDayOfWeek()
    {
        return (int) $this->format('N');
    }

    public function getMonthNumber()
    {
        return (int) $this->format('n');
    }
}

class Calendar
{

    use DateHelpers;

    protected $currentDate;
    protected $calendarDate;

    protected $dayLabels = [
        'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
    ];

    protected $monthLabels = [
        'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
    ];

    protected $mondayFirst = true;
    protected $weeks = [];


    public function __construct(CurrentDate $currentDate, CalendarDate $calendarDate)
    {
        $this->currentDate = $currentDate;
        $this->calendarDate = clone $calendarDate;
        $this->calendarDate->modify('first day of this month');
    }

    

    public function getCalendarDate()
    {
        return $this->calendarDate;
    }

    public function getDayLabels()
    {
        return $this->dayLabels;
    }

    public function getMonthLabels()
    {
        return $this->monthLabels;
    }

    public function setMondayFirst($bool)
    {
        $this->mondayFirst = $bool;
        if (!$this->mondayFirst) {
            array_push($this->dayLabels, array_shift($this->dayLabels));
        }
    }

    public function setMonth($monthNumber)
    {
        $this->calendarDate->setDate($this->calendarDate->getYear(), $monthNumber, 1);
    }

    public function getCalendarMonth()
    {
        return $this->calendarDate->getMonthName();
    }

    protected function getMonthFirstDay()
{
    $firstDay = $this->calendarDate->modify('first day of this month')->format('N');

    if ($this->mondayFirst) {
        return $firstDay;
    } else {
        return ($firstDay - 1) % 7 + 1;
    }
}

    

    public function isCurrentDate($dayNumber)
    {
        if ($this->calendarDate->getYear()=== $this->currentDate->getYear() &&
        $this->calendarDate->getMonthNumber() === $this->currentDate->getMonthNumber() &&
        $this->currentDate->getCurrentDayNumber() === $dayNumber
        ) {
            return true;
        }
        return false;
    }

    public function getWeeks()
    {
        return $this->weeks;
    }

    public function create()
    {
        $days = array_fill(0, ($this->getMonthFirstDay() - 1), ['currentMonth' => false, 'dayNumber' => '']);

        //current days

        for ($x = 1; $x <= $this->calendarDate->getMonthNumberDays(); $x++) {
            $days[] = ['currentMonth' => true, 'dayNumber' => $x];
        }

        $this->weeks = array_chunk($days, 7);

        //last month

        $firstWeek = $this->weeks[0];
        $prevMonth = clone $this->calendarDate;
        $prevMonth->modify('-1 month');
        $prevMonthNumDays = $prevMonth->getMonthNumberDays();

        for ($x = 6; $x >= 0; $x--) {
            if (!$firstWeek[$x]['dayNumber']) {
                $firstWeek[$x]['dayNumber'] = $prevMonthNumDays;
                $prevMonthNumDays -= 1;
            }
        }

        $this->weeks[0] = $firstWeek;

        //next month

        $lastWeek = $this->weeks[count($this->weeks) - 1];
        $nextMonth = clone $this->calendarDate;
        $nextMonth->modify('+1 month');

        $c = 1;
        for ($x = 0; $x < 7; $x++) {
            if (!isset($lastWeek[$x])) {
                $lastWeek[$x]['currentMonth'] = false;
                $lastWeek[$x]['dayNumber'] = $c;
                $c++;
            }
        }

        $this->weeks[count($this->weeks) - 1] = $lastWeek;
    }
}
