<?php

namespace Sebastian\PhpEcommerce\Repository;

use Sebastian\PhpEcommerce\Models\Database;
use Exception;
use Sebastian\PhpEcommerce\Services\SecureSession;

class LoginRepository extends BaseRepository
{
    public function __construct(Database $db)
    {
        parent::__construct($db, 'login_attempts');
    }

    public function insertFailedLogin(string $email): void
    {
        $this->db->insert($this->table, [
            'email' => $email,
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'success' => 0
        ]);
    }

    public function isLoginAttemptExceeded(string $email): bool
    {
        $attempts = $this->db->select(
            "SELECT COUNT(*) as count
            FROM `login_attempts`
            WHERE `email` = :email
            AND
            `success` = 0
            AND
            `attempt_time` > NOW() - INTERVAL 15 MINUTE",
            ['email' => $email]
        );
        if (count($attempts) >= 5) {
            return true;
        }
        return false;
    }
}