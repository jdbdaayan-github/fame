<?php
namespace System\Http;

use System\Core\DatabaseSessionHandler;
use System\Database\DB;
use App\Config\Database as DatabaseConfig;
use App\Config\Cookie as CookieConfig;
use App\Config\Session as SessionConfig;

class Session
{
    protected $sessionConfig;
    protected $cookieConfig;
    protected $dbConfig;

    public function __construct()
    {
        $this->sessionConfig = new SessionConfig();
        $this->cookieConfig  = new CookieConfig();
        $this->dbConfig      = (new DatabaseConfig())->default;
    }

    public function start()
    {
        session_name($this->sessionConfig->cookieName);

        session_set_cookie_params([
            'lifetime' => $this->sessionConfig->lifetime * 60,
            'path'     => $this->cookieConfig->path,
            'domain'   => $this->cookieConfig->domain,
            'secure'   => $this->cookieConfig->secure,
            'httponly' => $this->cookieConfig->httpOnly,
            'samesite' => $this->cookieConfig->sameSite,
        ]);

        if ($this->sessionConfig->driver === 'file') {
            ini_set('session.save_handler', 'files');
            session_save_path($this->sessionConfig->savePath);
        } elseif ($this->sessionConfig->driver === 'database') {
            $pdo = DB::connect($this->dbConfig); // <- pass db config
            $handler = new DatabaseSessionHandler(
                $pdo,
                $this->sessionConfig->table ?? 'sessions'
            );
            session_set_save_handler($handler, true);
        }

        session_start();
    }

    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function destroy()
    {
        $_SESSION = [];

        if (session_id() !== '' || isset($_COOKIE[$this->sessionConfig->cookieName])) {
            setcookie(
                $this->sessionConfig->cookieName,
                '',
                time() - 3600,
                $this->cookieConfig->path,
                $this->cookieConfig->domain,
                $this->cookieConfig->secure,
                $this->cookieConfig->httpOnly
            );
        }

        session_destroy();
    }
}
