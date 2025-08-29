<?php


if (!function_exists('userHasPermission')) {
	/**
	 * Check if a user has a specific permission URI.
	 *
	 * @param mysqli     $conn     Active MySQLi connection
	 * @param int|string $userId   User ID (will be cast to int). If empty/null returns false.
	 * @param string     $uri      Permission URI to check (e.g., 'add_employee')
	 * @return bool                True if user has permission, otherwise false
	 */
	function userHasPermission(mysqli $conn, $userId, string $uri): bool
	{
		$userId = (int)$userId;
		if ($userId <= 0 || $uri === '') {
			return false;
		}

		// Simple in-memory static cache for repeated checks within the same request
		static $cache = [];
		$cacheKey = $userId . '|' . $uri;
		if (array_key_exists($cacheKey, $cache)) {
			return $cache[$cacheKey];
		}

		$sql = "SELECT 1 FROM tb_permission_user pu INNER JOIN tb_permissions p ON pu.permission_id = p.permission_id WHERE pu.ur_id = ? AND p.uri = ? LIMIT 1";
		$has = false;
		if ($stmt = $conn->prepare($sql)) {
			$stmt->bind_param('is', $userId, $uri);
			if ($stmt->execute()) {
				$stmt->store_result();
				$has = $stmt->num_rows > 0;
			}
			$stmt->close();
		}

		$cache[$cacheKey] = $has;
		return $has;
	}
}
?>
