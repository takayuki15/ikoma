<!DOCTYPE html>
<?php $lang_options = get_option('lang_options'); if (!empty( $lang_options['c001'] )){ ?><html lang="<?php echo $lang_options['c001']; ?>">
<?php }else{ ?><html <?php language_attributes(); ?>><?php } ?>
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# blog: http://ogp.me/ns/blog#">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php
if (is_single()){
    echo '<meta name="description" content="'; setup_postdata($post); echo strip_tags(get_the_excerpt()); echo '">'."\n";
} else {
    echo '<meta name="description" content="'; bloginfo('description'); echo '">'."\n";
} ?>
<meta name="author" content="<?php bloginfo('name'); ?>">
<title><?php wp_title( ':', true, 'right' ); ?></title>
<link rel="start" href="<?php echo home_url(); ?>" title="<?php esc_html_e( 'TOP', 'liquid-corporate' ); ?>">
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?>" href="/feed">
<!-- OGP -->
<meta property="og:type" content="blog">
<meta property="og:site_name" content="<?php bloginfo('name'); ?>">
<?php
if ( is_single() || is_page() ){
    echo '<meta property="og:description" content="'; setup_postdata($post); echo strip_tags(get_the_excerpt()); echo '">'."\n";
    echo '<meta property="og:title" content="'; the_title(); echo ' | '; bloginfo('name'); echo '">'."\n";
    echo '<meta property="og:url" content="'; the_permalink(); echo '">'."\n";
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
    echo '<meta property="og:image" content="'.$src.'">'."\n";
    
    $prev_post = get_previous_post();
    $next_post = get_next_post();
    if (!empty( $prev_post )) {
        echo '<link rel="prev bookmark" href="'.get_permalink( $prev_post->ID ).'" title="'.htmlspecialchars($prev_post->post_title).'">'."\n";
    }
    if (!empty( $next_post )) {
        echo '<link rel="next bookmark" href="'.get_permalink( $next_post->ID ).'" title="'.htmlspecialchars($next_post->post_title).'">'."\n";
    }
    
} else {
    echo '<meta property="og:description" content="'; bloginfo('description'); echo '">'."\n";
    echo '<meta property="og:title" content="'; bloginfo('name'); echo '">'."\n";
    echo '<meta property="og:url" content="'; echo esc_url(home_url('/')); echo '">'."\n";
     $img_options = get_theme_mod('img_options');
     if(!empty( $img_options['img01']) ){
         echo '<meta property="og:image" content="'.$img_options['img01'].'">'."\n";
     }
}
?>
<!-- twitter:card -->
<meta name="twitter:card" content="summary_large_image">
<?php $sns_options = get_option('sns_options');
    if(!empty($sns_options['twitter'])){
    $twitter_site = preg_replace('(^https?://twitter.com/)', '', $sns_options['twitter']);
    echo '<meta name="twitter:site" content="@'.$twitter_site.'">';
}
?>

<?php wp_head(); ?>

<!-- JS -->
<!--[if lt IE 9]>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<script src="<?php echo esc_url(get_template_directory_uri()); ?>/js/respond.js"></script>
<![endif]-->

<?php $html_options = get_option('html_options'); if (!empty( $html_options['ga'] )){ ?>
<!-- GA -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '<?php echo $html_options['ga']; ?>', 'auto');
  ga('send', 'pageview');

</script>
<?php } ?>

<!-- hreflang -->
<?php 
    //uri
    $dir = $lang_options['c001'];
    $host = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"];
    $path = str_replace( $host, "", home_url() );
    $uri = str_replace( $path, "", $_SERVER['REQUEST_URI'] );
    if (!empty( $dir )) {
        $search = '/\/'.$dir.'$/';
        //home
        if (!empty( $lang_options['c002'] ) && !empty( $lang_options['p002'] )) {
            if (empty($lang_options['p001'])){ //isroot
                $home_002 = home_url('/').$lang_options['c002'];
            }else{
                $home_002 = rtrim(home_url(), $dir).$lang_options['c002'];
            }
        }else{
            $home_002 = preg_replace($search, '', home_url());
        }
        //home
        if (!empty( $lang_options['c003'] ) && !empty( $lang_options['p003'] )) {
            if (empty($lang_options['p001'])){ //isroot
                $home_003 = home_url('/').$lang_options['c003'];
            }else{
                $home_003 = rtrim(home_url(), $dir).$lang_options['c003'];
            }
        }else{
            $home_003 = preg_replace($search, '', home_url());
        }
    }
    //lang none
    if ( is_single() || is_page() ) {
        $lang = get_post_meta( $post->ID, 'lang', true );
    }
?>
<?php if (!empty( $lang_options['c001'] ) && empty( $lang )){ ?><link rel="alternate" hreflang="<?php echo $lang_options['c001']; ?>" href="<?php echo home_url().$uri; ?>"><?php } ?>
<?php if (!empty( $lang_options['c002'] ) && empty( $lang )){ ?><link rel="alternate" hreflang="<?php echo $lang_options['c002']; ?>" href="<?php echo $home_002.$uri; ?>"><?php } ?>
<?php if (!empty( $lang_options['c003'] ) && empty( $lang )){ ?><link rel="alternate" hreflang="<?php echo $lang_options['c003']; ?>" href="<?php echo $home_003.$uri; ?>"><?php } ?>

<style type="text/css">
    /*  customize  */
    <?php $options =  get_option('color_options'); ?>
    <?php if(!empty($options['color'])){ ?>
    body {
        color: <?php echo esc_html($options['color']); ?> !important;
    }
    <?php } ?>
    <?php if(!empty($options['color2'])){ ?>
    .carousel-indicators .active, .icon_big, .navbar-nav > .nav-item:last-child a {
        background-color: <?php echo esc_html($options['color2']); ?> !important;
    }
    .post_body h1 span, .post_body h2 span, .ttl span,
    .archive .ttl_h1, .search .ttl_h1, .breadcrumb, .headline, .formbox a {
        border-color: <?php echo esc_html($options['color2']); ?>;
    }
    .navbar .current-menu-item, .navbar .current-menu-parent, .navbar .current_page_item {
        color: <?php echo esc_html($options['color2']); ?> !important;
    }
    <?php } ?>
    <?php if(!empty($options['color3'])){ ?>
    a, a:hover, a:active, a:visited {
        color: <?php echo esc_html($options['color3']); ?>;
    }
    .post_body a, .post_body a:hover, .post_body a:active, .post_body a:visited {
        color: <?php echo esc_html($options['color3']); ?>;
    }
    <?php } ?>
    <?php if(!empty($options['color4'])){ ?>
    .wrapper, .dropdown-menu, .dropdown-item:focus, .dropdown-item:hover {
        background-color: <?php echo esc_html($options['color4']); ?>;
    }
    <?php } ?>
    /*  custom head  */
    <?php $head_options = get_option('head_options'); ?>
    <?php if(!empty($head_options['css'])){ ?>
    <?php echo $head_options['css']; ?>
    <?php } ?>
</style>
<?php if(!empty($head_options['meta'])){ ?>
<?php echo $head_options['meta']; ?>
<?php } ?>
</head>


<body <?php body_class(); ?>>

<!-- FB -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.4";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<a id="top"></a>
 <div class="wrapper">
   
    <div class="headline">
    
    <div class="logo_text">
      <div class="container">
        <div class="row">
            <div class="col-sm-3 col-sm-push-9 col-xs-12">
             <?php if (!empty( $lang_options['n001'] )){ ?>
                <div class="lang">
                    <span class="lang_001" title="<?php echo $lang_options['c001']; ?>"><?php echo $lang_options['n001']; ?></span>
                    <?php if (!empty( $lang_options['n002'] ) && empty( $lang ) ){ ?><a href="<?php echo $home_002.$uri; ?>" class="lang_002" title="<?php echo $lang_options['c002']; ?>"><?php echo $lang_options['n002']; ?></a><?php } ?>
                    <?php if (!empty( $lang_options['n003'] ) && empty( $lang ) ){ ?><a href="<?php echo $home_003.$uri; ?>" class="lang_003" title="<?php echo $lang_options['c003']; ?>"><?php echo $lang_options['n003']; ?></a><?php } ?>
                </div>
             <?php } ?>
            </div>
            <div class="col-sm-9 col-sm-pull-3 col-xs-12">
                <?php if ( is_single() || is_page() || is_category() ){ ?>
                    <div class="subttl"><?php bloginfo('description'); ?></div>
                <?php } else { ?>
                    <h1 class="subttl"><?php bloginfo('description'); ?></h1>
                <?php } ?>
            </div>
        </div>
      </div>
    </div>
    
     <div class="container">
      <div class="row">
       <div class="col-sm-6">
        <a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>" class="logo">
           <img src="<?php echo home_url(); ?>/wp-content/uploads/2017/01/logo.png"  alt="<?php bloginfo('name'); ?>" />
        </a>
       </div>
       <div class="col-sm-6">
        <div class="com">
        <?php $com_options = get_option('com_options'); ?>
        <?php if(!empty( $com_options['name']) ){ ?><div class="com_name"><?php echo $com_options['name']; ?></div><?php } ?>
        <?php if(!empty( $com_options['tel']) ){ ?><!-- <div class="com_tel"><?php esc_html_e( '', 'liquid-corporate' ); ?> <a id="com_tel"><?php echo $com_options['tel']; ?></a></div> --><?php } ?>
        <img src="<?php echo home_url(); ?>/wp-content/uploads/2017/01/icon_tel.png" />
        <?php if(!empty( $com_options['open']) ){ ?><div class="com_open"><?php echo $com_options['open']; ?></div><?php } ?>
        <a href="contact"><img src="<?php echo home_url(); ?>/wp-content/uploads/2017/01/btn_mail.png" /></a>
        </div>
       </div>
      </div>
     </div>
    </div>
   
    <nav class="navbar navbar-light bg-faded">
     <div class="container">
        <!-- Global Menu -->
        <?php if ( has_nav_menu( 'global-menu' ) ): ?>
        <?php wp_nav_menu(array(
            'theme_location'  => 'global-menu',
            'menu_class'      => 'nav navbar-nav',
            'container'       => false,
            'items_wrap'      => '<ul id="%1$s" class="%2$s nav navbar-nav">%3$s</ul>'
        )); ?>
          <button type="button" class="navbar-toggle collapsed">
            <span class="sr-only"><?php esc_html_e( 'Toggle navigation', 'liquid-corporate' ); ?></span>
            <span class="icon-bar top-bar"></span>
            <span class="icon-bar middle-bar"></span>
            <span class="icon-bar bottom-bar"></span>
          </button>
        <?php else: ?>
            <!-- Global Menu is not set. -->
        <?php endif; ?>
     </div>
     <div class="container searchform_nav none hidden-md-up">
      <?php get_search_form(); ?>
     </div>
    </nav>
    <?php if ( is_front_page() ) { ?>
      <div class="kv">
        <img src="<?php echo home_url(); ?>/wp-content/uploads/2017/01/kv.png" alt="埼玉県の建設業許可申請をお考えのみなさま！"/>
      </div>
      <div class="kv_sp">
        <img src="<?php echo home_url(); ?>/wp-content/uploads/2017/01/kv_sp.png" alt="埼玉県の建設業許可申請をお考えのみなさま！"/>
    <?php } ?>
    </div>