<?php

namespace Reactphp\App\realTimeChat;

use Colors\Color;
use Ds\Set;
use React\Socket\ConnectionInterface;

class ConnectionsPool
{
    public function __construct(
        private Set $connections = new Set(),
        private array $connectionsName = [],
    ) {}

    public function add(ConnectionInterface $connection) {
        $connection->write((new Color("Welcome to chat" . PHP_EOL))->fg('green'));

        $connection->write('Enter your name: ');
        $this->setConnectionName($connection,  '');

        $this->sendAll('New member joined the chat' . PHP_EOL);

        $this->connections->add($connection);

        $this->initEvents($connection);
    }

    private function getConnectionName(ConnectionInterface $connection)
    {
        return $this->connectionsName[spl_object_id($connection)];
    }

    private function setConnectionName(ConnectionInterface $connection, string $name)
    {
        $this->connectionsName[spl_object_id($connection)] = $name;
    }

    public function sendAll(mixed $message, ?ConnectionInterface $except = null): void
    {
        /** @var ConnectionInterface $connection */
        foreach($this->connections as $connection) {
            if($connection !== $except) {
                $connection->write($message);
            }
        }
    }

    /**
     * @param ConnectionInterface $connection
     * @return void
     */
    public function initEvents(ConnectionInterface $connection): void
    {
        $connection->on('data', function ($data) use ($connection) {
            $name = $this->getConnectionName($connection);

            if (empty($name)) {
                $this->addNewMember($connection, $data);
                return;
            }

            $this->sendAll((new Color("{$name}: "))->bold() . "{$data}", $connection);
        });

        $connection->on('close', function () use ($connection) {
            $name = $this->getConnectionName($connection);
            unset($this->connectionsName[$name]);
            $this->connections->remove($connection);

            $this->sendAll((new Color("{$name} leaved the chat" . PHP_EOL))->fg('red'));
        });
    }

    private function addNewMember(ConnectionInterface $connection, string $name)
    {
        $name = str_replace(["\n", "\r"], '', $name);
        $this->setConnectionName($connection, $name);
        $this->sendAll((new Color("User $name joins the chat" . PHP_EOL))->fg('blue'), $connection);
    }
}