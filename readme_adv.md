

## カスタム投稿タイプ&カスタム分類をコードを書いて追加する場合

### コードを書いて設定する場合

子テーマのfunctions.phpや自作のプラグインなどにコードを書くことででカスタム投稿タイプやカスタム分類を設定する事もできます。

#### コードで書くメリット

* プラグインはコードをかわりに実行してくれているだけ
* ファイルとして残るのでバージョン管理しやすい
* プラグインでは用意されていない細かい設定をする事が可能

```
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
```
##### register_post_type() カスタム投稿タイプ追加
https://wpdocs.osdn.jp/%E9%96%A2%E6%95%B0%E3%83%AA%E3%83%95%E3%82%A1%E3%83%AC%E3%83%B3%E3%82%B9/register_post_type

##### register_taxonomy() カスタム分類タイプ追加
https://wpdocs.osdn.jp/%E9%96%A2%E6%95%B0%E3%83%AA%E3%83%95%E3%82%A1%E3%83%AC%E3%83%B3%E3%82%B9/register_taxonomy


---

## 物件情報の 一覧ページでの１件分の表示をコードでカスタマイズする方法

### 子テーマの loop-item-chintai.php も作らずに 有料版のプラグインも使わずに一覧の画面も改変する

子テーマのfunctions.phpなどに以下を記載してください

```
/**
 * アーカイブページなどで一件分の表示要素を書き換え
 */
function my_vk_post_options_chintai( $options ) {

	// 投稿タイプが chintai の時
	if ( 'chintai' === get_post_type() ) {

			// 表示する要素の設定を変更
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
				'class_outer'                => 'vk_post-col-xs-12 vk_post-col-sm-12 vk_post-col-md-12 vk_post-col-lg-6 vk_post-col-xl-6',
				'class_title'                => '',
				'body_prepend'               => '',
				'body_append'                => '',
			);
	}
	return $options;
}
add_filter( 'vk_post_options', 'my_vk_post_options_chintai' );
```

---

## あまりお勧めではない : 物件情報の 一覧ページでの１件分の表示を 子テーマを使ってファイルで管理したい場合

#### loop-item.php の複製

投稿一覧に該当するテンプレートファイルは lightning/_g3/template-parts/loop-item.php なので、
これを子テーマの _g3/template-parts/ ディレクトリに複製

カスタム投稿タイプ chintai の場合だけ改変するので、ファイル名を

loop-item-chintai.php
（loop-item-★投稿タイプのスラッグ★.php）

に変更

これで、物件情報一覧では 
子テーマ/_g3_template-parts/loop-item-chintai.php が読み込まれるようになる

#### loop-item-chintai.php の改変

あらかじめ項目を設定すればそれっぽいHTMLになるようになっている。
ここでは以下のようにするが、お好みに応じてパラメータを変更してください。

```
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
	'class_outer'                => 'vk_post-col-xs-12 vk_post-col-sm-12 vk_post-col-md-12 vk_post-col-lg-6 vk_post-col-xl-6',
	'class_title'                => '',
	'body_prepend'               => '',
	'body_append'                => '',
);
VK_Component_Posts::the_view( $post, $options );
```

もちろん VK_Component_Posts を使用しないで全部自分で手書きでもOK。

---

## 固定ページ内に 物件情報の記事リストを表示する

### 物件情報表示用のショートコードを作成

```
/**
 * トップページ埋め込み用のショートコードを作成
 */
function my_custom_loop() {

	// 表示条件を発行
	$args         = array( 
		'post_type' => 'chintai', /* 表示する投稿タイプ */
		'posts_per_page' => 12,
	);
	$custom_query     = new WP_Query( $args );

	// 表示するHTML
	$options      = array(
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
		'class_outer'                => 'vk_post-col-xs-12 vk_post-col-sm-12 vk_post-col-md-12 vk_post-col-lg-6 vk_post-col-xl-6',
		'class_title'                => '',
		'body_prepend'               => '',
		'body_append'                => '',
	);
	$options_loop = array(
		'class_loop_outer' => '',
	);
	$loop_html = VK_Component_Posts::get_loop( $custom_query, $options, $options_loop );
	return $loop_html;
}
add_shortcode( 'my_custom_loop', 'my_custom_loop' );
```

1. トップページに指定した固定ページに、「ショートコード」ブロックを配置
1. `[my_custom_loop]` を入力


---

### 物件情報用のパラメーターを纏める

今まで $options = array( 〜 で始まるパラメーターが数回出てきましたが、どれも物件情報を表示するためのものなので、内容は同じになるはずです。
複数箇所に分かれていると、変更の際にどれか変更漏れが発生するので、 $options のパラメーターを返す関数を作ってまとめます。

```
/**
 * 賃貸物件で表示する設定値を取得する関数
 */
function my_get_options_chintai(){
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
		'class_outer'                => 'vk_post-col-xs-12 vk_post-col-sm-12 vk_post-col-md-12 vk_post-col-lg-6 vk_post-col-xl-6',
		'class_title'                => '',
		'body_prepend'               => '',
		'body_append'                => '',
	);
	return $options;
}
```

これを使って、今までのコードは下記のように書き換えられます。

```
/**
 * アーカイブページなどで一件分の表示要素を書き換え
 */
function my_vk_post_options_chintai( $options ) {

	// 投稿タイプが chintai の時
	if ( 'chintai' === get_post_type() ) {

			// 表示する要素の設定を変更
			$options      = my_get_options_chintai();
	}
	return $options;
}
add_filter( 'vk_post_options', 'my_vk_post_options_chintai' );
```

```
/**
 * トップページ埋め込み用のショートコードを作成
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
```

---

## 無料版での検索結果ページの表示注意

通常の検索結果画面のレイアウトと、特定のカスタム投稿タイプのレイアウトを変更したい場合

現状の問題点

* 今までのコードだと、キーワード検索で 検索該当記事が 物件情報検索と通常の投稿で両方該当したりすると、
検索結果画面で記事の投稿タイプ毎にレイアウトが異なってしまう。
* 投稿タイプが chintai なら問答無用ですべて書き換えてしまっている。  
→ VK Blocks Pro でサイドバーなどに表示項目を減らして表示しようとしても、投稿タイプが chintai ならすべて上書きされてしまう

表示を切り替える条件をもう少し細かく設定する

```
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
```