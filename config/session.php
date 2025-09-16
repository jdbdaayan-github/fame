<?php
namespace App\Config;

class Session
{
    /**
     * --------------------------------------------------------------------------
     * Session Driver
     * --------------------------------------------------------------------------
     * Supported: "file", "database", "native"
     */
    public string $driver = 'file';

    /**
     * --------------------------------------------------------------------------
     * Session Cookie Name
     * --------------------------------------------------------------------------
     */
    public string $cookieName = 'fame_session';

    /**
     * --------------------------------------------------------------------------
     * Session Lifetime
     * --------------------------------------------------------------------------
     * In minutes. After this, session will expire.
     */
    public int $lifetime = 120;

    /**
     * --------------------------------------------------------------------------
     * Session Save Path
     * --------------------------------------------------------------------------
     * For "file" driver: directory path (relative to project root).
     * Example: storage/fame_session
     */
    public string $savePath = WRITEPATH. 'fame_session';

    /**
     * --------------------------------------------------------------------------
     * Match IP
     * --------------------------------------------------------------------------
     * If true, session is tied to user’s IP address.
     */
    public bool $matchIP = false;

    /**
     * --------------------------------------------------------------------------
     * Regenerate ID
     * --------------------------------------------------------------------------
     * Interval (in minutes) for regenerating session ID.
     */
    public int $regenerateTime = 30;
}
