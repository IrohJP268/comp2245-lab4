<?php
header('Content-Type: application/json; charset=UTF-8');
require __DIR__ . '/db.php';


$q = $_GET['q'] ?? '';
$search = is_string($q) ? trim($q) : '';
$pattern = '%' . $search . '%';


$sql = 'SELECT books.title, books.author, books.year,
               subjects.name AS subject,
               books.copies,
               books.copies - books.on_loan AS available
        FROM books
        JOIN subjects ON subjects.id = books.subject_id
        WHERE (books.title LIKE :title OR books.author LIKE :author)';
$params = [':title' => $pattern, ':author' => $pattern];

$sql .= ' ORDER BY books.title';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
echo json_encode($stmt->fetchAll());