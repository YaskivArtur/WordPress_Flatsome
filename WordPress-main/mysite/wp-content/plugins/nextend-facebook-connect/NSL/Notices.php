<?php

namespace NSL;

use NSL\Persistent\Persistent;
use WP_Error;

class Notices {

    private static $notices;

    private static $instance;

    public static function init() {
        if (self::$instance === null) {
            self::$instance = new self;
        }
    }

    private function __construct() {
        if (is_admin() || (isset($_GET['nsl-notice']) && $_GET['nsl-notice'] == 1)) {
            add_action('init', array(
                $this,
                'load'
            ), 11);

            if ((!empty($_SERVER['PHP_SELF']) && basename($_SERVER['PHP_SELF']) !== 'options-general.php') || empty($_GET['page']) || $_GET['page'] !== 'nextend-social-login') {
                add_action('admin_notices', array(
                    $this,
                    'admin_notices'
                ));
            }

            add_action('admin_print_footer_scripts', array(
                $this,
                'notices_fallback'
            ));
            add_action('wp_print_footer_scripts', array(
                $this,
                'notices_fallback'
            ));
        }
    }

    public function load() {
        self::$notices = maybe_unserialize(self::get());
        if (!is_array(self::$notices)) {
            self::$notices = array();
        }
    }

    private static function add($type, $message) {
        if (!isset(self::$notices[$type])) {
            self::$notices[$type] = array();
        }

        if (!in_array($message, self::$notices[$type])) {
            self::$notices[$type][] = $message;
        }

        self::set();
    }

    /**
     * @param $message string|WP_Error
     */
    public static function addError($message) {
        if (is_wp_error($message)) {
            foreach ($message->get_error_messages() as $m) {
                self::add('error', $m);
            }
        } else {
            self::add('error', $message);
        }
    }

    public static function getErrors() {
        if (isset(self::$notices['error'])) {

            $errors = self::$notices['error'];

            unset(self::$notices['error']);
            self::set();

            return $errors;
        }

        return false;
    }

    public static function addSuccess($message) {
        self::add('success', $message);
    }

    public static function displayNotices() {

        $html = self::getHTML();

        if (!empty($html)) {
            echo '<div class="nsl-admin-notices">' . $html . '</div>';
        }
    }

    public function admin_notices() {
        echo self::getHTML();
    }

    /**
     * Displays the non-displayed notices in lightbox as a fallback
     */
    public function notices_fallback() {

        $html = self::getHTML();

        if (!empty($html)) {
            ?>
            <div id="nsl-notices-fallback" onclick="this.parentNode.removeChild(this);">
                <?php echo $html;
                \NextendSocialLogin::noticeStyles();
                ?>
            </div>
            <?php
        }
    }

    private static function getHTML() {
        $html = '';
        if (isset(self::$notices['success'])) {
            foreach (self::$notices['success'] AS $message) {
                $html .= '<div class="updated"><p>' . $message . '</p></div>';
            }
        }

        if (isset(self::$notices['error'])) {
            foreach (self::$notices['error'] AS $message) {
                $html .= '<div class="error"><p>' . $message . '</p></div>';
            }
        }

        self::clear();

        return $html;
    }

    private static function get() {
        return Persistent::get('notices');
    }

    private static function set() {
        Persistent::set('notices', self::$notices);
    }

    public static function clear() {

        Persistent::delete('notices');
        self::$notices = array();
    }

    public static function hasErrors() {
        if (isset(self::$notices['error'])) {
            return true;
        }

        return false;
    }
}