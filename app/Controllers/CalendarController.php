<?php
//  Відповідає за відображення сітки календаря
class CalendarController extends Controller
{
    private $eventService;

    public function __construct()
    {
        Auth::handle();

        $this->eventService = new EventService(
            new EventRepository(),
            new EventValidator(),
            new NotificationService(new NotificationRepository())
        );
    }

    public function index($request)
    {
        $year  = (int) $request->get('year',  date('Y'));
        $month = (int) $request->get('month', date('n'));

        // Якщо місяць вийшов за межі — виправляємо
        if ($month < 1)  { $month = 12; $year--; }
        if ($month > 12) { $month = 1;  $year++; }

        $events = $this->eventService->getEventsForMonth(
            $this->currentUserId(),
            $year,
            $month
        );

        // Розкладаємо події по датах щоб у шаблоні легко шукати
        $eventsByDate = $this->groupEventsByDate($events);

        $monthName = $this->getMonthName($month);

        // Посилання на попередній і наступний місяць
        $prevMonth = $month - 1;
        $prevYear  = $year;
        if ($prevMonth < 1) { $prevMonth = 12; $prevYear--; }

        $nextMonth = $month + 1;
        $nextYear  = $year;
        if ($nextMonth > 12) { $nextMonth = 1; $nextYear++; }

        $this->view('calendar/index', array(
            'year'         => $year,
            'month'        => $month,
            'monthName'    => $monthName,
            'eventsByDate' => $eventsByDate,
            'prevMonth'    => $prevMonth,
            'prevYear'     => $prevYear,
            'nextMonth'    => $nextMonth,
            'nextYear'     => $nextYear,
            'userName'     => isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '',
        ));
    }


    private function groupEventsByDate($events)
    {
        $grouped = array();

        foreach ($events as $event) {
            // Беремо тільки дату без часу
            $date = substr($event->getStartsAt(), 0, 10);

            if (!isset($grouped[$date])) {
                $grouped[$date] = array();
            }

            $grouped[$date][] = $event;
        }

        return $grouped;
    }

    private function getMonthName($month)
    {
        $names = array(
            1  => 'January',
            2  => 'February',
            3  => 'March',
            4  => 'April',
            5  => 'May',
            6  => 'June',
            7  => 'July',
            8  => 'August',
            9  => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        );

        return $names[$month];
    }
}