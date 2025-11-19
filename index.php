<?php
    include_once __DIR__ . "/data/db.php";
    include_once __DIR__ . "/data/functions.php";

    session_start();

    $view   = filter_input(INPUT_GET, 'view') ?: 'list';
    $action = filter_input(INPUT_POST, 'action');

    function require_login(): void {
        if (empty($_SESSION['user_id'])) {
            header('Location: ?view=login');
            exit;
        }
    }

    $public_views   = ['login', 'register'];
    $public_actions = ['login', 'register'];

    if ($action && !in_array($action, $public_actions, true)) {
        require_login();
    }

    if (!$action && !in_array($view, $public_views, true)) {
        require_login();
    }

    switch ($action) {
        case 'login':
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if ($username && $password) {
        $user = user_find_by_username($username);
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $view = 'list';
        } else {
            $login_error = "Invalid username or password.";
            $view = 'login';
        }
    } else {
        $login_error = "Enter both fields.";
        $view = 'login';
    }
    break;

    case 'logout':
        $_SESSION = [];
        session_destroy();
        session_start();
        $view = 'login';
        break;

    case 'register':
        $username  = trim((string)($_POST['username'] ?? ''));
        $full_name = trim((string)($_POST['full_name'] ?? ''));
        $password  = (string)($_POST['password'] ?? '');
        $confirm   = (string)($_POST['confirm_password'] ?? '');

        if ($username && $full_name && $password && $password === $confirm) {
            $existing = user_find_by_username($username);
            if ($existing) {
                $register_error = "That username already exists.";
                $view = 'register';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                user_create($username, $full_name, $hash);

                $user = user_find_by_username($username);
                $_SESSION['user_id'] = (int)$user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $view = 'list';
            }
        } else {
            $register_error = "Complete all fields and match passwords.";
            $view = 'register';
        }
        break;

    case 'add_to_cart':
        require_login();
        $record_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if ($record_id) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            $_SESSION['cart'][] = $record_id;
        }
        $view = 'list';
        break;

    case 'checkout':
        require_login();
        $cart_ids = $_SESSION['cart'] ?? [];

        if ($cart_ids) {
            foreach ($cart_ids as $rid) {
                purchase_create((int)$_SESSION['user_id'], (int)$rid);
            }
            $_SESSION['cart'] = [];
        }
        $view = 'checkout_success';
        break;
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

    // Prepare data for views
    $records_in_cart = [];
    if ($view === 'cart') {
        $cart_ids = $_SESSION['cart'] ?? [];
        if (!empty($cart_ids)) {
            $records_in_cart = records_by_ids($cart_ids);
        }
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
        if ($view === 'login') {
            include __DIR__ . '/partials/login_form.php';
        }
        elseif ($view === 'register') {
            include __DIR__ . '/partials/register_form.php';
        }
        elseif ($view === 'cart') {
            include __DIR__ . '/partials/cart.php';
        }
        elseif ($view === 'checkout_success') {
            include __DIR__ . '/partials/checkout_success.php';
        }
        elseif ($view === 'list') {
            include __DIR__ . '/partials/records_list.php';
        }
        elseif ($view === 'create') {
            include __DIR__ . '/partials/record_form.php';
        }
        elseif ($view === 'created') {
            include __DIR__ . '/partials/record_created.php';
        }
        elseif ($view === 'deleted') {
            include __DIR__ . '/partials/record_deleted.php';
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
