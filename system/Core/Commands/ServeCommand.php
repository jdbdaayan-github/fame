<?php
namespace System\Core\Commands;

use System\Core\Commands\CommandInterface;

class ServeCommand implements CommandInterface
{
    public function handle(array $args): void
    {
        $host = $args[0] ?? '127.0.0.1';
        $port = $args[1] ?? '8000';
        $publicPath = realpath(__DIR__ . '/../../../public');

        echo "Starting PHP development server at http://{$host}:{$port}\n";
        echo "Document root is {$publicPath}\n";
        echo "Press Ctrl+C to stop the server\n\n";

        passthru("php -S {$host}:{$port} -t {$publicPath}");
    }
}
