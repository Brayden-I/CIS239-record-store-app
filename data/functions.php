<?php

require "db.php";

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

