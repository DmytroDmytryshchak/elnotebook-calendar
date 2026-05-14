<?php
// JSON API для роботи зі сповіщеннями
class NotificationController extends Controller
{
    private $notificationService;

    public function __construct()
    {
        Auth::handle();

        $this->notificationService = new NotificationService(
            new NotificationRepository()
        );
    }

    // Polling endpoint — JS викликає кожні 30 секунд
    // Повертає непереглянуті сповіщення і кількість для дзвіночка
    public function pending($request)
    {
        $userId = $this->currentUserId();

        $rows = $this->notificationService->getPendingNotifications($userId);

        $notification = array();
        foreach ($rows as $row) {
            $notification[] = array(
                'id'              => (int) $row['id'],
                'event_id'        => (int) $row['event_id'],
                'event_title'     => $row['event_title'],
                'event_starts_at' => $row['event_starts_at'],
                'trigger_at'      => $row['trigger_at'],
            );
        }

        $this->json(array(
            'success'       => true,
            'notification' => $notification,
            'count'         => count($notification),
        ));
    }

    // Позначити одне сповіщення як переглянуте (клік на toast)
    public function markSeen($request, $id)
    {
        $result = $this->notificationService->markAsSeen(
            (int) $id,
            $this->currentUserId()
        );

        $this->json(array('success' => $result));
    }

    // Позначити всі сповіщення як переглянуті (клік "dismiss all")
    public function markAllSeen($request)
    {
        $this->notificationService->markAllAsSeen($this->currentUserId());

        $this->json(array('success' => true));
    }
}
