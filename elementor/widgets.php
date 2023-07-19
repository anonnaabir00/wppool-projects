<?php
namespace WPPoolProjects;


class WPPool_Projects_Widgets {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function wppool_widgets_styles() {
		wp_register_style( 'wppool_widgets_style', plugins_url( '../assets/app.css', __FILE__ ) );
		wp_enqueue_style( 'wppool_widgets_style' );
	}

	public function wppool_widgets_scripts() {
		wp_register_script( 'wppool_widgets_script', plugins_url( '../assets/main.js', __FILE__ ),true );
		wp_enqueue_script( 'wppool_widgets_script' ); 
	}

	public function wppool_widgets_preview_scripts() {
		wp_register_script( 'wppool-widgets-preview', plugins_url( '../assets/main.js', __FILE__ ),['elementor-editor'], false, true);
		wp_enqueue_script( 'wppool-widgets-preview' );
	}


	public function add_module_attribute($tag, $handle, $src) {
		// if not your script, do nothing and return original $tag
		if ( 'wppool_widgets_script' !== $handle ) {
			return $tag;
		}
		// change the script tag by adding type="module" and return it.
		$tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
		return $tag;
	}


	public function register_widgets( $widgets_manager ) {
		// Its is now safe to include Widgets files
		require_once( __DIR__ . '/widgets/proejcts.php' );


		// Register Widgets
		$widgets_manager->register( new Widgets\ProjectsGrid() );
	}

	public function widget_category($elements_manager){
		$elements_manager->add_category(
			'wppool_widgets',
			[
				'title' => esc_html__( 'WPPool Widgets', 'wppool-projects' ),
				'icon' => 'fa fa-plug',
			]
		);
	}


	public function __construct() {
		// Register widget scripts
		// add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'wppool_widgets_scripts' ]);
		// add_action( 'elementor/preview/enqueue_scripts', [ $this, 'wppool_widgets_preview_scripts' ]);
		// add_action( 'elementor/frontend/before_register_scripts', [ $this, 'wppool_widgets_scripts' ]);

		// add_action( 'elementor/frontend/before_enqueue_styles', [ $this, 'wppool_widgets_styles' ]);
		// add_action( 'elementor/preview/enqueue_styles', [ $this, 'wppool_widgets_styles' ]);

		add_filter( 'script_loader_tag', array( $this,'add_module_attribute'), 10,3 );

		// Register widgets
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );

		// Create Widget Category
		add_action( 'elementor/elements/categories_registered', [ $this, 'widget_category' ] );
		
	}
}

// Instantiate Plugin Class
WPPool_Projects_Widgets::instance();