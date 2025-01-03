<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' type='text/css' href='../styles/style.css?v=1'> 
    <title>PHP Calendar</title>
</head>  
    <?php
    $image = isset($_GET['bg_image']) ? $_GET['bg_image'] : 'default_image_path.jpg';
    $month = isset($_GET['month']) ? $_GET['month'] : null;
    $day = isset($_GET['day']) ? $_GET['day'] : null;
    $year = isset($_GET['year']) ? $_GET['year'] : null; 

    echo "<body class='image' style='background-image: url(\"$image\");'> 
    <div class='container-fluid thetitle'>
        <h1 class='thetitle'><strong>T R A G E D A Y . C O M</strong></h1>  
    </div> 
    <div class='container-sm summary'> 
        <h2 class='dater'>" . $month . " " . $day . ", " . $year . "</h2>
    </div> 
    <ul class='nav'>
        <li class='nav-item'>
            <a class='nav-link active' aria-current='page' href='index.php'>Home</a>
        </li>
        <li class='nav-item'>
            <a class='nav-link' href='tragedies.php'>Tragedies</a>
        </li> 
        <li class='nav-item'>
                <a class='nav-link' href='aboutus.php?bg_image=" . $image . "&month=" . $month . 
                        "&day=" . $day . "&year=" . $year . "'>About Us</a>
            </li>
        <li class='nav-item'>
            <a class='nav-link' href='contactus.php?bg_image=" . $image . "&month=" . $month . 
                    "&day=" . $day . "&year=" . $year . "'>Contact Us</a>
        </li> 
    </ul>  ";

    $xmonth = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
    $xyear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
    
    $daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    $firstDayOfMonth = mktime(0, 0, 0, $xmonth, 1, $xyear);
    $numberDays = date('t', $firstDayOfMonth);
    $dateComponents = getdate($firstDayOfMonth);
    $monthName = $dateComponents['month'];
    $dayOfWeek = $dateComponents['wday'];

    $prevMonth = $xmonth - 1;
    $prevYear = $xyear;
    if ($prevMonth < 1) {
        $prevMonth = 12;
        $prevYear--;
    }

    $nextMonth = $xmonth + 1;
    $nextYear = $xyear;
    if ($nextMonth > 12) {
        $nextMonth = 1;
        $nextYear++;
    }

    echo "<div class='calendar'>";
    echo "<header>";
    echo "<a href='?month=$prevMonth&year=$prevYear'>&lt;&lt;</a> ";
    echo "<span>$monthName $xyear</span> ";
    echo "<a href='?month=$nextMonth&year=$nextYear'>&gt;&gt;</a>";
    echo "</header>";
    echo "<table>";
    echo "<tr>";

    foreach ($daysOfWeek as $day) {
        echo "<th>$day</th>";
    }

    echo "</tr><tr>";

    if ($dayOfWeek > 0) {
        for ($i = 0; $i < $dayOfWeek; $i++) {
            echo "<td></td>";
        }
    }

    $currentDay = 1;

    while ($currentDay <= $numberDays) {
        if ($dayOfWeek == 7) {
            $dayOfWeek = 0;
            echo "</tr><tr>";
        }

        $todayClass = ($currentDay == date('j') && $xmonth == date('m') && $xyear == date('Y')) ? "today" : "";
        echo "<td class='$todayClass'>$currentDay</td>";

        $currentDay++;
        $dayOfWeek++;
    }

    if ($dayOfWeek != 7) {
        $remainingDays = 7 - $dayOfWeek;
        for ($i = 0; $i < $remainingDays; $i++) {
            echo "<td></td>";
        }
    }

    echo "</tr>";
    echo "</table>";
    echo "</div>";
    ?>
</body>
</html>
