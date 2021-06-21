USE test;

CREATE TABLE WoH_metadata(
    id varchar(6) PRIMARY KEY,
    /* The ID can be any six character-long alphanumeric string. Wall of History's are essentially random — I run the titles of works through a hashing algorithm, and the algorithm spits out six characters of gibberish to uniquely identify them. */
    publication_date date,
    /* This can be ignored — the front end doesn't currently do anything with it anyway, but the idea is that it'll eventually display the publication dates of anything that has one. */
    chronology int,
    /* This number, along with the boolean below, defines the default reading order for contents of the site. Only the actual contents of the site should have a chronology value — Tale of the Toa as a whole does not have one, for example, but the individual chapters of Tale of the Toa do. */
    spoiler_level int DEFAULT 1,
    /* This number determines what information will display by default when reference modals are opened. */
    recommended boolean
    /* This boolean defines whether or not the work is included in the default reading order for the site — Quest for the Toa (the GBA game) is on Wall of History's, for example, while Maze of Shadows (the GBA game) isn't. */
);

CREATE TABLE WoH_content(
    id varchar(6) PRIMARY KEY,
    /* Self-explantory — it's the same ID as above. */
    content_language varchar (2) DEFAULT "en",
    /* This is the language of the content in question, in the form of a two-character ISO 639-1 code. */
    content_version int DEFAULT 1,
    /* If there are multiple versions of a given piece of content, they can be identified here with an int, which points to the WoH_versions table (1 is the default, for “standard” versions.) */
    small_image text,
    /* This should be the URL of a square (or at least close to square) icon for the work in question. Chapters of larger works do not NEED this, as the program can recurse up the parent's image (but you can give each chapter a unique image if you want). */
    large_image text,
    /* This is the image that will appear in the summary cards generated for the work in question, so they should be banners. Twitter, for example, uses a 2:1 aspect ratio for these. */
    title text NOT NULL,
    /* Self-explantory, I'm sure. Titles should be minimal — the first chapter of Tale of the Toa is simply titled “Tahu — Toa of Fire,” not “BIONICLE Chronicles #1: Tale of the Toa: ‘Tahu — Toa of Fire.’” */
    snippet text,
    /* This is the descriptive text that will show up underneath the titles of pages in Google searches and on summary cards. Try to keep it brief — Google limits these to 320 characters. */
    header int NOT NULL,
    /* This defines which header HTML will be displayed on the page for this content — for example, the regular BIONICLE logo is used for most Wall of History pages, but the 2002 version is used for pages of Beware the Bohrok. */
    main longtext,
    /* The actual contents of the page (in HTML) go here. */
    word_count int
    /* Can be ignored — as with the publication date, the front end doesn't do anything with this yet. */
);

CREATE TABLE WoH_versions(
    version_id int NOT NULL,
    version_name text NOT NULL
);

CREATE TABLE WoH_headers(
    header_id int PRIMARY KEY,
    /* Self-explantory. */
    html mediumtext
    /* Self-explanatory. */
);

CREATE TABLE WoH_tags(
    id varchar(6),
    /* Self-explanatory — these are the same tags used for metadata and content. */
    tag_type text,
    /* Examples: Type, Language, Organizational, Author, etc. */
    tag text,
    /* Examples: */
    /* Types: animation, blog, card, comic, diary, game, growing_reader, movie, novel, podcast, serial, short_story, web_fiction */
    /* Language: en, es, fr */
    /* Organizational: chapter */
    /* Authors: C.A. Hapka, Greg Farshtey, Carlos D’Anda, Staurt Sayger, etc. */
    detailed_tag text
    /* This is the only part of this database design that's liable to change — this is a more descriptive version of the tag that will be displayed to users. For example, if you put “author” and “Carlos D’Anda” above, you would put “Illustrated by Carlos D’Anda” here. */
);

CREATE TABLE WoH_web(
    parent_id varchar(6) NOT NULL,
    /* This is the shit that really matters right here — the web that connects all the nested tables of contents. BIONICLE Chronicles is the parent to Tale of the Toa, which is the parent to “Tahu — Toa of Fire.” If you put Tale of the Toa's ID here… */
    child_id varchar(6) NOT NULL
    /* You'd put the ID of “Tahu — Toa of Fire” here, then do the same with “Lewa — Toa of Air” — both of these are children of Tale of the Toa, as are the other fourteen chapters. */
);

CREATE TABLE WoH_adaptations(
    /* You can probably ignore this one, since everything that'll be on your site is original (and because I haven't implemented any use for this yet). */
    original_id varchar(6) NOT NULL,
    adaptation_id varchar(6) NOT NULL
);

/*
TO-DO:
Remove author tags from chapters (the twelve big chapters, I mean).
Correct author tags for works not written/created by Farshtey (namely those on Hapka's books).
Remove headings from contents (existing h2s and h1s, since those are generated programmatically).
Remove the accidental recommended booleans from parent items (ex. Trial by Fire (0a63b4)). (This isn't really NECESSARY but it's good to do it anyway.)
Try and standardize single quotes, double quotes, escapes, et cetera.
Remove em tags from MNOG chapter titles. Blah blah blah.
Cards shouldn't be chapters, their names are descriptive enough.
Add images. Obviously.
Chapter will be necessary for read as standalone. Add those to novels, at the VERY least, by launch.
Maybe just delete CSS table entirely?
First MNOG cutscene has a duplicate animation tag. Fix that.
*/