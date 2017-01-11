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