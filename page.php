<?php get_header(); ?>

    <div class="detail page">
        <div class="container">
          <div class="row">
           <div class="<?php liquid_col_options('mainarea'); ?> mainarea">
          
           <?php if (have_posts()) : ?>
           <?php while (have_posts()) : the_post(); ?>
            <h1 class="ttl_h1" title="<?php the_title(); ?>"><?php the_title(); ?></h1>

            <!-- pan -->
            <?php
                $cat_name = get_the_title($post->post_parent);
                $cat_slug = get_page_link($post->post_parent);
            ?>
            <ul class="breadcrumb" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
              <li><a href="<?php echo home_url(); ?>" itemprop="url"><span itemprop="title">TOP</span></a></li>
              <?php if($post->post_parent){ echo '<li><a href="'.$cat_slug.'" itemprop="url"><span itemprop="title">'.$cat_name.'</span></a></li>'; } ?>
              <li class="active"><?php the_title(); ?></li>
            </ul>


            <div class="detail_text">
               
                <?php //share
                $share_options = get_option('share_options');
                if(empty($share_options['all'])){
                    get_template_part('share');
                }
                ?>
                
                <div class="post_meta">
                <?php if(has_post_thumbnail()) { the_post_thumbnail(); } ?>
                </div>
                
                <?php if(! dynamic_sidebar('page_head')): ?><!-- no widget --><?php endif; ?>
                <div class="post_body"><?php the_content(); ?></div>
                <?php if(! dynamic_sidebar('page_foot')): ?><!-- no widget --><?php endif; ?>
                
            </div>
           <?php endwhile; ?>
           <div class="detail_comments">
               <?php comments_template(); ?>
           </div>
           <?php else : ?>
               <p><?php esc_html_e( 'No articles', 'liquid-corporate' ); ?></p>
               <?php get_search_form(); ?>
           <?php endif; ?>

           
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

            
           </div><!-- /col -->
           <?php get_sidebar(); ?>
           
         </div><!-- /row -->
         
         
          <!-- pages -->
          <?php $col_options = get_option('col_options'); ?>
          <?php if (!empty( $col_options['tag'] )){ ?>
          <div class="pages_foot">
           <hr>
           <div class="row">
             <?php query_posts( array('post_type' => array('post','page'), 'posts_per_page' => -1, 'tag' => $col_options['tag'] ) );
              $i = 0; if (have_posts()) : while ( have_posts() ) : the_post(); $i++;
                // $postslist = get_posts(); foreach ($postslist as $post) : setup_postdata($post); ?>
                <?php
                //thumb
                $src = "";
                if(has_post_thumbnail($post->ID)){
                    // アイキャッチ画像を設定済みの場合
                    $thumbnail_id = get_post_thumbnail_id($post->ID);
                    $src_info = wp_get_attachment_image_src($thumbnail_id, 'large');
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
                $classes = array( 'list', 'col-md-4', 'list_big' );
                ?>
               <article <?php post_class( $classes );?>>
                 <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>" class="post_links">
                  <div class="list-block">
                   <div class="post_thumb" style="background-image: url('<?php echo $src; ?>')"><span>&nbsp;</span></div>
                   <div class="list-text">
                       <span class="post_time"></span>
                       <h3 class="list-title post_ttl text-center">
                           <?php the_title(); ?>
                       </h3>
                   </div>
                  </div>
                 </a>
               </article>
               <?php 
                //endforeach;
                endwhile;
                else : 
                echo '<div class="col-xs-12">'.esc_html__( 'No articles', 'liquid-corporate' ).'</div>';
                endif;
		       ?>
           </div>
          </div>
          <?php } ?>
          <!-- /pages -->
         
        </div>
    </div>

   
<?php get_footer(); ?>
