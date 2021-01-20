USE test;

CREATE TABLE WoH_metadata(
    id varchar(6) PRIMARY KEY,
    title text NOT NULL,
    snippet text,
    /* small_image text, */
    /* large_image text, */
    publish_date date,
    chronology int,
    recommended boolean
);

CREATE TABLE WoH_content(
    id varchar(6) PRIMARY KEY,
    /* For content with no spoken or written text, this can be NULL. */
    css int,
    header int NOT NULL,
    main longtext,
    word_count int
);

CREATE TABLE WoH_css(
    css_id int PRIMARY KEY,
    html mediumtext
);

CREATE TABLE WoH_headers(
    header_id int PRIMARY KEY,
    html mediumtext
);

CREATE TABLE WoH_tags(
    id varchar(6),
    tag_type text,
    /* Examples: Type, Language, Organizational, Author, Illustrator, etc. */
    tag text
    /* Examples: */
    /* Types: animation, blog, card, comic, diary, game, growing_reader, movie, novel, podcast, serial, short_story, web_fiction */
    /* Language: en, es, fr */
    /* Organizational: chapter */
    /* Authors: C.A. Hapka, Greg Farshtey, etc. */
    /* Illustrators: Carlos D'Anda, Staurt Sayger, etc. */
);

CREATE TABLE WoH_web(
    parent_id varchar(6) NOT NULL,
    child_id varchar(6) NOT NULL
);

CREATE TABLE WoH_adaptations(
    original_id varchar(6) NOT NULL,
    child_id varchar(6) NOT NULL
);

/*
TO-DO:
Remove author tags from chapters (the twelve big chapters, I mean).
Correct author tags for works not written/created by Farshtey (namely those on Hapka's books).
Remove headings from contents (existing h2s and h1s, since those are generated programmatically).
Remove the accidental recommended booleans from parent items (ex. Trial by Fire (0a63b4)). (This isn't really NECESSARY but it's good to do it anyway.)
Try and standardize single quotes, double quotes, escapes, et cetera.
*/

INSERT INTO woh_tags VALUES ("7f482f", "organizational", "chapter");