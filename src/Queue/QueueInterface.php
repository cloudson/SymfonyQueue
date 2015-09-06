<?php

namespace Cloudson\SymfonyQueue\Queue; 

interface QueueInterface
{
    public function add(Job $job);
}
