<?php

namespace Cloudson\SymfonyQueue\Transformer; 

interface EventJob
{
    public function getJobPayload(); 
}
