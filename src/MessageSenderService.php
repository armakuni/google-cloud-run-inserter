<?php


namespace Armakuni\Demo\PhpInserter;


use Google\Cloud\PubSub\PubSubClient;

class MessageSenderService
{
    /**
     * @var PubSubClient
     */
    private $pubSubClient;
    /**
     * @var string
     */
    private $topic;

    /**
     * CounterService constructor.
     * @param PubSubClient $pubSubClient
     * @param string $topic
     */
    public function __construct(PubSubClient $pubSubClient, string $topic)
    {
        $this->pubSubClient = $pubSubClient;
        $this->topic = $topic;
    }

    /**
     * @param string $message
     * @return void
     */
    public function sendMessage(string $message): void
    {
        $topic = $this->pubSubClient->topic($this->topic);
        if (!$topic->exists()) {
            $topic->create();
        }


        $topic->publish(['data' => $message]);
    }
}
