### ITEM BASED COLLABORATIVE FILTERING

import mysql.connector
import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import warnings
warnings.filterwarnings('ignore')
import sys

user_id = sys.argv[1]

# make the db connection
wine_snob = mysql.connector.connect(
host="127.0.0.1",
user="pdey3",
passwd="cs411",
database="wine_snob",
auth_plugin='mysql_native_password'
)

# get all the reviews/user's top 3 rated wines
columns = ['review_id', 'score', 'user_id', 'wine_id']
mycursor = wine_snob.cursor()
mycursor.execute("SELECT review_id, score, user_id, wine_id FROM REVIEWS")
all_reviews = mycursor.fetchall()
reviews = pd.DataFrame(np.array(all_reviews).reshape(len(all_reviews),4), columns = columns)
reviews.score = reviews.score.astype(int)
reviews.wine_id = reviews.wine_id.astype(int)

sql = "SELECT review_id , score, user_id, wine_id \
        FROM REVIEWS WHERE user_id='" + user_id + \
        "' ORDER BY score DESC LIMIT 5"
mycursor.execute(sql)
users_top_3 = mycursor.fetchall()
top_3 = pd.DataFrame(np.array(users_top_3).reshape(5,4), columns = columns)
top_3.score = top_3.score.astype(int)
top_3.wine_id = top_3.wine_id.astype(int)

mycursor.execute("SELECT wine_id FROM REVIEWS WHERE user_id='" + user_id + "'");
wine_ids = list(mycursor.fetchall())
wines=[]
for wine in wine_ids:
    wines.append(wine[0])

# ratings dataframe (sorted on most rated)
ratings = pd.DataFrame(reviews.groupby('wine_id')['score'].mean())
ratings['number_of_ratings'] = reviews.groupby('wine_id')['score'].count()
ratings = ratings.sort_values('number_of_ratings', ascending=False)

# create the wine matrix
wine_matrix = reviews.pivot_table(index='user_id', columns='wine_id',
                            values='score')

# now using the top 3 wines
wine_1 = top_3.iloc[0]
w_1 = wine_matrix[(wine_1['wine_id'])]
wine_2 = top_3.iloc[1]
w_2 = wine_matrix[(wine_2['wine_id'])]
wine_3 = top_3.iloc[2]
w_3 = wine_matrix[(wine_3['wine_id'])]
wine_4 = top_3.iloc[3]
w_4 = wine_matrix[(wine_4['wine_id'])]
wine_5 = top_3.iloc[4]
w_5 = wine_matrix[(wine_5['wine_id'])]
# find similar wines to the top 3 using correlation
similar_to_w1 = wine_matrix.corrwith(w_1)
similar_to_w2 = wine_matrix.corrwith(w_2)
similar_to_w3 = wine_matrix.corrwith(w_3)
similar_to_w4 = wine_matrix.corrwith(w_4)
similar_to_w5 = wine_matrix.corrwith(w_5)

# sort correlations in desc order
corr_w1 = pd.DataFrame(similar_to_w1, columns=['correlation'])
corr_w1 = corr_w1.sort_values(by=['correlation'], ascending=False)
corr_w2 = pd.DataFrame(similar_to_w2, columns=['correlation'])
corr_w2 = corr_w2.sort_values(by=['correlation'], ascending=False)
corr_w3 = pd.DataFrame(similar_to_w3, columns=['correlation'])
corr_w3 = corr_w3.sort_values(by=['correlation'], ascending=False)
corr_w4 = pd.DataFrame(similar_to_w4, columns=['correlation'])
corr_w4 = corr_w4.sort_values(by=['correlation'], ascending=False)
corr_w5 = pd.DataFrame(similar_to_w5, columns=['correlation'])
corr_w5 = corr_w5.sort_values(by=['correlation'], ascending=False)

# select top 3 from each
recommended_wines = []
count = 0
for c in corr_w1.iterrows():
    # print(c[0])
    if c[0] != wine_1['wine_id'] and count < 3:
        if c[0] not in recommended_wines:
            if c[0] not in wines:
                recommended_wines.append(c[0])
        count += 1
    if count == 3:
        break
count = 0

for c in corr_w2.iterrows():
    if c[0] != wine_2['wine_id'] and count < 3:
        if c[0] not in recommended_wines:
            if c[0] not in wines:
                recommended_wines.append(c[0])
        count += 1
    if count == 3:
        break
count = 0

for c in corr_w3.iterrows():
    if c[0] != wine_3['wine_id'] and count < 3:
        if c[0] not in recommended_wines:
            if c[0] not in wines:
                recommended_wines.append(c[0])
        count += 1
    if count == 3:
        break

for c in corr_w3.iterrows():
    if c[0] != wine_3['wine_id'] and count < 3:
        if c[0] not in recommended_wines:
            if c[0] not in wines:
                recommended_wines.append(c[0])
        count += 1
    if count == 3:
        break

for c in corr_w3.iterrows():
    if c[0] != wine_3['wine_id'] and count < 3:
        if c[0] not in recommended_wines:
            if c[0] not in wines:
                recommended_wines.append(c[0])
        count += 1
    if count == 3:
        break

for wine in recommended_wines:
    print(wine)

mycursor.close()
