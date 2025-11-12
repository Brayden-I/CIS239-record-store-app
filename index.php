<?php
    require_once "data/functions.php";

    $view   = filter_input(INPUT_GET, 'view') ?: 'list';
    $action = filter_input(INPUT_POST, 'action');

    switch ($action) {
        // ========== CREATE ==========
        case 'create':
            // Get data from form input
            $title    = trim((string)(filter_input(INPUT_POST, 'title') ?? ''));
            $artist   = trim((string)(filter_input(INPUT_POST, 'artist') ?? ''));
            $price    = (float)(filter_input(INPUT_POST, 'price') ?? 0);
            $format_id = (int)(filter_input(INPUT_POST, 'format_id') ?? 0);
            $genre_id = (int)(filter_input(INPUT_POST, 'genre_id') ?? 0);
            
            if ($title && $artist && $genre_id) {
                record_create($title, $artist, $price, $format_id, $genre_id);
                $view = 'created';
            } else {
                $view = 'create'; // missing fields
            }
            break;

        case 'update':
            $id        = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $title     = (string)filter_input(INPUT_POST, 'title',  FILTER_UNSAFE_RAW);
            $artist    = (string)filter_input(INPUT_POST, 'artist', FILTER_UNSAFE_RAW);
            $price_in  =        filter_input(INPUT_POST, 'price',   FILTER_UNSAFE_RAW);
            $format_id  =        filter_input(INPUT_POST, 'format_id', FILTER_VALIDATE_INT);
            $genre_id  =        filter_input(INPUT_POST, 'genre_id', FILTER_VALIDATE_INT);

            $price = is_numeric($price_in) ? (float)$price_in : null;

            if ($id && $title !== '' && $artist !== '' && $price !== null && $genre_id) {
                record_update($id, $title, $artist, $price, (int)$format_id, (int)$genre_id);
            }
            $view = 'updated';
            break;

        case 'delete':
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

            if ($id) {
                $deleted = record_delete($id);
            }
            $view = 'deleted';
            break;

        case 'edit':
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if ($id) {
                $record = record_get($id);
            }
            $view = 'create';   // reuse the same form view
            break;
        default:
            // No action, show the list
            break;
    }
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
     <div class="container py-4">
        <!-- navigation -->
        <?php include __DIR__ . '/components/nav.php'; ?>

        <?php
        if ($view === 'list')        include __DIR__ . '/partials/record-list.php';
        elseif ($view === 'create')  include __DIR__ . '/partials/record-form.php';
        elseif ($view === 'created') include __DIR__ . '/partials/record-created.php';
        elseif ($view === 'updated') include __DIR__ . '/partials/record-updated.php';
        elseif ($view === 'deleted') include __DIR__ . '/partials/record-deleted.php';
        else                         include __DIR__ . '/partials/record-list.php';
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
