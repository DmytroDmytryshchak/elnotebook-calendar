<?php
// модель події календаря без бізнес-логіки.
class Event
{
    private $id;
    private $userId;
    private $title;
    private $description;
    private $color;
    private $startsAt;
    private $endsAt;
    private $allDay;
    private $createdAt;
    private $updatedAt;

    public function __construct($id, $userId, $title, $description, $color, $startsAt, $endsAt, $allDay, $createdAt, $updatedAt)
    {
        $this->id          = (int) $id;
        $this->userId      = (int) $userId;
        $this->title       = $title;
        $this->description = $description;
        $this->color       = $color;
        $this->startsAt    = $startsAt;
        $this->endsAt      = $endsAt;
        $this->allDay      = (bool) $allDay;
        $this->createdAt   = $createdAt;
        $this->updatedAt   = $updatedAt;
    }

    public static function fromArray($row)
    {
        return new self(
            $row['id'],
            $row['user_id'],
            $row['title'],
            isset($row['description']) ? $row['description'] : '',
            $row['color'],
            $row['starts_at'],
            $row['ends_at'],
            $row['all_day'],
            $row['created_at'],
            $row['updated_at']
        );
    }

    public function getId()        { return $this->id; }
    public function getUserId()    { return $this->userId; }
    public function getTitle()     { return $this->title; }
    public function getDescription(){ return $this->description; }
    public function getColor()     { return $this->color; }
    public function getStartsAt()  { return $this->startsAt; }
    public function getEndsAt()    { return $this->endsAt; }
    public function isAllDay()     { return $this->allDay; }
    public function getCreatedAt() { return $this->createdAt; }
    public function getUpdatedAt() { return $this->updatedAt; }

    public function toArray()
    {
        return array(
            'id'          => $this->id,
            'user_id'     => $this->userId,
            'title'       => $this->title,
            'description' => $this->description,
            'color'       => $this->color,
            'starts_at'   => $this->startsAt,
            'ends_at'     => $this->endsAt,
            'all_day'     => $this->allDay,
            'created_at'  => $this->createdAt,
            'updated_at'  => $this->updatedAt,
        );
    }
}