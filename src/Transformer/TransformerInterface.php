<?php

namespace Cloudson\SymfonyQueue\Transformer; 

use Symfony\Component\EventDispatcher\Event;

interface TransformerInterface
{
    public function transform(Event $event, $listener); 
}
