<?php
/*
Plugin Name: AJAX Admin Navigation Sidebar AJAX
Description: Adds AJAX navigation to the WordPress admin dashboard to prevent full page reloads.
Version: 1.0
Author: Vaibhav Gangrade
Author URL:
Author URI:
Stable Version: 1.0
Tested Upto: 6.6
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('AJAX_Admin_Navigation_Menus')) {
    class AJAX_Admin_Navigation_Menus {

        // Calling constructor
        public function __construct() {
            add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
            add_action('wp_enqueue_scripts', array($this, 'load_admin_assets'));
        }

        // Enqueueing script files
        public function enqueue_scripts() {
            wp_enqueue_script('custom-admin-ajax', plugin_dir_url(__FILE__) . 'js/custom-admin-ajax.js', array('jquery'), null, true);

            wp_localize_script('custom-admin-ajax', 'ajax_object', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('custom-admin-ajax-nonce'),
            ));

            // Enqueue the custom CSS file
            wp_enqueue_style('custom-admin-style', plugin_dir_url(__FILE__) . 'css/style.css');
        }

        public function load_admin_assets() {
            if (defined('DOING_AJAX') && DOING_AJAX) {
                // Enqueue WordPress admin scripts for navigation and content management
                wp_enqueue_script('post');  // For post/page editing screens
                wp_enqueue_script('editor'); // The editor script (TinyMCE)
                wp_enqueue_script('nav-menu'); // Navigation menus management
                wp_enqueue_script('media'); // Media manager (for uploading images and files)
                wp_enqueue_script('link'); // Internal linking feature
                wp_enqueue_script('wplink'); // WordPress link editor
                wp_enqueue_script('wp-color-picker'); // Color picker tool
                wp_enqueue_script('customize-controls'); // Customizer controls
                wp_enqueue_script('dashboard'); // Dashboard widgets
                wp_enqueue_script('wp-api'); // REST API integration
                wp_enqueue_script('autosave'); // Autosave feature for posts/pages
                wp_enqueue_script('media-views'); // Media views for the media library
                wp_enqueue_script('tags-box'); // Tag and category boxes
                wp_enqueue_script('wp-block-editor'); // Gutenberg block editor (if needed)

                // Enqueue WordPress admin styles for navigation and content management
                wp_enqueue_style('editor-buttons'); // Styles for editor buttons
                wp_enqueue_style('wp-jquery-ui-dialog'); // jQuery UI dialog styles
                wp_enqueue_style('nav-menu'); // Navigation menus styles
                wp_enqueue_style('wp-color-picker'); // Color picker styles
                wp_enqueue_style('customize-controls'); // Customizer controls styles
                wp_enqueue_style('dashboard'); // Dashboard widgets styles
                wp_enqueue_style('media-views'); // Styles for media views
                wp_enqueue_style('wp-block-editor'); // Styles for Gutenberg block editor (if needed)
                
                // Add more scripts or styles if needed
            }
        }




    }//end of class
}

new AJAX_Admin_Navigation_Menus();


// Enqueue scripts
        function ajax_admin_login_enqueue_scripts() {
            if (!is_user_logged_in()) {
                
                // wp_localize_script('ajax-admin-login', 'ajax_login_object', array(
                //     'loginajaxajaxurl' => admin_url('admin-ajax.php'),
                //     'redirecturl' => admin_url(),
                //     'loadingmessage' => __('Sending user info, please wait...')
                // ));
            }
        }
        //add_action('wp_enqueue_scripts', 'ajax_admin_login_enqueue_scripts');
        // AJAX login handler
        function ajax_admin_login() {
            // First check the nonce, if it fails the function will break
            check_ajax_referer('ajax-login-nonce', 'security');

            // Nonce is checked, get the POST data and sign user on
            $info = array();
            $info['user_login'] = $_POST['username'];
            $info['user_password'] = $_POST['password'];
            $info['remember'] = true;

            $user_signon = wp_signon($info, false);

            if (is_wp_error($user_signon)) {
                echo json_encode(array('loggedin' => false, 'message' => __('Wrong username or password.')));
            } else {
                echo json_encode(array('loggedin' => true, 'message' => __('Login successful, redirecting...')));
            }

            die();
        }
        add_action('wp_ajax_nopriv_ajaxadminlogin', 'ajax_admin_login');


// AJAX handler to fetch posts by status
//  function fetch_posts_by_status() {
//     check_ajax_referer('custom-admin-ajax-nonce', 'nonce'); // Verify nonce

//     // Get the post status from AJAX request
//     $post_status = isset($_POST['post_status']) ? sanitize_text_field($_POST['post_status']) : 'publish';

//     echo "sts  ".$post_status;
//     // Query posts by status
//     $args = array(
//         'post_status' => ($post_status === 'all') ? array('publish', 'draft', 'pending', 'trash') : $post_status,
//         'posts_per_page' => -1, // Retrieve all posts
//         'post_type' => 'post' // Change this to custom post type if needed
//     );

//     $posts_query = new WP_Query($args);

//     // Prepare response
//     $posts = array();
//     if ($posts_query->have_posts()) {
//         while ($posts_query->have_posts()) {
//             $posts_query->the_post();
//             $posts[] = array(
//                 'ID' => get_the_ID(),
//                 'title' => get_the_title(),
//                 'link' => get_edit_post_link(),
//                 'status' => get_post_status()
//             );
//         }
//         wp_reset_postdata();
//     }

//     // Send JSON response
//     wp_send_json_success($posts);
//     wp_die(); // Always die in functions that handle AJAX
// }
