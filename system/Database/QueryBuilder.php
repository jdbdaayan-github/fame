<?php

namespace System\Database;

use PDO;
use PDOStatement;

class QueryBuilder
{
    protected PDO $pdo;
    protected string $table;

    protected array $columns = ['*'];
    protected array $wheres = [];
    protected array $bindings = [];
    protected array $joins = [];
    protected array $orders = [];
    protected ?int $limit = null;
    protected ?int $offset = null;

    public function __construct(PDO $pdo, string $table)
    {
        $this->pdo = $pdo;
        $this->table = $table;
    }

    // --- SELECT ---
    public function select(...$columns): self
    {
        $this->columns = $columns ?: ['*'];
        return $this;
    }

    // --- WHERE ---
    public function where(string $column, string $operator, $value): self
    {
        $this->wheres[] = "$column $operator ?";
        $this->bindings[] = $value;
        return $this;
    }

    public function orWhere(string $column, string $operator, $value): self
    {
        if ($this->wheres) {
            $this->wheres[count($this->wheres) - 1] .= " OR $column $operator ?";
        } else {
            $this->wheres[] = "$column $operator ?";
        }
        $this->bindings[] = $value;
        return $this;
    }

    // --- JOINS ---
    public function join(string $table, string $first, string $operator, string $second): self
    {
        $this->joins[] = "INNER JOIN $table ON $first $operator $second";
        return $this;
    }

    public function leftJoin(string $table, string $first, string $operator, string $second): self
    {
        $this->joins[] = "LEFT JOIN $table ON $first $operator $second";
        return $this;
    }

    // --- ORDER BY ---
    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orders[] = "$column " . strtoupper($direction);
        return $this;
    }

    // --- LIMIT & OFFSET ---
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    // --- GET RESULTS ---
    public function get(): array
    {
        $sql = $this->buildSelect();
        $stmt = $this->execute($sql, $this->bindings);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function first(): ?array
    {
        $this->limit(1);
        $sql = $this->buildSelect();
        $stmt = $this->execute($sql, $this->bindings);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        if ($this->wheres) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }
        $stmt = $this->execute($sql, $this->bindings);
        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    // --- INSERT ---
    public function insert(array $data): bool
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        return $this->execute($sql, array_values($data))->rowCount() > 0;
    }

    public function insertGetId(array $data): int
    {
        $this->insert($data);
        return (int)$this->pdo->lastInsertId();
    }

    // --- UPDATE ---
    public function update(array $data): bool
    {
        $set = implode(', ', array_map(fn($k) => "$k = ?", array_keys($data)));
        $sql = "UPDATE {$this->table} SET $set";
        if ($this->wheres) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }
        $bindings = array_merge(array_values($data), $this->bindings);
        return $this->execute($sql, $bindings)->rowCount() > 0;
    }

    // --- DELETE ---
    public function delete(): bool
    {
        $sql = "DELETE FROM {$this->table}";
        if ($this->wheres) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }
        return $this->execute($sql, $this->bindings)->rowCount() > 0;
    }

    // --- RAW QUERY ---
    public function raw(string $sql, array $bindings = []): array
    {
        $stmt = $this->execute($sql, $bindings);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- INTERNAL ---
    protected function buildSelect(): string
    {
        $sql = "SELECT " . implode(', ', $this->columns) . " FROM {$this->table}";
        if ($this->joins) {
            $sql .= " " . implode(' ', $this->joins);
        }
        if ($this->wheres) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }
        if ($this->orders) {
            $sql .= " ORDER BY " . implode(', ', $this->orders);
        }
        if ($this->limit !== null) {
            $sql .= " LIMIT {$this->limit}";
        }
        if ($this->offset !== null) {
            $sql .= " OFFSET {$this->offset}";
        }
        return $sql;
    }

    protected function execute(string $sql, array $bindings = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);
        return $stmt;
    }
}
