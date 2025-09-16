<?php
namespace System\Core;

use SessionHandlerInterface;
use PDO;

class DatabaseSessionHandler implements SessionHandlerInterface
{
    protected PDO $pdo;
    protected string $table;

    public function __construct(PDO $pdo, string $table = 'sessions')
    {
        $this->pdo   = $pdo;
        $this->table = $table;
    }

    public function open($savePath, $sessionName): bool
    {
        return true;
    }

    public function close(): bool
    {
        // nothing to do here
        return true;
    }

    public function read($sessionId): string
    {
        $stmt = $this->pdo->prepare("SELECT data FROM {$this->table} WHERE id = :id AND expiry > :time LIMIT 1");
        $stmt->execute([
            ':id'   => $sessionId,
            ':time' => time(),
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['data'] ?? '';
    }

    public function write($sessionId, $data): bool
    {
        $expiry = time() + (60 * 120); // 2 hours default
        $stmt = $this->pdo->prepare("
            REPLACE INTO {$this->table} (id, data, expiry) 
            VALUES (:id, :data, :expiry)
        ");
        return $stmt->execute([
            ':id'     => $sessionId,
            ':data'   => $data,
            ':expiry' => $expiry,
        ]);
    }

    public function destroy($sessionId): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $sessionId]);
    }

    public function gc($max_lifetime): int|false
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE expiry < :time");
        $stmt->execute([':time' => time()]);
        return $stmt->rowCount();
    }
}
