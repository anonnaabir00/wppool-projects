<?php

/**
 * Plugin Name:       WPPool Projects
 * Plugin URI:        https://wppool.dev/
 * Description:       WPPool Test Porject
 * Version:           1.2
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

                    add_action( 'wp_enqueue_scripts', array($this,'wppool_projects_assets'), 10, 1);
                    add_filter( 'script_loader_tag', array( $this,'add_module_attribute'), 10,3 );
                    add_action( 'rest_api_init', array( $this, 'wppool_projects' ));
                }

                public function wppool_projects_assets() {
                    wp_enqueue_style( 'app', plugin_dir_url( __FILE__ ) . 'assets/app.css', array(), '2.0', 'all' );
                    wp_enqueue_style('prime-icons', 'https://cdnjs.cloudflare.com/ajax/libs/primeicons/4.0.0/primeicons.min.css');
                    wp_enqueue_style( 'main', plugin_dir_url( __FILE__ ) . 'assets/main.css', array(), '1.0', 'all' );
                    wp_enqueue_script( 'main', plugin_dir_url( __FILE__ ) . 'assets/main.js', array(), '2.0', true );
                }

                public function add_module_attribute($tag, $handle, $src) {
                    // if not your script, do nothing and return original $tag
                    if ( 'main' !== $handle ) {
                        return $tag;
                    }
                    // change the script tag by adding type="module" and return it.
                    $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
                    return $tag;
                }

                public function wppool_projects(){
                    register_rest_route( 'wppool/v1', '/projects', array(
                        'methods' => 'GET',
                        'callback' => array( $this, 'wppool_projects_callback' ),
                    ));

                    register_rest_route('wppool/v1', '/projects/(?P<id>\d+)', array(
                        'methods' => 'GET',
                        'callback' => array($this, 'wppool_single_project_callback'),
                    ));                

                    register_rest_route('wppool/v1', '/categories', array(
                        'methods' => 'GET',
                        'callback' => array($this, 'wppool_categories_callback'),
                    ));
                }


                public function wppool_projects_callback($atts) {
                    $paged = isset($_GET['page']) ? intval($_GET['page']) : 1;
                    $category = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';
                
                    $query_args = array(
                        'post_type' => 'wppool_projects',
                        'posts_per_page' => 10,
                        'paged' => $paged,
                    );
                
                    if ($category) {
                        $query_args['tax_query'] = array(
                            array(
                                'taxonomy' => 'project_category', // Replace with your actual taxonomy slug
                                'field' => 'slug',
                                'terms' => $category,
                            ),
                        );
                    }
                
                    $projects = new WP_Query($query_args);
                
                    $project_data = array();
                
                    if ($projects->have_posts()) {
                        while ($projects->have_posts()) {
                            $projects->the_post();
                
                            $custom_field_value = get_post_meta(get_the_ID(), 'wppool_projects_meta', true);
                
                            $project_data[] = array(
                                'id' => get_the_ID(),
                                'title' => get_the_title(),
                                'content' => get_the_content(),
                                'custom_field' => $custom_field_value,
                                'thumbnail' => get_the_post_thumbnail_url(get_the_ID()),
                                'categories' => wp_get_post_terms(get_the_ID(), 'project_category', array('fields' => 'slugs')), // Replace 'project_category' with your actual taxonomy slug
                            );
                        }
                    } else {
                        $project_data[] = 'No projects found.';
                    }
                
                    wp_reset_postdata();
                
                    $total_pages = $projects->max_num_pages;
                
                    $response_data = array(
                        'projects' => $project_data,
                        'total_pages' => $total_pages,
                    );
                
                    return $response_data;
                }

                public function wppool_single_project_callback($request) {
                    $project_id = $request['id'];
                
                    $project = get_post($project_id);
                
                    if (empty($project) || $project->post_type !== 'wppool_projects') {
                        return new WP_Error('invalid_project', 'Invalid project ID.', array('status' => 404));
                    }
                
                    $custom_field_value = get_post_meta($project_id, 'wppool_projects_meta', true);

                    $gallery_images = array();
                    foreach ($custom_field_value['gallery_images'] as $image_id) {
                        $image_url = wp_get_attachment_image_url($image_id, 'full');
                        if ($image_url) {
                            $gallery_images[] = $image_url;
                        }
                    }
                
                    $project_data = array(
                        'title' => $project->post_title,
                        'content' => $project->post_content,
                        'gallery' => $gallery_images,
                        'custom_field' => $custom_field_value,
                        'external_url' => $custom_field_value['url'],
                        'thumbnail' => get_the_post_thumbnail_url($project_id),
                        'categories' => wp_get_post_terms($project_id, 'project_category', array('fields' => 'slugs')), // Replace 'project_category' with your actual taxonomy slug
                    );
                
                    return rest_ensure_response($project_data);
                }
                
                
                public function wppool_categories_callback($request) {
                    $categories = get_terms(array(
                        'taxonomy' => 'project_category', // Replace with your actual taxonomy slug
                        'hide_empty' => true,
                    ));
                
                    $response_data = array();
                
                    if (!is_wp_error($categories) && !empty($categories)) {
                        foreach ($categories as $category) {
                            $response_data[] = array(
                                'id' => $category->term_id,
                                'name' => $category->name,
                                'slug' => $category->slug,
                            );
                        }
                    }
                
                    return rest_ensure_response($response_data);
                }

            }

            $wppool_projects = new WPPool_Projects();
            
        }