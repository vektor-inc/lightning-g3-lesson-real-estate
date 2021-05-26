<?php
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


get_template_part('functions-adv');

/**
 * 検索結果画面のレイアウトの改変
 */
function my_vk_post_options_chintai_search_result( $options ) {

	// 検索結果画面で該当する投稿が特定のカスタム投稿タイプの場合
	if ( is_search() && 'chintai' === get_post_type() ) {

		// 賃貸物件とその他の投稿タイプで同じキーワードを含む投稿の場合、
		// キーワード検索だとレイアウトが統一されない問題がある。
		// → 検索条件に投稿タイプ指定がある場合のみ改変
		if ( ! empty( $_GET['post_type'] ) ) {

			// 表示する要素の設定を変更
			$options = my_get_options_chintai();
		}
	}
	return $options;
}
add_filter( 'vk_post_options', 'my_vk_post_options_chintai_search_result' );

/**
 * 一覧での一件分に独自の表示要素を追加
 */
function my_vk_post_options_chintai( $options ) {
	if ( 'chintai' === get_post_type() ) {
			// 表示する要素の設定を変更
			global $post;
			$options = my_get_options_chintai();
	}
	return $options;
}
add_filter( 'vk_post_options', 'my_vk_post_options_chintai' );
