INSERT INTO tb_ticket_update_log (
        tk_id,
        action,
        org_id,
        ur_id,
        tk_assignee,
        tk_priority,
        cat_id,
        created_at
    )
VALUES (?, 'UPDATE', ?, ?, ?, ?, ?, NOW());