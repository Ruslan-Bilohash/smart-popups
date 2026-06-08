<?php
/**
 * Bilohash Smart Popups - Frontend Popup
 * Version: 1.0.0
 * Author: Ruslan Bilohash
 */

if (!defined('ABSPATH')) {
    exit;
}

function bilo_popups_enqueue_frontend() {
    $settings = get_option('bilohash_smart_popups_settings', bilo_popups_default_settings());

    if (empty($settings['enable_popup'])) {
        return;
    }

    // === Безпечна перевірка пристрою ===
    $is_mobile  = wp_is_mobile();
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) 
        ? sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])) 
        : '';

    $is_tablet = $is_mobile && (
        stripos($user_agent, 'iPad') !== false || 
        stripos($user_agent, 'Tablet') !== false
    );

    $show = false;

    if (!empty($settings['show_desktop']) && !$is_mobile) {
        $show = true;
    }
    if (!empty($settings['show_tablet']) && $is_tablet) {
        $show = true;
    }
    if (!empty($settings['show_mobile']) && $is_mobile && !$is_tablet) {
        $show = true;
    }

    if (!$show) {
        return;
    }

    wp_enqueue_script(
        'bilo-smart-popup',
        BILO_POPUPS_URL . 'assets/popup.js',
        [],
        BILO_POPUPS_VERSION,
        true
    );

    wp_localize_script('bilo-smart-popup', 'biloSmartPopup', [
        'icon'                => esc_html($settings['icon'] ?? '🎉'),
        'title'               => esc_html($settings['popup_title']),
        'title_color'         => esc_attr($settings['title_color'] ?? '#ffffff'),
        'content'             => wp_kses_post($settings['popup_content']),
        'text_color'          => esc_attr($settings['text_color'] ?? '#dddddd'),
        'button_text'         => esc_html($settings['popup_button_text']),
        'button_link'         => esc_url($settings['popup_button_link'] ?? '#'),
        'delay'               => (int) ($settings['popup_delay'] ?? 7000),
        'trigger'             => esc_attr($settings['popup_trigger'] ?? 'time'),
        'background'          => esc_attr($settings['popup_background']),
        'accent'              => esc_attr($settings['popup_accent']),
        'show_countdown'      => (bool) $settings['show_countdown'],
        'countdown_end'       => esc_attr($settings['countdown_end_date']),
        'show_branding'       => (bool) $settings['show_branding'],
        'prevent_reopen'      => (bool) $settings['close_prevent_reopen'],
		'popup_width'  => esc_attr($settings['popup_width'] ?? '460px'),
'popup_height' => esc_attr($settings['popup_height'] ?? 'auto'),
    ]);
}
add_action('wp_enqueue_scripts', 'bilo_popups_enqueue_frontend', 20);