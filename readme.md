# Lightning Lesson Real Estate

https://github.com/vektor-inc/lightning-g3-lesson-real-estate

Lightningで極めて簡単な物件情報サイトを作る練習用の子テーマです。

目標
目次

- [前提条件](#前提条件)
- [カスタム投稿タイプとカスタム分類の作成](#カスタム投稿タイプとカスタム分類の作成)

---

## 前提条件

#### テーマ Lightning 14以降 G3 モード を使用

外観 > カスタマイズ > Lightning 機能設定 > 世代設定 で Generation 3

#### 有効化するプラグイン 

* VK All in One Expansion Unit
* VK Blocks Pro （激しく推奨）
* VK Filter Search （Pro版推奨）
#### その他の設定

* ExUnit > 有効化設定 > ウィジェット の有効を確認
* ExUnit > 有効化設定 > ウィジェット有効化設定 で「VK 最近の投稿」の有効を確認
* ExUnit > 有効化設定 > ウィジェット有効化設定 で「VK カテゴリー/カスタム分類リスト」の有効を確認
* ExUnit > 有効化設定 > ウィジェット有効化設定 で「VK アーカイブリスト」の有効を確認

---

## カスタム投稿タイプとカスタム分類の作成

WordPressには標準で「投稿」「固定ページ」という投稿タイプがありますが、任意の「カスタム」を増やすことができます。プラグインを使用する場合と直接コード書く場合の２種類があります。

### プラグインで行う場合

#### カスタム投稿タイプの登録

1. ExUnit > 有効化設定 > カスタム投稿タイプマネージャー を有効化
1. 管理画面の カスタム投稿タイプ設定 画面で「新規追加」
1. 投稿タイプID / 有効にする項目 / ブロックエディタ対応 を設定
1. パーマリンクを保存

#### カスタム分類の登録

5. カスタム分類名（スラッグ） / カスタム分類名（表示名）/ タグにするかどうか / ブロックエディタ対応 を設定
6. パーマリンクを保存

##### POINT : 検索に関連する項目はカスタム分類で工夫する

例えば家賃などは 5.9 万円 のように数値で登録し、ユーザーが検索するには 5万円〜6.9万円で検索できるようにしたい
という用脳はあると思います。ただし、カスタムフィールドを作成してそこに数値入力させたとしても、カスタムフィールドの値での検索は非常にサーバー負荷が高く実用上問題になります。
なので、検索用にカスタム分類で「家賃」を作成して、その項目として 5万円〜6.9万円 / 7万円〜10万円 というような分類項目を登録しておいたほうが実用的です。

#### 注意

カスタム投稿タイプやカスタム分類を新規に作成・変更した時は 設定 > パーマリンク設定 を保存しないと正しく反映されない。

---

## グローバルメニューに物件情報を追加

外観 > カスタマイズ > メニュー から 
項目を追加 > 物件情報 を 追加

※ 外観 > メニュー からも追加可能

---

## サイドバーの設定

### 新着物件の表示
#### サイドバーに新着物件の表示 _ 有料版（VK Blocks Pro）の場合

1. 外観 > ウィジェット 画面で サイドバー（物件情報）に「LTG Media Posts BS4」ウィジェットをセット
1. 表示条件で 物件情報を選択
1. 表示件数や表示要素を選択

#### サイドバーに新着物件の表示 _ 無料版の場合

外観 > ウィジェット 画面で サイドバー（物件情報）に「VK 最近の投稿」ウィジェットをセットし、投稿タイプを選択

---
### サイドバーにカスタム分類の表示

外観 > ウィジェット 画面で サイドバー（物件情報）に「VK カテゴリー/カスタム分類リスト」ウィジェットをセットし、表示したいカスタム分類名を選択

---
### サイドバーに月別アーカイブなどの表示の場合

不動産サイトでは月別アーカイブなどあまり必要ないかもしれませんが
「イベント情報」や「実績紹介」などの場合、記事の月別や年別のアーカイブリストは「VK アーカイブリスト」ウィジェットで設定できます。


外観 > ウィジェット 画面で サイドバー（物件情報）に「VK アーカイブリスト」ウィジェットをセットし、投稿タイプ名などを指定

---

## 記事一覧ページのカスタマイズ

物件一覧のページをカスタマイズしましょう。
### G3 Pro Unit 利用の場合

外観 > カスタマイズ > Lightning アーカイブ設定 画面で 
各投稿タイプ毎にアーカイブページの表示形式を指定できます。
今回は「物件情報」なので、表示タイプや表示項目などを指定します。

[ 無料版の場合はこちら ]

---
## トップページへの物件の表示

トップページに指定している固定ページに、物件一覧を表示させます。

### 有料版（VK Blocks Pro）の場合

「投稿リスト」を挿入して、表示条件 で 投稿タイプを 物件情報 に指定。

[ 無料版の場合はこちら ]

---

## 検索ボックスの設置

プラグイン VK Filter Search をインストール

* トップページに指定した固定ページに VK Filter Search ブロックを配置
* 配置したブロックで、検索結果画面にもVK Filter Search ブロックを表示するように設定
* 配置したブロックで、物件情報アーカイブのトップにも VK Filter Search ブロックを表示するように設定

有料版（ VK Filter Search Pro ）の違い

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

余分な項目をCSSで隠す

https://www.vektor-inc.co.jp/post/wordpress-css-customize-2020/

---
# おまけ

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
function my_vk_post_options_chintai_add_cf( $options ){

	if ( 'chintai' === get_post_type() ){
		// カスタムフィールドの値など独自に表示したい要素
		global $post;
		$append_html  = '<p class="data-yachin"><span class="data-yachin-number">' . esc_html( $post->yachin ) . '</span>万円</p>';
		$append_html .= '<table class="table-sm mt-3">';
		$append_html .= '<tr><th>管理費</th><td class="text-right">' . esc_html( $post->kanrihi ) . '円</td></tr>';
		$append_html .= '<tr><th>礼金</th><td class="text-right">' . esc_html( $post->reikin ) . '円</td></tr>';
		$append_html .= '<tr><th>築年数</th><td class="text-right">' . esc_html( $post->chikunen ) . '年</td></tr>';
		$append_html .= '</table>';

		$options['body_append'] .= $append_html;
	}

	return $option;
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
