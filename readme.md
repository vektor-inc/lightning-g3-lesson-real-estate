# Lightning Lesson Real Estate

https://github.com/vektor-inc/lightning-g3-lesson-real-estate

Lightningで極めて簡単な物件情報サイトを作る練習要のプラグインです。

---

## カスタム投稿タイプ「物件情報」と関連するカスタム分類の作成

WordPressには標準で「投稿」「固定ページ」という投稿タイプがありますが、任意の「カスタム」を増やすことができます。プラグインを使用する場合と直接コード書く場合の２種類があります。

### プラグインで行う場合

#### カスタム投稿タイプの登録

1. VK All in One Expansion Unit を有効化
2. ExUnit > 有効化設定 > カスタム投稿タイプマネージャー を有効化
3. 管理画面の カスタム投稿タイプ設定 画面で新規追加
4. 投稿タイプID / 有効にする項目 / ブロックエディタ対応 を設定
5. パーマリンクを保存

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

---

## グローバルメニューに物件情報を追加

外観 > メニュー または 外観 > カスタマイズ > メニュー から 追加

---

## サイドバーの設定

新着物件や物件のカスタム分類をサイドバーに表示しましょう

1. VK All in One Expansion Unit を有効化
2. ExUnit > 有効化設定 > ウィジェット の有効を確認
3. ExUnit > 有効化設定 > ウィジェット有効化設定 で「VK 最近の投稿」「VK カテゴリー/カスタム分類リスト」の有効を確認
4. 外観 > ウィジェット 画面で サイドバー（物件情報）に「VK 最近の投稿」「VK カテゴリー/カスタム分類リスト」ウィジェットをセットし、投稿タイプやカスタム分類を選択

---

