<?php get_header(); ?>
   
    <div <?php post_class('detail'); ?>>
        <div class="container">
          <div class="row">
           <div class="<?php liquid_col_options('mainarea'); ?> mainarea">

           <?php if (have_posts()) : ?>
           <?php while (have_posts()) : the_post(); ?>
           
            <h1 class="ttl_h1 entry-title" title="<?php the_title(); ?>"><?php the_title(); ?></h1>
            
            <!-- pan -->
            <?php $cat = get_the_category(); ?>
            <ul class="breadcrumb" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
              <li><a href="<?php echo home_url(); ?>" itemprop="url"><span itemprop="title">TOP</span></a></li>
              <li><?php $catstr = get_category_parents($cat[0]->term_id,TRUE,'</li><li>'); 
                  $search = array('href', '">', '</a>');
                  $replace = array('itemprop="url" href', '"><span itemprop="title">', '</span></a>');
                  $catstr = str_replace($search, $replace, $catstr);
                  echo substr($catstr, 0, strlen($catstr) -4 ); ?>
              <li class="active"><?php the_title(); ?></li>
            </ul>
                       
           
            <div class="detail_text">

                <?php //share
                $share_options = get_option('share_options');
                if(isset($share_options['all']) && $share_options['all'] == "2" || empty($share_options['all'])){
                    get_template_part('share');
                }
                ?>

                <div class="post_meta">
                <span class="post_time">
                 <i class="icon icon-clock" title="<?php echo get_the_time("Y/m/d H:i"); ?>"></i><?php if ( get_the_date() != get_the_modified_date() ) : ?> <i class="icon icon-spinner11" title="<?php echo get_the_modified_date("Y/m/d H:i"); ?>"></i><?php endif; ?> <time class="date updated"><?php echo get_the_date(); ?></time>
                </span>
                <?php if($cat){ ?>
                    <span class="post_cat"><i class="icon icon-folder"></i>
                    <?php the_category(', '); ?>
                    </span>
                <?php } ?>
                </div>
                
                <?php //thumbnail
                $col_options = get_option('col_options');
                if(empty($col_options['thumbnail'])){
                    if(has_post_thumbnail()) { the_post_thumbnail(); }   
                }
                ?>
                
                <?php if(! dynamic_sidebar('main_head')): ?><!-- no widget --><?php endif; ?>
                
                <!-- content -->
                <div class="post_body"><?php the_content(); ?></div>
                <?php
                // ページング
                $args = array(
                    'before' => '<nav><ul class="page-numbers">', 
                    'after' => '</ul></nav>', 
                    'link_before' => '<li>', 
                    'link_after' => '</li>'
                );
                wp_link_pages( $args );
                ?>
                <?php if(! dynamic_sidebar('main_foot')): ?><!-- no widget --><?php endif; ?>
                <?php the_tags( '<ul class="list-inline tag"><li>', '</li><li>', '</li></ul>' ); ?>
                
                <?php //share
                $share_options = get_option('share_options');
                if(empty($share_options['all'])){
                    get_template_part('share');
                }
                ?>
                
                <?php $col_options = get_option('col_options'); ?>
                <!-- form -->
                <?php if(!empty($col_options['form'])){ ?>
                <div class="formbox">
                    <?php if(!empty($col_options['form2'])){
                       $formbox = $col_options['form2'];
                    }else{
                       $formbox = esc_html__( 'Contact', 'liquid-corporate' );
                    } ?>
                    <a href="<?php echo $col_options['form']; ?>"><i class="icon icon-mail"></i> <?php echo $formbox; ?></a>
                </div>
                <?php } ?>
                
            </div>
           <?php endwhile; ?>
           <div class="detail_comments">
               <?php comments_template(); ?>
           </div>
           <?php else : ?>
               <p><?php esc_html_e( 'No articles', 'liquid-corporate' ); ?></p>
               <?php get_search_form(); ?>
           <?php endif; ?>         
           
           
            <nav>
              <ul class="pager">
                <?php
                $prev_post = get_previous_post(true);
                $next_post = get_next_post(true);
                if (!empty( $prev_post )) {
                    echo '<li class="pager-prev"><a href="'.get_permalink( $prev_post->ID ).'" title="'.htmlspecialchars($prev_post->post_title).'">'.esc_html__( '&laquo; Prev', 'liquid-corporate' ).'</a></li>';
                }
                //if (!empty( $cat )) {
                //    echo '<li class="pager-archive">';
                //    the_category('</li><li class="pager-archive">');
                //    echo '</li>';
                //}
                if (!empty( $next_post )) {
                    echo '<li class="pager-next"><a href="'.get_permalink( $next_post->ID ).'" title="'.htmlspecialchars($next_post->post_title).'">'.esc_html__( 'Next &raquo;', 'liquid-corporate' ).'</a></li>';
                } ?>
                </ul>
            </nav>
            
           
           <div class="recommend">
           <div class="ttl"><i class="icon icon-list"></i> <?php esc_html_e( 'Recommend', 'liquid-corporate' ); ?></div>
              <div class="row">
               <?php
                  //recommend
                  $original_post = $post;
                  $tags = wp_get_post_tags($post->ID);
                  $tagIDs = array();
                  if ($tags) {
                      $tagcount = count($tags);
                      for ($i = 0; $i < $tagcount; $i++) {
                          $tagIDs[$i] = $tags[$i]->term_id;
                      }
                      $args=array(
                      'tag__in' => $tagIDs,
                      'post__not_in' => array($post->ID),
                      'posts_per_page' => 4,
                      'ignore_sticky_posts' => 1
                      );
                  }elseif($cat){
                      $args=array(
                      'cat' => $cat[0]->cat_ID,
                      'post__not_in' => array($post->ID),
                      'posts_per_page' => 4,
                      'ignore_sticky_posts' => 1
                      );
                  }else{
                      $args=array(
                      'post__not_in' => array($post->ID),
                      'posts_per_page' => 4,
                      'ignore_sticky_posts' => 1
                      );
                  }
                  $my_query = new WP_Query($args);
                  if( $my_query->have_posts() ) {
                  while ($my_query->have_posts()) : $my_query->the_post();
                //thumb
                $src = "";
                if(has_post_thumbnail($post->ID)){
                    // アイキャッチ画像を設定済みの場合
                    $thumbnail_id = get_post_thumbnail_id($post->ID);
                    $src_info = wp_get_attachment_image_src($thumbnail_id, $size = array(300,200));
                    $src = $src_info[0];
                }else{
                    // アイキャッチが設定されていない場合
                    if(preg_match('/<img([ ]+)([^>]*)src\=["|\']([^"|^\']+)["|\']([^>]*)>/',$post->post_content,$img_array)){
                        $src = $img_array[3];
                    }else{
                        $src = get_stylesheet_directory_uri().'/images/noimage.png';
                    }
                }
                //post_class 
                $classes = array( 'list', 'col-md-12' );
                ?>

               <article <?php post_class( $classes );?>>
                 <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>" class="post_links">
                  <div class="list-block">
                   <div class="post_thumb" style="background-image: url('<?php echo $src; ?>')"><span>&nbsp;</span></div>
                   <div class="list-text">
                       <span class="post_time"><i class="icon icon-clock"></i> <?php echo get_the_date(); ?></span>
                       <h3 class="list-title post_ttl">
                           <?php the_title(); ?>
                       </h3>
                   </div>
                  </div>
                 </a>
               </article>
                <?php endwhile; wp_reset_query(); ?>
                <?php } else { ?>
                <div class="col-xs-12"><?php esc_html_e( 'No articles', 'liquid-corporate' ); ?></div>
                <?php } ?>
              </div>
            </div>
            
            
            
           </div><!-- /col -->
           <?php get_sidebar(); ?>
           
         </div>
        </div>
    </div>


<?php get_footer(); ?>