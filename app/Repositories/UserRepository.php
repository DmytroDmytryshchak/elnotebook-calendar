<?php
// збереження і читання користувачів з БД
// містить всі базові CRUD операції
class UserRepository implements RepositoryInterface
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(array('id' => $id));
        $row = $stmt->fetch();

        return $row ? $row : null;
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(array('email' => $email));
        $row = $stmt->fetch();

        return $row ? $row : null;
    }

    public function findAll()
    {
        return $this->db->query('SELECT * FROM users ORDER BY created_at DESC')->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users (name, email, password, created_at)
             VALUES (:name, :email, :password, NOW())'
        );

        $stmt->execute(array(
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'],
        ));

        return (int) $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare(
            'UPDATE users SET name = :name, email = :email WHERE id = :id'
        );

        return $stmt->execute(array(
            'name'  => $data['name'],
            'email' => $data['email'],
            'id'    => $id,
        ));
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
        return $stmt->execute(array('id' => $id));
    }

    public function emailExists($email)
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE email = :email');
        $stmt->execute(array('email' => $email));
        return (int) $stmt->fetchColumn() > 0;
    }
}