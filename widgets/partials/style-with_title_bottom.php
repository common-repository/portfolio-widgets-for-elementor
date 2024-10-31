<div class="codeless-item codeless-msnr-small">
    <div class="codeless-item__wrapper">
        <div class="codeless-item__inner-wrapper">
            <div class="codeless-item__media">
                <img src="<?php echo esc_url( get_the_post_thumbnail_url(get_the_ID(),'full') ) ?>" />
                <div class="codeless-item__overlay"></div>
            </div>
            
            <h3><a href="<?php echo get_permalink() ?>"><?php echo get_the_title() ?></a></h3>
        </div>
    </div>
</div>