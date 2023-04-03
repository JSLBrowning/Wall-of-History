# Filename:    count_words.py
# Description: Simple program to count the number of words on each line of a file and output to a list of SQL statements.
# Package:     Shin 1.2
# Author:      JSLBrowning
# License:     Apache License, Version 2.0


def count_words(database_name):
    # Get contents.
    f = open("in/words.txt", "r")
    content = f.read()
    content_list = content.split("\n")
    f.close()
    
    new_file = open("count.sql", "w", encoding="utf8")
    new_file.write("USE " + database_name + ";\n")
    for i in content_list:
        linearray = i.split(": ")
        identifiers = linearray[0].split(".")
        new_file.write("UPDATE story_content SET word_count=" + linearray[1] + " WHERE id='" + identifiers[0] + "' AND content_version=" + identifiers[1] + " AND content_language='" + identifiers[2] + "';\n")
    new_file.close()


count_words("test")
