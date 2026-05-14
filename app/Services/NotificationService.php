<?php

class NotificationService implements NotificationInterface
{
    // За скільки хвилин до події створювати сповіщення
    const MINUTES_BEFORE = 1;

    private $notificationRepository;

    public function __construct($notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    // Повертає масив рядків з БД
    public function getPendingNotifications($userId)
    {
        return $this->notificationRepository->findPending($userId);
    }

    // Позначає одне сповіщення як переглянуте
    public function markAsSeen($notificationId, $userId)
    {
        $row = $this->notificationRepository->findById($notificationId);

        if ($row === null) {
            return false;
        }

        if ((int) $row['user_id'] !== (int) $userId) {
            return false;
        }

        return $this->notificationRepository->update($notificationId, array('seen' => true));
    }

    // Позначає всі сповіщення юзера як переглянуті
    public function markAllAsSeen($userId)
    {
        return $this->notificationRepository->markAllSeenByUser($userId);
    }

    // Створює сповіщення для щойно створеної події
    public function createForEvent($eventId, $userId, $startsAt)
    {
        $dt = new DateTime($startsAt, new DateTimeZone('Europe/Bratislava'));
        $dt->modify('-' . self::MINUTES_BEFORE . ' minutes');

        $triggerAt = $dt->format('Y-m-d H:i:s');

        return $this->notificationRepository->create([
            'user_id'    => $userId,
            'event_id'   => $eventId,
            'trigger_at' => $triggerAt,
        ]);
    }

    // Повертає кількість непереглянутих сповіщень для дзвіночка
    public function countUnseen($userId)
    {
        return $this->notificationRepository->countUnseen($userId);
    }

    // Видаляє всі сповіщення для події (при оновленні часу події)
    public function deleteForEvent($eventId)
    {
        return $this->notificationRepository->deleteByEventId($eventId);
    }
}
