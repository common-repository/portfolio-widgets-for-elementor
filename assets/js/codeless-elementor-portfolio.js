( function( $ ) {
	/**
 	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */ 
	var WidgetCodelessPortfolioGrid = function( $scope, $ ) {
		var $codeless_portfolio_elems = $( '.codeless-portfolio--grid', $scope );

		$codeless_portfolio_elems.each(function(){
			var $codeless_portfolio = $(this);

			$codeless_portfolio.imagesLoaded( function() {
				$codeless_portfolio.isotope({
					itemSelector: '.codeless-item',
					percentPosition: true,
					layoutMode: 'masonry'
				});
			});
		})
		
	};
	
	// Make sure you run this code under Elementor.
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/codeless-portfolio-grid.default', WidgetCodelessPortfolioGrid );
		
		if( typeof elementor !== "undefined" ){
			elementor.channels.editor.on( 'change:section', function(childView, editedElementView){
				WidgetCodelessPortfolioGrid(editedElementView.$el, $)
			});
			elementor.channels.editor.on( 'change:column', function(childView, editedElementView){
				WidgetCodelessPortfolioGrid(editedElementView.$el, $)
			});
		}
	} );
} )( jQuery );
