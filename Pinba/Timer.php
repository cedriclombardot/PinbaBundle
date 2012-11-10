<?php

namespace Cedriclombardot\PinbaBundle\Pinba;

class Timer
{
    protected $context;

    protected $ressource = null;

    protected $isRunning = false;

    protected $tags = array();

    /**
    * Creates a new sfTimer instance.
    *
    * @param string $name The name of the timer
    */
    public function __construct(Context $context, $name = '')
    {
        $this->context = $context;
        $this->name = $name;
    }

    /**
    * Starts the timer.
    */
    public function startTimer()
    {
        $this->ressource = $this->context->start($this->tags);
        $this->isRunning = true;
    }

    /**
    *
    * Change the tags for the start timer
    * @param array $tags
    */
    public function setTags($tags=array())
    {
        $this->tags = $tags;
    }

    /**
    * Stops the timer and add the amount of time since the start to the total time.
    *
    * @return float Time spend for the last call
    */
    public function addTime()
    {
        if ($this->isRunning()) {
            $this->context->stop($this->ressource);
            $this->isRunning = false;
        }
    }

    protected function isRunning()
    {
        return $this->isRunning;
    }

}
