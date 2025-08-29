SELECT p.uri
FROM tb_permission_user pu
INNER JOIN tb_permissions p ON pu.permission_id = p.permission_id
WHERE pu.ur_id = ?;
