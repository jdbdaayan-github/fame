<?php
namespace System\Core;

class Cli
{
    protected static array $commands = [];

    /**
     * Run the CLI
     */
    public static function run(array $argv): void
    {
        self::registerCommands();

        $commandName = $argv[1] ?? 'help';
        $args = array_slice($argv, 2);

        if (!isset(self::$commands[$commandName])) {
            echo "Command '{$commandName}' not found.\n";
            self::call('help');
            exit(1);
        }

        $commandClass = self::$commands[$commandName];
        $commandInstance = new $commandClass();
        $commandInstance->handle($args);
    }

    /**
     * Register all CLI commands here
     */
    protected static function registerCommands(): void
    {
        self::$commands = [
            'help' => Commands\HelpCommand::class,
            'serve' => Commands\ServeCommand::class,
            'routes' => Commands\RoutesCommand::class,
            // future commands: make:controller, migrate, etc.
        ];
    }

    /**
     * Call a command programmatically
     */
    public static function call(string $command, array $args = []): void
    {
        if (isset(self::$commands[$command])) {
            $instance = new self::$commands[$command]();
            $instance->handle($args);
        }
    }
}
