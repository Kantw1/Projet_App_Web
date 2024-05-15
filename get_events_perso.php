<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$agenda_perso_code = $_SESSION['agenda_perso_code'];

try {
    $pdo = new PDO('mysql:host=your_host;dbname=your_db', 'your_user', 'your_password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer tous les codes d'agenda auxquels l'utilisateur a accès
    $stmt = $pdo->prepare('
        SELECT agenda_code
        FROM agenda_access
        WHERE user_id = :user_id
    ');
    $stmt->execute(['user_id' => $user_id]);
    $agenda_codes = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Inclure le code d'agenda personnel de l'utilisateur
    if ($agenda_perso_code) {
        $agenda_codes[] = $agenda_perso_code;
    }

    if (empty($agenda_codes)) {
        echo json_encode([]);
        exit();
    }

    // Récupérer tous les événements des agendas accessibles
    $placeholders = str_repeat('?,', count($agenda_codes) - 1) . '?';
    $stmt = $pdo->prepare("
        SELECT e.event_id, e.agenda_code, e.title, e.description, e.place, e.time_from, e.time_to, e.event_date
        FROM events e
        WHERE e.agenda_code IN ($placeholders)
    ");
    $stmt->execute($agenda_codes);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($events);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>

