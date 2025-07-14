<?php
/**
 * Plugin Name: Iran Branches
 * Description: A plugin to display company branches in Iran.
 * Version: 1
 * Author: 0xSYJiK
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
function create_branch_post_type() {
    register_post_type('branch', array(
        'labels' => array(
            'name' => 'Branches',
            'singular_name' => 'Branch',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'page-attributes'),
        'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-shop" viewBox="0 0 16 16"><path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5M4 15h3v-5H4zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zm3 0h-2v3h2z"/></svg>'),
        'taxonomies'  => array( 'province' ),
    ));
}

function create_province_taxonomy() {
    register_taxonomy('province', 'branch', array(
        'labels' => array(
            'name' => 'Provinces',
            'singular_name' => 'Province',
        ),
        'public' => true,
        'hierarchical' => true,
    ));
}
add_action('init', 'create_province_taxonomy', 0);
add_action('init', 'create_branch_post_type');

// Add Order field to Add New Province screen
function province_add_order_field() {
    ?>
    <div class="form-field">
        <label for="province_order"><?php _e( 'Order', 'text_domain' ); ?></label>
        <input type="number" name="province_order" id="province_order" value="">
        <p class="description"><?php _e( 'Enter a number to manually order the provinces. Provinces with lower numbers appear first. Provinces without a number will be sorted alphabetically.','text_domain' ); ?></p>
    </div>
    <?php
}
add_action( 'province_add_form_fields', 'province_add_order_field', 10, 2 );

// Add Order field to Edit Province screen
function province_edit_order_field($term) {
    $province_order = get_term_meta($term->term_id, 'province_order', true);
    ?>
    <tr class="form-field">
    <th scope="row" valign="top"><label for="province_order"><?php _e( 'Order', 'text_domain' ); ?></label></th>
        <td>
            <input type="number" name="province_order" id="province_order" value="<?php echo esc_attr( $province_order ) ? esc_attr( $province_order ) : ''; ?>">
            <p class="description"><?php _e( 'Enter a number to manually order the provinces. Provinces with lower numbers appear first. Provinces without a number will be sorted alphabetically.','text_domain' ); ?></p>
        </td>
    </tr>
    <?php
}
add_action( 'province_edit_form_fields', 'province_edit_order_field', 10, 2 );

// Save custom meta field
function save_province_order_field( $term_id ) {
    if ( isset( $_POST['province_order'] ) ) {
        update_term_meta( $term_id, 'province_order', sanitize_text_field($_POST['province_order']) );
    }
}
add_action( 'edited_province', 'save_province_order_field', 10, 2 );
add_action( 'create_province', 'save_province_order_field', 10, 2 );


function add_branch_meta_boxes() {
    add_meta_box(
        'branch_details',
        'Branch Details',
        'display_branch_meta_box',
        'branch',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_branch_meta_boxes');

function display_branch_meta_box($post) {
    wp_nonce_field('branch_meta_box', 'branch_meta_box_nonce');

    $name = get_post_meta($post->ID, '_branch_name', true);
    $phone = get_post_meta($post->ID, '_branch_phone', true);
    $address = get_post_meta($post->ID, '_branch_address', true);
    $google_maps = get_post_meta($post->ID, '_branch_google_maps', true);
    $waze = get_post_meta($post->ID, '_branch_waze', true);

    echo '<label for="branch_name">Branch Name:</label>';
    echo '<input type="text" id="branch_name" name="branch_name" value="' . esc_attr($name) . '" size="25" />';
    echo '<br/><br/>';
    echo '<label for="branch_phone">Phone Number:</label>';
    echo '<input type="text" id="branch_phone" name="branch_phone" value="' . esc_attr($phone) . '" size="25" />';
    echo '<p class="description">Enter multiple numbers separated by commas (,)</p>';
    echo '<br/><br/>';
    echo '<label for="branch_address">Address:</label>';
    echo '<textarea id="branch_address" name="branch_address" rows="4" cols="50">' . esc_textarea($address) . '</textarea>';
    echo '<br/><br/>';
    echo '<label for="branch_google_maps">Google Maps Link:</label>';
    echo '<input type="text" id="branch_google_maps" name="branch_google_maps" value="' . esc_attr($google_maps) . '" size="25" />';
    echo '<br/><br/>';
    echo '<label for="branch_waze">Waze/Balad Link:</label>';
    echo '<input type="text" id="branch_waze" name="branch_waze" value="' . esc_attr($waze) . '" size="25" />';
}

function save_branch_meta_box_data($post_id) {
    if (!isset($_POST['branch_meta_box_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['branch_meta_box_nonce'], 'branch_meta_box')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['post_type']) && 'branch' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    } else {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    if (isset($_POST['branch_name'])) {
        update_post_meta($post_id, '_branch_name', sanitize_text_field($_POST['branch_name']));
    }

    if (isset($_POST['branch_phone'])) {
        update_post_meta($post_id, '_branch_phone', sanitize_text_field($_POST['branch_phone']));
    }

    if (isset($_POST['branch_address'])) {
        update_post_meta($post_id, '_branch_address', sanitize_textarea_field($_POST['branch_address']));
    }

    if (isset($_POST['branch_google_maps'])) {
        update_post_meta($post_id, '_branch_google_maps', sanitize_text_field($_POST['branch_google_maps']));
    }

    if (isset($_POST['branch_waze'])) {
        update_post_meta($post_id, '_branch_waze', sanitize_text_field($_POST['branch_waze']));
    }
}
add_action('save_post', 'save_branch_meta_box_data');

function to_persian_numerals($string) {
    $persian_numerals = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
    $english_numerals = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    return str_replace($english_numerals, $persian_numerals, $string);
}

function display_branches_shortcode($atts) {
    ob_start();

    $atts = shortcode_atts(array(
        'province' => ''
    ), $atts, 'iran_branches');

    $province_slug = sanitize_text_field(get_query_var('province', $atts['province']));

    $all_provinces = get_terms(array('taxonomy' => 'province', 'hide_empty' => true));

    if (!empty($all_provinces) && !is_wp_error($all_provinces)) {
        usort($all_provinces, function($a, $b) {
            $order_a = get_term_meta($a->term_id, 'province_order', true);
            $order_b = get_term_meta($b->term_id, 'province_order', true);

            if ($order_a != '' && $order_b != '') {
                if ($order_a == $order_b) {
                    return strcmp($a->name, $b->name);
                }
                return $order_a - $order_b;
            } elseif ($order_a != '') {
                return -1;
            } elseif ($order_b != '') {
                return 1;
            } else {
                return strcmp($a->name, $b->name);
            }
        });

        echo '<div class="branch-search">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/></svg>';
        echo '<input type="text" id="branch-search-input" placeholder="جستجوی نمایندگی (شهر - محله - اسم نمایندگی و...)">';
        echo '</div>';

        echo '<div class="province-filter">';
        echo '<select id="province-select">';
        echo '<option value="">انتخاب استان</option>';
        foreach ($all_provinces as $prov) {
            echo '<option value="' . esc_attr($prov->slug) . '" ' . selected($province_slug, $prov->slug, false) . '>' . esc_html($prov->name) . '</option>';
        }
        echo '</select>';
        echo '</div>';
    }

    if (!empty($province_slug)) {
        $province = get_term_by('slug', $province_slug, 'province');
        if ($province) {
            echo '<h2 class="province-title">' . esc_html($province->name) . '</h2>';
            $args = array(
                'post_type' => 'branch',
                'posts_per_page' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'province',
                        'field'    => 'slug',
                        'terms'    => $province_slug,
                    ),
                ),
                'orderby' => array( 'menu_order' => 'ASC', 'title' => 'ASC' )
            );
            $branches = new WP_Query($args);

            if ($branches->have_posts()) {
                echo '<div class="iran-branches-container">';
                while ($branches->have_posts()) {
                    $branches->the_post();
                    $name = get_post_meta(get_the_ID(), '_branch_name', true);
                    $phone = get_post_meta(get_the_ID(), '_branch_phone', true);
                    $address = get_post_meta(get_the_ID(), '_branch_address', true);
                    $google_maps = get_post_meta(get_the_ID(), '_branch_google_maps', true);
                    $waze = get_post_meta(get_the_ID(), '_branch_waze', true);

                    echo '<div class="branch-accordion">';
                    echo '<div class="branch-header">' . get_the_title() . '</div>';
                    echo '<div class="branch-content">';
                    echo '<div class="branch-content-inner">';
                    echo '<p class="branch-info-line"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shop" viewBox="0 0 16 16"><path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5M4 15h3v-5H4zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zm3 0h-2v3h2z"/></svg> ' . esc_html($name) . '</p>';
                    
                    $phone_numbers = explode(',', $phone);
                    $phone_links = array();
                    foreach ($phone_numbers as $phone_number) {
                        $trimmed_number = trim($phone_number);
                        $persian_number = to_persian_numerals($trimmed_number);
                        $phone_links[] = '<a href="tel:' . esc_attr($trimmed_number) . '">' . esc_html($persian_number) . '</a>';
                    }
                    echo '<p class="branch-info-line"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/></svg> ' . implode(' - ', $phone_links) . '</p>';

                    echo '<p class="branch-info-line"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16"><path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/></svg> ' . esc_html($address) . '</p>';
                    echo '<p class="branch-routing">مسیریابی: ';
                    if (!empty($google_maps)) {
                        echo '<a href="' . esc_url($google_maps) . '" target="_blank"><img src="' . plugins_url('images/google-maps.png', __FILE__) . '" alt="Google Maps"></a>';
                    }
                    if (!empty($waze)) {
                        echo '<a href="' . esc_url($waze) . '" target="_blank"><img src="' . plugins_url('images/waze.png', __FILE__) . '" alt="Waze"></a>';
                    }
                    echo '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                echo '</div>';
                wp_reset_postdata();
            } else {
                echo '<p>No branches found in this province.</p>';
            }
        }
    } else {
        $provinces = get_terms(array(
            'taxonomy' => 'province',
            'hide_empty' => true,
        ));

        if (!empty($provinces) && !is_wp_error($provinces)) {
            usort($provinces, function($a, $b) {
                $order_a = get_term_meta($a->term_id, 'province_order', true);
                $order_b = get_term_meta($b->term_id, 'province_order', true);

                if ($order_a != '' && $order_b != '') {
                    if ($order_a == $order_b) {
                        return strcmp($a->name, $b->name);
                    }
                    return $order_a - $order_b;
                } elseif ($order_a != '') {
                    return -1;
                } elseif ($order_b != '') {
                    return 1;
                } else {
                    return strcmp($a->name, $b->name);
                }
            });

            echo '<div class="iran-branches-container">';

            foreach ($provinces as $province) {
                $args = array(
                    'post_type' => 'branch',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'province',
                            'field'    => 'slug',
                            'terms'    => $province->slug,
                        ),
                    ),
                    'orderby' => array( 'menu_order' => 'ASC', 'title' => 'ASC' )
                );
                $branches = new WP_Query($args);

                if ($branches->have_posts()) {
                    echo '<div class="province-group">';
                    echo '<h2 class="province-title">' . esc_html($province->name) . '</h2>';

                    while ($branches->have_posts()) {
                        $branches->the_post();
                        $name = get_post_meta(get_the_ID(), '_branch_name', true);
                        $phone = get_post_meta(get_the_ID(), '_branch_phone', true);
                        $address = get_post_meta(get_the_ID(), '_branch_address', true);
                        $google_maps = get_post_meta(get_the_ID(), '_branch_google_maps', true);
                        $waze = get_post_meta(get_the_ID(), '_branch_waze', true);

                        echo '<div class="branch-accordion">';
                        echo '<div class="branch-header">' . get_the_title() . '</div>';
                        echo '<div class="branch-content">';
                        echo '<div class="branch-content-inner">';
                        echo '<p class="branch-info-line"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shop" viewBox="0 0 16 16"><path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5M4 15h3v-5H4zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zm3 0h-2v3h2z"/></svg> ' . esc_html($name) . '</p>';
                        
                        $phone_numbers = explode(',', $phone);
                        $phone_links = array();
                        foreach ($phone_numbers as $phone_number) {
                            $trimmed_number = trim($phone_number);
                            $persian_number = to_persian_numerals($trimmed_number);
                            $phone_links[] = '<a href="tel:' . esc_attr($trimmed_number) . '">' . esc_html($persian_number) . '</a>';
                        }
                        echo '<p class="branch-info-line"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/></svg> ' . implode(' - ', $phone_links) . '</p>';

                        echo '<p class="branch-info-line"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16"><path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/></svg> ' . esc_html($address) . '</p>';
                        echo '<p class="branch-routing">مسیریابی: ';
                        if (!empty($google_maps)) {
                            echo '<a href="' . esc_url($google_maps) . '" target="_blank"><img src="https://www.bonhorse.ir/wp-content/uploads/2025/07/google-maps.png" alt="Google Maps"></a>';
                        }
                        if (!empty($waze)) {
                            echo '<a href="' . esc_url($waze) . '" target="_blank"><img src="https://www.bonhorse.ir/wp-content/uploads/2025/07/neshan.png"></a>';
                        }
                        echo '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                    wp_reset_postdata();
                    echo '</div>'; // close province-group
                }
            }
            echo '</div>'; // close iran-branches-container

            echo '<div id="pagination-container"></div>';
            echo '<div id="no-results-message" style="display: none;">No results found.</div>';
        } else {
            echo '<p>No branches found.</p>';
        }
    }

    return ob_get_clean();
}
add_shortcode('iran_branches', 'display_branches_shortcode');

function add_query_vars_filter($vars) {
    $vars[] = "province";
    return $vars;
}
add_filter('query_vars', 'add_query_vars_filter');

function enqueue_branch_scripts() {
    wp_enqueue_style('iran-branches-style', plugins_url('css/style.css', __FILE__));
    wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css');
    wp_enqueue_script('iran-branches-script', plugins_url('js/main.js', __FILE__), array(), '1.0', true);
    add_action('wp_head', 'iran_branches_custom_styles');
}
add_action('wp_enqueue_scripts', 'enqueue_branch_scripts');

function enqueue_admin_branch_scripts() {
    wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css');
}
add_action('admin_enqueue_scripts', 'enqueue_admin_branch_scripts');

// Add settings page
function iran_branches_settings_page() {
    add_submenu_page(
        'edit.php?post_type=branch',
        'Iran Branches Settings',
        'Styles',
        'manage_options',
        'iran-branches-settings',
        'iran_branches_settings_page_callback'
    );
}
add_action('admin_menu', 'iran_branches_settings_page');

function iran_branches_settings_page_callback() {
    ?>
    <div class="wrap">
        <h1>Iran Branches Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('iran_branches_settings');
            do_settings_sections('iran-branches-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function iran_branches_get_style_defaults() {
    return array(
        'header_bg_color' => '#f4f4f4',
        'header_text_color' => '#333333',
        'header_active_bg_color' => '#cccccc',
        'content_bg_color' => '#ffffff',
        'content_text_color' => '#333333',
        'province_title_color' => '#333333',
    );
}

function iran_branches_register_settings() {
    register_setting('iran_branches_settings', 'iran_branches_styles', 'iran_branches_sanitize_styles');

    add_settings_section(
        'iran_branches_style_section',
        'Custom Styles',
        '<p>Here you can override the default styles. Leave a field empty or set it to the default color to use the style from the main stylesheet.</p>',
        'iran-branches-settings'
    );

    $defaults = iran_branches_get_style_defaults();

    add_settings_field(
        'header_bg_color',
        'Header Background Color',
        'iran_branches_color_field_callback',
        'iran-branches-settings',
        'iran_branches_style_section',
        ['label_for' => 'header_bg_color', 'default' => $defaults['header_bg_color']]
    );

    add_settings_field(
        'header_text_color',
        'Header Text Color',
        'iran_branches_color_field_callback',
        'iran-branches-settings',
        'iran_branches_style_section',
        ['label_for' => 'header_text_color', 'default' => $defaults['header_text_color']]
    );

     add_settings_field(
        'header_active_bg_color',
        'Header Active Background Color',
        'iran_branches_color_field_callback',
        'iran-branches-settings',
        'iran_branches_style_section',
        ['label_for' => 'header_active_bg_color', 'default' => $defaults['header_active_bg_color']]
    );

    add_settings_field(
        'content_bg_color',
        'Content Background Color',
        'iran_branches_color_field_callback',
        'iran-branches-settings',
        'iran_branches_style_section',
        ['label_for' => 'content_bg_color', 'default' => $defaults['content_bg_color']]
    );

    add_settings_field(
        'content_text_color',
        'Content Text Color',
        'iran_branches_color_field_callback',
        'iran-branches-settings',
        'iran_branches_style_section',
        ['label_for' => 'content_text_color', 'default' => $defaults['content_text_color']]
    );

    add_settings_field(
        'province_title_color',
        'Province Title Color',
        'iran_branches_color_field_callback',
        'iran-branches-settings',
        'iran_branches_style_section',
        ['label_for' => 'province_title_color', 'default' => $defaults['province_title_color']]
    );
}
add_action('admin_init', 'iran_branches_register_settings');

function iran_branches_sanitize_styles($input) {
    $output = array();
    foreach ($input as $key => $value) {
        if (!empty($value)) {
            $output[$key] = sanitize_hex_color($value);
        }
    }
    return $output;
}

function iran_branches_color_field_callback($args) {
    $options = get_option('iran_branches_styles');
    $defaults = iran_branches_get_style_defaults();
    $value = isset($options[$args['label_for']]) ? $options[$args['label_for']] : $defaults[$args['label_for']];
    echo '<input type="color" id="' . $args['label_for'] . '" name="iran_branches_styles[' . $args['label_for'] . ']" value="' . esc_attr($value) . '" />';
    echo '<p class="description">Default: ' . esc_html($defaults[$args['label_for']]) . '</p>';
}

function iran_branches_custom_styles() {
    $options = get_option('iran_branches_styles');
    $defaults = iran_branches_get_style_defaults();
    $css = '';

    $style_map = [
        'header_bg_color'        => '.branch-header',
        'header_text_color'      => '.branch-header',
        'header_active_bg_color' => '.branch-header.active',
        'content_bg_color'       => '.branch-content',
        'content_text_color'     => '.branch-content-inner p',
        'province_title_color'   => '.province-title'
    ];

    $css_properties = [
        'header_bg_color'        => 'background-color',
        'header_text_color'      => 'color',
        'header_active_bg_color' => 'background-color',
        'content_bg_color'       => 'background-color',
        'content_text_color'     => 'color',
        'province_title_color'   => 'color'
    ];

    if (!empty($options)) {
        foreach ($options as $key => $value) {
            if (isset($style_map[$key]) && !empty($value) && strtolower($value) !== strtolower($defaults[$key])) {
                $css .= $style_map[$key] . ' { ' . $css_properties[$key] . ': ' . esc_attr($value) . '; }' . "\n";
            }
        }
    }

    if (!empty($css)) {
        echo "<style>\n" . $css . "</style>\n";
    }
}

?>