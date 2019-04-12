<?php

require __DIR__ . "/vendor/autoload.php";

use Armakuni\Demo\PhpInserter\ConfigFactory;
use Armakuni\Demo\PhpInserter\MessageSenderService;
use Armakuni\Demo\PhpInserter\StackdriverExporterFactory;
use Armakuni\Demo\PhpInserter\TraceService;
use Google\Cloud\PubSub\PubSubClient;
use OpenCensus\Trace\Tracer;


$googleConfig = (new ConfigFactory())->build();
$exporter = (new StackdriverExporterFactory($googleConfig))->build();
(new TraceService($exporter))->start();


$messageSenderService = Tracer::inSpan(
    ['name' => 'init'],
    function () use ($googleConfig) {
        $pubSubClient = new PubSubClient($googleConfig);
        return new MessageSenderService($pubSubClient, "example-topic");
    }
);

$count = Tracer::inSpan(
    ['name' => 'send-message'],
    function () use ($messageSenderService) {
        return $messageSenderService->sendMessage("Hello, it's " . date("c"));
    }
);

Tracer::inSpan(
    ['name' => 'render'],
    function () {
        echo "sent";
    }
);
