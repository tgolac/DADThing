<?php
require_once 'comments.php';
$filters = array(
    'comment' => array(
        'filter' => FILTER_SANITIZE_STRING,
        'options' => array('min_range' => 1, 'max_range' => 10000)
    ),
    'comment_id' => FILTER_SANITIZE_NUMBER_INT,
    'location_id' => FILTER_SANITIZE_NUMBER_INT,
    'location_type' => FILTER_SANITIZE_STRING
);
if ($input = filter_input_array(INPUT_POST, $filters)) {
    $stmt = getDB()->prepare("INSERT INTO comments (user_id, comment_id, location_id, location_type, comment, created) VALUES (:user_id, :comment_id, :location_id, :location_type, :comment, NOW())");
    $stmt->bindParam(':user_id', $_SESSION['user']['id']);
    $stmt->bindParam(':comment', $input['comment']);
    $stmt->bindParam(':comment_id', $input['comment_id']);
    $stmt->bindParam(':location_id', $input['location_id']);
    $location_type = $input['location_type'] == null ? 'GLOBAL' : $input['location_type'];
    $stmt->bindParam(':location_type', $location_type);
    $stmt->execute() or die(print_r($stmt->errorInfo(), true));
}
