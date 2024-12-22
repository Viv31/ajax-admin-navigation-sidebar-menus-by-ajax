<?php
/*
Plugin Name: AJAX Admin Navigation Sidebar AJAX
Description: Adds AJAX navigation to the WordPress admin dashboard to prevent full page reloads.
Version: 1.0
Author: Vaibhav Gangrade
Author URI: 
Stable Version: 1.0
Tested Up To: 6.6
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Short Description: Plugin for controlling admin menus sideabr with ajax for prevnting extra server load.
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

           wp_localize_script('custom-admin-ajax', 'ajax_admin_nav_object', array(
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


