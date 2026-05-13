<?php
// Цей partial очікує змінну $event (об'єкт Event)
// яка встановлюється в циклі foreach у calendar/grid.php
$bgColor = htmlspecialchars($event->getColor());
$title   = htmlspecialchars($event->getTitle());
$eventId = (int) $event->getId();

$timeStr = '';
if (!$event->isAllDay()) {
    // Показуємо тільки години:хвилини
    $timeStr = substr($event->getStartsAt(), 11, 5);
}
?>

<div class="event-chip"
     style="background-color: <?php echo $bgColor; ?>;"
     data-event-id="<?php echo $eventId; ?>"
     title="<?php echo $title; ?>">

    <?php if ($timeStr): ?>
        <span class="event-chip-time"><?php echo $timeStr; ?></span>
    <?php endif; ?>

    <span class="event-chip-title"><?php echo $title; ?></span>

</div>