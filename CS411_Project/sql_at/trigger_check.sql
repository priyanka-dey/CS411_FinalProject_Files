DROP TRIGGER change_check_var;

DELIMITER //
CREATE TRIGGER `change_check_var` AFTER INSERT ON REVIEWS
FOR EACH ROW BEGIN
/*
	DECLARE user_name varchar(255);
	SET user_name = new.user_id;
*/
	CALL check_rev_count(new.user_id); 
END //

