<?php

namespace NSL\Persistent;

use NSL\Persistent\Storage\Session;
use NSL\Persistent\Storage\StorageAbstract;
use NSL\Persistent\Storage\Transient;
use WP_User;

require_once dirname(__FILE__) . '/Storage/Abstract.php';
require_once dirname(__FILE__) . '/Storage/Session.php';
require_once dirname(__FILE__) . '/Storage/Transient.php';

class Persistent {

    private static $instance;

    /** @var StorageAbstract */
    private $storage;

    public function __construct() {
        self::$instance = $this;
        add_action('init', array(
            $this,
            'init'
        ), 0);

        add_action('nsl_before_wp_login', function () {
            add_action('wp_login', array(
                $this,
                'transferSessionToUser'
            ), 10, 2);
        });
    }

    public function init() {
        if ($this->storage === NULL) {
            if (is_user_logged_in()) {
                $this->storage = new Transient();
            } else {
                $this->storage = new Session();
            }
        }
    }

    public static function set($key, $value) {
        if (self::$instance->storage) {
            self::$instance->storage->set($key, $value);
        }
    }

    public static function get($key) {
        if (self::$instance->storage) {
            return self::$instance->storage->get($key);
        }

        return false;
    }

    public static function delete($key) {
        if (self::$instance->storage) {
            self::$instance->storage->delete($key);
        }
    }

    /**
     * @param          $user_login
     * @param WP_User  $user
     */
    public function transferSessionToUser($user_login, $user = null) {

        if (!$user) { // For do_action( 'wp_login' ) calls that lacked passing the 2nd arg.
            $user = get_user_by('login', $user_login);
        }

        $newStorage = new Transient($user->ID);
        /**
         * $this->storage might be NULL if init action not called yet
         */
        if ($this->storage !== NULL) {
            $newStorage->transferData($this->storage);
        }

        $this->storage = $newStorage;
    }

    public static function clear() {
        if (self::$instance->storage) {
            self::$instance->storage->clear();
        }
    }
}


new Persistent();