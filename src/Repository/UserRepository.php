<?php

namespace Sebastian\PhpEcommerce\Repository;

use Sebastian\PhpEcommerce\Models\Database;
use Exception;

class UserRepository extends BaseRepository
{
    public function __construct(Database $db)
    {
        parent::__construct($db, 'users');
    }

    public function getUserIdByCreds(string $email, string $password): array
    {
        return $this->db->select(
            "SELECT id, confirmed FROM `users`
            WHERE `user_email` = :e AND `user_password` = :p",
            ["e" => $email, "p" => $password]
        );
    }

    public function isUserExistByEmail(string $email): bool
    {
        $result = $this->db->select(
            "SELECT COUNT(*) as count FROM `users` WHERE `user_email` = :email",
            ["email" => $email]
        );
        return $result[0]['count'] > 0;
    }

    public function insertUserDetails(string $userId)
    {
        $this->db->insert('user_details', [
            'user_id' => $userId,
        ]);
    }
}