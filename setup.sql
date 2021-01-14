USE test;

CREATE TABLE WoH_metadata(
    id text PRIMARY KEY,
    title text NOT NULL,
    snippet text,
    publish_date date,
    chronology int,
    recommended boolean
);

CREATE TABLE WoH_content(
    id text PRIMARY KEY,
    content_language text NOT NULL,
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
    id text,
    tagtype text,
    /* Examples: Type, Organizational, Author, Illustrator, etc. */
    tag text
    /* Examples: */
    /* Types: animation, blog, card, comic, diary, game, growing_reader, movie, novel, podcast, serial, short_story, web_fiction */
    /* Organizational: chapter */
    /* Authors: C.A. Hapka, Greg Farshtey, etc. */
    /* Illustrators: Carlos D'Anda, Staurt Sayger, etc. */
);

CREATE TABLE WoH_web(
    parent_id text NOT NULL,
    child_id text NOT NULL
);

CREATE TABLE WoH_adaptations(
    original_id text NOT NULL,
    child_id text NOT NULL
);