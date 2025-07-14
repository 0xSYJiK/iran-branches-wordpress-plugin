<?php

function iran_branches_settings_page() {
    add_submenu_page(
        'edit.php?post_type=branch',
        __('Branch Settings', 'iran-branches'),
        __('Settings', 'iran-branches'),
        'manage_options',
        'iran-branches-settings',
        'iran_branches_settings_page_callback'
    );
}
add_action('admin_menu', 'iran_branches_settings_page');

function iran_branches_settings_page_callback() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('iran_branches_settings');
            do_settings_sections('iran_branches_settings');
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}

function iran_branches_settings_init() {
    register_setting('iran_branches_settings', 'iran_branches_style_options');

    add_settings_section(
        'iran_branches_style_section',
        __('Styling Options', 'iran-branches'),
        '',
        'iran_branches_settings'
    );

    add_settings_field(
        'iran_branches_style',
        __('Theme', 'iran-branches'),
        'iran_branches_style_callback',
        'iran_branches_settings',
        'iran_branches_style_section'
    );

    add_settings_field(
        'iran_branches_header_color',
        __('Header Background Color', 'iran-branches'),
        'iran_branches_header_color_callback',
        'iran_branches_settings',
        'iran_branches_style_section'
    );

    add_settings_field(
        'iran_branches_title_color',
        __('Province Title Color', 'iran-branches'),
        'iran_branches_title_color_callback',
        'iran_branches_settings',
        'iran_branches_style_section'
    );
}
add_action('admin_init', 'iran_branches_settings_init');

function iran_branches_style_callback() {
    $options = get_option('iran_branches_style_options');
    $style = isset($options['style']) ? $options['style'] : 'default';
    ?>
    <select name="iran_branches_style_options[style]">
        <option value="default" <?php selected($style, 'default'); ?>>Default</option>
        <option value="sleek" <?php selected($style, 'sleek'); ?>>Sleek</option>
        <option value="vibrant" <?php selected($style, 'vibrant'); ?>>Vibrant</option>
    </select>
    <?php
}

function iran_branches_header_color_callback() {
    $options = get_option('iran_branches_style_options');
    $color = isset($options['header_color']) ? $options['header_color'] : '#f1f1f1';
    ?>
    <input type="text" name="iran_branches_style_options[header_color]" value="<?php echo esc_attr($color); ?>" class="color-picker" />
    <?php
}

function iran_branches_title_color_callback() {
    $options = get_option('iran_branches_style_options');
    $color = isset($options['title_color']) ? $options['title_color'] : '#333333';
    ?>
    <input type="text" name="iran_branches_style_options[title_color]" value="<?php echo esc_attr($color); ?>" class="color-picker" />
    <?php
}

function iran_branches_enqueue_admin_styles($hook) {
    if ('branch_page_iran-branches-settings' != $hook) {
        return;
    }
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    add_action('admin_footer', 'iran_branches_admin_footer_scripts');
}
add_action('admin_enqueue_scripts', 'iran_branches_enqueue_admin_styles');

function iran_branches_admin_footer_scripts() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $('.color-picker').wpColorPicker();
        });
    </script>
    <?php
}

?>