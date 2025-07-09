<?php
require_once 'config.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $age = trim($_POST['age'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $satisfaction = $_POST['satisfaction'] ?? '';
    $comments = trim($_POST['comments'] ?? '');
    
    // バリデーション
    if (empty($name)) $errors[] = '名前を入力してください。';
    if (empty($email)) {
        $errors[] = 'メールアドレスを入力してください。';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = '有効なメールアドレスを入力してください。';
    }
    if (empty($age)) {
        $errors[] = '年齢を入力してください。';
    } elseif (!is_numeric($age) || $age < 1 || $age > 120) {
        $errors[] = '有効な年齢を入力してください。';
    }
    if (empty($gender)) $errors[] = '性別を選択してください。';
    if (empty($satisfaction)) $errors[] = '満足度を選択してください。';
    
    // エラーがなければデータベースに保存
    if (empty($errors)) {
        try {
            $sql = "INSERT INTO surveys (name, email, age, gender, satisfaction, comments) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $email, $age, $gender, $satisfaction, $comments]);
            $success = true;
            
            // フォームをクリア
            $name = $email = $age = $gender = $satisfaction = $comments = '';
        } catch (PDOException $e) {
            $errors[] = 'データベースエラーが発生しました。';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アンケート</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"],
        input[type="email"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
            resize: vertical;
        }
        .radio-group {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        .radio-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .radio-item input[type="radio"] {
            width: auto;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .btn-secondary:hover {
            background-color: #545b62;
        }
        .error {
            color: #dc3545;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .success {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .navigation {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>アンケート調査</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success">
                <p>アンケートの回答ありがとうございました。正常に送信されました。</p>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label for="name">名前 *</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">メールアドレス *</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="age">年齢 *</label>
                <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($age ?? ''); ?>" min="1" max="120" required>
            </div>
            
            <div class="form-group">
                <label>性別 *</label>
                <div class="radio-group">
                    <div class="radio-item">
                        <input type="radio" id="male" name="gender" value="male" <?php echo (isset($gender) && $gender == 'male') ? 'checked' : ''; ?>>
                        <label for="male">男性</label>
                    </div>
                    <div class="radio-item">
                        <input type="radio" id="female" name="gender" value="female" <?php echo (isset($gender) && $gender == 'female') ? 'checked' : ''; ?>>
                        <label for="female">女性</label>
                    </div>
                    <div class="radio-item">
                        <input type="radio" id="other" name="gender" value="other" <?php echo (isset($gender) && $gender == 'other') ? 'checked' : ''; ?>>
                        <label for="other">その他</label>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="satisfaction">満足度 *</label>
                <select id="satisfaction" name="satisfaction" required>
                    <option value="">選択してください</option>
                    <option value="very_satisfied" <?php echo (isset($satisfaction) && $satisfaction == 'very_satisfied') ? 'selected' : ''; ?>>非常に満足</option>
                    <option value="satisfied" <?php echo (isset($satisfaction) && $satisfaction == 'satisfied') ? 'selected' : ''; ?>>満足</option>
                    <option value="neutral" <?php echo (isset($satisfaction) && $satisfaction == 'neutral') ? 'selected' : ''; ?>>普通</option>
                    <option value="dissatisfied" <?php echo (isset($satisfaction) && $satisfaction == 'dissatisfied') ? 'selected' : ''; ?>>不満</option>
                    <option value="very_dissatisfied" <?php echo (isset($satisfaction) && $satisfaction == 'very_dissatisfied') ? 'selected' : ''; ?>>非常に不満</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="comments">コメント（任意）</label>
                <textarea id="comments" name="comments" placeholder="ご意見やご感想をお聞かせください"><?php echo htmlspecialchars($comments ?? ''); ?></textarea>
            </div>
            
            <button type="submit" class="btn-primary">送信</button>
        </form>
        
        <div class="navigation">
            <a href="results.php" class="btn-secondary">アンケート結果を見る</a>
        </div>
    </div>
</body>
</html>