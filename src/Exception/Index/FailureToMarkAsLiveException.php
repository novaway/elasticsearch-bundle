<?php
namespace Novaway\ElasticsearchBundle\Exception\Index;

class FailureToMarkAsLiveException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct('Failure to mark index as live : ' . $message);
    }
}
