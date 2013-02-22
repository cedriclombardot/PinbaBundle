<?php

namespace Cedriclombardot\PinbaBundle\Pinba;

use Symfony\Component\HttpFoundation\Request;

class Context
{
    protected $scriptNamePattern;

    /**
     * @param string $scriptNamePattern pattern to use to render server values from request object
     * @see PinbaBundle/Resources/config/services.xml
     */
    public function __construct($scriptNamePattern)
    {
        $this->scriptNamePattern = $scriptNamePattern;
    }

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

    /**
     * builds script name from given pattern, and request server vars
     * @param  Request $request
     * @return string
     */
    protected function extractScriptName(Request $request)
    {
        $computedScriptName = $this->scriptNamePattern;

        // given pattern doesnt have dynamic vars
        if (!preg_match_all('#\{([a-zA-Z_]+)\}#', $this->scriptNamePattern, $matches, PREG_SET_ORDER)) {
            return $computedScriptName;
        }

        foreach ($matches as $match) {
            list($patternOrigin, $serverVar) = $match;
            if (!$request->server->has($serverVar)) {
                throw new \InvalidArgumentException('Given argument "'.$serverVar.'" is not an existing _SERVER var. Check your configuration.');
            }
            $computedScriptName = str_replace(
                $patternOrigin,
                $request->server->get($serverVar),
                $computedScriptName
            );
        }

        return $computedScriptName;
    }
}
