<?php
if (!defined('ABSPATH')) {
    exit;
}

function bilo_popups_default_settings() {
    return [
        'enable_popup'          => false,
        'icon'                  => '🎉',
        'popup_title'           => 'Special Limited Offer',
        'title_color'           => '#ffffff',
        'popup_content'         => '<p style="font-size:18px;">Get <strong>20% OFF</strong> your first cleaning service today!</p>',
        'text_color'            => '#dddddd',
        'popup_button_text'     => 'Get Offer Now',
        'popup_button_link'     => '#',
        'popup_trigger'         => 'time',
        'popup_delay'           => 7000,
        'popup_background'      => '#0f0f2d',
        'popup_accent'          => '#00f5ff',
        'show_countdown'        => false,
        'countdown_end_date'    => gmdate('Y-m-d H:i', strtotime('+3 days')),
        'show_branding'         => false,
        'close_prevent_reopen'  => true,
        'show_desktop'          => true,
        'show_tablet'           => true,
        'show_mobile'           => true,
        'show_on_pages'         => 'all',
        'specific_pages'        => '',
        'popups'                => [],                    // ← кома додана
        'popup_width'           => '460px',
        'popup_height'          => 'auto',
    ];
}

$bilo_popups_settings = get_option('bilohash_smart_popups_settings', bilo_popups_default_settings());