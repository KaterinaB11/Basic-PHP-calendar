<?php
require_once 'Calendar.php';


$calendar = new Calendar(new CurrentDate(), new CalendarDate());

$calendar->setMondayFirst(true);



if (isset($_GET['prev'])) {
    $calendar->setMonth($_GET['prev']);
} elseif (isset($_GET['next'])) {
    $calendar->setMonth($_GET['next']);
}

$calendar->create();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <h1>Calendar 2024</h1>
    <hr>
    
    <div class="navigation">
    <a href="?prev=<?php echo ($calendar->getCalendarDate()->getMonthNumber() - 2 + 12) % 12 + 1; ?>"><</a>

    <div class="current-month"><h2><?php echo $calendar->getCalendarMonth(); ?></h2></div>
    <a href="?next=<?php echo ($calendar->getCalendarDate()->getMonthNumber() % 12) + 1; ?>">></a>

    </div>
    <table class=" table table-active">
        <thead>
            <?php foreach ($calendar->getDayLabels() as $dayLabel) : ?>
                <th>
                    <?php echo $dayLabel; ?>
                </th>
            <?php endforeach; ?>
        </thead>
        <tbody>
            <?php foreach ($calendar->getWeeks() as $week) : ?>
                <tr>
                    <?php foreach ($week as $day) : ?>
                        <td <?php if (!$day['currentMonth']) : ?> class="text-gray" <?php endif; ?>>
                            <span <?php if ($calendar->isCurrentDate($day['dayNumber'])): ?> class="text-blue" <?php endif; ?>>
                                <?php echo $day['dayNumber']; ?>
                            </span>
                            <?php if (isset($day['event'])): ?>
        <div class="event">
            <?php echo $day['event']['eventName']; ?>
        </div>
    <?php endif; ?>
                        </td>

                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>


    </table>

</body>

</html>