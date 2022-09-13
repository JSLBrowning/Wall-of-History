# Filename:    blank.py
# Description: Simple program to generate a specified number of blank lines formatted for Alexandria.
# Package:     Alexandria 1.0
# Author:      JSLBrowning
# License:     Apache License, Version 2.0


with open("util/out/blank.html", "w", encoding="utf-8") as f:
    for i in range(1, 257):
        position = str(i)
        f.write("<span id='p" + position + "' class='anchors'><a class='anchor' href='#p" + position + "'>" + position + "</a>\n")
        f.write("    <p></p>\n")
        f.write("</span>\n")