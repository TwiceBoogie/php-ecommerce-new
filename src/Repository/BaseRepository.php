<?php

namespace Sebastian\PhpEcommerce\Repository;

use Sebastian\PhpEcommerce\Models\Database;
use InvalidArgumentException;
use Exception;

/**
 * Class BaseRepository
 *
 * A base repository that provides common CRUD operations similar to Spring Data JPA.
 */
abstract class BaseRepository
{
    /**
     * @var Database The database connection.
     */
    protected Database $db;

    /**
     * @var string The table name associated with this repository.
     */
    protected string $table;

    /**
     * BaseRepository constructor.
     *
     * @param Database $db    The database connection.
     * @param string   $table The table name.
     */
    public function __construct(Database $db, string $table)
    {
        $this->db = $db;
        $this->table = $table;
    }

    public function findBy(array $column): array
    {
        $sql = "SELECT * FROM `$this->table` WHERE ";
        $placeholders = [];
        $bindings = [];
        foreach ($column as $col => $value) {
            $placeholders[] = "`$col` = :$col";
            $bindings[$col] = $value;
        }
        $sql .= implode(" AND ", $placeholders);
        $result = $this->db->select($sql, $bindings);
        return !empty($result) ? $result : [];
    }

    /**
     * Retrieves all records from the table with optional sorting.
     *
     * @param array|null $sort Associative array of column => direction (ASC|DESC).
     * @return array           List of records.
     */
    public function findAll(?array $sort = null): array
    {
        $sql = "SELECT * FROM `$this->table`";
        if ($sort) {
            $sql .= $this->buildOrderByClause($sort);
        }
        return $this->db->select($sql);
    }

    /**
     * Finds a record by its ID.
     *
     * @param int $id Record identifier.
     * @return array|null The record data or null if not found.
     */
    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM `$this->table` WHERE id = :id";
        $result = $this->db->select($sql, ['id' => $id]);
        return $result[0] ?? null;
    }

    public function findIdBy(array $conditions): int
    {
        $sql = "SELECT `id` FROM `$this->table` WHERE ";
        $placeholders = [];
        $bindings = [];
        foreach ($conditions as $col => $value) {
            $placeholders[] = "`$col` = :$col";
            $bindings[$col] = $value;
        }
        $sql .= implode(" AND ", $placeholders) . " LIMIT 1";
        $result = $this->db->select($sql, $bindings);
        return $result[0]['id'] ?? 0;
    }

    /**
     * Check if records exists by a column and value
     * 
     * @param array    $column Array ['column' => value]
     * @return bool            True if record exist, false otherwise
     */
    public function exists(array $column): bool
    {
        $sql = "SELECT 1 From `$this->table` WHERE ";
        $placeholders = [];
        $bindings = [];
        foreach ($column as $col => $value) {
            $placeholders[] = "`$col` = :$col";
            $bindings[$col] = $value;
        }
        $sql .= implode(" AND ", $placeholders) . " LIMIT 1";
        $result = $this->db->select($sql, $bindings);
        return !empty($result);
    }

    /**
     * Saves a record. If an ID exists, it updates; otherwise, it inserts.
     *
     * @param array $data The record data.
     * @return array      The saved record.
     */
    public function save(array $data): array
    {
        if (isset($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
            $this->db->update($this->table, $data, "id = :id", ['id' => $id]);
            return $this->findById($id);
        } else {
            $insertId = $this->db->insert($this->table, $data);
            return $this->findById($insertId);
        }
    }

    /**
     * Deletes a record by its ID.
     *
     * @param int $id The record identifier.
     * @return void
     */
    public function delete(int $id): void
    {
        $this->db->delete($this->table, "id = :id", ['id' => $id]);
    }

    /**
     * Counts the number of records in the table.
     *
     * @return int The count of records.
     */
    public function count(): int
    {
        $result = $this->db->select("SELECT COUNT(*) AS count FROM `$this->table`");
        return (int) ($result[0]['count'] ?? 0);
    }

    /**
     * Builds the ORDER BY clause for SQL queries.
     *
     * @param array $sort Associative array of column => direction.
     * @return string     ORDER BY clause.
     * @throws InvalidArgumentException If the sort direction is invalid.
     */
    private function buildOrderByClause(array $sort): string
    {
        $orderBy = [];
        foreach ($sort as $column => $direction) {
            $direction = strtoupper($direction);
            if (!in_array($direction, ['ASC', 'DESC'], true)) {
                throw new InvalidArgumentException("Invalid sort direction for column `$column`: $direction");
            }
            $orderBy[] = "`$column` $direction";
        }
        return ' ORDER BY ' . implode(', ', $orderBy);
    }

    /**
     * Executes the given callback within a transaction.
     *
     * @param callable $callback The business operation to perform.
     * @return mixed The callback's return value.
     * @throws Exception If the operation fails.
     */
    public function transactional(callable $callback)
    {
        try {
            $this->db->beginTransaction();
            $result = $callback();
            $this->db->commit();
            return $result;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
