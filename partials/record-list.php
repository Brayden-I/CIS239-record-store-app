<?php
// partials/record-list.php
require_once __DIR__ . '/../data/functions.php';
$rows = records_all();
?>

<?php if (empty($rows)): ?>
    <strong>No Results</strong>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Artist</th>
                <th>Price</th>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rows as $record): ?>
                <tr>
                    <td><?= htmlspecialchars($record['title']) ?></td>
                    <td><?= htmlspecialchars($record['artist']) ?></td>
                    <td><?= htmlspecialchars($record['price']) ?></td>
                    <td><?= htmlspecialchars($record['name']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif ?>