<?php
require_once 'comments.php';
$filters = array(
	'comment' => array(
		'filter' => FILTER_SANITIZE_STRING,
		'options' => array('min_range' => 1, 'max_range' => 10000),
	),
	'comment_id' => array(
		'filter' => FILTER_SANITIZE_NUMBER_INT,
		'optional' => true
	),
	'location_id' => array(
		'filter' => FILTER_SANITIZE_NUMBER_INT,
		'optional' => true
	),
	'location_type' => array(
		'filter' => FILTER_SANITIZE_STRING,
		'optional' => true
	)
);
if (!isset($_SESSION['user']['id'])) {
	die(json_encode(array(
		"status" => -3
	)));
} elseif ($input = filter_input_array(INPUT_POST, $filters)) {
	if (filter_input_array_required_valid($input, $filters)) {
		$stmt = getDB()->prepare("INSERT INTO comments (user_id, comment_id, location_id, location_type, comment, created) VALUES (:user_id, :comment_id, :location_id, :location_type, :comment, NOW())");
		$stmt->bindParam(':user_id', $_SESSION['user']['id']);
		$stmt->bindParam(':comment', $input['comment']);
		$stmt->bindParam(':comment_id', $input['comment_id']);
		$stmt->bindParam(':location_id', $input['location_id']);
		$location_type = $input['location_type'] == null ? 'GLOBAL' : $input['location_type'];
		$stmt->bindParam(':location_type', $location_type);
		$stmt->execute() or die(print_r($stmt->errorInfo(), true));
		die(json_encode(array(
			"status" => 1
		)));
	} else {
		die(json_encode(array(
				"status" => -1,
				"errors" => get_input_errors($input, $filters))
		));
	}
} else {
	die(json_encode(array(
		"status" => -2
	)));
}
