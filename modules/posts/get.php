<?php
require_once 'comments.php';
$filters = array(
	'location_id' => array(
		'filter' => FILTER_SANITIZE_NUMBER_INT
	),
	'location_type' => array(
		'filter' => FILTER_SANITIZE_STRING
	)
);
if ($input = filter_input_array(INPUT_POST, $filters)) {
	if (filter_input_array_required_valid($input, $filters)) {
		$stmt = getDB()->prepare("SELECT p.id, CONCAT(u.first_name, ' ', u.last_name) AS 'fullname', u.username, p.title, p.content, p.created FROM posts p LEFT JOIN users u ON p.user_id = u.id LIMIT g:start,:limit");
		$stmt->bindParam(':location_id', $input['location_id']);
		$stmt->bindParam(':location_type', $input['location_type']);
		$stmt->execute() or die(print_r($stmt->errorInfo(), true));
		if ($stmt->rowCount() > 0) {
			if ($data = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
				die(json_encode(array(
					"status" => 1,
					"data" => $data
				)));
			}
		} else {
			die(json_encode(array(
				"status" => 0,
			)));
		}
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