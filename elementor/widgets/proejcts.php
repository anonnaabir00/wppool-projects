<?php
namespace WPPoolProjects\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class ProjectsGrid extends Widget_Base {

	public function get_name() {
		return 'wppool-projects-grid';
	}

	public function get_title() {
		return __( 'Projects Grid', 'wppool-projects' );
	}


	public function get_icon() {
		return 'eicon-link';
	}


	public function get_categories() {
		return [ 'wppool_widgets' ];
	}


	public function get_script_depends() {
		return ['wppool_widgets_script'];
	}


	protected function register_controls() {
		
	}


	protected function render() {
		$settings = $this->get_settings_for_display();
        ?>
        <div id="wppool-projects"></div>
        <?php
	}
}