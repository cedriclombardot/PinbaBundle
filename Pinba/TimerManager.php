<?php

namespace Cedriclombardot\PinbaBundle\Pinba;

class TimerManager
{
    protected $context;

    protected static $instance;

    protected $timers = array();

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public static function createInstance(Context $context)
    {
        self::$instance = $manager = new self($context);
    }

    public static function getInstance()
    {
        return self::$instance;
    }

    /**
    * Gets a sfPinbaTimer instance.
    *
    * It returns the timer named $name or create a new one if it does not exist.
    *
    * @param string $name The name of the timer
    *
    * @return sfPinbaTimer The timer instance
    */
    public function getTimer($name, $tags=array())
    {
        $this->timers[$name] = new Timer($this->context, $name);
        $this->timers[$name]->setTags($tags);

        $this->timers[$name]->startTimer();

        return $this->timers[$name];
    }
}
