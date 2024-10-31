<?php
namespace ElementorPortfolio\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class CodelessPortfolio extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'codeless-portfolio-grid';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Codeless Portfolio Grid', 'elementor-portfolio' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'codeless-elements' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'codeless-elementor-portfolio' ];
	}


	public function get_style_depends() {
		return [ 'codeless-elementor-portfolio' ];
	 }

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'general',
			[
				'label' => __( 'General', 'elementor-portfolio' ),
			]
		);

		$this->add_control(
			'style',
			[
				'label' => __( 'Style', 'elementor-portfolio' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'only_image',
				'options' => [
					'only_image' => esc_html__( 'Only Image', 'elementor-portfolio' ),
					'with_title_bottom' => esc_html__( 'With Title Bottom', 'elementor-portfolio' )
				]
			]
		);

		$this->add_control(
			'columns',
			[
				'label' => __( 'Columns', 'elementor-portfolio' ),
				'type' => Controls_Manager::SELECT,
				'default' => '3',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'query',
			[
				'label' => __( 'Query', 'elementor-portfolio' ),
			]
		);

		$this->add_control(
			'categories',
			[
				'label' => __( 'Categories', 'elementor-portfolio' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => codeless_get_portfolio_categories(),
				'default' => []
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label' => __( 'Posts Per Page', 'elementor-portfolio' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 9,
			]
		);

		$this->add_control(
			'includes',
			[
				'label' => __( 'Includes', 'elementor-portfolio' ),
				'description' => __( 'Include custom portfolio items, will overwrite other options', 'elementor-portfolio' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => codeless_get_portfolio_items(),
				'default' => []
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' => __( 'Order By', 'elementor-portfolio' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'none' => __('None', 'elementor-portfolio'),
					'date' => __('Date', 'elementor-portfolio'),
					'ID' => __('ID', 'elementor-portfolio'),
					'title' => __('Title', 'elementor-portfolio'),
				]
			]
		);

		$this->add_control(
			'order',
			[
				'label' => __( 'Order', 'elementor-portfolio' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'ASC' => __('ASC', 'elementor-portfolio'),
					'DESC' => __('DESC', 'elementor-portfolio')
				]
			]
		);

		$this->end_controls_section();
	}

	protected function build_portfolio_query( $settings ){
		$new_query = array( 
			'post_type' => 'portfolio',
			'orderby'   => $settings['orderby'], 
			'order'     => $settings['order'],
			'posts_per_page' => $settings['posts_per_page'],
		); 

		if( !empty( $settings['includes'] ) ){
			$new_query['ignore_sticky_posts'] = 1;
			$new_query['post__in'] = $settings['includes'];
			$new_query['ignore_custom_sort'] = true;
		}


		if( !empty( $settings['categories'] ) ){
			$taxonomies_types = get_taxonomies( array( 'public' => true ) );
			$terms = get_terms( array_keys( $taxonomies_types ), array(
				'hide_empty' => false,
				'include' => $settings['categories'],
			) );
			$tax_query = array();
		
			$tax_queries = array(); // List of taxnonimes
			foreach ( $terms as $t ) {
				if ( ! isset( $tax_queries[ $t->taxonomy ] ) ) {
					$tax_queries[ $t->taxonomy ] = array(
						'taxonomy' => $t->taxonomy,
						'field' => 'id',
						'terms' => array( $t->term_id ),
						'relation' => 'IN',
					);
				} else {
					$tax_queries[ $t->taxonomy ]['terms'][] = $t->term_id;
				}
			}
		
			$tax_query = array_values( $tax_queries );
			$tax_query['relation'] = 'OR';
		
			$new_query['tax_query'] = $tax_query;
		}

		return $new_query;
	}

	protected function get_template_part( $style = 'only_image' ){
		include 'partials/style-'.$style.'.php';
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		
		$query = $this->build_portfolio_query( $settings );
		$the_query = new \WP_Query( $query );
		
		$extra_classes = array(
			'codeless-portfolio--style-'.$settings['style']
		);

		// Display posts
		if ( is_object( $the_query ) && $the_query->have_posts() ) : ?>

		<div class="codeless-portfolio codeless-portfolio--grid <?php echo implode( " ", $extra_classes ); ?>" data-cols="<?php echo esc_attr( $settings['columns'] ) ?>">
			<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
				<?php $this->get_template_part( $settings['style'] ) ?>
			<?php endwhile; ?>
		</div>

		<?php else: ?>
		<div class="codeless-portfolio codeless-portfolio--empty"><?php echo esc_html__( 'Please select portfolio items', 'elementor-portfolio' ) ?></div>
		<?php endif; ?>

		<?php
	}
}
