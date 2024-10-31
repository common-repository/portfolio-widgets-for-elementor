<?php
namespace ElementorPortfolio;

/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.2.0
 */
class Plugin {

	/**
	 * Instance
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function widget_scripts() {
		wp_register_script( 'isotope', plugins_url( '/assets/js/isotope.js', __FILE__ ), [ 'jquery' ], false, true );
		wp_register_script( 'imagesloaded', plugins_url( '/assets/js/imagesloaded.pkgd.min.js', __FILE__ ), [ 'jquery' ], false, true );
		wp_register_script( 'codeless-elementor-portfolio', plugins_url( '/assets/js/codeless-elementor-portfolio.js', __FILE__ ), [ 'jquery', 'isotope', 'imagesloaded' ], false, true );
	}

	public function widget_styles() {
		wp_register_style( 'codeless-elementor-portfolio', plugins_url( '/assets/css/elementor-portfolio.css', __FILE__ ) );
	}

	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.2.0
	 * @access private
	 */
	private function include_widgets_files() {
		require_once( __DIR__ . '/includes/helpers.php' );
		require_once( __DIR__ . '/widgets/codeless-portfolio-grid.php' );
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_widgets() {
		// Its is now safe to include Widgets files
		$this->include_widgets_files();

		// Register Widgets
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\CodelessPortfolio() );
	}

	public function register_category( $elements_manager ){
		$elements_manager->add_category(
			'codeless-elements',
			[
				'title' => __( 'Codeless Elements', 'elementor-portfolio' ),
				'icon' => 'fa fa-plug',
			]
		);
	}


	public function register_portfolio(){
		$labels = array(

			'name' => _x('Portfolio Items', 'post type general name', 'elementor-portfolio'),
	
			'singular_name' => _x('Portfolio Entry', 'post type singular name', 'elementor-portfolio'),
	
			'add_new' => _x('Add New', 'portfolio', 'elementor-portfolio'),
	
			'add_new_item' => __('Add New Portfolio Entry', 'elementor-portfolio'),
	
			'edit_item' => __('Edit Portfolio Entry', 'elementor-portfolio'),
	
			'new_item' => __('New Portfolio Entry', 'elementor-portfolio'),
	
			'view_item' => __('View Portfolio Entry', 'elementor-portfolio'),
	
			'search_items' => __('Search Portfolio Entries', 'elementor-portfolio'),
	
			'not_found' =>  __('No Portfolio Entries found', 'elementor-portfolio'),
	
			'not_found_in_trash' => __('No Portfolio Entries found in Trash', 'elementor-portfolio'), 
	
			'parent_item_colon' => ''
	
		);
	
		
	
		$slugRule = 'portfolio';
	
		
	
		$args = array(
	
			'labels' => $labels,
	
			'public' => true,
	
			'show_ui' => true,
	
			'capability_type' => 'post',
	
			'hierarchical' => false,
	
			'rewrite' => array('slug'=>$slugRule,'with_front'=>true),
	
			'query_var' => true,
	
			'show_in_nav_menus'=> true,
	
			'supports' => array('title','thumbnail','excerpt','editor','comments')
	
		);

		register_post_type( 'portfolio' , $args );
	
		register_taxonomy("portfolio_entries", 
	
			array("portfolio"), 
	
			array(	"hierarchical" => true, 
	
			"label" => esc_html("Portfolio Categories", 'elementor-portfolio'),
	
			"singular_label" => esc_html("Portfolio Categories", 'elementor-portfolio'), 
	
			"rewrite" => true,
	
			"query_var" => true
	
		));  
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'init', [ $this, 'register_portfolio' ] );
		

		add_action( 'elementor/elements/categories_registered', [ $this, 'register_category' ] );

		// Register widget scripts
		add_action( 'elementor/frontend/before_register_scripts', [ $this, 'widget_scripts' ] );

		add_action( 'elementor/frontend/before_register_styles', [ $this, 'widget_styles' ] );

		// Register widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
	}
}

// Instantiate Plugin Class
Plugin::instance();
