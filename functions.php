<?php
/*
Author: LIQUID DESIGN
Author URI: https://lqd.jp/
*/

// ------------------------------------
// scripts and styles
// ------------------------------------
$liquid_theme = wp_get_theme();
function liquid_scripts_styles() {
    global $liquid_theme;
    wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array() );
    wp_enqueue_style( 'icomoon', get_template_directory_uri() . '/css/icomoon.css', array() );
    wp_enqueue_style( 'liquid-style', get_stylesheet_uri(), array(), $liquid_theme->Version );
    wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array( 'jquery' ) );
    wp_enqueue_script( 'liquid-script', get_template_directory_uri() . '/js/common.min.js', array( 'jquery' ) );
    if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
}
add_action( 'wp_enqueue_scripts', 'liquid_scripts_styles' );

// title
if ( ! function_exists( 'liquid_wp_title' ) ) :
function liquid_wp_title( $title, $sep ) {
	global $paged, $page;
	if ( is_feed() )
		return $title;
	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );
	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";
	return $title;
}
add_filter( 'wp_title', 'liquid_wp_title', 10, 2 );
endif;

// get_the_archive_title
if ( ! function_exists( 'liquid_custom_archive_title' ) ) :
function liquid_custom_archive_title( $title ){
    if ( is_category() ) {
        $title = single_term_title( '', false );
    }
    return $title;
}
add_filter( 'get_the_archive_title', 'liquid_custom_archive_title', 10 );
endif;

// body_class
add_filter('body_class', 'liquid_class_names');
function liquid_class_names($classes) {
    if (is_single()){
        $cat = get_the_category(); // 表示中の記事のカテゴリ（配列）
        $parent_cat_id = $cat[0]->parent; // 親カテゴリのIDを取得
        if(!$parent_cat_id){ $parent_cat_id = $cat[0]->cat_ID; }
        $classes[] = "category_".$parent_cat_id;
    }
    if (is_page()){
        $page = get_post( get_the_ID() );
        $slug = $page->post_name;
        $classes[] = "page_".$slug;
    }
	return $classes;
}

// set_post_thumbnail_size(220, 165, true ); // 幅、高さ、トリミング

// カテゴリ説明でHTML使用可能
remove_filter( 'pre_term_description', 'wp_filter_kses' );

// ウィジェットでショートコード使用可能
add_filter('widget_text', 'do_shortcode');

//固定ページでタグ使用可能
function liquid_add_tag_to_page() {
    register_taxonomy_for_object_type('post_tag', 'page');
}
add_action('init', 'liquid_add_tag_to_page');

// Remove p tags from category description
remove_filter('term_description','wpautop');

// ビジュアルエディタ用CSS
add_editor_style();

// excerpt_more
function liquid_new_excerpt_more($more) {
	return '...';
}
add_filter('excerpt_more', 'liquid_new_excerpt_more');

// after_setup_theme
if ( ! function_exists( 'liquid_after_setup_theme' ) ) :
function liquid_after_setup_theme() {
    // アイキャッチ画像、投稿とコメントのRSSフィード
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    // editor-style
    add_editor_style( 'css/editor-style.css' );
    // Set
    if ( ! isset( $content_width ) ) $content_width = 1024;
    // nav_menu
    register_nav_menus(array(
        'global-menu' => ('Global Menu')
    ));
    // 固定ページの抜粋対応
    add_post_type_support( 'page', 'excerpt' );
    // languages
    load_theme_textdomain( 'liquid-corporate', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'liquid_after_setup_theme' );
endif;

// no_self_ping
function liquid_no_self_ping( &$links ) {
    $home = home_url();
    foreach ( $links as $l => $link )
    if ( 0 === strpos( $link, $home ) )
    unset($links[$l]);
}
add_action( 'pre_ping', 'liquid_no_self_ping' );

// ------------------------------------
// カスタムヘッダーの設定
// ------------------------------------
$defaults = array(
	'default-image'          => get_template_directory_uri().'/images/logo.png',
	'random-default'         => false,
	'width'                  => 400,
	'height'                 => 72,
	'flex-height'            => true,
	'flex-width'             => false,
	'default-text-color'     => '333333',
	'header-text'            => false,
	'uploads'                => true,
	'admin-preview-callback' => 'liquid_admin_header_image',
	'admin-head-callback'    => 'liquid_admin_header_style',
);

function liquid_admin_header_image() {
?>
	<p class="header_preview"><?php bloginfo('description'); ?></p>
	<?php if(get_header_image()): ?>
		<img src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="<?php bloginfo('name'); ?>" />
	<?php else : ?>
		<h2 class="header_preview"><?php bloginfo('name'); ?></h2>
	<?php endif; ?>
<?php
}

function liquid_admin_header_style() {
?>
<style type="text/css">
p.header_preview,h2.header_preview {
	color:#<?php echo get_header_textcolor(); ?>;
}
</style>
<?php
}
add_theme_support( 'custom-header', $defaults );

// インラインスタイル削除
function liquid_remove_recent_comments_style() {
    global $wp_widget_factory;
    remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
}
add_action('widgets_init', 'liquid_remove_recent_comments_style');

// generator削除
remove_action('wp_head', 'wp_generator');

// ------------------------------------
// カスタム背景
// ------------------------------------
//$custom_background_defaults = array(
//	'default-color' => 'ffffff',
//	'default-image' => '',
//);
//add_theme_support( 'custom-background', $custom_background_defaults );

// ------------------------------------
// カスタマイザーで設定する項目を追加
// ------------------------------------
if ( ! function_exists( 'liquid_theme_customize_register' ) ) :
function liquid_theme_customize_register($lqd_customize) {
    //テキストカラー
	$lqd_customize->add_setting( 'color_options[color]', array(
        'default' => '#333333',
		'type'    => 'option',
	));
	$lqd_customize->add_control( new WP_Customize_Color_Control(
		$lqd_customize, 'color_options[color]',
		array(
			'label' => 'テキストカラー',
			'section' => 'colors',
			'settings' => 'color_options[color]'
	)));
    //テーマカラー
	$lqd_customize->add_setting( 'color_options[color2]', array(
        'default' => '#00aeef',
		'type'    => 'option'
	));
	$lqd_customize->add_control( new WP_Customize_Color_Control(
		$lqd_customize, 'color_options[color2]',
		array(
			'label' => 'テーマカラー',
			'section' => 'colors',
			'settings' => 'color_options[color2]'
	)));
    //リンクカラー
	$lqd_customize->add_setting( 'color_options[color3]', array(
        'default' => '#00aeef',
		'type'    => 'option'
	));
	$lqd_customize->add_control( new WP_Customize_Color_Control(
		$lqd_customize, 'color_options[color3]',
		array(
			'label' => 'リンクカラー',
			'section' => 'colors',
			'settings' => 'color_options[color3]'
	)));
    //背景カラー
	$lqd_customize->add_setting( 'color_options[color4]', array(
        'default' => '#ffffff',
		'type'    => 'option'
	));
	$lqd_customize->add_control( new WP_Customize_Color_Control(
		$lqd_customize, 'color_options[color4]',
		array(
			'label' => '背景カラー',
			'section' => 'colors',
			'settings' => 'color_options[color4]'
	)));
    
    //レイアウト
    $lqd_customize->add_section('col_sections', array(
        'title'    => 'レイアウト',
        'priority' => 102,
    ));
    $lqd_customize->add_setting('col_options[sidebar]', array(
        'type' => 'option',
        'default' => '0',
    ));
    $lqd_customize->add_control('col_options[sidebar]', array(
        'label'      => 'レイアウト',
        'description'=> '<img src="'.get_template_directory_uri().'/images/col.png" alt="カラム">',
        'section'    => 'col_sections',
        'settings'   => 'col_options[sidebar]',
        'type'     => 'select',
		'choices'  => array(
			'0' => '2カラム',
			'1'  => '1カラム',
		),
    ));
    //アイキャッチ画像
    $lqd_customize->add_setting('col_options[thumbnail]', array(
        'type' => 'option',
        'default' => '0',
    ));
    $lqd_customize->add_control('col_options[thumbnail]', array(
        'label'      => 'アイキャッチ画像',
        'description'=> '投稿ページ内にアイキャッチ画像を表示',
        'section'    => 'col_sections',
        'settings'   => 'col_options[thumbnail]',
        'type'     => 'select',
		'choices'  => array(
			'0'  => 'する',
			'1' => 'しない',
		),
    ));
    //固定表示
    $lqd_customize->add_setting('col_options[tag]', array(
        'type'           => 'option',
    ));
    $lqd_customize->add_control('col_options[tag]', array(
        'label'      => '固定表示するページのタグ',
        'description'=> '指定したタグが付いている投稿or固定ページを、TOPと固定ページ下部に表示します。<br>例：サービス',
        'section'    => 'col_sections',
        'settings'   => 'col_options[tag]',
        'type'       => 'text',
    ));
    //カテゴリー
    $lqd_customize->add_setting('col_options[cat01]', array(
        'type'           => 'option',
    ));
    $lqd_customize->add_control('col_options[cat01]', array(
        'label'      => 'TOPに表示するカテゴリー(1)',
        'description'=> '指定したカテゴリー（スラッグ名）の新着一覧をTOPに表示します。',
        'section'    => 'col_sections',
        'settings'   => 'col_options[cat01]',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('col_options[cat02]', array(
        'type'           => 'option',
    ));
    $lqd_customize->add_control('col_options[cat02]', array(
        'label'      => 'TOPに表示するカテゴリー(2)',
        'section'    => 'col_sections',
        'settings'   => 'col_options[cat02]',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('col_options[cat03]', array(
        'type'           => 'option',
    ));
    $lqd_customize->add_control('col_options[cat03]', array(
        'label'      => 'TOPに表示するカテゴリー(3)',
        'section'    => 'col_sections',
        'settings'   => 'col_options[cat03]',
        'type'       => 'text',
    ));
    //アイテム1
    $lqd_customize->add_setting('col_options[item01-icon]', array(
        'type' => 'option',
    ));
    $lqd_customize->add_control('col_options[item01-icon]', array(
        'label'      => 'アイテム(1)-アイコン',
        'description'=> '画像名を選んで入力してください。<br><a href="https://lqd.jp/icomoon/demo.html" target="_blank">アイコン一覧</a>',
        'section'    => 'col_sections',
        'settings'   => 'col_options[item01-icon]',
        'type'       => 'text',
    ));
    //アイテム1
    $lqd_customize->add_setting('col_options[item01-ttl]', array(
        'type' => 'option',
    ));
    $lqd_customize->add_control('col_options[item01-ttl]', array(
        'label'      => 'アイテム(1)-見出し',
        'section'    => 'col_sections',
        'settings'   => 'col_options[item01-ttl]',
        'type'       => 'text',
    ));
    //アイテム1
    $lqd_customize->add_setting('col_options[item01-txt]', array(
        'type' => 'option',
    ));
    $lqd_customize->add_control('col_options[item01-txt]', array(
        'label'      => 'アイテム(1)-テキスト',
        'section'    => 'col_sections',
        'settings'   => 'col_options[item01-txt]',
        'type'       => 'textarea',
    ));
    //アイテム2
    $lqd_customize->add_setting('col_options[item02-icon]', array(
        'type' => 'option',
    ));
    $lqd_customize->add_control('col_options[item02-icon]', array(
        'label'      => 'アイテム(2)-アイコン',
        'description'=> '画像名を選んで入力してください。<br><a href="https://lqd.jp/icomoon/demo.html" target="_blank">アイコン一覧</a>',
        'section'    => 'col_sections',
        'settings'   => 'col_options[item02-icon]',
        'type'       => 'text',
    ));
    //アイテム2
    $lqd_customize->add_setting('col_options[item02-ttl]', array(
        'type' => 'option',
    ));
    $lqd_customize->add_control('col_options[item02-ttl]', array(
        'label'      => 'アイテム(2)-見出し',
        'section'    => 'col_sections',
        'settings'   => 'col_options[item02-ttl]',
        'type'       => 'text',
    ));
    //アイテム2
    $lqd_customize->add_setting('col_options[item02-txt]', array(
        'type' => 'option',
    ));
    $lqd_customize->add_control('col_options[item02-txt]', array(
        'label'      => 'アイテム(2)-テキスト',
        'section'    => 'col_sections',
        'settings'   => 'col_options[item02-txt]',
        'type'       => 'textarea',
    ));
    //アイテム2
    $lqd_customize->add_setting('col_options[item03-icon]', array(
        'type' => 'option',
    ));
    $lqd_customize->add_control('col_options[item03-icon]', array(
        'label'      => 'アイテム(3)-アイコン',
        'description'=> '画像名を選んで入力してください。<br><a href="https://lqd.jp/icomoon/demo.html" target="_blank">アイコン一覧</a>',
        'section'    => 'col_sections',
        'settings'   => 'col_options[item03-icon]',
        'type'       => 'text',
    ));
    //アイテム2
    $lqd_customize->add_setting('col_options[item03-ttl]', array(
        'type' => 'option',
    ));
    $lqd_customize->add_control('col_options[item03-ttl]', array(
        'label'      => 'アイテム(3)-見出し',
        'section'    => 'col_sections',
        'settings'   => 'col_options[item03-ttl]',
        'type'       => 'text',
    ));
    //アイテム2
    $lqd_customize->add_setting('col_options[item03-txt]', array(
        'type' => 'option',
    ));
    $lqd_customize->add_control('col_options[item03-txt]', array(
        'label'      => 'アイテム(3)-テキスト',
        'section'    => 'col_sections',
        'settings'   => 'col_options[item03-txt]',
        'type'       => 'textarea',
    ));
    
    //お問い合せURL
    $lqd_customize->add_setting('col_options[form]', array(
        'type' => 'option',
    ));
    $lqd_customize->add_control('col_options[form]', array(
        'label'      => 'お問い合せBOXURL',
        'description'=> '記事の下部にお問い合せBOXを表示する場合入力します。<br>例：http://example.com/form',
        'section'    => 'col_sections',
        'settings'   => 'col_options[form]',
        'type'       => 'text',
    ));
    //お問い合せラベル
    $lqd_customize->add_setting('col_options[form2]', array(
        'type' => 'option',
    ));
    $lqd_customize->add_control('col_options[form2]', array(
        'label'      => 'お問い合せBOX表示名',
        'description'=> '例：資料請求',
        'section'    => 'col_sections',
        'settings'   => 'col_options[form2]',
        'type'       => 'text',
    ));
    //wpautop
    $lqd_customize->add_setting('col_options[wpautop]', array(
        'type' => 'option',
        'default' => '0',
    ));
    $lqd_customize->add_control('col_options[wpautop]', array(
        'label'      => 'タグの自動挿入',
        'description'=> '作成した記事内の改行をpタグに変換して自動挿入します。WordPressのデフォルトは「する」です。',
        'section'    => 'col_sections',
        'settings'   => 'col_options[wpautop]',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'する',
			'1'  => 'しない',
		),
    ));
    
    //スライドショー画像
    $lqd_customize->add_section( 'img_sections' , array(
        'title'        => 'スライドショー画像',
        'description'  => '3枚まで設定できます。1枚のみ固定表示もできます。全て空にするとエリアが非表示になります。画像のアスペクト比は横縦2:1くらいがおすすめです。',
        'priority'     => 100,
    ));
    $lqd_customize->add_setting( 'img_options[img01]' );
    $lqd_customize->add_control( new WP_Customize_Image_Control(
        $lqd_customize, 'img_options[img01]', array(
            'label' => '画像1',
            'section' => 'img_sections',
            'settings' => 'img_options[img01]',
            'description' => '画像をアップロード',
    )));
    $lqd_customize->add_setting( 'img_options[img02]' );
    $lqd_customize->add_control( new WP_Customize_Image_Control(
        $lqd_customize, 'img_options[img02]', array(
            'label' => '画像2',
            'section' => 'img_sections',
            'settings' => 'img_options[img02]',
            'description' => '画像をアップロード',
    )));
    $lqd_customize->add_setting( 'img_options[img03]' );
    $lqd_customize->add_control( new WP_Customize_Image_Control(
        $lqd_customize, 'img_options[img03]', array(
            'label' => '画像3',
            'section' => 'img_sections',
            'settings' => 'img_options[img03]',
            'description' => '画像をアップロード',
    )));
    
    //テキスト
    $lqd_customize->add_section('text_sections', array(
        'title'    => 'スライドショーコピー',
        'priority' => 101,
        'description'=> 'スライドショー画像の下に表示されます。全て空にするとエリアが非表示になります。&lt;a href="xxxx"&gt;テキスト&lt;/a&gt;でリンクになります。',
    ));
    $lqd_customize->add_setting('text_options[text01]', array(
        'type'           => 'option',
    ));
    $lqd_customize->add_control('text_options[text01]', array(
        'label'      => 'スライドショーコピー1',
        'section'    => 'text_sections',
        'settings'   => 'text_options[text01]',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('text_options[text02]', array(
        'type'           => 'option',
    ));
    $lqd_customize->add_control('text_options[text02]', array(
        'label'      => 'スライドショーコピー2',
        'section'    => 'text_sections',
        'settings'   => 'text_options[text02]',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('text_options[text03]', array(
        'type'           => 'option',
    ));
    $lqd_customize->add_control('text_options[text03]', array(
        'label'      => 'スライドショーコピー3',
        'section'    => 'text_sections',
        'settings'   => 'text_options[text03]',
        'type'       => 'text',
    ));
    
    //会社情報
    $lqd_customize->add_section('com_sections', array(
        'title'    => '会社情報',
        'priority' => 104,
        'description'=> 'サイトに表示される電話番号や住所などを指定します。',
    ));
    $lqd_customize->add_setting('com_options[name]', array(
        'type'           => 'option'
    ));
    $lqd_customize->add_control('com_options[name]', array(
        'label'      => '会社名',
        'description'=> '例：サンプル株式会社',
        'section'    => 'com_sections',
        'settings'   => 'com_options[name]',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('com_options[open]', array(
        'type'           => 'option'
    ));
    $lqd_customize->add_control('com_options[open]', array(
        'label'      => '営業時間',
        'description'=> '例：10：00～19：00（月〜金）',
        'section'    => 'com_sections',
        'settings'   => 'com_options[open]',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('com_options[tel]', array(
        'type'           => 'option'
    ));
    $lqd_customize->add_control('com_options[tel]', array(
        'label'      => '電話番号',
        'description'=> '例：0120-00-0000',
        'section'    => 'com_sections',
        'settings'   => 'com_options[tel]',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('com_options[fax]', array(
        'type'           => 'option'
    ));
    $lqd_customize->add_control('com_options[fax]', array(
        'label'      => 'FAX番号',
        'description'=> '例：0120-00-0000',
        'section'    => 'com_sections',
        'settings'   => 'com_options[fax]',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('com_options[adr]', array(
        'type'           => 'option'
    ));
    $lqd_customize->add_control('com_options[adr]', array(
        'label'      => '住所',
        'description'=> '例：東京都墨田区押上1-1-2',
        'section'    => 'com_sections',
        'settings'   => 'com_options[adr]',
        'type'       => 'textarea',
    ));
    $lqd_customize->add_setting('com_options[map]', array(
        'type'           => 'option'
    ));
    $lqd_customize->add_control('com_options[map]', array(
        'label'      => 'GoogleMap埋め込みコード',
        'description'=> '<a href="https://lqd.jp/wp/manual_ope.html#map" target="_blank">マニュアル</a><br>例：&lt;iframe src="https://www.google...',
        'section'    => 'com_sections',
        'settings'   => 'com_options[map]',
        'type'       => 'text',
    ));
    
    //SNS
    $lqd_customize->add_section('sns_sections', array(
        'title'    => 'SNSアカウント',
        'description'=> 'URLを入力するとアイコンなどが表示されます。',
        'priority' => 107,
    ));
    $lqd_customize->add_setting('sns_options[facebook]', array('type' => 'option',));
    $lqd_customize->add_control('sns_options[facebook]', array(
        'label'      => 'Facebook URL',
        'description'=> '例：https://www.facebook.com/lqdjp',
        'settings'   => 'sns_options[facebook]',
        'section'    => 'sns_sections',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('sns_options[twitter]', array('type' => 'option',));
    $lqd_customize->add_control('sns_options[twitter]', array(
        'label'      => 'Twitter URL',
        'settings'   => 'sns_options[twitter]',
        'section'    => 'sns_sections',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('sns_options[google-plus]', array('type' => 'option',));
    $lqd_customize->add_control('sns_options[google-plus]', array(
        'label'      => 'Google-plus URL',
        'settings'   => 'sns_options[google-plus]',
        'section'    => 'sns_sections',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('sns_options[tumblr]', array('type' => 'option',));
    $lqd_customize->add_control('sns_options[tumblr]', array(
        'label'      => 'Tumblr URL',
        'settings'   => 'sns_options[tumblr]',
        'section'    => 'sns_sections',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('sns_options[instagram]', array('type' => 'option',));
    $lqd_customize->add_control('sns_options[instagram]', array(
        'label'      => 'Instagram URL',
        'settings'   => 'sns_options[instagram]',
        'section'    => 'sns_sections',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('sns_options[youtube]', array('type' => 'option',));
    $lqd_customize->add_control('sns_options[youtube]', array(
        'label'      => 'YouTube URL',
        'settings'   => 'sns_options[youtube]',
        'section'    => 'sns_sections',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('sns_options[flickr]', array('type' => 'option',));
    $lqd_customize->add_control('sns_options[flickr]', array(
        'label'      => 'Flickr URL',
        'settings'   => 'sns_options[flickr]',
        'section'    => 'sns_sections',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('sns_options[pinterest]', array('type' => 'option',));
    $lqd_customize->add_control('sns_options[pinterest]', array(
        'label'      => 'Pinterest URL',
        'settings'   => 'sns_options[pinterest]',
        'section'    => 'sns_sections',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('sns_options[feed]', array('type' => 'option',));
    $lqd_customize->add_control('sns_options[feed]', array(
        'label'      => 'Feed アイコン表示',
        'settings'   => 'sns_options[feed]',
        'section'    => 'sns_sections',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'する',
			'1'  => 'しない',
		),
    ));
    
    //SNSシェア
    $lqd_customize->add_section('share_sections', array(
        'title'    => 'SNSシェアボタン',
        'description'=> '投稿ページなどに表示するシェアボタンを選択します。',
        'priority' => 108,
    ));
    $lqd_customize->add_setting('share_options[all]', array('type' => 'option',));
    $lqd_customize->add_control('share_options[all]', array(
        'label'      => 'シェアボタン表示',
        'settings'   => 'share_options[all]',
        'section'    => 'share_sections',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'する',
			'1'  => 'しない',
			'2'  => '固定ページは表示しない',
		),
    ));
    $lqd_customize->add_setting('share_options[facebook]', array('type' => 'option',));
    $lqd_customize->add_control('share_options[facebook]', array(
        'label'      => 'Facebook',
        'settings'   => 'share_options[facebook]',
        'section'    => 'share_sections',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'する',
			'1'  => 'しない',
		),
    ));
    $lqd_customize->add_setting('share_options[twitter]', array('type' => 'option',));
    $lqd_customize->add_control('share_options[twitter]', array(
        'label'      => 'Twitter',
        'settings'   => 'share_options[twitter]',
        'section'    => 'share_sections',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'する',
			'1'  => 'しない',
		),
    ));
    $lqd_customize->add_setting('share_options[google-plus]', array('type' => 'option',));
    $lqd_customize->add_control('share_options[google-plus]', array(
        'label'      => 'Google-plus',
        'settings'   => 'share_options[google-plus]',
        'section'    => 'share_sections',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'する',
			'1'  => 'しない',
		),
    ));
    $lqd_customize->add_setting('share_options[hatena]', array('type' => 'option',));
    $lqd_customize->add_control('share_options[hatena]', array(
        'label'      => 'Hatena',
        'settings'   => 'share_options[hatena]',
        'section'    => 'share_sections',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'する',
			'1'  => 'しない',
		),
    ));
    $lqd_customize->add_setting('share_options[pocket]', array('type' => 'option',));
    $lqd_customize->add_control('share_options[pocket]', array(
        'label'      => 'Pocket',
        'settings'   => 'share_options[pocket]',
        'section'    => 'share_sections',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'する',
			'1'  => 'しない',
		),
    ));
    $lqd_customize->add_setting('share_options[line]', array('type' => 'option',));
    $lqd_customize->add_control('share_options[line]', array(
        'label'      => 'LINE',
        'settings'   => 'share_options[line]',
        'section'    => 'share_sections',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'する',
			'1'  => 'しない',
		),
    ));

        
    //GA
    $lqd_customize->add_section('html_sections', array(
        'title'    => 'Googleアナリティクス',
        'description'=> 'Googleアナリティクスのアナリティクス設定＞プロパティ設定でトラッキングIDを確認できます。',
        'priority' => 109,
    ));
    $lqd_customize->add_setting('html_options[ga]', array('type'  => 'option',));
    $lqd_customize->add_control('html_options[ga]', array(
        'label'      => 'トラッキングID',
        'description'=> '例：UA-XXXXXXX-XX',
        'section'    => 'html_sections',
        'settings'   => 'html_options[ga]',
        'type'       => 'text',
    ));
    
    //多言語
    $lqd_customize->add_section('lang_sections', array(
        'title'    => '多言語対応',
        'description'=> '<a href="https://lqd.jp/wp/manual_lang.html" target="_blank">マニュアル</a><br><a href="https://ja.wikipedia.org/wiki/ISO_639-1%E3%82%B3%E3%83%BC%E3%83%89%E4%B8%80%E8%A6%A7" target="_blank">ISO 639-1言語コード</a>',
        'priority' => 997,
    ));
    $lqd_customize->add_setting('lang_options[c001]', array('type'  => 'option',));
    $lqd_customize->add_control('lang_options[c001]', array(
        'label'      => '(1)このサイトの言語コードを指定します。',
        'description'=> '例：ja',
        'section'    => 'lang_sections',
        'settings'   => 'lang_options[c001]',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('lang_options[n001]', array('type'  => 'option',));
    $lqd_customize->add_control('lang_options[n001]', array(
        'label'      => '(1)このサイトの言語コードの表示名を指定します。',
        'description'=> '例：日本語',
        'section'    => 'lang_sections',
        'settings'   => 'lang_options[n001]',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('lang_options[p001]', array(
        'type'  => 'option',
        'default' => '0',
    ));
    $lqd_customize->add_control('lang_options[p001]', array(
        'label'      => '(1)このサイトの言語コードの階層を選択します。',
        'section'    => 'lang_sections',
        'settings'   => 'lang_options[p001]',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'ルート（例：example.com/）',
			'1'  => 'ディレクトリ（例：example.com/**/）',
		),
    ));
    $lqd_customize->add_setting('lang_options[c002]', array('type'  => 'option',));
    $lqd_customize->add_control('lang_options[c002]', array(
        'label'      => '(2)言語コードを指定します。',
        'description'=> '例：en',
        'section'    => 'lang_sections',
        'settings'   => 'lang_options[c002]',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('lang_options[n002]', array('type'  => 'option',));
    $lqd_customize->add_control('lang_options[n002]', array(
        'label'      => '(2)言語コードの表示名を指定します。',
        'description'=> '例：English',
        'section'    => 'lang_sections',
        'settings'   => 'lang_options[n002]',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('lang_options[p002]', array(
        'type'  => 'option',
        'default' => '0',
    ));
    $lqd_customize->add_control('lang_options[p002]', array(
        'label'      => '(2)言語コードの階層を選択します。',
        'section'    => 'lang_sections',
        'settings'   => 'lang_options[p002]',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'ルート（例：example.com/）',
			'1'  => 'ディレクトリ（例：example.com/**/）',
		),
    ));
    $lqd_customize->add_setting('lang_options[c003]', array('type'  => 'option',));
    $lqd_customize->add_control('lang_options[c003]', array(
        'label'      => '(3)言語コードを指定します。',
        'description'=> '例：zh',
        'section'    => 'lang_sections',
        'settings'   => 'lang_options[c003]',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('lang_options[n003]', array('type'  => 'option',));
    $lqd_customize->add_control('lang_options[n003]', array(
        'label'      => '(3)言語コードの表示名を指定します。',
        'description'=> '例：中文',
        'section'    => 'lang_sections',
        'settings'   => 'lang_options[n003]',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('lang_options[p003]', array(
        'type'  => 'option',
        'default' => '0',
    ));
    $lqd_customize->add_control('lang_options[p003]', array(
        'label'      => '(3)言語コードの階層を選択します。',
        'section'    => 'lang_sections',
        'settings'   => 'lang_options[p003]',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'ルート（例：example.com/）',
			'1'  => 'ディレクトリ（例：example.com/**/）',
		),
    ));
    
    //カスタムヘッダー
    $lqd_customize->add_section('head_sections', array(
        'title'    => 'カスタムヘッダー',
        'description'=> '【上級者向け】HEADにタグやCSSを挿入します。',
        'priority' => 997,
    ));
    $lqd_customize->add_setting('head_options[meta]', array('type'  => 'option',));
    $lqd_customize->add_control('head_options[meta]', array(
        'label'      => 'カスタムHEAD',
        'description'=> '例：&lt;link rel="stylesheet" href="add.css"&gt;',
        'section'    => 'head_sections',
        'settings'   => 'head_options[meta]',
        'type'       => 'textarea',
    ));
    $lqd_customize->add_setting('head_options[css]', array('type'  => 'option',));
    $lqd_customize->add_control('head_options[css]', array(
        'label'      => 'カスタムCSS',
        'description'=> '例：.class { color: #000; }',
        'section'    => 'head_sections',
        'settings'   => 'head_options[css]',
        'type'       => 'textarea',
    ));
    
    //ライセンス
    $lqd_customize->add_section('lqd_sections', array(
        'title'    => 'ライセンスID',
        'description'=> 'LIQUID PRESS テーマのライセンスID（メールアドレス）を入力してください。<br>ライセンスIDのご購入は<a href="https://lqd.jp/wp/theme.html" target="_blank">こちら</a>。',
        'priority' => 999,
    ));
    $lqd_customize->add_setting('lqd_options[ls]', array('type'  => 'option',));
    $lqd_customize->add_control('lqd_options[ls]', array(
        'label'      => 'メールアドレス',
        'section'    => 'lqd_sections',
        'settings'   => 'lqd_options[ls]',
        'type'       => 'text',
    ));
    $lqd_customize->add_setting('lqd_options[cp]', array(
        'type'  => 'option',
        'default' => '0',
    ));
    $lqd_customize->add_control('lqd_options[cp]', array(
        'label'      => '著作権表示削除',
        'section'    => 'lqd_sections',
        'settings'   => 'lqd_options[cp]',
        'type'     => 'select',
		'choices'  => array(
			'0' => 'しない',
			'1'  => 'する',
		),
    ));
}
add_action( 'customize_register', 'liquid_theme_customize_register' );
endif;

// ------------------------------------
// wp_nav_menu
// ------------------------------------
function liquid_special_nav_class( $classes, $item ) {
    $classes[] = 'nav-item hidden-sm-down';
    return $classes;
}
add_filter( 'nav_menu_css_class', 'liquid_special_nav_class', 10, 2 );

// ------------------------------------
// 投稿フォーマットを追加
// ------------------------------------
//add_theme_support( 'post-formats', array( 'aside' ) );

// ------------------------------------
// ウィジェットの登録
// ------------------------------------
if ( ! function_exists( 'liquid_widgets_init' ) ) :
function liquid_widgets_init() {
    register_sidebar(array(
        'name' => 'サイドバー',
        'id' => 'sidebar',
        'before_title' => '<div class="ttl">',
        'after_title' => '</div>',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>'
    ));
    register_sidebar(array(
        'name' => 'メインエリア上部',
        'id' => 'main_head',
        'before_title' => '<div class="ttl">',
        'after_title' => '</div>',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>'
    ));
    register_sidebar(array(
        'name' => 'メインエリア下部',
        'id' => 'main_foot',
        'before_title' => '<div class="ttl">',
        'after_title' => '</div>',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>'
    ));
    register_sidebar(array(
        'name' => '固定ページ上部',
        'id' => 'page_head',
        'before_title' => '<div class="ttl">',
        'after_title' => '</div>',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>'
    ));
    register_sidebar(array(
        'name' => '固定ページ下部',
        'id' => 'page_foot',
        'before_title' => '<div class="ttl">',
        'after_title' => '</div>',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>'
    ));
    register_sidebar(array(
        'name' => 'トップページ上部',
        'id' => 'top_header',
        'before_title' => '<div class="ttl">',
        'after_title' => '</div>',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>'
    ));
    register_sidebar(array(
        'name' => 'トップページ下部',
        'id' => 'top_footer',
        'before_title' => '<div class="ttl">',
        'after_title' => '</div>',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>'
    ));
    register_sidebar(array(
        'name' => 'フッター',
        'id' => 'footer',
        'before_title' => '<div class="ttl">',
        'after_title' => '</div>',
        'before_widget' => '<div id="%1$s" class="widget %2$s col-sm-4">',
        'after_widget'  => '</div>'
    ));
}
add_action( 'widgets_init', 'liquid_widgets_init' );
endif;

// facebook_box
class liquid_widget_fb extends WP_Widget {
	function liquid_widget_fb() {
    	parent::__construct(false, $name = 'Facebook Page Plugin');
    }
    function widget($args, $instance) {
        extract( $args );
        $facebook_ttl = apply_filters( 'widget_facebook_ttl', $instance['facebook_ttl'] );
        $facebook_box = apply_filters( 'widget_facebook_box', $instance['facebook_box'] );
    	?>
        <?php echo $before_widget; ?>
        <?php if ( $facebook_ttl ) echo $before_title . $facebook_ttl . $after_title; ?>
        <div class="fb-page" data-href="<?php echo $facebook_box; ?>" data-width="500" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/facebook"><a href="https://www.facebook.com/facebook">Facebook</a></blockquote></div></div>
        <?php echo $after_widget; ?>
        <?php
    }
    function update($new_instance, $old_instance) {
	$instance = $old_instance;
	$instance['facebook_ttl'] = trim($new_instance['facebook_ttl']);
	$instance['facebook_box'] = trim($new_instance['facebook_box']);
        return $instance;
    }
    function form($instance) {
        $facebook_ttl = isset( $instance['facebook_ttl'] ) ? esc_attr( $instance['facebook_ttl'] ) : '';
        $facebook_box = isset( $instance['facebook_box'] ) ? esc_attr( $instance['facebook_box'] ) : '';
        ?>
        <p>
           <label for="<?php echo $this->get_field_id('facebook_ttl'); ?>">
           <?php _e( 'Title:', 'liquid-corporate' ); ?>
           </label>
           <input type="text" class="widefat" rows="16" id="<?php echo $this->get_field_id('facebook_ttl'); ?>" name="<?php echo $this->get_field_name('facebook_ttl'); ?>" value="<?php echo $facebook_ttl; ?>">
        </p>
        <p>
           <label for="<?php echo $this->get_field_id('facebook_box'); ?>">
           <?php _e( 'Facebook Page URL:', 'liquid-corporate' ); ?>
           </label>
           <input type="text" class="widefat" rows="16" colls="20" id="<?php echo $this->get_field_id('facebook_box'); ?>" name="<?php echo $this->get_field_name('facebook_box'); ?>" value="<?php echo $facebook_box; ?>">
        </p>
        <?php
    }
}
add_action('widgets_init', create_function('', 'return register_widget("liquid_widget_fb");'));

// 最近の投稿 (画像付き)
class liquid_widget_newpost extends WP_Widget {

    function liquid_widget_newpost() {
        parent::__construct( false, $name = '最近の投稿 (画像付き)' );
    }
    function widget( $args, $instance ) {
        $cache = wp_cache_get( 'widget_recent_posts', 'widget' );
        if ( !is_array( $cache ) ) {
            $cache = array();
        }
        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }
        if ( isset( $cache[ $args['widget_id'] ] ) ) {
            echo $cache[ $args['widget_id'] ];
            return;
        }
        ob_start();
        extract( $args );

        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

        if ( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) ) {
            $number = 10;
        }

        $r = new WP_Query( apply_filters( 'widget_posts_args', array( 'posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) );
        if ( $r->have_posts() ) {
        ?>
            <?php echo $before_widget; ?>

            <?php if ( $title ) echo $before_title . $title . $after_title; ?>
            <ul class="newpost">
            <?php while ( $r->have_posts() ) : $r->the_post(); ?>
                <li>
                    <a href="<?php the_permalink(); ?>" title="<?php echo get_the_title(); ?>">
                    <span class="post_thumb"><span><?php echo get_the_post_thumbnail( null, 'thumbnail' ); ?></span></span>
                    <span class="post_ttl"><?php if (get_the_title() ) the_title(); else the_ID(); ?></span></a>
                </li>
            <?php endwhile; ?>
            </ul>
            <?php echo $after_widget; ?>
            <?php
                wp_reset_postdata();
            }
            $cache[ $args['widget_id'] ] = ob_get_flush();
            wp_cache_set( 'widget_recent_posts', $cache, 'widget' );
        }

        function update( $new_instance, $old_instance ) {
            $instance              = $old_instance;
            $instance['title']     = strip_tags($new_instance['title']);
            $instance['number']    = (int) $new_instance['number'];
            //$this->flush_widget_cache();
 
            $alloptions = wp_cache_get( 'alloptions', 'options' );
 
            if ( isset( $alloptions['widget_recent_entries'] ) ) {
                delete_option( 'widget_recent_entries' );
            }
            return $instance;
        }
        //function flush_widget_cache() {
        //    wp_cache_delete( 'widget_recent_posts', 'widget' );
        //}
        /* ウィジェットの設定フォーム */
        function form( $instance ) {
            $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
            $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        ?>
            <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'liquid-corporate' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
 
            <p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:', 'liquid-corporate' ); ?></label>
            <input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

        <?php
        }
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "liquid_widget_newpost" );' ) );


// com
class liquid_widget_com extends WP_Widget {
	function liquid_widget_com() {
    	parent::__construct(false, $name = '会社情報');
    }
    function widget($args, $instance) {
        extract( $args );
        $com_ttl = apply_filters( 'widget_com_ttl', $instance['com_ttl'] );
    	?>
        <?php echo $before_widget; ?>
        <?php if ( $com_ttl ) echo $before_title . $com_ttl . $after_title; ?>
        <?php $com_options = get_option('com_options'); ?>
        <?php if(!empty($com_options)) { ?><div class="com"><?php } ?>
            <?php if(!empty( $com_options['name']) ){ ?><div class="com_name"><?php echo $com_options['name']; ?></div><?php } ?>
            <?php if(!empty( $com_options['tel']) ){ ?><br><span class="com_tel"><?php esc_html_e( 'TEL:', 'liquid-corporate' ); ?> <?php echo $com_options['tel']; ?> </span><?php } ?>
            <?php if(!empty( $com_options['fax']) ){ ?><span class="com_fax"><?php esc_html_e( 'FAX:', 'liquid-corporate' ); ?> <?php echo $com_options['fax']; ?></span><?php } ?>
            <?php if(!empty( $com_options['open']) ){ ?><div class="com_open"><?php echo $com_options['open']; ?></div><?php } ?>
            <?php if(!empty( $com_options['adr']) ){ ?><div class="com_adr"><?php echo $com_options['adr']; ?></div><?php } ?>
        <?php if(!empty($com_options)) { ?></div><?php } ?>
        <?php echo $after_widget; ?>
        <?php
    }
    function update($new_instance, $old_instance) {
	$instance = $old_instance;
	$instance['com_ttl'] = trim($new_instance['com_ttl']);
        return $instance;
    }
    function form($instance) {
        $com_ttl = isset( $instance['com_ttl'] ) ? esc_attr( $instance['com_ttl'] ) : '';
        ?>
        <p>
           <label for="<?php echo $this->get_field_id('com_ttl'); ?>">
           <?php _e( 'Title:', 'liquid-corporate' ); ?>
           </label>
           <input type="text" class="widefat" rows="16" id="<?php echo $this->get_field_id('com_ttl'); ?>" name="<?php echo $this->get_field_name('com_ttl'); ?>" value="<?php echo $com_ttl; ?>">
        </p>
        <?php
    }
}
add_action('widgets_init', create_function('', 'return register_widget("liquid_widget_com");'));


// lang
class liquid_widget_lang extends WP_Widget {
	function liquid_widget_lang() {
    	parent::__construct(false, $name = '言語切替');
    }
    function widget($args, $instance) {
        extract( $args );
        $lang_ttl = apply_filters( 'widget_lang_ttl', $instance['lang_ttl'] );
    	?>
        <?php echo $before_widget; ?>
        <?php if ( $lang_ttl ) echo $before_title . $lang_ttl . $after_title; ?>
        <?php $lang_options = get_option('lang_options'); ?>
        <div class="langc"></div>
        <script>jQuery(function($){ $(".lang").clone().prependTo(".langc"); });</script>
        <?php echo $after_widget; ?>
        <?php
    }
    function update($new_instance, $old_instance) {
	$instance = $old_instance;
	$instance['lang_ttl'] = trim($new_instance['lang_ttl']);
        return $instance;
    }
    function form($instance) {
        $lang_ttl = isset( $instance['lang_ttl'] ) ? esc_attr( $instance['lang_ttl'] ) : '';
        ?>
        <p>
           <label for="<?php echo $this->get_field_id('lang_ttl'); ?>">
           <?php _e( 'Title:', 'liquid-corporate' ); ?>
           </label>
           <input type="text" class="widefat" rows="16" id="<?php echo $this->get_field_id('lang_ttl'); ?>" name="<?php echo $this->get_field_name('lang_ttl'); ?>" value="<?php echo $lang_ttl; ?>">
        </p>
        <?php
    }
}
add_action('widgets_init', create_function('', 'return register_widget("liquid_widget_lang");'));

// ------------------------------------
// LIQUID PRESS functions
// ------------------------------------

//Initialize the update checker.
if ( is_admin() ) {
    //json
    $json_url = "https://lqd.jp/wp/data/_CrXkj6uaC3fj2EH.json";
    $json = wp_remote_get($json_url);
    $json = json_decode($json['body']);
    //json_admin
    $json_admin_url = "https://lqd.jp/wp/data/liquid-corporate-admin.json";
    $json_admin = wp_remote_get($json_admin_url);
    $json_admin = json_decode($json_admin['body']);
    //update
    liquid_wp_update();
}
function liquid_wp_update(){
    global $json_url;
    require ( get_template_directory() . '/theme-update-checker.php' );
    $liquid_update_checker = new ThemeUpdateChecker(
        'liquid-corporate',
        $json_url
    );
}

// theme-options
function liquid_theme_support_menu() {
    global $json;
    $ls = '';
    $lqd_options = get_option('lqd_options');
    if (!empty( $lqd_options['ls'] )){
        $ls = htmlspecialchars($lqd_options['ls']);
    }
    echo '<div class="wrap lqd-info"><h1>テーマのサポート</h1>';
    echo '<iframe src="https://lqd.jp/wp/data/liquid-corporate-info.html?id='.time().'&ls='.$ls.'" frameborder="0" style="width: 100%; height: 1200px;"></iframe>';
    if (!empty( $ls )){
        echo '<div class="lqd-footer"><p class="alignleft"><a href="https://lqd.jp/wp/" target="_blank">LIQUID PRESS</a> のご利用ありがとうございます。</p><p class="alignright"><a href="'.$json->download_url.'" target="_blank" class="dlurl">テーマ '.$json->version.' のzipを入手する</a></p></div>';
    }
    echo '</div>';
}
add_action ( 'admin_menu', 'liquid_theme_support' );
function liquid_theme_support() {
     add_theme_page('テーマのサポート', 'テーマのサポート', 'manage_options', 
               'liquid_theme_support', 'liquid_theme_support_menu');
}

// notices
function liquid_admin_notices() {
	global $liquid_theme, $json, $json_admin, $pagenow;
	if ( $pagenow == 'index.php' || $pagenow == 'themes.php' || $pagenow == 'nav-menus.php' ) {
        if( !empty($json_admin->notices) && version_compare($liquid_theme->Version, $json->version, "<") || $json_admin->flag == "always" ){
            echo '<div class="notice notice-info"><p>'.$json_admin->notices.'</p></div>';
        }
    } elseif ( $pagenow == 'edit-tags.php' ) {
        if(!empty($json_admin->catinfo)){
            echo '<div class="notice notice-info"><p>'.$json_admin->catinfo.'</p></div>';
        }
    }
}
add_action( 'admin_notices', 'liquid_admin_notices' );

//col_options
$col_options = get_option('col_options');
if(!empty($col_options['wpautop'])){
    function liquid_wpautop_action() {
        remove_filter('the_excerpt', 'wpautop');
        remove_filter('the_content', 'wpautop');
    }
    add_filter( 'init', 'liquid_wpautop_action' );
    function liquid_wpautop_filter($init) {
        $init['wpautop'] = false;
        $init['apply_source_formatting'] = ture;
        return $init;
    }
    add_filter( 'tiny_mce_before_init', 'liquid_wpautop_filter' );
}

//sidebar
if ( ! function_exists( 'liquid_col_options' ) ) :
function liquid_col_options($key){
    $col_options = get_option('col_options');
    //is
    if(!empty($col_options['sidebar2'])){
        if ( is_single() || is_page() ) {
            $mainarea = 'col-md-8';
            $sidebar = 'col-md-4';
        }else{
            $mainarea = 'col-md-12';
            $sidebar = 'col-md-12';
        }
    }elseif(empty($col_options['sidebar'])){
        $mainarea = 'col-md-8';
        $sidebar = 'col-md-4';
    }else{
        $mainarea = 'col-md-12';
        $sidebar = 'col-md-12';
    }
    //class
    if($key == 'mainarea'){
        echo $mainarea;
    }else{
        echo $sidebar;
    }
}
endif;

// navigation
if ( ! function_exists( 'liquid_paging_nav' ) ) :
function liquid_paging_nav() {
	global $wp_query, $wp_rewrite;
	if ( $wp_query->max_num_pages < 2 ) {
		return;
	}
	$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
	$pagenum_link = html_entity_decode( get_pagenum_link() );
	$query_args   = array();
	$url_parts    = explode( '?', $pagenum_link );
	if ( isset( $url_parts[1] ) ) {
		wp_parse_str( $url_parts[1], $query_args );
	}
	$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
	$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';
	$format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
	$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';
	$links = paginate_links( array(
		'base'     => $pagenum_link,
		'format'   => $format,
		'total'    => $wp_query->max_num_pages,
		'current'  => $paged,
		'mid_size' => 4,
		'add_args' => array_map( 'urlencode', $query_args ),
		'prev_text' => __( '&laquo; Prev', 'liquid-corporate' ),
		'next_text' => __( 'Next &raquo;', 'liquid-corporate' ),
	) );
	if ( $links ) :
	?>
	<nav class="navigation">
		<ul class="page-numbers">
			<li><?php echo $links; ?></li>
		</ul>
	</nav>
	<?php
	endif;
}
endif;
?>