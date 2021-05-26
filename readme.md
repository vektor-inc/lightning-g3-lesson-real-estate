# Lightning Lesson Real Estate

https://github.com/vektor-inc/lightning-g3-lesson-real-estate

Lightningで極めて簡単な物件情報サイトを作る練習用の子テーマです。

目標
目次

---

## 前提条件

#### テーマ Lightning 14以降 G3 モード を使用

外観 > カスタマイズ > Lightning 機能設定 > 世代設定 で Generation 3

#### 有効化するプラグイン 

* VK All in One Expansion Unit
* VK Filter Search （Pro版推奨）
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

```_g3_template-parts/loop-item-chintai.php
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
	'body_append'                => '',
);
VK_Component_Posts::the_view( $post, $options );
```

もちろん VK_Component_Posts を使用しないで全部自分で手書きでもOK。

### 子テーマの loop-item-chintai.php も作らずに 有料版のプラグインも使わずに一覧の画面も改変する

```
/**
 * アーカイブページなどで一件分の表示要素を書き換え
 */
function my_vk_post_options_chintai( $options ) {
	// 投稿タイプが chintai の時
	if ( 'chintai' === get_post_type() ) {
			// 表示する要素の設定を変更
			global $post;
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
				'body_append'                => '',
			);
	}
	return $options;
}
add_filter( 'vk_post_options', 'my_vk_post_options_chintai' );
```

---
## トップページへの物件の表示

トップページに指定している固定ページに、物件一覧を表示させます。

### 有料版（VK Blocks Pro）の場合

投稿リストブロック で 表示条件 で 投稿タイプなどを指定してカンタン設定！

### 無料版の場合...

カスタム投稿タイプ表示用のショートコードを作成

```
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
		'class_outer'                => 'vk_post-col-sm-12 vk_post-col-md-12 vk_post-col-lg-6 vk_post-col-xl-6',
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
		'class_outer'                => 'vk_post-col-sm-12 vk_post-col-md-12 vk_post-col-lg-6 vk_post-col-xl-6',
		'class_title'                => '',
		'body_prepend'               => '',
		'body_append'                => '',
	);
	return $option;
}
```

これを使って、例えば上記のショートコードなら以下のように書き換えられます。

```
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
```

アーカイブページ用のの書き換えも $option の部分を 以下のように関数で取得に書き換えます。

```
/**
 * アーカイブページなどで一件分の表示要素を書き換え
 */
function my_vk_post_options_chintai( $options ) {
	// 投稿タイプが chintai の時
	if ( 'chintai' === get_post_type() ) {
			// 表示する要素の設定を変更
			global $post;
			$options      = my_get_options_chintai();
	}
	return $options;
}
add_filter( 'vk_post_options', 'my_vk_post_options_chintai' );
```

---

## 検索ボックスの設置

プラグイン VK Filter Search をインストール

* トップページに指定した固定ページに VK Filter Search ブロックを配置
* 配置したブロックで、検索結果画面にもVK Filter Search ブロックを表示するように設定
* 配置したブロックで、物件情報アーカイブのトップにも VK Filter Search ブロックを表示するように設定

---

## 検索結果画面の調整

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

---
## カスタムフィールドの登録・表示

* プラグイン Advance Custom Fields をインストール・有効化
* カスタムフィールド > 新規追加
* フィールドを登録 & フィールドグループを表示する条件を指定

---
## カスタムフィールドの値などを投稿一覧に挿入する

既に作成済みの 賃貸情報 で表示する設定に、カスタムフィールドの情報を追加します

```
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
```

---
## 物件詳細ページにカスタムフィールドの値などを表示する

_g3/template-parts/entry.php を子テーマに複製...
してもできますが、本文欄下などに何か要素を追加するだけならアクションフックを使います。

```
function my_add_bukken_info($post){
	// カスタムフィールドの値など独自に表示したい要素
	global $post;
	$append_html  = '<p class="data-yachin"><span class="data-yachin-number">' . esc_html( $post->yachin ) . '</span>万円</p>';
	$append_html .= '<table class="table-sm mt-3">';
	$append_html .= '<tr><th>管理費</th><td class="text-right">' . esc_html( $post->kanrihi ) . '円</td></tr>';
	$append_html .= '<tr><th>礼金</th><td class="text-right">' . esc_html( $post->reikin ) . '円</td></tr>';
	$append_html .= '<tr><th>築年数</th><td class="text-right">' . esc_html( $post->chikunen ) . '年</td></tr>';
	$append_html .= '</table>';
	echo $append_html;
}
add_action( 'lightning_entry_body_apppend', 'my_add_bukken_info' );
```
