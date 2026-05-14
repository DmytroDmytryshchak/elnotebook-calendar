<?php
// бізнес логіка для роботи з подіями календаря
class EventService
{
    private $eventRepository;
    private $eventValidator;
    private $notificationService;

    public function __construct($eventRepository, $eventValidator, $notificationService)
    {
        $this->eventRepository = $eventRepository;
        $this->eventValidator  = $eventValidator;
        $this->notificationService = $notificationService;
    }

    public function getEventsForMonth($userId, $year, $month)
    {
        $from = sprintf('%04d-%02d-01 00:00:00', $year, $month);
        $to   = date('Y-m-t 23:59:59', mktime(0, 0, 0, $month, 1, $year));

        $rows = $this->eventRepository->findByUserAndDateRange($userId, $from, $to);

        $events = array();
        foreach ($rows as $row) {
            $events[] = Event::fromArray($row);
        }

        return $events;
    }

    public function getEventById($eventId, $userId)
    {
        $row = $this->eventRepository->findById($eventId);

        if ($row === null) {
            throw new NotFoundException('Event #' . $eventId . ' not found.');
        }

        if ((int) $row['user_id'] !== (int) $userId) {
            throw new NotFoundException('Event #' . $eventId . ' not found.');
        }

        return Event::fromArray($row);
    }

    public function createEvent($userId, $data)
    {
        $this->eventValidator->validate($data);

        $startsAt = $this->normalizeDateTime($data['starts_at']);

        $eventId = $this->eventRepository->create(array(
            'user_id'     => $userId,
            'title'       => trim($data['title']),
            'description' => isset($data['description']) ? trim($data['description']) : '',
            'color'       => isset($data['color']) ? $data['color'] : '#5856d6',
            'starts_at'   => $this->normalizeDateTime($data['starts_at']),
            'ends_at'     => $this->normalizeDateTime($data['ends_at']),
            'all_day'     => isset($data['all_day']) && $data['all_day'] === '1',
        ));

        $this->notificationService->createForEvent($eventId, $userId, $startsAt);

        return $this->getEventById($eventId, $userId);
    }

    public function updateEvent($eventId, $userId, $data)
    {
        $this->getEventById($eventId, $userId);

        $this->eventValidator->validate($data);

        $startsAt = $this->normalizeDateTime($data['starts_at']);

        $this->eventRepository->update($eventId, array(
            'title'       => trim($data['title']),
            'description' => isset($data['description']) ? trim($data['description']) : '',
            'color'       => isset($data['color']) ? $data['color'] : '#5856d6',
            'starts_at'   => $this->normalizeDateTime($data['starts_at']),
            'ends_at'     => $this->normalizeDateTime($data['ends_at']),
            'all_day'     => isset($data['all_day']) && $data['all_day'] === '1',
        ));

        $this->notificationService->deleteForEvent($eventId);
        $this->notificationService->createForEvent($eventId, $userId, $startsAt);

        return $this->getEventById($eventId, $userId);
    }

    public function deleteEvent($eventId, $userId)
    {
        $this->getEventById($eventId, $userId);

        $this->eventRepository->delete($eventId);
    }

    private function normalizeDateTime($value)
    {
        $formats = array('Y-m-d\TH:i', 'Y-m-d H:i:s', 'Y-m-d');

        foreach ($formats as $format) {
            $dt = DateTime::createFromFormat($format, $value);
            if ($dt !== false) {
                return $dt->format('Y-m-d H:i:s');
            }
        }

        return $value;
    }
}