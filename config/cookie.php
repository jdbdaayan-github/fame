<?php
namespace App\Config;

class Cookie
{
    /**
     * Cookie default lifetime (in minutes).
     */
    public int $lifetime = 120;

    /**
     * Default path where cookies are available.
     */
    public string $path = '/';

    /**
     * Default domain for cookies.
     */
    public string $domain = '';

    /**
     * Secure flag (true = only via HTTPS).
     */
    public bool $secure = false;

    /**
     * HttpOnly flag (true = inaccessible to JS).
     */
    public bool $httpOnly = true;

    /**
     * SameSite attribute: "Lax", "Strict", "None"
     */
    public string $sameSite = 'Lax';
}
