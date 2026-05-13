<?php

class NotificationRepository implements RepositoryInterface
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM notifications WHERE id = :id LIMIT 1'
        );
        $stmt->execute(array('id' => $id));
        $row = $stmt->fetch();

        return $row ? $row : null;
    }

    public function findAll()
    {
        return $this->db->query('SELECT * FROM notifications ORDER BY trigger_at ASC')->fetchAll();
    }

    // Знайти всі непереглянуті сповіщення юзера що вже настав час показати
    public function findPending($userId)
    {
        $now  = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare(
            'SELECT n.*, e.title AS event_title, e.starts_at AS event_starts_at
             FROM notifications n
             JOIN events e ON e.id = n.event_id
             WHERE n.user_id   = :user_id
               AND n.seen      = 0
               AND n.trigger_at <= :now
             ORDER BY n.trigger_at ASC'
        );
        $stmt->execute(array('user_id' => $userId, 'now' => $now));

        return $stmt->fetchAll();
    }

    // Знайти всі сповіщення юзера (переглянуті і ні)
    public function findAllByUser($userId)
    {
        $stmt = $this->db->prepare(
            'SELECT n.*, e.title AS event_title, e.starts_at AS event_starts_at
             FROM notifications n
             JOIN events e ON e.id = n.event_id
             WHERE n.user_id = :user_id
             ORDER BY n.trigger_at DESC'
        );
        $stmt->execute(array('user_id' => $userId));

        return $stmt->fetchAll();
    }

    // Порахувати скільки непереглянутих сповіщень у юзера
    public function countUnseen($userId)
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM notifications
             WHERE user_id = :user_id AND seen = 0'
        );
        $stmt->execute(array('user_id' => $userId));

        return (int) $stmt->fetchColumn();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO notifications (user_id, event_id, trigger_at)
             VALUES (:user_id, :event_id, :trigger_at)'
        );

        $stmt->execute(array(
            'user_id'    => $data['user_id'],
            'event_id'   => $data['event_id'],
            'trigger_at' => $data['trigger_at'],
        ));

        return (int) $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare(
            'UPDATE notifications SET seen = :seen WHERE id = :id'
        );

        return $stmt->execute(array(
            'id'   => $id,
            'seen' => $data['seen'] ? 1 : 0,
        ));
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM notifications WHERE id = :id');
        return $stmt->execute(array('id' => $id));
    }

    // Позначити всі сповіщення юзера як переглянуті одним запитом
    public function markAllSeenByUser($userId)
    {
        $stmt = $this->db->prepare(
            'UPDATE notifications SET seen = 1
             WHERE user_id = :user_id AND seen = 0'
        );

        return $stmt->execute(array('user_id' => $userId));
    }

    // Видалити всі сповіщення пов'язані з подією
    public function deleteByEventId($eventId)
    {
        $stmt = $this->db->prepare(
            'DELETE FROM notifications WHERE event_id = :event_id'
        );

        return $stmt->execute(array('event_id' => $eventId));
    }
}
