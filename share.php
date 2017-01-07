<div class="share">
<?php $share_options = get_option('share_options'); ?>
<?php if(empty($share_options['facebook'])){ ?>
<a href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>&amp;t=<?php esc_url(wp_title( ':', true, 'right' )); ?>" target="_blank" class="share_facebook"><i class="icon icon-facebook"></i> <?php esc_html_e( 'Facebook', 'liquid-corporate' ); ?></a>
<?php } ?>
<?php if(empty($share_options['twitter'])){ ?>
<a href="https://twitter.com/intent/tweet?text=<?php esc_url(wp_title( ':', true, 'right' )); ?>&url=<?php the_permalink(); ?>" target="_blank" class="share_twitter"><i class="icon icon-twitter"></i> <?php esc_html_e( 'Twitter', 'liquid-corporate' ); ?></a>
<?php } ?>
<?php if(empty($share_options['google-plus'])){ ?>
<a href="https://plusone.google.com/_/+1/confirm?hl=ja&amp;url=<?php the_permalink(); ?>" target="_blank" class="share_google"><i class="icon icon-google-plus"></i> <?php esc_html_e( 'Google+', 'liquid-corporate' ); ?></a>
<?php } ?>
<?php if(empty($share_options['hatena'])){ ?>
<a href="http://b.hatena.ne.jp/add?mode=confirm&url=<?php the_permalink(); ?>&title=<?php esc_url(wp_title( ':', true, 'right' )); ?>" target="_blank" class="share_hatena"><i class="icon icon-bookmark"></i> <?php esc_html_e( 'Hatena', 'liquid-corporate' ); ?></a>
<?php } ?>
<?php if(empty($share_options['pocket'])){ ?>
<a href="http://getpocket.com/edit?url=<?php the_permalink(); ?>&title=<?php esc_url(wp_title( ':', true, 'right' )); ?>" target="_blank" class="share_pocket"><i class="icon icon-checkmark"></i> <?php esc_html_e( 'Pocket', 'liquid-corporate' ); ?></a>
<?php } ?>
<?php if(empty($share_options['line'])){ ?>
<a href="http://line.me/R/msg/text/?<?php esc_url(wp_title( ':', true, 'right' )); ?>%0D%0A<?php the_permalink(); ?>" target="_blank" class="share_line"><i class="icon icon-bubble"></i> <?php esc_html_e( 'LINE', 'liquid-corporate' ); ?></a>
<?php } ?>
</div>