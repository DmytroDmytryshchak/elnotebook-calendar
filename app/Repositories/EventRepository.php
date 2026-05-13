<?php
// Відповідає за всі операції з таблицею events, містить спеціалізовані методи пошуку подій за датою
class EventRepository implements RepositoryInterface
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM events WHERE id = :id LIMIT 1');
        $stmt->execute(array('id' => $id));
        $row = $stmt->fetch();

        return $row ? $row : null;
    }

    public function findAll()
    {
        return $this->db->query('SELECT * FROM events ORDER BY starts_at ASC')->fetchAll();
    }

    public function findByUserAndDateRange($userId, $from, $to)
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM events
             WHERE user_id  = :user_id
               AND starts_at < :to
               AND ends_at   > :from
             ORDER BY starts_at ASC'
        );

        $stmt->execute(array(
            'user_id' => $userId,
            'from'    => $from,
            'to'      => $to,
        ));

        return $stmt->fetchAll();
    }

    public function findAllByUser($userId)
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM events WHERE user_id = :user_id ORDER BY starts_at ASC'
        );
        $stmt->execute(array('user_id' => $userId));

        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO events
                (user_id, title, description, color, starts_at, ends_at, all_day)
             VALUES
                (:user_id, :title, :description, :color, :starts_at, :ends_at, :all_day)'
        );

        $stmt->execute(array(
            'user_id'     => $data['user_id'],
            'title'       => $data['title'],
            'description' => isset($data['description']) ? $data['description'] : '',
            'color'       => isset($data['color']) ? $data['color'] : '#5856d6',
            'starts_at'   => $data['starts_at'],
            'ends_at'     => $data['ends_at'],
            'all_day'     => $data['all_day'] ? 1 : 0,
        ));

        return (int) $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare(
            'UPDATE events
             SET title       = :title,
                 description = :description,
                 color       = :color,
                 starts_at   = :starts_at,
                 ends_at     = :ends_at,
                 all_day     = :all_day
             WHERE id = :id'
        );

        return $stmt->execute(array(
            'id'          => $id,
            'title'       => $data['title'],
            'description' => isset($data['description']) ? $data['description'] : '',
            'color'       => isset($data['color']) ? $data['color'] : '#5856d6',
            'starts_at'   => $data['starts_at'],
            'ends_at'     => $data['ends_at'],
            'all_day'     => $data['all_day'] ? 1 : 0,
        ));
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM events WHERE id = :id');
        return $stmt->execute(array('id' => $id));
    }

    public function belongsToUser($eventId, $userId)
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM events WHERE id = :id AND user_id = :user_id'
        );
        $stmt->execute(array('id' => $eventId, 'user_id' => $userId));

        return (int) $stmt->fetchColumn() > 0;
    }
}