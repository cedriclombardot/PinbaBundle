<?php

namespace Cedriclombardot\PinbaBundle\Pinba;

use Symfony\Component\HttpFoundation\Request;

class Context
{
    /**
    * Change the pinba_script_name_set
    * @param string $script_name the new script name
    *
    * @return Context the current context
    */
    public function setScriptName($script_name)
    {
        if ($this->isEnabled()) {
            pinba_script_name_set($script_name);
        }

        return $this;
    }

    /**
    * Start pinba timer
    * @see pinba_timer_start
    * execute pinba_timer_start
    * @param $tags array of tags
    *
    * @return $ressource timer
    */
    public function start($tags=null)
    {
        if ($this->isEnabled()) {
            return pinba_timer_start($tags);
        }

        return false;
    }

    /**
    * Stop pinba timer
    * @see pinba_timer_stop
    * execute pinba_timer_stop
    * @param Resource $timer
    *
    * @return boolean
    */
    public function stop($timer)
    {
        if ($this->isEnabled()) {
            return pinba_timer_stop($timer);
        }

        return false;
    }

    public function startTimerForRequest(Request $request)
    {
        $this->setScriptName($request->getPathInfo());

        $option = array(
            "pathInfo" => $request->getPathInfo(),
        );

        $this->start($option);
    }

    /**
    * Check if pinba is enabled
    *
    * @return boolean
    */
    protected function isEnabled()
    {
        return extension_loaded('pinba');
    }
}
