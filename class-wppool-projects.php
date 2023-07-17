<?php

/**
 * Plugin Name:       WPPool Projects
 * Plugin URI:        https://wppool.dev/
 * Description:       WPPool Test Porject
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Asaduzzaman Abir
 * Author URI:        https://wppool.dev/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wppool-projects
 * Domain Path:       /languages
 */


        if (! class_exists ('WPPool_Projects') ) {

            Class WPPool_Projects {

                public function __construct() {
                    require_once( plugin_dir_path( __FILE__ ) . 'class-cpt.php' );
                }

            }

            $page_harvester = new WPPool_Projects();
            
        }