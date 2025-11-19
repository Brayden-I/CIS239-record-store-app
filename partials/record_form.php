<?php
require_once __DIR__ . '/../data/functions.php';

// Detect edit mode and set defaults
$is_edit  = isset($record) && isset($record['id']);
$action   = $is_edit ? 'update' : 'create';

$title    = $is_edit ? htmlspecialchars($record['title'])  : '';
$artist   = $is_edit ? htmlspecialchars($record['artist']) : '';
$price    = $is_edit ? htmlspecialchars($record['price'])  : '';
$genre_id = $is_edit ? (int)$record['genre_id']            : 0;
$format_id = $is_edit ? (int)$record['format_id']          : 0;

$genres = genres_all_query();
$formats = formats_all_query();

?>
<h2 class="h4 mb-3"><?= $is_edit ? 'Edit Record' : 'Add Record' ?></h2>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="post" class="row g-3">
            <div class="col-12">
                <label class="form-label">Title</label>
                <input name="title" class="form-control" value="<?= $title ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Artist</label>
                <input name="artist" class="form-control" value="<?= $artist ?>" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Price</label>
                <input name="price" type="number" step="0.01" class="form-control" value="<?= $price ?>" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Genre</label>
                <select name="genre_id" class="form-select" required>
                    <option value="">Select...</option>
                    <?php foreach ($genres as $g): ?>
                        <option value="<?= (int)$g['id'] ?>" <?= (int)$g['id'] === $genre_id ? 'selected' : '' ?>>   
                            <?= htmlspecialchars($g['genre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label">Format</label>
                <select name="format_id" class="form-select" required>
                    <option value="">Select...</option>
                    <?php foreach ($formats as $f): ?>
                        <option value="<?= (int)$f['id'] ?>" <?= (int)$f['id'] === $format_id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($f['format']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <input type="hidden" name="action" value="<?= $action ?>">
            <?php if ($is_edit): ?>
                <input type="hidden" name="id" value="<?= (int)$record['id'] ?>">
            <?php endif; ?>

            <div class="col-12">
                <button class="btn btn-success"><?= $is_edit ? 'Update' : 'Create' ?></button>
                <a href="?view=list" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
