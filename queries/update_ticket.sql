UPDATE tb_ticket
SET org_id = ?,
    ur_id = ?,
    tk_assignee = ?,
    tk_priority = ?,
    cat_id = ?
WHERE tk_id = ?;