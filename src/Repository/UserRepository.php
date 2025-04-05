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

    public function findUserByEmail(string $email): array
    {
        return $this->db->select(
            "SELECT * FROM `users` WHERE user_email = :email",
            ["email" => $email]
        );
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

    public function getUserDetails(int $userId): array
    {
        $result = $this->db->select(
            "SELECT u.user_email AS email, u.user_name AS name, u.register_date, ud.id, ud.phone, ud.address, ud.city, ud.state, ud.postal_code, ud.country
            FROM `users` u
            LEFT JOIN `user_details` ud ON u.id = ud.user_id
            WHERE u.id = :userId",
            ["userId" => $userId]
        );

        return $result[0];
    }

    public function isAdmin(int $userId): bool
    {
        $result = $this->db->select(
            "SELECT COUNT(*) AS count FROM `users` WHERE `id` = :userId AND `user_role` = 1",
            ['userId' => $userId]
        );

        return !empty($result) && $result[0]['count'] > 0;
    }

    public function updateUserDetails(
        int $userId,
        string $name,
        string $phone,
        string $address,
        string $city,
        string $state,
        string $postal,
        string $country
    ) {
        $this->transactional(function () use ($userId, $name, $phone, $address, $city, $state, $postal, $country) {
            $this->db->update(
                'users',
                ['user_name' => $name],
                'id = :userId',
                ['userId' => $userId]
            );
            $this->db->update(
                'user_details',
                [
                    'phone' => $phone,
                    'address' => $address,
                    'city' => $city,
                    'state' => $state,
                    'postal_code' => $postal,
                    'country' => $country
                ],
                'user_id = :userId',
                ['userId' => $userId]
            );
        });
    }

}