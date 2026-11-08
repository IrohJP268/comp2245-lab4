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

// Optional filter: query by subject id
$subject = filter_input(INPUT_GET, 'subject', FILTER_VALIDATE_INT,
                        ['options' => ['min_range' => 1]]);
if (is_int($subject)) {
    $sql .= ' AND books.subject_id = :subject';
    $params[':subject'] = $subject;
}

// Optional filter: at least one copy on the shelf
if (($_GET['available'] ?? '') === '1') {
    $sql .= ' AND books.copies - books.on_loan > 0';
}


$sql .= ' ORDER BY books.title';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
echo json_encode($stmt->fetchAll());