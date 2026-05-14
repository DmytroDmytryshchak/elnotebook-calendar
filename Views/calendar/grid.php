<?php
// Перший день місяця — який день тижня? (0=Нд, 1=Пн, ..., 6=Сб)
$firstDayTimestamp = mktime(0, 0, 0, $month, 1, $year);
$firstDayOfWeek    = (int) date('w', $firstDayTimestamp);

// Робимо тиждень з понеділка (0=Пн, 6=Нд)
// date('w') повертає 0 для неділі — переробляємо
$startOffset = ($firstDayOfWeek === 0) ? 6 : $firstDayOfWeek - 1;

// Скільки днів у місяці
$daysInMonth = (int) date('t', $firstDayTimestamp);

// Сьогоднішня дата для підсвічування
$todayStr = date('Y-m-d');
?>

<div class="calendar-header">
    <div class="calendar-nav">
        <a href="/final_project/Public/calendar?year=<?php echo $prevYear; ?>&month=<?php echo $prevMonth; ?>"
           class="btn btn-outline btn-sm">← Prev</a>

        <h2 class="calendar-title">
            <?php echo htmlspecialchars($monthName); ?>
            <?php echo $year; ?>
        </h2>

        <a href="/final_project/Public/calendar?year=<?php echo $nextYear; ?>&month=<?php echo $nextMonth; ?>"
           class="btn btn-outline btn-sm">Next →</a>
    </div>

    <button class="btn btn-primary btn-sm" id="btn-add-event">+ Add Event</button>
</div>

<!-- Назви днів тижня -->
<div class="calendar-grid">
    <div class="calendar-weekdays">
        <div class="weekday">Mon</div>
        <div class="weekday">Tue</div>
        <div class="weekday">Wed</div>
        <div class="weekday">Thu</div>
        <div class="weekday">Fri</div>
        <div class="weekday">Sat</div>
        <div class="weekday">Sun</div>
    </div>

    <!-- Клітинки днів -->
    <div class="calendar-days">

        <!-- Порожні клітинки на початку (зсув першого дня) -->
        <?php for ($i = 0; $i < $startOffset; $i++): ?>
            <div class="day-cell day-cell--empty"></div>
        <?php endfor; ?>

        <!-- Дні місяця -->
        <?php for ($day = 1; $day <= $daysInMonth; $day++): ?>
            <?php
            $dateStr      = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $isToday      = ($dateStr === $todayStr);
            $dayEvents    = isset($eventsByDate[$dateStr]) ? $eventsByDate[$dateStr] : array();
            $cellClass    = 'day-cell';
            if ($isToday)             { $cellClass .= ' day-cell--today'; }
            if (!empty($dayEvents))   { $cellClass .= ' day-cell--has-events'; }
            ?>

            <div class="<?php echo $cellClass; ?>"
                 data-date="<?php echo $dateStr; ?>">

                <span class="day-number"><?php echo $day; ?></span>

                <!-- Події цього дня -->
                <div class="day-events">
                    <?php foreach ($dayEvents as $event): ?>
                        <?php require BASE_PATH . '/Views/part/event-chip.php'; ?>
                    <?php endforeach; ?>
                </div>

            </div>

        <?php endfor; ?>

    </div><!-- .calendar-days -->
</div><!-- .calendar-grid -->

<!-- Модальне вікно — підключимо у Фазі 4 -->
<div id="modal-placeholder"></div>

<!-- Передаємо поточний місяць/рік у JS -->
<script>
    var CALENDAR_YEAR  = <?php echo $year; ?>;
    var CALENDAR_MONTH = <?php echo $month; ?>;
</script>