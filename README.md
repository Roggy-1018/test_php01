PHPアンケートアプリ
概要
このアプリは、PHPとMySQLを使用したシンプルなアンケートシステムです。アンケートの登録と結果表示機能を提供します。
機能

アンケート回答の登録
アンケート結果の表示
集計データの表示
ページネーション機能
レスポンシブデザイン

ファイル構成
/
├── config.php          # データベース設定
├── index.php           # アンケート入力フォーム
├── results.php         # アンケート結果表示
├── setup.php           # データベース初期化
└── README.md          # このファイル
セットアップ手順
1. ローカル環境（XAMPP）でのセットアップ

XAMPPを起動し、ApacheとMySQLを開始
http://localhost/phpmyadminにアクセス
新しいデータベース survey_db を作成
プロジェクトファイルを htdocs フォルダ内に配置

例：htdocs/test_php01/ フォルダを作成して配置


ブラウザで http://localhost/test_php01/setup.php にアクセスしてテーブルを作成
http://localhost/test_php01/index.php でアプリを開始

注意: フォルダ名を test_php01 とした場合、URLは http://localhost/test_php01/ファイル名.php となります。
2. サクラサーバーでのセットアップ

データベース設定

サクラサーバーのコントロールパネルにログイン
データベース設定でMySQLデータベースを作成
データベース名、ユーザー名、パスワードを記録


設定ファイル編集

config.php の以下の部分を編集：

php$host = 'mysql○○○.db.sakura.ne.jp'; // サクラサーバーのMySQLホスト名
$dbname = 'your_database_name';       // 作成したデータベース名
$username = 'your_username';          // データベースユーザー名
$password = 'your_password';          // データベースパスワード

ファイルアップロード

FTPクライアントを使用してファイルをアップロード
www フォルダまたは指定のドキュメントルートに配置


初期化

ブラウザで https://your-domain.com/setup.php にアクセス
テーブルが作成されることを確認


アプリ開始

https://your-domain.com/index.php でアプリを開始



使用方法
アンケート回答

index.php にアクセス
必要事項を入力（名前、メール、年齢、性別、満足度）
コメントを任意で入力
送信ボタンをクリック

結果確認

results.php にアクセス
集計データと個別回答を確認
ページネーションで複数ページを確認可能

データベース構造
surveysテーブル
カラム名型説明idINT主キー（自動増分）nameVARCHAR(100)回答者名emailVARCHAR(100)メールアドレスageINT年齢genderENUM性別（male, female, other）satisfactionENUM満足度（very_satisfied, satisfied, neutral, dissatisfied, very_dissatisfied）commentsTEXTコメント（任意）created_atTIMESTAMP作成日時
セキュリティ対策

PDOを使用したプリペアドステートメント
XSS対策（htmlspecialchars）
バリデーション機能
セッション管理

カスタマイズ

デザインは各ファイルの <style> セクションで変更可能
質問項目は index.php で追加・変更可能
集計項目は results.php で追加・変更可能

トラブルシューティング
データベース接続エラー

config.php の設定を確認
データベースが作成されているか確認
ホスト名、ユーザー名、パスワードが正しいか確認

500エラー

PHPエラーログを確認
ファイルパーミッションを確認（644または755）
PHPのバージョンを確認（PHP 7.0以上推奨）

文字化け

データベースの文字セットがutf8になっているか確認
HTML
