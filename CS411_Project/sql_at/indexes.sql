/* creating some indexes for the most commonly used attributes in each table
We have tables and most used attributes:
    USERS: ---
    REVIEWS: score
    WINES: country, variety, title, price
*/
/*
CREATE INDEX r_score ON REVIEWS(score);*/

CREATE INDEX w_variety ON WINES(variety);
CREATE INDEX w_country ON WINES(country);
CREATE INDEX w_price ON WINES(price);
