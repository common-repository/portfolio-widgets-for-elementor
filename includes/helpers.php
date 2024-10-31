<?php

function codeless_get_portfolio_categories( ){
    $return = array();

    $terms = get_terms( 'portfolio_entries' );

    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
        foreach ( $terms as $term ) {
            $return[ $term->term_id ] = $term->name; 
        }
    }

    return $return;
}

function codeless_get_portfolio_items( ){
    $return = array();

    $items = get_posts( array(
        'post_type' => 'portfolio',
        'posts_per_page' => -1,
        'orderby' 		=> 'title',
        'order' 		=> 'ASC'
    ));

    if($items)
        foreach ($items as $item){
            $return[ $item->ID ] = get_the_title( $item->ID );
        }

    return $return;
}