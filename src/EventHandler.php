<?php
namespace Bankiru\Yii\Profiling\Pinba;

class EventHandler
{
    private $timerStack = [];

    /**
     * @param \CComponent $component
     * @param string $action
     * @param string $beginEventName
     * @param string $endEventName
     */
    public function attach(\CComponent $component, $action, $beginEventName, $endEventName)
    {
        $component->attachEventHandler($beginEventName, function (\CEvent $event) use ($action) {
            $this->handleBegin($event, $action);
        });

        $component->attachEventHandler($endEventName, function (\CEvent $event) use ($action) {
            $this->handleEnd($event, $action);
        });
    }

    /**
     * @param \CEvent $event
     * @param string $action
     */
    private function handleBegin(\CEvent $event, $action)
    {
        $timer = Timer::start($this->timerTags($event, $action), $event->params ?: []);
        $this->pushTimer($event, $action, $timer);
    }

    /**
     * @param \CEvent $event
     * @param string $action
     */
    private function handleEnd(\CEvent $event, $action)
    {

        if ($timer = $this->popTimer($event, $action)) {
            if ($event->params) {
                Timer::dataMerge($timer, $event->params);
            }
            Timer::stop($timer);
        }
    }

    /**
     * @param \CEvent $event
     * @param string $action
     * @param resource $timer
     */
    private function pushTimer(\CEvent $event, $action, $timer)
    {
        $key = $this->timerKey($event, $action);
        if (!isset($this->timerStack[$key])) {
            $this->timerStack[$key] = [];
        }

        array_push($this->timerStack[$key], $timer);
    }

    /**
     * @param \CEvent $event
     * @param string $action
     * @return resource|null
     */
    private function popTimer(\CEvent $event, $action)
    {
        $key = $this->timerKey($event, $action);
        if (!empty($this->timerStack[$key])) {
            return array_pop($this->timerStack[$key]);
        }

        return null;
    }

    /**
     * @param \CEvent $event
     * @param string $action
     * @return array
     */
    private function timerTags(\CEvent $event, $action)
    {
        return ['component' => get_class($event->sender), 'action' => $action];
    }

    /**
     * @param \CEvent $event
     * @param string $action
     * @return string
     */
    private function timerKey(\CEvent $event, $action)
    {
        return get_class($event->sender) . '@' . $action;
    }
}