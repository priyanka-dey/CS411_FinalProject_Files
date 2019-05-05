# retrieved using show create procedure check_rev_count
CREATE PROCEDURE `check_rev_count`(IN user_name VARCHAR(255))
BEGIN
	DECLARE rev_count INT;
	DECLARE ran_recs_once varchar(10);
	SELECT COUNT(*)
	INTO rev_count
	FROM REVIEWS;
	IF ((rev_count % 10) = 0) THEN
		BEGIN
			SELECT rec_ran
			INTO ran_recs_once
			FROM USERS
			WHERE user_id = user_name;
			IF (ran_recs_once IS NOT NULL) THEN
				BEGIN
					UPDATE USERS
					SET check_var = 'True'
					WHERE user_id = user_name;
				END;
			END IF;
		END;
	END IF;
END
