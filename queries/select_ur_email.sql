-- Adjust to your real user table/columns
-- Must return: email, name (in that order)
SELECT emp_email, emp_name
FROM tb_employee
WHERE emp_id = ?;
