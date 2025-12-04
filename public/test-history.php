<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Get current user
$user = auth()->user();
if (!$user) {
    die('Please login first. Go to <a href="/login">/login</a>');
}

// Query assessments
$assessments = \App\Models\Assessment::where('user_id', $user->id)
    ->orderBy('created_at', 'desc')
    ->get();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Test History - User: <?= $user->email ?></title>
    <style>
        body { font-family: Arial; padding: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Test History Page</h1>
    <p><strong>User:</strong> <?= $user->email ?> (ID: <?= $user->id ?>)</p>
    <p><strong>Total Assessments:</strong> <?= $assessments->count() ?></p>

    <?php if ($assessments->count() > 0): ?>
        <h2 class="success">✓ Data FOUND!</h2>
        <table>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>Title</th>
                <th>Status</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($assessments as $i => $a): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= $a->id ?></td>
                <td><?= htmlspecialchars($a->title) ?></td>
                <td><strong><?= $a->status ?></strong></td>
                <td><?= $a->created_at->format('d M Y H:i') ?></td>
                <td>
                    <?php if ($a->status === 'done'): ?>
                        <a href="/assess/<?= $a->id ?>/result">Lihat Hasil</a>
                    <?php elseif ($a->status === 'draft'): ?>
                        <a href="/assess/<?= $a->id ?>/wizard">Lanjutkan</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <h3 class="success">Kesimpulan:</h3>
        <p>✓ Data assessment ADA di database</p>
        <p>✓ Query berhasil</p>
        <p>✗ Masalah ada di Livewire component atau view rendering</p>

    <?php else: ?>
        <h2 class="error">✗ No Data Found</h2>
        <p>User ini belum punya assessment.</p>
    <?php endif; ?>

    <hr>
    <p><a href="/user/history">← Kembali ke halaman history asli</a></p>
</body>
</html>
