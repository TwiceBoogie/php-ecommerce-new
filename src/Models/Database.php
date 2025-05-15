<?php

namespace Sebastian\PhpEcommerce\Models;

use PDO;
use PDOException;

/**
 * Class Database
 *
 * A lightweight database abstraction layer built on top of PDO to provide
 * simple CRUD operations similar to Spring Data JPA. This version does not
 * manage transactions internally; instead, transaction boundaries should be
 * defined at a higher level (for example, in the service layer via a TransactionManager).
 */
class Database extends PDO
{
    /**
     * Database constructor.
     *
     * @param string $type         Database type (e.g., "mysql").
     * @param string $host         Hostname.
     * @param string $databaseName Database name.
     * @param string $username     Username.
     * @param string $password     Password.
     */
    public function __construct(string $type, string $host, string $databaseName, string $username, string $password)
    {
        $dsn = sprintf("%s:host=%s;dbname=%s;charset=utf8mb4", $type, $host, $databaseName);
        parent::__construct($dsn, $username, $password);

        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    /**
     * Executes a SELECT query.
     *
     * @param string $sql       The SQL SELECT query.
     * @param array  $params    Parameters to bind.
     * @param int    $fetchMode PDO fetch mode.
     * @return array            Result set.
     * @throws PDOException
     */
    public function select(string $sql, array $params = [], int $fetchMode = PDO::FETCH_ASSOC): array
    {
        $sth = $this->prepare($sql);
        foreach ($params as $key => $value) {
            $sth->bindValue(":$key", $value);
        }
        $sth->execute();

        return $sth->fetchAll($fetchMode);
    }

    /**
     * Inserts data into a table.
     *
     * @param string $table Table name.
     * @param array  $data  Associative array of column => value.
     * @return int          Last inserted ID.
     * @throws PDOException
     */
    public function insert(string $table, array $data): int
    {
        $fields = implode('`, `', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO `$table` (`$fields`) VALUES ($placeholders)";

        $sth = $this->prepare($sql);
        foreach ($data as $key => $value) {
            $sth->bindValue(":$key", $value);
        }
        $sth->execute();

        return (int) $this->lastInsertId();
    }

    /**
     * Insert data in bulk
     * @param string $table Table name.
     * @param array  $rows  Array of associative arrays
     * @return void
     * @throws PDOException
     */
    public function bulkInsert(string $table, array $rows): void
    {
        if (empty($rows)) {
            return;
        }

        $columns = array_keys($rows);
        $columnsList = "`" . implode("`, ", $columns) . "`";

        $placeholders = [];
        $bindings = [];

        foreach ($rows as $i => $row) {
            $rowPlaceholders = [];

            foreach ($columns as $col) {
                $key = "{$col}_{$i}";
                $rowPlaceholders[] = ":{$key}";
                $bindings[":{$key}"] = $row[$col];
            }
            $placeholders[] = "(" . implode(", ", $rowPlaceholders) . ")";
        }
        $sql = "INSERT INTO `$table` ($columnsList) VALUES " . implode(", ", $placeholders);
        $sth = $this->prepare($sql);

        foreach ($bindings as $key => $value) {
            $sth->bindValue($key, $value);
        }

        $sth->execute();
    }

    /**
     * Updates records in a table.
     *
     * @param string $table       Table name.
     * @param array  $data        Associative array of column => value.
     * @param string $where       WHERE clause (e.g., "id = :id").
     * @param array  $whereParams Parameters for the WHERE clause.
     * @return void
     * @throws PDOException
     */
    public function update(string $table, array $data, string $where, array $whereParams = []): void
    {
        $setClauseParts = [];
        foreach ($data as $column => $value) {
            // Build each assignment as: `column` = :column
            $setClauseParts[] = "`$column` = :$column";
        }
        $setClause = implode(', ', $setClauseParts);
        $sql = "UPDATE `$table` SET $setClause WHERE $where";

        $sth = $this->prepare($sql);
        // Bind values for data to update
        foreach ($data as $key => $value) {
            $sth->bindValue(":$key", $value);
        }
        // Bind values for the WHERE clause
        foreach ($whereParams as $key => $value) {
            $sth->bindValue(":$key", $value);
        }
        $sth->execute();
    }

    /**
     * Deletes records from a table.
     *
     * @param string $table  Table name.
     * @param string $where  WHERE clause.
     * @param array  $params Parameters for the WHERE clause.
     * @return void
     * @throws PDOException
     */
    public function delete(string $table, string $where, array $params = []): void
    {
        $sql = "DELETE FROM `$table` WHERE $where";
        $sth = $this->prepare($sql);
        foreach ($params as $key => $value) {
            $sth->bindValue(":$key", $value);
        }
        $sth->execute();
    }
}
