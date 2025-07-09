<?php
require_once 'config.php';

// ページング設定
$page = (int)($_GET['page'] ?? 1);
$limit = 10;
$offset = ($page - 1) * $limit;

// 総件数を取得
$totalSql = "SELECT COUNT(*) FROM surveys";
$totalStmt = $pdo->prepare($totalSql);
$totalStmt->execute();
$totalCount = $totalStmt->fetchColumn();

// 総ページ数を計算
$totalPages = ceil($totalCount / $limit);

// アンケート結果を取得
$sql = "SELECT * FROM surveys ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(1, $limit, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$surveys = $stmt->fetchAll();

// 集計データを取得
$statsSql = "SELECT 
    COUNT(*) as total_responses,
    AVG(age) as avg_age,
    COUNT(CASE WHEN gender = 'male' THEN 1 END) as male_count,
    COUNT(CASE WHEN gender = 'female' THEN 1 END) as female_count,
    COUNT(CASE WHEN gender = 'other' THEN 1 END) as other_count,
    COUNT(CASE WHEN satisfaction = 'very_satisfied' THEN 1 END) as very_satisfied_count,
    COUNT(CASE WHEN satisfaction = 'satisfied' THEN 1 END) as satisfied_count,
    COUNT(CASE WHEN satisfaction = 'neutral' THEN 1 END) as neutral_count,
    COUNT(CASE WHEN satisfaction = 'dissatisfied' THEN 1 END) as dissatisfied_count,
    COUNT(CASE WHEN satisfaction = 'very_dissatisfied' THEN 1 END) as very_dissatisfied_count
FROM surveys";
$statsStmt = $pdo->prepare($statsSql);
$statsStmt->execute();
$stats = $statsStmt->fetch();

// 日本語ラベル
$genderLabels = [
    'male' => '男性',
    'female' => '女性',
    'other' => 'その他'
];

$satisfactionLabels = [
    'very_satisfied' => '非常に満足',
    'satisfied' => '満足',
    'neutral' => '普通',
    'dissatisfied' => '不満',
    'very_dissatisfied' => '非常に不満'
];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アンケート結果</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
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
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        .stat-label {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin: 20px 0;
        }
        .pagination a {
            padding: 8px 12px;
            text-decoration: none;
            background-color: #007bff;
            color: white;
            border-radius: 4px;
        }
        .pagination a:hover {
            background-color: #0056b3;
        }
        .pagination .current {
            padding: 8px 12px;
            background-color: #6c757d;
            color: white;
            border-radius: 4px;
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
        .no-data {
            text-align: center;
            color: #666;
            padding: 40px;
        }
        .comments {
            max-width: 200px;
            word-wrap: break-word;
        }
        .navigation {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>アンケート結果</h1>
        
        <?php if ($stats['total_responses'] > 0): ?>
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-number"><?php echo number_format($stats['total_responses']); ?></div>
                    <div class="stat-label">総回答数</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo number_format($stats['avg_age'], 1); ?>歳</div>
                    <div class="stat-label">平均年齢</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo number_format($stats['male_count']); ?></div>
                    <div class="stat-label">男性</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo number_format($stats['female_count']); ?></div>
                    <div class="stat-label">女性</div>
                </div>
            </div>
            
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-number"><?php echo number_format($stats['very_satisfied_count']); ?></div>
                    <div class="stat-label">非常に満足</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo number_format($stats['satisfied_count']); ?></div>
                    <div class="stat-label">満足</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo number_format($stats['neutral_count']); ?></div>
                    <div class="stat-label">普通</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo number_format($stats['dissatisfied_count']); ?></div>
                    <div class="stat-label">不満</div>
                </div>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>名前</th>
                            <th>年齢</th>
                            <th>性別</th>
                            <th>満足度</th>
                            <th>コメント</th>
                            <th>回答日時</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($surveys as $survey): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($survey['id']); ?></td>
                            <td><?php echo htmlspecialchars($survey['name']); ?></td>
                            <td><?php echo htmlspecialchars($survey['age']); ?>歳</td>
                            <td><?php echo htmlspecialchars($genderLabels[$survey['gender']]); ?></td>
                            <td><?php echo htmlspecialchars($satisfactionLabels[$survey['satisfaction']]); ?></td>
                            <td class="comments"><?php echo htmlspecialchars($survey['comments'] ?: '－'); ?></td>
                            <td><?php echo date('Y/m/d H:i', strtotime($survey['created_at'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=1">&laquo; 最初</a>
                    <a href="?page=<?php echo $page - 1; ?>">&lsaquo; 前</a>
                <?php endif; ?>
                
                <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                    <?php if ($i == $page): ?>
                        <span class="current"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>">次 &rsaquo;</a>
                    <a href="?page=<?php echo $totalPages; ?>">最後 &raquo;</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
        <?php else: ?>
            <div class="no-data">
                <p>まだアンケートの回答がありません。</p>
            </div>
        <?php endif; ?>
        
        <div class="navigation">
            <a href="index.php" class="btn-secondary">アンケートに戻る</a>
        </div>
    </div>
</body>
</html>