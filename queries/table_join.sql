SELECT
    t.tk_id,
    t.tk_summary,
    t.tk_priority,
    t.tk_created_at,
    t.tk_updated_at,
    t.tk_due_date as due_date,
    t.tk_description,
    t.tk_creator as creator_name,
    assignee.emp_name as assignee_name,
    org.org_name,
    cat.cat_name,
    t.status_name,
    log.changed_at
FROM
    tb_ticket t
    LEFT JOIN tb_user creator ON t.tk_creator = creator.ur_email
    LEFT JOIN tb_employee assignee ON t.tk_assignee = assignee.emp_id
    LEFT JOIN tb_organization org ON t.org_id = org.org_id
    LEFT JOIN tb_category cat ON t.cat_id = cat.cat_id
    LEFT JOIN tb_status st ON t.st_id = st.st_id
    LEFT JOIN (
        SELECT
            tk_id,
            MAX(changed_at) AS changed_at
        FROM
            tb_ticket_log
        GROUP BY
            tk_id
    ) log ON t.tk_id = log.tk_id;