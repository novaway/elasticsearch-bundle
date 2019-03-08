<?php
declare(strict_types=1);


namespace Novaway\ElasticsearchBundle\Traits;


use Symfony\Component\EventDispatcher\EventDispatcherInterface;

trait EventDispatcherAwareTrait
{
    /** @var EventDispatcherInterface */
    protected $dispatcher;

    /**
     * @required
     */
    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
}