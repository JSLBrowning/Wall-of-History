# Filename:    route.py
# Description: Simple program to turn a text file of space-delimited content IDs into a JSON file formatted for Shin.
# Package:     Shin 1.2
# Author:      JSLBrowning
# License:     Apache License, Version 2.0


def generate_object(id, version):
    return "{\"content_id\": \"" + id + "\", \"content_version\": " + str(version) + "\"}"


def route_to_json(path):
    # Get contents.
    f = open(path, "r")
    content = f.read()
    content_list = content.split(" ")
    f.close()

    new_file = open("out/route.json", "w+", encoding="utf8")
    new_file.write("[\n")
    for i in range(len(content_list)):
        if i == 0:
            new_file.write("    [null, " + generate_object(content_list[0],
                           1) + ", " + generate_object(content_list[1], 1) + "],\n")
        elif i == len(content_list) - 1:
            new_file.write("    [" + generate_object(content_list[i-1], 1) +
                           ", " + generate_object(content_list[i], 1) + ", null]\n")
        else:
            new_file.write("    [" + generate_object(content_list[i-1], 1) + ", " + generate_object(
                content_list[i], 1) + ", " + generate_object(content_list[i+1], 1) + "],\n")
    new_file.write("]")
    new_file.close()


route_to_json("in/route.txt")
