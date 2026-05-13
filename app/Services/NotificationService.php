<?php

class NotificationService implements NotifiableInterface
{
    // За скільки хвилин до події створювати сповіщення
    const MINUTES_BEFORE = 15;

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
    // trigger_at = starts_at мінус MINUTES_BEFORE хвилин
    public function createForEvent($eventId, $userId, $startsAt)
    {
        $triggerTimestamp = strtotime($startsAt) - (self::MINUTES_BEFORE * 60);

        // Не створюємо сповіщення якщо час вже минув
        if ($triggerTimestamp <= time()) {
            return null;
        }

        $triggerAt = date('Y-m-d H:i:s', $triggerTimestamp);

        $id = $this->notificationRepository->create(array(
            'user_id'    => $userId,
            'event_id'   => $eventId,
            'trigger_at' => $triggerAt,
        ));

        return $id;
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
