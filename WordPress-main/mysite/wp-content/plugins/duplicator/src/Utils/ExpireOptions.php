<?php

/**
 * Expire options
 *
 * @package   Duplicator
 * @copyright (c) 2022, Snap Creek LLC
 */

namespace Duplicator\Utils;

use Duplicator\Libs\Snap\JsonSerialize\JsonSerialize;
use Duplicator\Libs\Snap\SnapDB;

final class ExpireOptions
{
    const OPTION_PREFIX = 'duplicator_expire_';

    /** @var array<string, array{expire: int, value: mixed}> */
    private static $cacheOptions = array();


    /**
     * Sets/updates the value of a expire option.
     *
     * You do not need to serialize values. If the value needs to be serialized,
     * then it will be serialized before it is set.
     *
     * @param string $key        Expire option key.
     * @param mixed  $value      Option  value.
     * @param int    $expiration Optional. Time until expiration in seconds. Default 0 (no expiration).
     *
     * @return bool True if the value was set, false otherwise.
     */
    public static function set($key, $value, $expiration = 0)
    {
        $time = ($expiration > 0 ? time() + $expiration : 0);

        self::$cacheOptions[$key] = array(
            'expire' => $time,
            'value'  => $value,
        );

        return update_option(self::OPTION_PREFIX . $key, JsonSerialize::serialize(self::$cacheOptions[$key]), true);
    }

    /**
     * Retrieves the value of a expire option.
     *
     * If the option does not exist, does not have a value, or has expired,
     * then the return value will be false.
     *
     * @param string $key     Expire option key.
     * @param mixed  $default Return this value if option don\'t exists os is expired
     *
     * @return mixed Value of transient.
     */
    public static function get($key, $default = false)
    {
        if (!isset(self::$cacheOptions[$key])) {
            if (($option = get_option(self::OPTION_PREFIX . $key)) == false) {
                self::$cacheOptions[$key] = self::unexistsKeyValue();
            } else {
                self::$cacheOptions[$key] = JsonSerialize::unserialize($option);
            }
        }

        if (self::$cacheOptions[$key]['expire'] < 0) {
            // don't exists the wp-option
            return $default;
        }

        if (self::$cacheOptions[$key]['expire'] > 0 && self::$cacheOptions[$key]['expire'] < time()) {
            // if 0 don't expire so check only if time is > 0
            self::delete($key);
            return $default;
        }

        return self::$cacheOptions[$key]['value'];
    }

    /**
     * This function returns the value of the option or false if it has expired. In case the option has expired then it is updated.
     * It does the same thing as a get and a set but with one less query.
     *
     * @param string $key        Expire option key.
     * @param mixed  $value      Option  value.
     * @param int    $expiration Optional. Time until expiration in seconds. Default 0 (no expiration).
     *
     * @return mixed Value of transient.
     */
    public static function getUpdate($key, $value, $expiration = 0)
    {
        if (!isset(self::$cacheOptions[$key])) {
            if (($option = get_option(self::OPTION_PREFIX . $key)) == false) {
                self::$cacheOptions[$key] = self::unexistsKeyValue();
            } else {
                self::$cacheOptions[$key] = JsonSerialize::unserialize($option);
            }
        }

        if (self::$cacheOptions[$key]['expire'] < time()) {
            self::set($key, $value, $expiration);
            return false;
        }

        return self::$cacheOptions[$key]['value'];
    }

    /**
     * Deletes a option
     *
     * @param string $key Expire option key. Expected to not be SQL-escaped.
     *
     * @return bool True if the option was deleted, false otherwise.
     */
    public static function delete($key)
    {
        if (delete_option(self::OPTION_PREFIX . $key)) {
            self::$cacheOptions[$key] = self::unexistsKeyValue();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete all options
     *
     * @return bool
     */
    public static function deleteAll()
    {
        /** @var \wpdb $wpdb */
        global $wpdb;

        $optionsTableName = $wpdb->base_prefix . "options";
        /** @var literal-string */
        $prepare = 'SELECT `option_name` FROM `' . $optionsTableName . '` WHERE `option_name` REGEXP %s';
        /** @var string */
        $query          = $wpdb->prepare(
            $prepare,
            SnapDB::quoteRegex(self::OPTION_PREFIX)
        );
        $dupOptionNames = $wpdb->get_col($query);

        foreach ($dupOptionNames as $dupOptionName) {
            delete_option($dupOptionName);
        }
        self::$cacheOptions = array();

        return true;
    }

    /**
     * Return value for unexists key option
     *
     * @return array{expire: int, value: false}
     */
    private static function unexistsKeyValue()
    {
        return array(
            'expire' => -1,
            'value'  => false,
        );
    }
}
