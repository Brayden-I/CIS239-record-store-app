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
                <th>Format</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rows as $record): ?>
                <tr>
                    <td><?= htmlspecialchars($record['title']) ?></td>
                    <td><?= htmlspecialchars($record['artist']) ?></td>
                    <td class="text-end">$<?= number_format((float)$record['price'],2) ?></td>
                    <td><?= htmlspecialchars($record['name']) ?></td>
                    <td>
                        <form method="post" class="d-inline">
                            <input type="hidden" name="id" value="<?= (int)$record['id'] ?>">
                            <input type="hidden" name="action" value="edit">
                            <button class="btn btn-sm btn-outline-primary">Edit</button>
                        </form>
                        <form method="post" class="d-inline" onsubmit="return confirm('Delete this record?');">
                            <input type="hidden" name="id" value="<?= (int)$record['id'] ?>">
                            <input type="hidden" name="action" value="delete">
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif ?>