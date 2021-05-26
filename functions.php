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

function my_custom_loop() {

	// 表示条件を発行
	$args         = array(
		'post_type'      => 'chintai', /* 表示する投稿タイプ */
		'posts_per_page' => 12,
	);
	$custom_query = new WP_Query( $args );

	// 表示するHTML
	$options      = array(
		// card, card-noborder, card-intext, card-horizontal , media, postListText
		'layout'                     => 'card-noborder',
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
		'class_outer'                => 'vk_post-col-sm-6 vk_post-col-md-4 vk_post-col-xl-3',
		'class_title'                => '',
		'body_prepend'               => '',
		'body_append'                => '',
	);
	$options_loop = array(
		'class_loop_outer' => '',
	);
	$loop_html    = VK_Component_Posts::get_loop( $custom_query, $options, $options_loop );
	return $loop_html;
}
add_shortcode( 'my_custom_loop', 'my_custom_loop' );

/**
 * 検索結果画面のレイアウトの改変
 */
function my_chintai_search_result_vk_post_options( $options ) {
	if ( is_search() && 'chintai' === get_post_type() ) {

		// 賃貸物件とその他の投稿タイプで同じキーワードを含む投稿の場合、
		// キーワード検索だとレイアウトが統一されない問題がある。
		// → 検索条件に投稿タイプ指定がある場合のみ改変
		if ( ! empty( $_GET['post_type'] ) ) {

			// 表示する要素の設定を変更
			$options = array(
				// card, card-noborder, card-intext, card-horizontal , media, postListText
				'layout'                     => 'card-noborder',
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
				'class_outer'                => 'vk_post-col-sm-6 vk_post-col-md-4 vk_post-col-xl-3',
				'class_title'                => '',
				'body_prepend'               => '',
				'body_append'                => '',
			);
		}
	}
	return $options;
}
add_filter( 'vk_post_options', 'my_chintai_search_result_vk_post_options' );
