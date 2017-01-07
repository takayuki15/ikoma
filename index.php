<?php get_header(); ?>

         <!-- Slides -->
         <?php 
         $img_options = get_theme_mod('img_options');
         $text_options = get_option('text_options');
         if(!empty( $img_options['img01']) ): ?>
             <div class="cover">
             <div id="carousel-generic" class="carousel slide" data-ride="carousel">
             
              <div class="carousel-inner" role="listbox">
                <?php 
                    $textop[1] = ""; $textop[2] = ""; $textop[3] = "";
                    if(!empty($text_options["text01"])){ 
                        $textop[1] = '<div class="main"><h3>'.$text_options["text01"].'</h3></div>';
                    }
                    if(!empty($text_options["text02"])){ 
                        $textop[2] = '<div class="main"><h3>'.$text_options["text02"].'</h3></div>';
                    }
                    if(!empty($text_options["text03"])){ 
                        $textop[3] = '<div class="main"><h3>'.$text_options["text03"].'</h3></div>';
                    }
                ?>
                <?php 
                    if(!empty($img_options["img01"])){
                        echo '<div class="carousel-item active"><img src="'.$img_options["img01"].'" alt="">'.$textop[1].'</div>';
                    }
                    if(!empty($img_options["img02"])){
                        echo '<div class="carousel-item"><img src="'.$img_options["img02"].'" alt="">'.$textop[2].'</div>';
                    }
                    if(!empty($img_options["img03"])){
                        echo '<div class="carousel-item"><img src="'.$img_options["img03"].'" alt="">'.$textop[3].'</div>';
                    }
                ?>
              </div>
              
              <?php if(!empty( $img_options['img02']) ){ ?>
              <!-- Controls -->
              <a class="left carousel-control" href="#carousel-generic" role="button" data-slide="prev">
                <span class="icon icon-arrow-left2" aria-hidden="true"></span>
                <span class="sr-only"><?php esc_html_e( 'Previous', 'liquid-corporate' ); ?></span>
              </a>
              <a class="right carousel-control" href="#carousel-generic" role="button" data-slide="next">
                <span class="icon icon-arrow-right2" aria-hidden="true"></span>
                <span class="sr-only"><?php esc_html_e( 'Next', 'liquid-corporate' ); ?></span>
              </a>
              <!-- Indicators -->
              <ol class="carousel-indicators">
                <?php 
                    if(!empty($img_options["img01"])){
                        echo '<li data-target="#carousel-generic" data-slide-to="0" class="active"></li>';
                    }
                    if(!empty($img_options["img02"])){
                        echo '<li data-target="#carousel-generic" data-slide-to="1"></li>';
                    }
                    if(!empty($img_options["img03"])){
                        echo '<li data-target="#carousel-generic" data-slide-to="2"></li>';
                    }
                ?>
              </ol>
              <?php } ?>
            
             
              </div>
              </div>
              
              
          <?php else: ?>
          <!-- carousel -->
          <?php endif; ?>
        <!-- /Slides -->  

    <div class="mainpost">
     <div class="container">

      <?php if(! dynamic_sidebar('top_header')): ?><!-- no widget --><?php endif; ?>
        
          <!-- biz -->
          <?php $col_options = get_option('col_options'); if (!empty( $col_options['item01-icon'] )){ ?>
          <div class="biz">
           <div class="row">
             <div class="biz_list col-md-4">
                 <?php if (!empty( $col_options['item01-icon'] )){ ?><div class="icon_big"><i class="icon <?php echo $col_options['item01-icon']; ?>"></i></div><?php } ?>
                 <?php if (!empty( $col_options['item01-ttl'] )){ ?><div class="ttl"><?php echo $col_options['item01-ttl']; ?></div><?php } ?>
                 <?php if (!empty( $col_options['item01-txt'] )){ ?><div class="info"><?php echo $col_options['item01-txt']; ?></div><?php } ?>
             </div>
             <div class="biz_list col-md-4">
                 <?php if (!empty( $col_options['item02-icon'] )){ ?><div class="icon_big"><i class="icon <?php echo $col_options['item02-icon']; ?>"></i></div><?php } ?>
                 <?php if (!empty( $col_options['item02-ttl'] )){ ?><div class="ttl"><?php echo $col_options['item02-ttl']; ?></div><?php } ?>
                 <?php if (!empty( $col_options['item02-txt'] )){ ?><div class="info"><?php echo $col_options['item02-txt']; ?></div><?php } ?>
             </div>
             <div class="biz_list col-md-4">
                 <?php if (!empty( $col_options['item03-icon'] )){ ?><div class="icon_big"><i class="icon <?php echo $col_options['item03-icon']; ?>"></i></div><?php } ?>
                 <?php if (!empty( $col_options['item03-ttl'] )){ ?><div class="ttl"><?php echo $col_options['item03-ttl']; ?></div><?php } ?>
                 <?php if (!empty( $col_options['item03-txt'] )){ ?><div class="info"><?php echo $col_options['item03-txt']; ?></div><?php } ?>
             </div>
           </div>
          </div>
          <?php } ?>
          <!-- /biz -->
          
          <!-- pages -->
          <?php if (!empty( $col_options['tag'] )){ ?>
          </div>
          <div class="pages">
          <div class="container">
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
                    $src_info = wp_get_attachment_image_src($thumbnail_id, 'full');
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
                echo '<!-- <div class="col-xs-12 noarticles">'.esc_html__( 'No articles', 'liquid-corporate' ).'</div> -->';
                endif;
		       ?>
          </div>
          </div>
          </div>
          <div class="container">
          <?php } ?>
          <!-- /pages -->
          
          
      <div class="row">
       <div class="<?php liquid_col_options('mainarea'); ?> mainarea">        
        
         <?php $col_options = get_option('col_options');
           if(!empty( $col_options['cat02']) ){
               $posts_per_page = 5;
           }else{
               $posts_per_page = 10;
           }
         ?>
         
         <?php if(empty($col_options['cat01']) && empty($col_options['cat02']) && empty($col_options['cat03'])){ ?>
          <!-- single 0 -->
          <div class="ttl"><i class="icon icon-list"></i> <?php esc_html_e( 'What&rsquo;s New', 'liquid-corporate' ); ?></div>
          <div class="row single">
             <?php wp_reset_query(); //query_posts初期化
              $i = 0; if (have_posts()) : while ( have_posts() ) : the_post(); $i++; ?>
                <?php 
                //cat
                $cat = get_the_category();
                if(!empty($cat)){
                    if($cat[0]->parent){
                        $parent_info = get_category($cat[0]->parent);
                        $cat_name = $parent_info->name;
                        $cat_slug = $parent_info->slug;
                    }else{
                        $cat_info = get_category($cat[0]->cat_ID);
                        $cat_name = $cat_info->name;
                        $cat_slug = $cat_info->slug;
                    }
                }
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
                       <?php if($cat){ echo '<span class="post_cat"><i class="icon icon-folder"></i> '.$cat_name.'</span>';} ?>
                       <h3 class="list-title post_ttl">
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
                echo '<!-- <div class="col-xs-12 noarticles">'.esc_html__( 'No articles', 'liquid-corporate' ).'</div> -->';
                endif;
		       ?>
          </div>
          <?php liquid_paging_nav(); ?>
         <?php } ?>
         <?php if(!empty( $col_options['cat01']) ){ ?>
          <!-- single 1 -->
          <?php //cat
	           $cat_id  = get_category_by_slug( $col_options['cat01'] );
	           $cat = get_category( $cat_id );
               $cat_name = $cat->name;
               $cat_link = get_category_link( $cat_id ); ?>
          <div class="ttl"><i class="icon icon-list"></i> <?php echo $cat_name; ?></div>
          <div class="row single">
             <?php //1カテゴリのみの場合10件表示
              query_posts( array('category_name' => $col_options['cat01'], 'posts_per_page' => $posts_per_page) );
              $i = 0; if (have_posts()) : while ( have_posts() ) : the_post(); $i++; ?>
                <?php 
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
                       <?php if($cat){ echo '<span class="post_cat"><i class="icon icon-folder"></i> '.$cat_name.'</span>';} ?>
                       <h3 class="list-title post_ttl">
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
                echo '<!-- <div class="col-xs-12 noarticles">'.esc_html__( 'No articles', 'liquid-corporate' ).'</div> -->';
                endif;
		       ?>
          </div>
          <div class="more"><a href="<?php echo $cat_link; ?>"><?php esc_html_e( 'More &gt;&gt;', 'liquid-corporate' ); ?></a></div>
         <?php } ?>
         <?php if(!empty( $col_options['cat02']) ){ ?>
          <!-- single 2 -->
          <?php //cat
	           $cat_id  = get_category_by_slug( $col_options['cat02'] );
	           $cat = get_category( $cat_id );
               $cat_name = $cat->name;
               $cat_link = get_category_link( $cat_id ); ?>
          <div class="ttl"><i class="icon icon-list"></i> <?php echo $cat_name; ?></div>
          <div class="row single">
             <?php query_posts( array('category_name' => $col_options['cat02'], 'posts_per_page' => 5) );
              $i = 0; if (have_posts()) : while ( have_posts() ) : the_post(); $i++; ?>
                <?php 
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
                       <?php if($cat){ echo '<span class="post_cat"><i class="icon icon-folder"></i> '.$cat_name.'</span>';} ?>
                       <h3 class="list-title post_ttl">
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
                echo '<!-- <div class="col-xs-12 noarticles">'.esc_html__( 'No articles', 'liquid-corporate' ).'</div> -->';
                endif;
		       ?>
          </div>
          <div class="more"><a href="<?php echo $cat_link; ?>"><?php esc_html_e( 'More &gt;&gt;', 'liquid-corporate' ); ?></a></div>
         <?php } ?>
         <?php if(!empty( $col_options['cat03']) ){ ?>
          <!-- single 3 -->
          <?php //cat
	           $cat_id  = get_category_by_slug( $col_options['cat03'] );
	           $cat = get_category( $cat_id );
               $cat_name = $cat->name;
               $cat_link = get_category_link( $cat_id ); ?>
          <div class="ttl"><i class="icon icon-list"></i> <?php echo $cat_name; ?></div>
          <div class="row single" id="main">
             <?php query_posts( array('category_name' => $col_options['cat03'], 'posts_per_page' => 5) );
              $i = 0; if (have_posts()) : while ( have_posts() ) : the_post(); $i++; ?>
                <?php 
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
                       <?php if($cat){ echo '<span class="post_cat"><i class="icon icon-folder"></i> '.$cat_name.'</span>';} ?>
                       <h3 class="list-title post_ttl">
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
                echo '<!-- <div class="col-xs-12 noarticles">'.esc_html__( 'No articles', 'liquid-corporate' ).'</div> -->';
                endif;
		       ?>
          </div>
          <div class="more"><a href="<?php echo $cat_link; ?>"><?php esc_html_e( 'More &gt;&gt;', 'liquid-corporate' ); ?></a></div>
         <?php } ?>
         
         
       </div><!-- /col -->
       <?php get_sidebar(); ?>
      </div><!-- /row -->
      
        <!-- map -->
         <?php $com_options = get_option('com_options'); ?>
         <?php if(!empty( $com_options['map']) ){ ?>
            <div class="ttl"><i class="icon icon-map2"></i> <?php esc_html_e( 'Map', 'liquid-corporate' ); ?></div>
            <div class="map"><?php echo $com_options['map']; ?></div>
         <?php } ?>
         
      <?php if(! dynamic_sidebar('top_footer')): ?><!-- no widget --><?php endif; ?> 
    
     
     </div>
    </div>

<?php get_footer(); ?>   
