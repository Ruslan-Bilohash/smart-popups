<?php
/**
 * Plugin Name:       Bilohash Smart Popups
 * Plugin URI:        https://github.com/Ruslan-Bilohash/bilohash-smart-popups
 * Description:       Advanced popup builder with device targeting, icons, colors, page rules and countdown timers.
 * Version:           1.0.0
 * Author:            Ruslan Bilohash
 * Author URI:        https://bilohash.com
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       bilohash-smart-popups
 * Domain Path:       /languages
 * Requires at least: 6.4
 * Tested up to:      7.0
 * Requires PHP:      7.4
 */

if (!defined('ABSPATH')) {
    exit;
}

define('BILO_POPUPS_VERSION', '1.0.0');
define('BILO_POPUPS_PATH', plugin_dir_path(__FILE__));
define('BILO_POPUPS_URL',  plugin_dir_url(__FILE__));
define('BILO_POPUPS_SLUG', 'bilohash-smart-popups');

require_once BILO_POPUPS_PATH . 'includes/config.php';
require_once BILO_POPUPS_PATH . 'includes/admin-settings.php';
require_once BILO_POPUPS_PATH . 'includes/popup.php';

function bilo_popups_activate() {
    if (!get_option('bilohash_smart_popups_settings')) {
        add_option('bilohash_smart_popups_settings', bilo_popups_default_settings());
    }
}
register_activation_hook(__FILE__, 'bilo_popups_activate');

function bilo_popups_admin_menu() {
    add_menu_page(
        __('Smart Popups', 'bilohash-smart-popups'),
        __('Smart Popups', 'bilohash-smart-popups'),
        'manage_options',
        BILO_POPUPS_SLUG,
        'bilo_popups_settings_page',
        'dashicons-welcome-widgets-menus',
        58
    );
}
add_action('admin_menu', 'bilo_popups_admin_menu');

function bilo_popups_action_links($links) {
    $settings_link = '<a href="' . esc_url(admin_url('admin.php?page=' . BILO_POPUPS_SLUG)) . '"><strong>' . esc_html__('Settings', 'bilohash-smart-popups') . '</strong></a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'bilo_popups_action_links');