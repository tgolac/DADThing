<?php
require_once 'comments.php';
$filters = array(
	'user_id' => array(
		'filter' => FILTER_SANITIZE_STRING,
	),
	'title' => array(
		'filter' => FILTER_SANITIZE_STRING
	),
	'content' => array(
	)
);
if ($input = filter_input_array(INPUT_POST, $filters)) {
	if (filter_input_array_required_valid($input, $filters)) {
		$stmt = getDB()->prepare("INSERT INTO posts (user_id, title, content, created) VALUES (:user_id, :title, :content, NOW())");
		$stmt->bindParam(':user_id', $_SESSION['user']['id']);
		$stmt->bindParam(':title', $input['title']);
		$stmt->bindParam(':content', $input['content']);
		$stmt->execute() or die(print_r($stmt->errorInfo(), true));
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
