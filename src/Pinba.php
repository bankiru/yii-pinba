<?php
namespace Bankiru\Yii\Profiling\Pinba;

class Pinba extends \CApplicationComponent
{
    /** @var bool */
    private $enabled;

    /** @var bool */
    private $fixScriptName = true;

    /**
     * @var array
     */
    private $profileEvents = [];

    /**
     * Pinba constructor.
     */
    public function __construct()
    {
        $this->enabled = extension_loaded('pinba');
    }

    /**
     * @param boolean $fixScriptName
     * @return Pinba
     */
    public function setFixScriptName($fixScriptName)
    {
        $this->fixScriptName = $fixScriptName;
        return $this;
    }

    /**
     * @param array $profileEvents
     * @return Pinba
     */
    public function setProfileEvents(array $profileEvents)
    {
        $this->profileEvents = $profileEvents;
        return $this;
    }

    /**
     * Returns all request data (including timers user data).
     *
     * @return array
     */
    public function getInfo() {
        return $this->enabled ? pinba_get_info() : null;
    }

    /**
     * Set custom script name instead of $_SERVER['SCRIPT_NAME'] used by default.
     * Useful for those using front controllers, when all requests are served by one PHP script.
     *
     * @param string $scriptName
     * @return Pinba
     */
    public function setScriptName($scriptName)
    {
        if ($this->enabled && $scriptName) {
            pinba_script_name_set($scriptName);
            $this->fixScriptName = false;
        }

        return $this;
    }

    /**
     * Set custom hostname instead of the result of gethostname() used by default.
     *
     * @param string $hostName
     * @return Pinba
     */
    public function setHostName($hostName)
    {
        if ($this->enabled && $hostName) {
            pinba_hostname_set($hostName);
        }

        return $this;
    }

    /**
     * Set custom server name instead of $_SERVER['SERVER_NAME'] used by default.
     *
     * @param string $serverName
     * @return Pinba
     */
    public function setServerName($serverName)
    {
        if ($this->enabled && $serverName) {
            pinba_server_name_set($serverName);
        }

        return $this;
    }

    /**
     * Set request schema (HTTP/HTTPS/whatever).
     *
     * @param string $schema
     * @return Pinba
     */
    public function setSchema($schema)
    {
        if ($this->enabled && $schema) {
            pinba_schema_set($schema);
        }

        return $this;
    }

    /**
     * Set custom request time.
     *
     * @param float $requestTime
     */
    public function setRequestTime($requestTime)
    {
        if ($this->enabled && $requestTime !== null) {
            pinba_request_time_set($requestTime);
        }
    }

    /**
     * @param string $scriptName
     * @param int $flags
     */
    public function flush($scriptName = null, $flags = PINBA_FLUSH_ONLY_STOPPED_TIMERS)
    {
        if ($this->enabled) {
            pinba_flush($scriptName, $flags);
        }
    }

    public function init()
    {
        if (!$this->enabled) return;

        if ($this->fixScriptName) {
            $scriptName = php_sapi_name() === 'cli'
                ? implode(' ', $_SERVER['argv'])
                : ('/' . \Yii::app()->urlManager->parseUrl(\Yii::app()->request));

            $this->setScriptName($scriptName);
        }

        $eventHandler = new EventHandler();
        $eventHandler->attach(\Yii::app(), 'request', 'onBeginRequest', 'onEndRequest');

        foreach ($this->profileEvents as $event) {
            if (is_string($event[0])) {
                $event[0] = \Yii::app()->getComponent($event[0]);
            }

            if (!$event[0] instanceof \CComponent) {
                throw new \InvalidArgumentException(var_export($event[0], true) . ' is not string or instance of CComponent');
            }

            call_user_func_array([$eventHandler, 'attach'], $event);
        }

        \Yii::app()->attachEventHandler('onEndRequest', function(){
            Timer::stopAll();
        });
    }
}