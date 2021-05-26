<?php
/**
 * カスタム投稿タイプ「物件情報」を追加
 */
function my_add_post_type() {
	register_post_type(
		'chintai', /* カスタム投稿タイプのスラッグ */
		array(
			'labels'       => array(
				'name' => '物件情報',
			),
			'public'       => true,
			'has_archive'  => true,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author' ),
			'show_in_rest' => true,
		)
	);
}
add_action( 'init', 'my_add_post_type', 0 );

/**
 * カスタム分類を追加
 */
function my_add_custom_taxonomy() {

	// 「エリア」を追加
	register_taxonomy(
		'area', /* カテゴリーの識別スラッグ */
		'chintai', /* 対象の投稿タイプのスラッグ */
		array(
			'hierarchical'          => true, // 階層構造にするかどうか
			'update_count_callback' => '_update_post_term_count',
			'label'                 => 'エリア',
			'show_in_rest'          => true,
		)
	);

	// 「こだわり」を追加
	register_taxonomy(
		'kodawari', /* カテゴリーの識別スラッグ */
		'chintai', /* 対象の投稿タイプのスラッグ */
		array(
			'hierarchical'          => false, // 階層構造にするかどうか
			'update_count_callback' => '_update_post_term_count',
			'label'                 => 'こだわり',
			'show_in_rest'          => true,
		)
	);

}
add_action( 'init', 'my_add_custom_taxonomy', 0 );

/**
 * トップページ埋め込み用のショートコードを作成（無料版用）
 */
function my_custom_loop() {

	// 表示条件を発行
	$args         = array( 
		'post_type' => 'chintai', /* 表示する投稿タイプ */
		'posts_per_page' => 12,
	);
	$custom_query     = new WP_Query( $args );

	// 表示するHTML
	$options      = my_get_options_chintai();
	$options_loop = array(
		'class_loop_outer' => '',
	);
	$loop_html = VK_Component_Posts::get_loop( $custom_query, $options, $options_loop );
	return $loop_html;
}
add_shortcode( 'my_custom_loop', 'my_custom_loop' );

/**
 * 賃貸物件で表示する設定値を取得する関数
 */
function my_get_option_chintai(){

	// カスタムフィールドの値など独自に表示したい要素
	global $post;
	$append_html  = '<p class="data-yachin"><span class="data-yachin-number">' . esc_html( $post->yachin ) . '</span>万円</p>';
	$append_html .= '<table class="table-sm mt-3">';
	$append_html .= '<tr><th>管理費</th><td class="text-right">' . esc_html( $post->kanrihi ) . '円</td></tr>';
	$append_html .= '<tr><th>礼金</th><td class="text-right">' . esc_html( $post->reikin ) . '円</td></tr>';
	$append_html .= '<tr><th>築年数</th><td class="text-right">' . esc_html( $post->chikunen ) . '年</td></tr>';
	$append_html .= '</table>';

	$options = array(
		// card, card-noborder, card-intext, card-horizontal , media, postListText
		'layout'                     => 'media',
		'display_image'              => true,
		'display_image_overlay_term' => true,
		'display_excerpt'            => true,
		'display_date'               => false,
		'display_new'                => true,
		'display_taxonomies'         => true,
		'display_btn'                => false,
		'image_default_url'          => false,
		'overlay'                    => false,
		'btn_text'                   => __( 'Read more', 'lightning' ),
		'btn_align'                  => 'text-right',
		'new_text'                   => __( 'New!!', 'lightning' ),
		'new_date'                   => 7,
		'class_outer'                => 'vk_post-col-sm-12 vk_post-col-md-12 vk_post-col-lg-6 vk_post-col-xl-6',
		'class_title'                => '',
		'body_prepend'               => '',
		'body_append'                => $append_html,
	);
	return $option;
}

/**
 * アーカイブページなどで一件分の表示要素を書き換え
 */
function my_vk_post_options_chintai( $options ) {
	// 投稿タイプが chintai の時
	if ( 'chintai' === get_post_type() ) {
		// 投稿タイプなどアーカイブページ or 検索で投稿タイプ chintai が指定のページの場合
		if ( is_archive() || isset( $_GET['post_type'] ) && 'chintai' === $_GET['post_type']  ){

			// ただし、ここまでの条件でもサイドバーに配置されている場合など
			// 改変してほしくないケースもあるので必要に応じて条件を指定する

			// サイドバーに Media Posts BS4 を配置して 新着の表示を 7 日に設定した場合、
			// $options['new_date'] が 7 以外の場合に $option を改変する
			if ( 7 != $options['new_date'] ){
				// 表示する要素の設定を変更
				$options = my_get_options_chintai();
			}
		}
	}
	return $options;
}
add_filter( 'vk_post_options', 'my_vk_post_options_chintai' );