Symfony Queue
========= 

A library to decorate the symfony Event dispatcher and make it works with any queue manager.  

:exclamation: For while, this library is under development, it's an insane idea and just for science! 

# Motivation 

Symfony was born using event oriented programming, and many times we want to 
use a listener to enqueue a job in a queue manager (eg:. rabbitmq, activemq, amazon sqs...). 

So, why the listener is not the consumer itself, and a event dispatch doest not create a job in
that queue? 

# How to use 

```php
<?php

$app->share($app->extend('dispatcher',  function ($dispatcher) use ($app) {
    $transformer = new \Cloudson\SymfonyQueue\Transformer\ByInterfaceTransformer(
        $app['serializer']
    );
    $queue = new \Cloudson\SymfonyQueue\Queue\RabbitMQ([
        'host' => 'yourhost',
        'port' => 'port',
        'username' => 'username',
        'password' => 'password',
            
    ]);
    
    return new \Cloudson\SymfonyQueue\QueueManager(
        $dispatcher,
        $queue,
        $transformer
    );
}));  


```

And booommmm!!!


