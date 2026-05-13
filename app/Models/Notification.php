<?php

class Notification
{
    private $id;
    private $userId;
    private $eventId;
    private $triggerAt;
    private $seen;
    private $createdAt;

    public function __construct($id, $userId, $eventId, $triggerAt, $seen, $createdAt)
    {
        $this->id        = (int)  $id;
        $this->userId    = (int)  $userId;
        $this->eventId   = (int)  $eventId;
        $this->triggerAt = $triggerAt;
        $this->seen      = (bool) $seen;
        $this->createdAt = $createdAt;
    }

    public static function fromArray($row)
    {
        return new self(
            $row['id'],
            $row['user_id'],
            $row['event_id'],
            $row['trigger_at'],
            $row['seen'],
            $row['created_at']
        );
    }

    public function getId()        { return $this->id; }
    public function getUserId()    { return $this->userId; }
    public function getEventId()   { return $this->eventId; }
    public function getTriggerAt() { return $this->triggerAt; }
    public function isSeen()       { return $this->seen; }
    public function getCreatedAt() { return $this->createdAt; }

    public function toArray()
    {
        return array(
            'id'         => $this->id,
            'user_id'    => $this->userId,
            'event_id'   => $this->eventId,
            'trigger_at' => $this->triggerAt,
            'seen'       => $this->seen,
            'created_at' => $this->createdAt,
        );
    }
}
