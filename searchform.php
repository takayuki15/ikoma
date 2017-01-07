<div class="searchform">  
    <form action="<?php echo home_url( '/' ); ?>" method="get" class="search-form">
        <fieldset class="form-group">
            <label class="screen-reader-text"><?php esc_html_e( 'Search', 'liquid-corporate' ); ?></label>
            <input type="text" name="s" value="<?php the_search_query(); ?>" placeholder="<?php esc_html_e( 'Search', 'liquid-corporate' ); ?>" class="form-control search-text">
            <button type="submit" value="Search" class="btn btn-primary"><i class="icon icon-search"></i></button>
        </fieldset>
    </form>
</div>