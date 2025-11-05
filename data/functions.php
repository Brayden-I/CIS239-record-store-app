<?php

require "db.php";

function records_all(): array
{
    $pdo = get_pdo();
    return $pdo->query("SELECT f.id,title,artist,price, f.name FROM `records` AS r JOIN formats AS f ON r.format_id = f.id;")->fetchAll();
}

function record_create(string $title, string $artist, float $price, int $format_id, int $genre_id): bool
{
    $pdo = get_pdo();
    $stmt = $pdo->prepare("INSERT INTO `records` (title, artist, price, format_id, genre_id) VALUES (:title, :artist, :price, :format_id, :genre_id);");
    return $stmt->execute([
        ':title' => $title,
        ':artist' => $artist,
        ':price' => $price,
        ':format_id' => $format_id,
        ':genre_id' => $genre_id,
    ]);
}

function genres_all_query(): array
{
    $pdo = get_pdo();
    return $pdo->query("SELECT id, name FROM genres ORDER BY name")->fetchAll();
}

function formats_all_query(): array
{
    $pdo = get_pdo();
    return $pdo->query("SELECT id, name FROM formats ORDER BY name")->fetchAll();
}