<?php
namespace System\Core\Commands;

use System\Core\Commands\CommandInterface;

class HelpCommand implements CommandInterface
{
    public function handle(array $args): void
    {
        echo "PHP Fame CLI\n";
        echo "Usage:\n";
        echo "  php fame <command> [options]\n\n";
        echo "Available commands:\n";
        echo "  help            Show this help message\n";
        echo "  serve           Start PHP development server\n";
        echo "  routes          List all registered routes\n";
        echo "  make:controller Create a new controller (future)\n";
        echo "  migrate         Run database migrations (future)\n";
    }
}
