<?php
// Модель користувача без бізнес логіки
class User
{
    private $id;
    private $name;
    private $email;
    private $passwordHash;
    private $createdAt;

    public function __construct($id, $name, $email, $passwordHash, $createdAt)
    {
        $this->id           = (int) $id;
        $this->name         = $name;
        $this->email        = $email;
        $this->passwordHash = $passwordHash;
        $this->createdAt    = $createdAt;
    }

    public static function fromArray($row)
    {
        return new self(
            $row['id'],
            $row['name'],
            $row['email'],
            $row['password'],
            $row['created_at']
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function toArray()
    {
        return array(
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'created_at' => $this->createdAt,
        );
    }
}