<?php

    if (! class_exists ('WPPool_CPT') ) {

    Class WPPool_CPT {

        private static $instance = false;

        public static function get_instance() {
                if ( !self::$instance )
                    self::$instance = new self;
                return self::$instance;
        }

        public function __construct(){
            add_action( 'init', array( $this, 'wppool_projects_cpt' ) );
            add_action( 'init', array( $this, 'wppool_projects_taxonomy' ) );
            add_action( 'admin_enqueue_scripts', array($this,'wppool_admin_assets'));
            add_filter( 'script_loader_tag', array( $this,'add_module_attribute'), 10,3 );
            add_action( 'add_meta_boxes', array($this,'wppool_projects_meta_box'));
            add_action( 'save_post_wppool_projects', array($this,'wppool_projects_meta_save'));
            add_shortcode( 'wppool_projects', array($this,'wppool_projects_shortcode_handler'));
        }

        public function wppool_projects_cpt() {
            register_post_type( 'wppool_projects',
                array(
                    'labels' => array(
                        'name' => __( 'Projects' ),
                        'singular_name' => __( 'Project' ),
                        'add_new' => __( 'Add New' ),
                        'add_new_item' => __( 'Add New Project' ),
                        'edit' => __( 'Edit' ),
                        'edit_item' => __( 'Edit Project' ),
                        'new_item' => __( 'New Project' ),
                        'view' => __( 'View Project' ),
                        'view_item' => __( 'View Project' ),
                        'search_items' => __( 'Search Project' ),
                        'not_found' => __( 'No Project found' ),
                        'not_found_in_trash' => __( 'No Project found in Trash' ),
                        'parent' => __( 'Parent Project' ),
                    ),
                    'public' => true,
                    'has_archive' => true,
                    'publicly_queryable' => true,
                    'exclude_from_search' => true,
                    'query_var' => true,
                    'capability_type' => 'post',
                    'hierarchical' => false,
                    'rewrite' => array('slug' => 'wppool-projects'),
                    'supports' => array( 'title', 'editor','thumbnail'),
                    'show_in_rest' => true,
                    'menu_position' => 5,
                    'menu_icon' => 'dashicons-location',
                    'show_in_menu' => true,
                    'show_in_nav_menus' => true,
                    'show_in_admin_bar' => true,
                    'rest_base' => 'wppool-projects',
                    'rest_controller_class' => 'WP_REST_Posts_Controller',
                )
            );
        }

        public function wppool_projects_taxonomy() {
            $labels = array(
                'name'              => _x('Categories', 'taxonomy general name'),
                'singular_name'     => _x('Category', 'taxonomy singular name'),
                'search_items'      => __('Search Categories'),
                'all_items'         => __('All Categories'),
                'parent_item'       => __('Parent Category'),
                'parent_item_colon' => __('Parent Category:'),
                'edit_item'         => __('Edit Category'),
                'update_item'       => __('Update Category'),
                'add_new_item'      => __('Add New Category'),
                'new_item_name'     => __('New Category Name'),
                'menu_name'         => __('Categories'),
            );
        
            $args = array(
                'hierarchical'      => true,
                'labels'            => $labels,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => array('slug' => 'project-category'),
            );
        
            register_taxonomy('project_category', 'wppool_projects', $args);
        }

        public function wppool_admin_assets($hook){
            global $post_type;

            if ($hook === 'post-new.php' || $hook === 'post.php') {
                // Check if the current post type is 'wppool_projects'
                if ($post_type === 'wppool_projects') {
                    $post_id = isset($_GET['post']) ? intval($_GET['post']) : 0;
                    $options = get_post_meta($post_id, 'wppool_projects_meta', true);

                    wp_enqueue_style( 'app', plugins_url( 'assets/app.css', __FILE__ ) );
                    wp_enqueue_script( 'admin', plugins_url( 'assets/admin.js', __FILE__ ), [], '1.0', true );
                    wp_localize_script( 'admin', 'admin_settings', array(
                        'root' => esc_url_raw( rest_url() ),
                        'nonce' => wp_create_nonce('wp_rest'),
                        'options' => $options,
                    ) );
                }
            }
        }

        public function add_module_attribute($tag, $handle, $src) {
            // if not your script, do nothing and return original $tag
            if ( 'admin' !== $handle ) {
                return $tag;
            }
            // change the script tag by adding type="module" and return it.
            $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
            return $tag;
        }

        public function wppool_projects_meta_box() {
            add_meta_box(
                'wppool_projects_meta_box',
                'Project Details',
                array( $this,'render_wppool_projects_meta_box'),
                'wppool_projects',
                'normal',
                'default'
            );
        }

        public function render_wppool_projects_meta_box($post) {
            // Retrieve existing field values
            $custom_field_value = get_post_meta($post->ID, 'wppool_projects_meta', true);
            if (is_array($custom_field_value) && isset($custom_field_value['gallery_images'])) {
                $gallery_images = $custom_field_value['gallery_images'];
            } else {
                $gallery_images = array();
            }

            // Display the custom field input
            ?>
            <div id="wppool_fields"></div>
            <div id="wppool-projects-gallery">
                <ul class="flex flex-row flex-wrap mt-8 mb-8" id="gallery-images">
                    <?php if (!empty($gallery_images)) : ?>
                        <?php foreach ($gallery_images as $image_id) : ?>
                            <?php $image_url = wp_get_attachment_image_url($image_id, 'thumbnail'); ?>
                            <li class="mr-8 mb-8">
                                <img src="<?php echo esc_url($image_url); ?>" alt="Gallery Image">
                                <input type="hidden" name="gallery_images[]" value="<?php echo esc_attr($image_id); ?>">
                                <span class="remove-image">Remove</span>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
                
            </div>
            <?php
        }


        public function wppool_projects_meta_save($post_id) {
            if (isset($_POST['url']) || isset($_POST['gallery_images'])) {
                $url = sanitize_text_field($_POST['url']);
                $gallery_images = array_map('absint', $_POST['gallery_images']);
        
                $data = array(
                    'url' => $url,
                    'gallery_images' => $gallery_images
                );
        
                update_post_meta($post_id, 'wppool_projects_meta', $data);
            }
        }
        
        public function wppool_projects_shortcode_handler($atts) {
            // Query all projects
            $projects = new WP_Query(array(
                'post_type' => 'wppool_projects',
                'posts_per_page' => -1,
            ));
        
            ob_start();
        
            if ($projects->have_posts()) {
                while ($projects->have_posts()) {
                    $projects->the_post();

                    $custom_field_value = get_post_meta(get_the_ID(), 'wppool_projects_meta', true);

                    $project_data = array(
                        'title' => get_the_title(),
                        'content' => get_the_content(),
                        'custom_field' => $custom_field_value,
                        'thumbnail' => get_the_post_thumbnail_url(get_the_ID()),
                    );

                    echo '<div id="wppool-projects"></div>';
                }
            } else {
                echo 'No projects found.';
            }        
        
            wp_reset_postdata();
        
            return ob_get_clean();
        }

    }

    WPPool_CPT::get_instance();
}