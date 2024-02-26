<?php

trait DateHelpers
{
    public function debugDate()
    {
        var_dump($this->format('Y-m-d H:i:s'));
    }
    public function getMonthNumberDays()
    {
        return (int) $this->format('t');
    }
    public function getCurrentDayNumber()
    {
        return (int) $this->format('j');
    }

    public function getMonthNumber()
    {
        return (int) $this->format('n');
    }

    public function getMonthName()
    {
        return $this->format('M');
    }

    public function getYear()
    {
        if ($this instanceof DateTime || $this instanceof DateTimeImmutable) {
            return $this->format('Y');
        }

        return null;
    }
}
