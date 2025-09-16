<?php
namespace System\Core\Commands;

use System\Core\Commands\CommandInterface;

class RoutesCommand implements CommandInterface
{
    public function handle(array $args): void
    {
        echo "Route listing not implemented yet.\n";
        // Future: load routes/web.php & routes/api.php, then print each
    }
}
