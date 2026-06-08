<?php
/**
 * Bilohash Smart Popups - Settings Page
 * Version: 1.0.0
 * Author: Ruslan Bilohash
 */

if (!defined('ABSPATH')) {
    exit;
}

// Вимикаємо "зомбі" TinyMCE плагіни
add_filter('mce_external_plugins', function($plugins) {
    if (isset($plugins['aich_classic_plugin'])) {
        unset($plugins['aich_classic_plugin']);
    }
    return $plugins;
}, 999);

function bilo_popups_settings_page() {
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('Insufficient permissions.', 'bilohash-smart-popups'));
    }

    $settings = get_option('bilohash_smart_popups_settings', bilo_popups_default_settings());

    if (isset($_POST['bilo_popups_save'])) {
        check_admin_referer('bilo_popups_save_settings');

        $new_settings = [
            'enable_popup'          => !empty($_POST['enable_popup']),
            'icon'                  => sanitize_text_field(wp_unslash($_POST['icon'] ?? '🎉')),
            'popup_title'           => sanitize_text_field(wp_unslash($_POST['popup_title'] ?? '')),
            'title_color'           => sanitize_hex_color(wp_unslash($_POST['title_color'] ?? '#ffffff')),
            'popup_content'         => wp_kses_post(wp_unslash($_POST['popup_content'] ?? '')),
            'text_color'            => sanitize_hex_color(wp_unslash($_POST['text_color'] ?? '#dddddd')),
            'popup_button_text'     => sanitize_text_field(wp_unslash($_POST['popup_button_text'] ?? '')),
            'popup_button_link'     => esc_url_raw(wp_unslash($_POST['popup_button_link'] ?? '#')),
            'popup_trigger'         => sanitize_text_field(wp_unslash($_POST['popup_trigger'] ?? 'time')),
            'popup_delay'           => absint($_POST['popup_delay'] ?? 7000),
            'popup_background'      => sanitize_hex_color(wp_unslash($_POST['popup_background'] ?? '#0f0f2d')),
            'popup_accent'          => sanitize_hex_color(wp_unslash($_POST['popup_accent'] ?? '#00f5ff')),
            'show_countdown'        => !empty($_POST['show_countdown']),
            'countdown_end_date'    => sanitize_text_field(wp_unslash($_POST['countdown_end_date'] ?? '')),
            'show_branding'         => !empty($_POST['show_branding']),
            'close_prevent_reopen'  => !empty($_POST['close_prevent_reopen']),
            'show_desktop'          => !empty($_POST['show_desktop']),
            'show_tablet'           => !empty($_POST['show_tablet']),
            'show_mobile'           => !empty($_POST['show_mobile']),
            'show_on_pages'         => sanitize_text_field(wp_unslash($_POST['show_on_pages'] ?? 'all')),
            'specific_pages'        => sanitize_text_field(wp_unslash($_POST['specific_pages'] ?? '')),
            'popup_width'           => sanitize_text_field(wp_unslash($_POST['popup_width'] ?? '460px')),
            'popup_height'          => sanitize_text_field(wp_unslash($_POST['popup_height'] ?? 'auto')),
        ];

        update_option('bilohash_smart_popups_settings', $new_settings);

        echo '<div class="notice notice-success is-dismissible"><p><strong>✅ Settings saved successfully!</strong></p></div>';
    }

    $settings = get_option('bilohash_smart_popups_settings', bilo_popups_default_settings());
    ?>
    <div class="wrap bilo-popups-settings">

        <div class="bilo-popups-header">
            <div class="header-left">
                <span class="dashicons dashicons-welcome-widgets-menus bilo-logo-icon"></span>
                <div>
                    <h1><?php esc_html_e('Bilohash Smart Popups', 'bilohash-smart-popups'); ?></h1>
                    <p class="version-info">Version <?php echo esc_html(BILO_POPUPS_VERSION); ?> • Professional</p>
                </div>
            </div>
            <div class="header-right">
                <a href="https://github.com/Ruslan-Bilohash/bilohash-smart-popups" target="_blank" class="header-btn">🐙 GitHub</a>
                <a href="https://bilohash.com" target="_blank" class="header-btn">🌐 Website</a>
                <a href="https://bilohash.com/donate.php" target="_blank" class="pro-btn">❤️ Support</a>
            </div>
        </div>

        <form method="post">
            <?php wp_nonce_field('bilo_popups_save_settings'); ?>

            <h2 class="nav-tab-wrapper">
                <a href="#" class="nav-tab nav-tab-active" data-tab="general">🛠 General</a>
                <a href="#" class="nav-tab" data-tab="content">📝 Content & Design</a>
                <a href="#" class="nav-tab" data-tab="triggers">⏱ Triggers</a>
                <a href="#" class="nav-tab" data-tab="rules">📍 Display Rules</a>
                <a href="#" class="nav-tab" data-tab="feedback">💡 Feedback</a>
            </h2>

            <!-- General -->
            <div id="tab-general" class="tab-content">
                <div class="section-header">
                    <h2>🛠 General Settings</h2>
                    <p>Basic settings of the popup.</p>
                </div>

                <table class="form-table">
                    <tr>
                        <th>🟢 Enable Popup</th>
                        <td>
                            <input type="checkbox" name="enable_popup" <?php checked($settings['enable_popup']); ?>>
                            <p class="description">Turn the popup on or off on the entire site.</p>
                        </td>
                    </tr>
                    <tr>
                        <th>🤖 Popup Icon</th>
                        <td>
                            <input type="text" name="icon" value="<?php echo esc_attr($settings['icon']); ?>" style="font-size:38px; width:70px; text-align:center;">
                            <p class="description">Enter any emoji (example: 🎉 🔥 🛒 💎)</p>
                        </td>
                    </tr>
                    <tr>
                        <th>🔄 Prevent Reopen After Close</th>
                        <td>
                            <input type="checkbox" name="close_prevent_reopen" <?php checked($settings['close_prevent_reopen']); ?>>
                            <p class="description">If enabled, the popup will not appear again after the user closes it.</p>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Content & Design -->
            <div id="tab-content" class="tab-content" style="display:none;">
                <div class="section-header">
                    <h2>📝 Content & Design</h2>
                    <p>Customize the appearance and content of the popup.</p>
                </div>

                <table class="form-table">
                    <tr>
                        <th>Popup Title</th>
                        <td>
                            <input type="text" name="popup_title" value="<?php echo esc_attr($settings['popup_title']); ?>" style="width:100%;">
                            <p class="description">The main title of the popup.</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Title Color</th>
                        <td>
                            <input type="color" name="title_color" value="<?php echo esc_attr($settings['title_color']); ?>">
                            <p class="description">Color of the popup title.</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Popup Content</th>
                        <td><?php wp_editor($settings['popup_content'], 'popup_content', ['textarea_rows' => 8, 'media_buttons' => true]); ?>
                            <p class="description">Main text of the popup. You can use formatting and images.</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Text Color</th>
                        <td>
                            <input type="color" name="text_color" value="<?php echo esc_attr($settings['text_color']); ?>">
                            <p class="description">Color of the main text.</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Background Color</th>
                        <td>
                            <input type="color" name="popup_background" value="<?php echo esc_attr($settings['popup_background']); ?>">
                            <p class="description">Background color of the popup window.</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Accent Color</th>
                        <td>
                            <input type="color" name="popup_accent" value="<?php echo esc_attr($settings['popup_accent']); ?>">
                            <p class="description">Color of the button and accents.</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Popup Width</th>
                        <td>
                            <input type="text" name="popup_width" value="<?php echo esc_attr($settings['popup_width'] ?? '460px'); ?>" style="width:130px;">
                            <p class="description">Width of the popup (example: 460px, 80vw)</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Popup Height</th>
                        <td>
                            <input type="text" name="popup_height" value="<?php echo esc_attr($settings['popup_height'] ?? 'auto'); ?>" style="width:130px;">
                            <p class="description">Height of the popup (example: auto, 600px, 70vh)</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Button Text</th>
                        <td>
                            <input type="text" name="popup_button_text" value="<?php echo esc_attr($settings['popup_button_text']); ?>">
                            <p class="description">Text on the button.</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Button Link</th>
                        <td>
                            <input type="url" name="popup_button_link" value="<?php echo esc_attr($settings['popup_button_link']); ?>">
                            <p class="description">Link that opens when clicking the button.</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Enable Countdown Timer</th>
                        <td>
                            <input type="checkbox" name="show_countdown" <?php checked($settings['show_countdown']); ?>>
                            <p class="description">Show countdown timer in the popup.</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Countdown End Date</th>
                        <td>
                            <input type="datetime-local" name="countdown_end_date" value="<?php echo esc_attr($settings['countdown_end_date']); ?>">
                            <p class="description">Date and time when the countdown ends.</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Show Branding</th>
                        <td>
                            <input type="checkbox" name="show_branding" <?php checked($settings['show_branding']); ?>>
                            <p class="description">Show "Powered by Bilohash Smart Popups".</p>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Triggers -->
            <div id="tab-triggers" class="tab-content" style="display:none;">
                <div class="section-header">
                    <h2>⏱ Triggers & Timing</h2>
                    <p>Choose when the popup should appear.</p>
                </div>

                <table class="form-table">
                    <tr>
                        <th>Trigger Type</th>
                        <td>
                            <select name="popup_trigger">
                                <option value="time" <?php selected($settings['popup_trigger'], 'time'); ?>>Time Delay</option>
                                <option value="scroll" <?php selected($settings['popup_trigger'], 'scroll'); ?>>Scroll (50%)</option>
                                <option value="exit" <?php selected($settings['popup_trigger'], 'exit'); ?>>Exit Intent</option>
                            </select>
                            <p class="description">When the popup should appear to the visitor.</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Delay (ms)</th>
                        <td>
                            <input type="number" name="popup_delay" value="<?php echo esc_attr($settings['popup_delay']); ?>" step="500">
                            <p class="description">Delay before showing the popup (in milliseconds).</p>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Display Rules -->
            <div id="tab-rules" class="tab-content" style="display:none;">
                <div class="section-header">
                    <h2>📍 Display Rules</h2>
                    <p>Control on which devices and pages the popup should appear.</p>
                </div>

                <table class="form-table">
                    <tr>
                        <th>Show on Devices</th>
                        <td>
                            <label><input type="checkbox" name="show_desktop" <?php checked($settings['show_desktop']); ?>> Desktop</label><br>
                            <label><input type="checkbox" name="show_tablet" <?php checked($settings['show_tablet']); ?>> Tablet</label><br>
                            <label><input type="checkbox" name="show_mobile" <?php checked($settings['show_mobile']); ?>> Mobile</label>
                            <p class="description">Choose on which devices to show the popup.</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Show on Pages</th>
                        <td>
                            <select name="show_on_pages">
                                <option value="all" <?php selected($settings['show_on_pages'], 'all'); ?>>All Pages</option>
                                <option value="home" <?php selected($settings['show_on_pages'], 'home'); ?>>Homepage Only</option>
                                <option value="specific" <?php selected($settings['show_on_pages'], 'specific'); ?>>Specific Pages</option>
                            </select>
                            <input type="text" name="specific_pages" value="<?php echo esc_attr($settings['specific_pages']); ?>" placeholder="Page IDs or slugs" style="width:100%; max-width:500px; margin-top:8px;">
                            <p class="description">Choose where the popup should appear.</p>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Feedback Tab -->
<div id="tab-feedback" class="tab-content" style="display:none;">
    <h3>💡 Suggest Improvements</h3>
    
    <div style="background:#f0f8ff; padding:25px; border-radius:12px; border:2px solid #00aaff; margin-bottom:25px;">
        <h4 style="margin-top:0;">Found a bug or have an idea?</h4>
        <p>Contact me directly:</p>
        <ul style="line-height:1.9;">
            <li>→ <a href="https://github.com/Ruslan-Bilohash/bilohash-smart-popups/issues" target="_blank"><strong>GitHub Issues</strong></a> (recommended)</li>
            <li>→ Email: <a href="mailto:rbilohash@gmail.com">rbilohash@gmail.com</a></li>
        </ul>
    </div>

    <!-- Посилання на інший плагін -->
    <div style="background:#e8f5e9; padding:25px; border-radius:12px; border:2px solid #4caf50;">
        <h4 style="margin-top:0; color:#2e7d32;">🚀 Also check out my other plugin:</h4>
        <p><strong>Bilohash AI Chat Consultant</strong> — Modern Grok (xAI) + OpenAI powered chatbot for WordPress with Telegram notifications, model selection, conversation history and professional responses.</p>
        
        <a href="https://wordpress.org/plugins/bilohash-ai-chat-consultant/" 
           target="_blank" 
           class="button button-primary" 
           style="background:#4caf50; border:none; padding:12px 24px; font-size:15px; text-decoration:none;">
            🔗 View on WordPress.org
        </a>
        
        <a href="https://bilohash.com/ai/wordpress" 
           target="_blank" 
           class="button" 
           style="margin-left:10px; padding:12px 24px; font-size:15px;">
            🌐 Plugin Website
        </a>
    </div>
</div>

            <p class="submit">
                <input type="submit" name="bilo_popups_save" class="button button-primary button-large" value="💾 Save All Settings">
            </p>
        </form>
    </div>

    <?php
    wp_enqueue_style('bilo-popups-admin', BILO_POPUPS_URL . 'assets/admin.css', [], BILO_POPUPS_VERSION);
    wp_enqueue_script('bilo-popups-admin', BILO_POPUPS_URL . 'assets/admin.js', [], BILO_POPUPS_VERSION, true);
}
