# Lightningで物件情報（っぽい）サイトを作ってみよう

## 制作するサイトのイメージ

https://demo.dev3.biz/vk-filter-search/
## 本日の資料

https://github.com/vektor-inc/lightning-g3-lesson-real-estate

Lightningで極めて簡単な物件情報サイトを作る練習用のファイルです。
テーマディレクトリにアップロードすれば子テーマとして、
プラグインディレクトリにアップロードすればプラグインとして動作します。

本文中に記載のある PHPのコードは、functions.php に記載すれば動きます。
## 目次

- [前提条件](#前提条件)
- [カスタム投稿タイプとカスタム分類の作成](#カスタム投稿タイプ物件情報とカスタム分類の作成)
- [サイドバーをウィジェットで設定](#サイドバーをウィジェットで設定)
- [物件一覧ページの設定](#物件一覧ページの設定)
- [トップページへの物件情報の表示](#トップページへの物件情報の表示)
- [検索ボックスの設置](#検索ボックスの設置)
- [検索結果画面のレイアウトについて](#検索結果画面のレイアウトについて)
- [余分な項目をCSSで隠す](#余分な項目をcssで隠す)
- [おまけ](#おまけ)
    * [物件情報一覧ページでの１件分の表示のカスタマイズコード](#物件情報一覧ページでの1件分の表示のカスタマイズコード) 
	* [固定ページ内に物件情報の記事リストをショートコードで表示する](#固定ページ内に物件情報の記事リストを表示する)
	* [物件情報用のパラメーターを纏める](#物件情報用のパラメーターを纏める)
	* [無料版での検索結果ページの表示注意](#無料版での検索結果ページの表示注意)
	* [カスタムフィールドの登録・表示](#カスタムフィールドの登録表示)

---

## 前提条件

* 本レッスンでは有料版の機能を前提で進めます
* 無料版でも近い事を実現できるように参考コードは軽く紹介します
* 無料対応の詳しいコード解説は 時間の都合 及び 有料版を買っていただいている方のメリットが減ってしまうので本編ではしません
#### テーマ Lightning 14以降 G3 モード を使用

外観 > カスタマイズ > Lightning 機能設定 > 世代設定 で Generation 3

#### 有効化するプラグイン 

* Lightning G3 Pro Unit（推奨）
* VK All in One Expansion Unit（以下 ExUnit と呼びます）
* VK Blocks Pro （推奨）
* VK Filter Search （Pro版推奨）
#### その他の設定

* 設定 > Lightning G3 Pro Unit 設定 で「Media Posts BS4」の有効を確認（推奨）
* ExUnit > 有効化設定 > カスタム投稿タイプマネージャー を有効化
* ExUnit > 有効化設定 > ウィジェット の有効を確認
* ExUnit > 有効化設定 > ウィジェット有効化設定 で「VK 最近の投稿」の有効を確認
* ExUnit > 有効化設定 > ウィジェット有効化設定 で「VK カテゴリー/カスタム分類リスト」の有効を確認
* ExUnit > 有効化設定 > ウィジェット有効化設定 で「VK アーカイブリスト」の有効を確認

---

## カスタム投稿タイプ「物件情報」とカスタム分類の作成

WordPressには標準で「投稿」「固定ページ」という投稿タイプがありますが、任意の「カスタム」を増やすことができます。プラグインを使用する場合と直接コード書く場合の２種類があります。

### プラグインで行う場合

#### カスタム投稿タイプの登録

1. 管理画面の カスタム投稿タイプ設定 画面で「新規追加」
1. 投稿タイプID / 有効にする項目 / ブロックエディタ対応 を設定
1. パーマリンクを保存

#### カスタム分類の登録

5. カスタム分類名（スラッグ） / カスタム分類名（表示名）/ タグにするかどうか / ブロックエディタ対応 を設定
6. パーマリンクを保存

##### POINT : 検索に関連する項目はカスタム分類で工夫する

例えば家賃などは「5.9万円」のように数値で登録し、ユーザーが検索するには 5万円〜6.9万円で検索できるようにしたい という要望はあると思います。しかしながら、

* 特定の項目用の入力欄を追加する「カスタムフィールド」という機能がWordPressにはある
* __カスタムフィールドの値はキーワード検索にはヒットしない。__
* __カスタムフィールドを指定しての検索は非常にサーバー負荷が高い__ ので実用上問題になる。

という問題があるので、検索用にカスタム分類で「家賃」を作成して、その項目として 5万円〜6.9万円 / 7万円〜10万円 というような分類項目を登録しておいたほうが実用的。

#### 注意

カスタム投稿タイプやカスタム分類を新規に作成・変更した時は 設定 > パーマリンク設定 を保存しないと正しく反映されない。

#### おまけ

プラグイン Admin Column めっちゃええで。

---

### カスタム投稿タイプやカスタム分類をコードを書いて設定する場合

子テーマのfunctions.phpや自作のプラグインなどにコードを書くことででカスタム投稿タイプやカスタム分類を設定する事もできます。
#### コードで書くメリット

* ExUnit だと追加できるカスタム分類の最大数が5個まで
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

	// 「駅徒歩」を追加
	register_taxonomy(
		'ekitoho', /* カテゴリーの識別スラッグ */
		'chintai', /* 対象の投稿タイプのスラッグ */
		array(
			'hierarchical'          => false, // 階層構造にするかどうか
			'update_count_callback' => '_update_post_term_count',
			'label'                 => '駅徒歩',
			'show_in_rest'          => true,
		)
	);

	// 「賃料」を追加
	register_taxonomy(
		'chinryo', /* カテゴリーの識別スラッグ */
		'chintai', /* 対象の投稿タイプのスラッグ */
		array(
			'hierarchical'          => false, // 階層構造にするかどうか
			'update_count_callback' => '_update_post_term_count',
			'label'                 => '賃料',
			'show_in_rest'          => true,
		)
	);

	// 「間取り」を追加
	register_taxonomy(
		'madori', /* カテゴリーの識別スラッグ */
		'chintai', /* 対象の投稿タイプのスラッグ */
		array(
			'hierarchical'          => false, // 階層構造にするかどうか
			'update_count_callback' => '_update_post_term_count',
			'label'                 => '間取り',
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

外観 > カスタマイズ > メニュー から 
項目を追加 > 物件情報 を 追加

※ 外観 > メニュー からも追加可能

---

## サイドバーをウィジェットで設定

ウィジェットを使ってサイドバーを設定します

まずは以下の何れかの手順で物件情報の関するページのウィジェット設定画面を表示します。

* 物件情報に関連するページを表示 -> 上部管理バーの「カスタマイズ」
* 外観 > カスタマイズ > ウィジェット > サイドバー（物件情報）
* 外観 > ウィジェット の サイドバー（物件情報）
### サイドバーに新着物件の表示

#### VK Blocks Pro 利用の場合

1. 「LTG Media Posts BS4」ウィジェットをセット
1. 表示条件で「物件情報」を選択
1. 表示件数や表示要素を設定

#### ExUnit 利用の場合

1. 「VK 最近の投稿」ウィジェットをセット
1. 投稿タイプで「物件情報」選択
1. 表示件数など設定
### サイドバーにカスタム分類の表示

1. 「VK カテゴリー/カスタム分類リスト」ウィジェットをセット
1. 表示したいカスタム分類名を選択

### サイドバーに月別アーカイブなどの表示の場合

不動産サイトでは月別アーカイブなどあまり必要ないかもしれませんが
「イベント情報」や「実績紹介」などの場合、記事の月別や年別のアーカイブリストは「VK アーカイブリスト」ウィジェットで設定できます。


1. 「VK アーカイブリスト」ウィジェットをセットし
1. 投稿タイプ名などを指定

---

## 物件一覧ページの設定

物件一覧のページをカスタマイズしましょう。
### G3 Pro Unit 利用の場合

外観 > カスタマイズ > Lightning アーカイブ設定 画面で 
各投稿タイプ毎にアーカイブページの表示形式を指定できます。
今回は「物件情報」なので、表示タイプや表示項目などを指定します。

### 無料版の場合

[ → [物件情報一覧ページでの1件分の表示のカスタマイズコード](#物件情報一覧ページでの1件分の表示のカスタマイズコード) ]

---
## トップページへの物件情報の表示

トップページに指定している固定ページに、物件一覧を表示させます。

### 有料版（VK Blocks Pro）の場合

1. トップページに指定している固定ページの編集画面を表示
2. 「投稿リスト」ブロックを挿入
3. 編集画面右側のパネルから「表示条件」で投稿タイプを「物件情報」に指定

### 無料版の場合

ショートコードを作成して挿入します。

[ → [無料版の場合はこちら](#固定ページ内に物件情報の記事リストを表示する) ]

---

## 検索ボックスの設置

1. プラグイン VK Filter Search をインストール
1. トップページに指定した固定ページに VK Filter Search ブロックを配置
1. 右側のパネルから検索対象の投稿タイプを「物件情報」に設定
1. カスタム分類を指定
1. 右側のパネルから検索結果画面にも VK Filter Search ブロックを表示するように設定
1. 右側のパネルから物件情報アーカイブのトップにも VK Filter Search ブロックを表示するように設定

### 有料版（ VK Filter Search Pro ）の違い

* チェックボックスやラジオボタンが使える
* AND検索 / OR 検索 が選べる
* ラベル名が変更できる
* カラム数が変更できる

---
## 検索結果画面のレイアウトについて

G3 Pro Unit （0.3.0〜） の場合は、
外観 > カスタマイズ > Lightnig アーカイブ設定 > 物件情報

で指定したレイアウトが適用される


---

## 余分な項目をCSSで隠す

日付など不要な項目はCSSで display:none; を指定して 非表示にしましょう。
※ G3 Pro　Unit で 後日非表示機能を追加する可能性あり

例えば日付部分なら

```
.entry-meta {
	display:none; 
}
```

など 外観 > カスタマイズ > 追加CSS や、子テーマの style.css などに記載します。

ただし、これだと物件情報だけでなくすべての投稿タイプで日付や投稿者名などが非表示になってしまいます。

投稿タイプ chintai だけ非表示にしたいので、bodyのclass名に含まれている post-type-chintai を追加します。 

```
.post-type-chintai .entry-meta {
	display:none; 
}
```

#### CSSのカスタマイズについては下記参照ください
https://www.vektor-inc.co.jp/post/wordpress-css-customize-2020/

---
# おまけ

---

## 物件情報一覧ページでの１件分の表示のカスタマイズコード

子テーマのfunctions.phpなどに以下を記載してください

```
/**
 * アーカイブページなどで一件分の表示要素を書き換え
 */
function my_vk_post_options_chintai( $options ) {

	// 投稿タイプが chintai の場合改変する
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

[ → [物件一覧ページの設定に戻る](#物件一覧ページの設定) ]

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

## 固定ページ内に物件情報の記事リストを表示する

### 物件情報表示用のショートコードを作成

```
/**
 * トップページ埋め込み用のショートコードを作成
 */
function my_custom_loop() {

	// 表示条件を発行
	$args         = array( 
		'post_type' => 'chintai', /* 表示する投稿タイプ */
		'posts_per_page' => 12, /* 表示件数 */
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

[ → [トップページへの物件情報の表示に戻る](#トップページへの物件情報の表示) ]

---

### 物件情報用のパラメーターを纏める

今まで `$options = array('` で始まるパラメーターが数回出てきましたが、どれも物件情報を表示するためのものなので、内容は同じになるはずです。
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

---
## カスタムフィールドの登録・表示

* プラグイン Advance Custom Fields をインストール・有効化
* カスタムフィールド > 新規追加
* フィールドを登録 & フィールドグループを表示する条件で投稿タイプを指定
## カスタムフィールドの値などを投稿一覧に挿入する

```
/**
 * 賃貸物件で表示する投稿情報にカスタムフィールドの値を追加
 */
function my_vk_post_options_chintai_add_cf( $options ) {

	if ( 'chintai' === get_post_type() ) {
		// カスタムフィールドの値など独自に表示したい要素
		global $post;
		$append_html  = '';
        if ( $post->yachin ){
            $append_html .= '<p class="data-yachin"><span class="data-yachin-number">' . esc_html( $post->yachin ) . '</span>万円</p>';
        }
		$append_html .= '<table class="table-sm mt-3">';
		$append_html .= '<tr><th>管理費</th><td class="text-right">' . esc_html( $post->kanrihi ) . '円</td></tr>';
		$append_html .= '<tr><th>礼金</th><td class="text-right">' . esc_html( $post->reikin ) . '円</td></tr>';
		$append_html .= '<tr><th>築年数</th><td class="text-right">' . esc_html( $post->chikunen ) . '年</td></tr>';
		$append_html .= '</table>';

		$options['body_append'] .= $append_html;
	}

	return $options;
}
add_filter( 'vk_post_options', 'my_vk_post_options_chintai_add_cf' );
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

---