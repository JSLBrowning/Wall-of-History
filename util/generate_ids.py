# Filename:    generate_ids.py
# Description: Simple program to generate a large number of unused IDs.
# Package:     Alexandria 1.0
# Author:      JSLBrowning
# License:     Apache License, Version 2.0


import mysql.connector
from better_profanity import profanity
from nanoid import generate


db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="usbw",
    database="test",
    port=3307
)

cursor = db.cursor()


def make_new_hashes():
    # Get all existing IDs from database.
    cursor.execute("SELECT id FROM story_metadata")
    sql_array = cursor.fetchall()
    cleaned_array = []
    for i in sql_array:
        cleaned_array.append(i[0])
    print(cleaned_array)

    # Generate a bunch of new hashes.
    new_hashes = []
    for _ in range(10000):
        title_hash = generate("0123456789ABCDEF", 6)
        # Check if new hash is clean.
        if profanity.contains_profanity(title_hash):
            print("Skipping inappropriate hash.")
        else:
            # If in existing IDs, drop from array.
            if cleaned_array.count(title_hash) > 0:
                print("Skipping duplicate.")
            else:
                new_hashes.append(title_hash)
    
    # Save array.
    file = open("out/ids.txt", "a")
    for new_hash in new_hashes:
        file.write(new_hash)
        file.write("\n")
    file.close()


make_new_hashes()
