# Lightning Lesson Real Estate

https://github.com/vektor-inc/lightning-g3-lesson-real-estate

Lightningで極めて簡単な物件情報サイトを作る練習要のプラグインです。

---

## 前提条件

#### テーマ Lightning 14以降 G3 モード を使用

外観 > カスタマイズ > Lightning 機能設定 > 世代設定 で Generation 3

#### 有効化するプラグイン 

* VK All in One Expansion Unit
* VK Blocks Pro （激しく推奨）

#### その他の設定

ExUnit > 有効化設定 > ウィジェット の有効を確認

---

## カスタム投稿タイプ「物件情報」と関連するカスタム分類の作成

WordPressには標準で「投稿」「固定ページ」という投稿タイプがありますが、任意の「カスタム」を増やすことができます。プラグインを使用する場合と直接コード書く場合の２種類があります。

### プラグインで行う場合

#### カスタム投稿タイプの登録

1. ExUnit > 有効化設定 > カスタム投稿タイプマネージャー を有効化
1. 管理画面の カスタム投稿タイプ設定 画面で新規追加
1. 投稿タイプID / 有効にする項目 / ブロックエディタ対応 を設定
1. パーマリンクを保存

#### カスタム分類の登録

5. カスタム分類名（スラッグ） / カスタム分類名（表示名）/ タグにするかどうか / ブロックエディタ対応 を設定
6. パーマリンクを保存

#### 注意

カスタム投稿タイプやカスタム分類を新規に作成・変更した時は 設定 > パーマリンク設定 を保存しないと正しく反映されない。

---

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

## グローバルメニューに物件情報を追加

外観 > メニュー または 外観 > カスタマイズ > メニュー から 追加

---

## サイドバーの設定

### 新着物件の表示
#### 新着物件の表示 _ 有料版（VK Blocks Pro）の場合

1. 外観 > ウィジェット 画面で サイドバー（物件情報）に「Media Posts BS4」ウィジェットをセットし、投稿タイプやカスタム分類を選択

#### 新着物件の表示 _ 無料版の場合

1. ExUnit > 有効化設定 > ウィジェット有効化設定 で「VK 最近の投稿」「VK カテゴリー/カスタム分類リスト」の有効を確認
1. 外観 > ウィジェット 画面で サイドバー（物件情報）に「VK 最近の投稿」ウィジェットをセットし、投稿タイプを選択

---
### カスタム分類の表示

1. ExUnit > 有効化設定 > ウィジェット有効化設定 で「VK カテゴリー/カスタム分類リスト」の有効を確認
1. 外観 > ウィジェット 画面で サイドバー（物件情報）に「VK カテゴリー/カスタム分類リスト」ウィジェットをセットし、表示したいカスタム分類名を選択

---
### 月別アーカイブなどの表示の場合

不動産サイトでは月別アーカイブなどあまり必要ないかもしれませんが「イベント情報」や「実績紹介」などの場合、記事の月別や年別のアーカイブリストのリンクが必要だと思います。

1. VK All in One Expansion Unit を有効化
2. ExUnit > 有効化設定 > ウィジェット の有効を確認
3. ExUnit > 有効化設定 > ウィジェット有効化設定 で「VK アーカイブリスト」の有効を確認
4. 外観 > ウィジェット 画面で サイドバー（物件情報）に「VK アーカイブリスト」ウィジェットをセットし、投稿タイプ名などを指定

---

## 記事一覧ページのカスタマイズ

物件一覧のページをカスタマイズしましょう。

### G3 Pro Unit 利用の場合

外観 > カスタマイズ > Lightning アーカイブ設定 で表示タイプや表示項目などを指定

### 無料版の場合
#### loop-item.php の複製

投稿一覧に該当するテンプレートファイルは lightning/_g3/template-parts/loop-item.php なので、
これを子テーマの _g3/template-parts/ ディレクトリに複製

カスタム投稿タイプ chintai でだけ改変するので、ファイル名を

loop-item-chintai.php

に変更

これで、物件情報一覧では 
子テーマ/_g3_template-parts/loop-item-chintai.php が読み込まれるようになる

#### loop-item-chintai.php の改変

あらかじめ項目を設定すればそれっぽいHTMLになるようになっている。
ここでは以下のようにするが、お好みに応じてパラメータを変更してください。

```
$options = array(
	// card, card-noborder, card-intext, card-horizontal , media, postListText
	'layout'                     => 'card',
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
VK_Component_Posts::the_view( $post, $options );
```

もちろん VK_Component_Posts を使用しないで全部自分で手書きでもOK。

### 子テーマではなく自作プラグインなどから改変する（上級者向け）

参考コード

```
/**
 * デフォルトの投稿ループを非表示にする
 */
function my_hidden_normal_loop_custom() {
	$post_type_info = VK_Helpers::get_post_type_info();
	if ( 'chintai' === $post_type_info['slug'] ) {
		$return = true;
	}
	return $return;
}
add_filter( 'lightning_is_extend_loop', 'my_hidden_normal_loop_custom' );

/**
 * カスタム投稿タイプ用のループを表示する
 */
function my_custom_loop_archive() {
	$post_type_info = VK_Helpers::get_post_type_info();
	if ( 'chintai' === $post_type_info['slug'] ) {
		global $wp_query;
		$options      = array(
			'layout'                     => 'card',
			'display_image'              => true,
			'display_image_overlay_term' => true,
			'display_excerpt'            => false,
			'display_author'             => false,
			'display_date'               => true,
			'display_new'                => true,
			'display_taxonomies'         => false,
			'display_btn'                => false,
			'image_default_url'          => false,
			'overlay'                    => false,
			'btn_text'                   => __( 'Read more', 'lightning' ),
			'btn_align'                  => 'text-right',
			'new_text'                   => __( 'New!!', 'lightning' ),
			'new_date'                   => 7,
			'textlink'                   => true,
			'class_outer'                => 'vk_post-col-lg-3',
			'class_title'                => '',
			'body_prepend'               => '',
			'body_append'                => '',
		);
		$options_loop = array(
			'class_loop_outer' => 'vk_posts-col-lg-4',
		);
		VK_Component_Posts::the_loop( $wp_query, $options, $options_loop );
	}
}
add_action( 'lightning_extend_loop', 'my_custom_loop_archive' );
```


---
## トップページへの物件の表示

トップページに指定している固定ページに、物件一覧を表示させます。

### 有料版（VK Blocks Pro）の場合

投稿リストブロック で 表示条件 で 投稿タイプなどを指定してカンタン設定！

### 無料版の場合...

カスタム投稿タイプ表示用のショートコードを作成

```
function my_custom_loop() {

	// 表示条件を発行
	$args         = array( 
		'post_type' => 'chintai', /* 表示する投稿タイプ */
		'posts_per_page' => 12,
	);
	$custom_query     = new WP_Query( $args );

	// 表示するHTML
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
	$options_loop = array(
		'class_loop_outer' => '',
	);
	$loop_html = VK_Component_Posts::get_loop( $custom_query, $options, $options_loop );
	return $loop_html;
}
add_shortcode( 'my_custom_loop', 'my_custom_loop' );
```

トップページに指定した固定ページに、「ショートコード」ブロックで `[my_custom_loop]` を入力

