<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20.09.17
 * Time: 13:16
 */

namespace BotBundle\Websocket;


use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class WebSocketApplication implements MessageComponentInterface
{
    public function onOpen(ConnectionInterface $conn)
    {
        // TODO: Implement onOpen() method.
    }

    public function onClose(ConnectionInterface $conn)
    {
        // TODO: Implement onClose() method.
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $messageData = $msg;
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        // TODO: Implement onError() method.
    }
}