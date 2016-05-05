<?php

require_once __DIR__ . '/vendor/autoload.php'; //change require 
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

Class Task
{
    function newTask($data)
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest'); //create connection to rabbitMQ server
        $channel = $connection->channel(); //V port for communication of data
        $channel->queue_declare('task_queue', false, true, false, false); //change the limit of the retries

        if (empty($data)) {
            echo $data = "ERROR: No data was passed";
        } else {
            $msg = new AMQPMessage($data,
                array('delivery_mode' => 2) # make message persistent
            );

            $channel->basic_publish($msg, '', 'task_queue');
            echo " [x] Sent ", $data, "\n";
        }
        $channel->close();
        $connection->close();
    }
}