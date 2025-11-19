<?php

require_once "db.php";

function records_all(): array
{
    $pdo = get_pdo();
    return $pdo->query("SELECT f.id,title,artist,price, f.name FROM `records` AS r JOIN formats AS f ON r.format_id = f.id;")->fetchAll();
}

function record_get(int $id): ?array
{
    $pdo = get_pdo();
    $stmt = $pdo->prepare("SELECT * FROM `records` WHERE id = :id;");
    $stmt->execute([':id' => $id]);
    $record = $stmt->fetch();
    return $record ?: null;
}

function genres_all_query(): array
{
    $pdo = get_pdo();
    return $pdo->query("SELECT id, name AS genre FROM genres ORDER BY genre")->fetchAll();
}

function formats_all_query(): array
{
    $pdo = get_pdo();
    return $pdo->query("SELECT id, name AS format FROM formats ORDER BY format")->fetchAll();
}

function record_create(string $title, string $artist, float $price, int $format_id, int $genre_id): void
{
    $pdo = get_pdo();
    $stmt = $pdo->prepare("INSERT INTO `records` (title, artist, price, format_id, genre_id) VALUES (:title, :artist, :price, :format_id, :genre_id);");
    $stmt->execute([
        ':title' => $title,
        ':artist' => $artist,
        ':price' => $price,
        ':format_id' => $format_id,
        ':genre_id' => $genre_id,
    ]);
}

function record_delete(int $id): int
{
    $pdo = get_pdo();
    $stmt = $pdo->prepare("DELETE FROM `records` WHERE id = :id;");
    $stmt->execute([':id' => $id]);
    return $stmt->rowCount(); // 1 if deleted, 0 if not found
}

function record_update(int $id, string $title, string $artist, float $price, int $format_id, int $genre_id): int
{
    $pdo = get_pdo();
    $stmt = $pdo->prepare("UPDATE `records` SET title = :title, artist = :artist, price = :price, format_id = :format_id, genre_id = :genre_id WHERE id = :id;");
    $stmt->execute([
        ':id' => $id,
        ':title' => $title,
        ':artist' => $artist,
        ':price' => $price,
        ':format_id' => $format_id,
        ':genre_id' => $genre_id,
    ]);
    return $stmt->rowCount(); // 1 if updated, 0 if not found or no change
}

function user_create(string $username, string $full_name, string $hash): void {
    $pdo = get_pdo();
    $sql = "INSERT INTO users (username, full_name, password_hash)
            VALUES (:u, :f, :p)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':u'=>$username, ':f'=>$full_name, ':p'=>$hash]);
}

function user_find_by_username(string $username): ?array {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :u");
    $stmt->execute([':u'=>$username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
}

function records_by_ids(array $ids): array {
    if (empty($ids)) return [];
    $pdo = get_pdo();
    $ph = implode(',', array_fill(0, count($ids), '?'));
    $sql = "SELECT r.id, r.title, r.artist, r.price, f.name
            FROM records r
            JOIN formats f ON r.format_id = f.id
            WHERE r.id IN ($ph)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_values($ids));
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function purchase_create(int $user_id, int $record_id): void {
    $pdo = get_pdo();
    $sql = "INSERT INTO purchases (user_id, record_id, purchase_date)
            VALUES (:u, :r, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':u'=>$user_id, ':r'=>$record_id]);
}