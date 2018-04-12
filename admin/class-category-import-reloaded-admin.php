<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.niroma.net/
 * @since      1.0.0
 *
 * @package    Category_Import_Reloaded
 * @subpackage Category_Import_Reloaded/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Category_Import_Reloaded
 * @subpackage Category_Import_Reloaded/admin
 * @author     NiRoMa <info@niroma.net>
 */
class Category_Import_Reloaded_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Category_Import_Reloaded_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Category_Import_Reloaded_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/category-import-reloaded-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Category_Import_Reloaded_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Category_Import_Reloaded_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/category-import-reloaded-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	public function add_plugin_admin_menu() {

    /*
     * Add a settings page for this plugin to the Settings menu.
     *
     * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
     *
     *        Administration Menus: http://codex.wordpress.org/Administration_Menus
     *
     */
		add_submenu_page("edit.php", 'Category Import Reloaded', 'Category Import', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page'));	
	}
	
	public function display_plugin_setup_page() {
		include_once( 'partials/category-import-reloaded-admin-display.php' );
	}
	
	
	private function termAlreadyExists($array, $key, $val, $return) {
		foreach ($array as $item) {
			if (isset($item[$key])) {
				$compare = sanitize_text_field($item[$key]);
				if ($compare == $val) return $item[$return];
			}
		}
		return false;
	}
	
	//add_action('template_redirect', 'check_for_event_submissions');

	function check_for_event_submissions(){
	
			if (isset($_POST[$this->plugin_name.'-submit'])){
				$taxonomyActive = $_POST[$this->plugin_name.'-taxonomy'];
				$delimiter = strlen(trim($_POST[$this->plugin_name.'-delimiter'])) != 0?$_POST[$this->plugin_name.'-delimiter']:"$";
				$lines = explode(PHP_EOL, $_POST[$this->plugin_name.'-bulkCategoryList']);
				$countSuccess = 0;
				$countErrors = 0;
				$parent_id = '';
				$rootCategories = array();
				$rootTerms = get_terms( array( 'taxonomy' => 'category', 'parent' => 0, 'hide_empty' => false ) );
				foreach ($rootTerms as $rootTerm) {
					$rootCategories[] = array('id' => $rootTerm->term_id, 'name' => $rootTerm->name);
				}
				
				foreach($lines as $line){
					$split_line = explode('/', $line);
					$l =  count($split_line);
					for ($i = 0; $i < $l; $i++) {
						$new_line = $split_line[$i];
						$prev_line = '';
						
						if (strlen(trim($new_line)) == 0) break;
						$new_line = sanitize_text_field(trim($new_line));
						
						if(strpos($new_line, $delimiter) !== false){
							$cat_name_slug = explode($delimiter,$new_line);
							$cat_name =  sanitize_text_field(trim($cat_name_slug[0]));
							$cat_slug =  sanitize_text_field(trim($cat_name_slug[1]));
						} else {
							$cat_name = $new_line;
							$cat_slug = $new_line;
						}
						
						if ($i == 0) {
							if ( $this->termAlreadyExists($rootCategories, 'name', $cat_name, 'id') ) {
								$parent_id = $this->termAlreadyExists($rootCategories, 'name', $cat_name, 'id');
								$countErrors++;
							} else {
								$result = wp_insert_term( $cat_name, 'category', array('slug' => $cat_slug) );
								if ( ! is_wp_error( $result ) ) {
									$parent_id = isset( $result['term_id'] ) ? $result['term_id'] : '';
									$rootCategories[] = array('id' => $parent_id, 'name' => $cat_name);
									$countSuccess++;
								} else $countErrors++;
							}
						} else {
							if (!empty($parent_id)) {
								$siblingsCategories = array();
								$parentChildren = get_terms( array('taxonomy' => 'category', 'parent' => $parent_id, 'hide_empty' => false ) );
								foreach ($parentChildren as $child) {
									$siblingsCategories[] = array('id' => $child->term_id, 'name' => $child->name);
								}
								if ( $this->termAlreadyExists($siblingsCategories, 'name', $cat_name, 'id') ) {
									$parent_id = $this->termAlreadyExists($siblingsCategories, 'name', $cat_name, 'id');
									$countErrors++;
								} else {
									$result = wp_insert_term( $cat_name, 'category', array('parent' => $parent_id, 'slug' => $cat_slug) );
									if ( ! is_wp_error( $result ) ) {
										$parent_id = isset( $result['term_id'] ) ? $result['term_id'] : '';
										$countSuccess++;
									} else $countErrors++;
								}
							} else $countErrors++;
						}	
					}
				}
				/*
				if ($countErrors > 0 ) echo '<div id="message" class="updated fade"><p><strong>'. $countErrors .' categories already in database </strong></p></div>';
				if ($countSuccess > 0 ) echo '<div id="message" class="updated fade"><p><strong>'. $countSuccess .' categories successully added!! </strong></p></div>';
				*/
				wp_redirect($_POST[$this->plugin_name.'-redirect_url']); // add a hidden input with get_permalink()
				die();
			}
	}

}
