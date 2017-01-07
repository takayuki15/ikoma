<div class="pagetop">
    <a href="#top"><i class="icon icon-arrow-up2"></i></a>
</div>
      
<footer>
        <div class="container">
          <div class="row">
            <?php if(! dynamic_sidebar('footer')): ?>
             <!-- no widget -->
            <?php endif; ?>
          </div>
        </div>
        
        <div class="foot">
            <div class="container com">
            <?php $com_options = get_option('com_options'); ?>
            <a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>" class="logo">
               <?php if(get_header_image()): ?>
                <img src="<?php header_image(); ?>" alt="<?php bloginfo('name'); ?>">
               <?php else: ?>
                <?php echo bloginfo('name'); ?>
               <?php endif; ?>
            </a>
            <?php if(!empty( $com_options['name']) ){ ?><div class="com_name"><?php echo $com_options['name']; ?></div><?php } ?>
            <?php if(!empty( $com_options['tel']) ){ ?><br><span class="com_tel"><?php esc_html_e( 'TEL:', 'liquid-corporate' ); ?> <?php echo $com_options['tel']; ?> </span><?php } ?>
            <?php if(!empty( $com_options['fax']) ){ ?><span class="com_fax"><?php esc_html_e( 'FAX:', 'liquid-corporate' ); ?> <?php echo $com_options['fax']; ?></span><?php } ?>
            <?php if(!empty( $com_options['open']) ){ ?><div class="com_open"><?php echo $com_options['open']; ?></div><?php } ?>
            <?php if(!empty( $com_options['adr']) ){ ?><div class="com_adr"><?php echo $com_options['adr']; ?></div><?php } ?>
            </div>
            
          <div class="container sns">
            <?php $sns_options = get_option('sns_options'); ?>
            <?php if(!empty($sns_options['facebook'])){ ?>
            <a href="<?php echo $sns_options['facebook']; ?>" target="_blank"><i class="icon icon-facebook"></i> <?php esc_html_e( 'Facebook', 'liquid-corporate' ); ?></a>
            <?php } ?>
            <?php if(!empty($sns_options['twitter'])){ ?>
            <a href="<?php echo $sns_options['twitter']; ?>" target="_blank"><i class="icon icon-twitter"></i> <?php esc_html_e( 'Twitter', 'liquid-corporate' ); ?></a>
            <?php } ?>
            <?php if(!empty($sns_options['google-plus'])){ ?>
            <a href="<?php echo $sns_options['google-plus']; ?>" target="_blank"><i class="icon icon-google-plus"></i> <?php esc_html_e( 'Google+', 'liquid-corporate' ); ?></a>
            <?php } ?>
            <?php if(!empty($sns_options['tumblr'])){ ?>
            <a href="<?php echo $sns_options['tumblr']; ?>" target="_blank"><i class="icon icon-tumblr"></i> <?php esc_html_e( 'Tumblr', 'liquid-corporate' ); ?></a>
            <?php } ?>
            <?php if(!empty($sns_options['instagram'])){ ?>
            <a href="<?php echo $sns_options['instagram']; ?>" target="_blank"><i class="icon icon-instagram"></i> <?php esc_html_e( 'Instagram', 'liquid-corporate' ); ?></a>
            <?php } ?>
            <?php if(!empty($sns_options['youtube'])){ ?>
            <a href="<?php echo $sns_options['youtube']; ?>" target="_blank"><i class="icon icon-youtube"></i> <?php esc_html_e( 'YouTube', 'liquid-corporate' ); ?></a>
            <?php } ?>
            <?php if(!empty($sns_options['flickr'])){ ?>
            <a href="<?php echo $sns_options['flickr']; ?>" target="_blank"><i class="icon icon-flickr2"></i> <?php esc_html_e( 'Flickr', 'liquid-corporate' ); ?></a>
            <?php } ?>
            <?php if(!empty($sns_options['pinterest'])){ ?>
            <a href="<?php echo $sns_options['pinterest']; ?>" target="_blank"><i class="icon icon-pinterest"></i> <?php esc_html_e( 'Pinterest', 'liquid-corporate' ); ?></a>
            <?php } ?>
            <?php if(empty($sns_options['feed'])){ ?>
            <a href="<?php echo home_url(); ?>/feed/"><i class="icon icon-feed2"></i> <?php esc_html_e( 'Feed', 'liquid-corporate' ); ?></a>
            <?php } ?>
          </div>
        </div>
        
        <div class="copy">
        <?php esc_html_e( '(C)', 'liquid-corporate' ); ?> <?php echo date('Y'); ?> <a href="<?php echo home_url(); ?>">
        <?php if(!empty( $com_options['name']) ): ?>
        <?php echo $com_options['name']; ?>
        <?php else: ?>
        <?php bloginfo('name'); ?>
        <?php endif; ?>
        </a><?php esc_html_e( '. All right reserved.', 'liquid-corporate' ); ?>
        <!-- Powered by -->
        <?php $lqd_options = get_option('lqd_options'); if(!empty($lqd_options['ls']) && !empty($lqd_options['cp'])): ?>
        <?php else: ?>
        <?php esc_html_e( 'Theme by', 'liquid-corporate' ); ?> <a href="https://lqd.jp/wp/" rel="nofollow" title="<?php esc_html_e( 'Responsive WordPress Theme LIQUID PRESS', 'liquid-corporate' ); ?>"><?php esc_html_e( 'LIQUID PRESS', 'liquid-corporate' ); ?></a>.
        <?php endif; ?>
        <!-- /Powered by -->
        </div>

    </footer>
      
</div><!--/site-wrapper-->

<?php wp_footer(); ?>

</body>
</html>