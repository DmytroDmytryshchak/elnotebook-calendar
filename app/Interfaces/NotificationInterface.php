<?php

interface NotificationInterface
{
    public function getPendingNotifications($userId);

    public function markAsSeen($notificationId, $userId);

    public function markAllAsSeen($userId);
}
