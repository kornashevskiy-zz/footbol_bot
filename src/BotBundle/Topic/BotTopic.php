<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 19.09.17
 * Time: 18:04
 */

namespace BotBundle\Topic;


use Gos\Bundle\WebSocketBundle\Router\WampRequest;
use Gos\Bundle\WebSocketBundle\Topic\TopicInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;

class BotTopic implements TopicInterface
{
    public function onSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
        //this will broadcast the message to ALL subscribers of this topic.
        $topic->broadcast('message', ['msg' => $connection->resourceId . " has joined " . $topic->getId()]);
    }

    public function onUnSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
        $test = 0;
        $topic->broadcast('message', ['msg' => $connection->resourceId . " has left " . $topic->getId()]);
    }

    public function onPublish(ConnectionInterface $connection, Topic $topic, WampRequest $request, $event, array $exclude, array $eligible)
    {
        $test = 0;
        $topic->broadcast('publishMessage',[
            'msg' => $event,
        ]);
    }

    public function getName()
    {
        $test = 0;
        return 'app.topic.service';
    }

}