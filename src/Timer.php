<?php
namespace Bankiru\Yii\Profiling\Pinba;

class Timer
{
    /**
     * @return bool
     */
    private static function isEnabled()
    {
        static $enabled;

        if ($enabled === null) {
            $enabled = extension_loaded('pinba');
        }

        return $enabled;
    }

    /**
     * Creates and starts new timer.
     *
     * @param array $tags an array of tags and their values in the form of "tag" => "value". Cannot contain numeric indexes for obvious reasons.
     * @param array $data optional array with user data, not sent to the server. "hit_count" - optional hit_count parameter, set to 1 be default.
     * @return resource new timer resource.
     */
    public static function start(array $tags, array $data = [])
    {
        if (!self::isEnabled()) return;

        return pinba_timer_start($tags, $data);
    }

    /**
     * Stops the timer.
     *
     * @param resource $timer valid timer resource.
     * @return bool Returns true on success and false on failure (if the timer has already been stopped).
     */
    public static function stop($timer)
    {
        if (!self::isEnabled()) return;

        return pinba_timer_stop($timer);
    }

    /**
     * Creates new timer. This timer is already stopped and have specified time value.
     *
     * @param array $tags an array of tags and their values in the form of "tag" => "value". Cannot contain numeric indexes for obvious reasons.
     * @param $value timer value for new timer.
     * @param array $data optional array with user data, not sent to the server.
     * @return resource
     */
    public static function add(array $tags, $value, array $data = [])
    {
        if (!self::isEnabled()) return;

        return pinba_timer_add($tags, $value, $data);
    }

    /**
     * Deletes the timer.
     *
     * @param resource $timer valid timer resource.
     * @return bool Returns true on success and false on failure.
     */
    public static function delete($timer)
    {
        if (!self::isEnabled()) return;

        return pinba_timer_delete($timer);
    }

    /**
     * Merges $tags array with the timer tags replacing existing elements.
     *
     * @param resource $timer valid timer resource
     * @param array $tags an array of tags.
     * @return bool Returns true on success and false on failure.
     */
    public static function tagsMerge($timer, array $tags)
    {
        if (!self::isEnabled()) return;

        return pinba_timer_tags_merge($timer, $tags);
    }

    /**
     * Replaces timer tags with the passed $tags array.
     *
     * @param resource $timer valid timer resource
     * @param array $tags an array of tags.
     * @return bool Returns true on success and false on failure.
     */
    public static function tagsReplace($timer, array $tags)
    {
        if (!self::isEnabled()) return;

        return pinba_timer_tags_replace($timer, $tags);
    }

    /**
     * Merges $data array with the timer user data replacing existing elements.
     *
     * @param resource $timer valid timer resource
     * @param array $data
     * @return bool Returns true on success and false on failure.
     */
    public static function dataMerge($timer, array $data)
    {
        if (!self::isEnabled()) return;

        return pinba_timer_data_merge($timer, $data);
    }

    /**
     * Replaces timer user data with the passed $data array.
     * Use NULL value to reset user data in the timer.
     *
     * @param resource $timer valid timer resource
     * @param array $data
     * @return bool Returns true on success and false on failure.
     */
    public static function dataReplace($timer, array $data)
    {
        if (!self::isEnabled()) return;

        return pinba_timer_data_replace($timer, $data);
    }

    /**
     * Returns timer data.
     *
     * @param resource $timer valid timer resource
     * @return array
     */
    public static function getInfo($timer)
    {
        if (!self::isEnabled()) return;

        return pinba_timer_get_info($timer);
    }

    /**
     * Stops all running timers.
     *
     * @return bool
     */
    public static function stopAll()
    {
        if (!self::isEnabled()) return;

        return pinba_timers_stop();
    }

    /**
     * Get all timers info.
     *
     * @param int $flag
     * @return array
     */
    public static function getAll($flag = PINBA_ONLY_STOPPED_TIMERS)
    {
        if (!self::isEnabled()) return;

        return pinba_timers_get($flag);
    }
}