USE test;


/*********************
 * MEDIA TYPES SETUP *
 *********************/


CREATE TABLE IF NOT EXISTS media_tags (
    media_tag text NOT NULL,
    /* The media type in all-lowercase, with underscores instead of spaces (as it will appear on the back end and in class names). */
    media_tag_singular text NOT NULL,
    /* The singular form of the media type (as it will appear on the front end). If this OR the below are NULL, this type will never be displayed to users, and is considered an internal tag. */
    media_tag_plural text NOT NULL,
    /* The plural form of the media type (as it will appear on the front end). */
    media_type_ordinal int
    /* For ordering tags such as rating systems. */
);


CREATE TABLE IF NOT EXISTS tag_web (
    parent_tag text NOT NULL,
    /* The parent tag. */
    child_tag text NOT NULL,
    /* The child tag. */
);


/*
 * MEDIA CATEGORIES
 */


INSERT INTO media_tags VALUES (
    "developmental",
    NULL,
    NULL,
    NULL
);


INSERT INTO media_tags VALUES (
    "advertisement",
    NULL,
    NULL,
    NULL
);


INSERT INTO media_tags VALUES (
    "main",
    NULL,
    NULL,
    NULL
);


INSERT INTO media_tags VALUES (
    "supplemental",
    NULL,
    NULL,
    NULL
);


/*
 * MEDIA SUPERTYPES
 */


/* Books, news stories, etc. */
INSERT INTO media_tags VALUES (
    "text",
    NULL,
    NULL,
    NULL
);


/* Images, comics, etc. */
INSERT INTO media_tags VALUES (
    "visual",
    NULL,
    NULL,
    NULL
);


/* Podcasts, radio broadcasts, etc. */
INSERT INTO media_tags VALUES (
    "audio",
    NULL,
    NULL,
    NULL
);


/* Movies, TV shows, etc. */
INSERT INTO media_tags VALUES (
    "audiovisual",
    NULL,
    NULL,
    NULL
);


/* Games, Flash, etc. */
INSERT INTO media_tags VALUES (
    "interactive",
    NULL,
    NULL,
    NULL
);


/*
 * DEVELOPMENTAL MEDIA TYPES
 */


INSERT INTO media_tags VALUES (
    "concept__art",
    "Concept Art",
    "Concept Art",
    NULL
);


INSERT INTO tag_web VALUES (
    "developmental",
    "concept__art"
);


INSERT INTO media_tags VALUES (
    "script",
    "Script",
    "Scripts",
    NULL
);


INSERT INTO tag_web VALUES (
    "developmental",
    "script"
);


INSERT INTO media_tags VALUES (
    "source__code",
    "Source Code",
    "Source Code",
    NULL
);


INSERT INTO tag_web VALUES (
    "developmental",
    "source__code"
);


INSERT INTO media_tags VALUES (
    "storyboard",
    "Storyboard",
    "Storyboards",
    NULL
);


INSERT INTO tag_web VALUES (
    "developmental",
    "storyboard"
);


INSERT INTO media_tags VALUES (
    "test__footage",
    "Test Footage",
    "Test Footage",
    NULL
);


INSERT INTO tag_web VALUES (
    "developmental",
    "test__footage"
);


/*
 * ADVERTISEMENT MEDIA TYPES
 */


INSERT INTO media_tags VALUES (
    "billboard",
    "Billboard",
    "Billboards",
    NULL
);


INSERT INTO tag_web VALUES (
    "advertisement",
    "billboard"
);


INSERT INTO media_tags VALUES (
    "commercial",
    "Commercial",
    "Commercials",
    NULL
);


INSERT INTO tag_web VALUES (
    "advertisement",
    "commercial"
);


INSERT INTO media_tags VALUES (
    "poster",
    "Poster",
    "Posters",
    NULL
);


INSERT INTO tag_web VALUES (
    "advertisement",
    "poster"
);


INSERT INTO media_tags VALUES (
    "print__ad",
    "Print Ad",
    "Print Ads",
    NULL
);


INSERT INTO tag_web VALUES (
    "advertisement",
    "print__ad"
);


INSERT INTO media_tags VALUES (
    "radio__ad",
    "Radio Ad",
    "Radio Ads",
    NULL
);


INSERT INTO tag_web VALUES (
    "advertisement",
    "radio__ad"
);


INSERT INTO media_tags VALUES (
    "teaser",
    "Teaser",
    "Teasers",
    NULL
);


INSERT INTO tag_web VALUES (
    "advertisement",
    "teaser"
);


INSERT INTO media_tags VALUES (
    "trailer",
    "Trailer",
    "Trailers",
    NULL
);


INSERT INTO tag_web VALUES (
    "advertisement",
    "trailer"
);


INSERT INTO media_tags VALUES (
    "tv__spot",
    "TV Spot",
    "TV Spots",
    NULL
);


INSERT INTO tag_web VALUES (
    "advertisement",
    "tv__spot"
);


/*
 * MAIN MEDIA TYPES
 */


INSERT INTO media_tags VALUES (
    "animation",
    "Animation",
    "Animations",
    NULL
);


INSERT INTO tag_web VALUES (
    "main",
    "animation"
);


INSERT INTO media_tags VALUES (
    "art",
    "Art",
    "Art",
    NULL
);


INSERT INTO tag_web VALUES (
    "main",
    "art"
);


INSERT INTO media_tags VALUES (
    "audio__book",
    "Audio Book",
    "Audio Books",
    NULL
);


INSERT INTO tag_web VALUES (
    "main",
    "audio__book"
);


INSERT INTO media_tags VALUES (
    "blog",
    "Blog",
    "Blogs",
    NULL
);


INSERT INTO tag_web VALUES (
    "main",
    "blog"
);


INSERT INTO media_tags VALUES (
    "book",
    "Book",
    "Books",
    NULL
);


INSERT INTO tag_web VALUES (
    "main",
    "book"
);


INSERT INTO media_tags VALUES (
    "card",
    "Card",
    "Cards",
    NULL
);


INSERT INTO tag_web VALUES (
    "main",
    "card"
);


INSERT INTO media_tags VALUES (
    "comic",
    "Comic",
    "Comics",
    NULL
);


INSERT INTO tag_web VALUES (
    "main",
    "comic"
);


INSERT INTO media_tags VALUES (
    "game",
    "Game",
    "Games",
    NULL
);


INSERT INTO tag_web VALUES (
    "main",
    "game"
);


INSERT INTO media_tags VALUES (
    "graphic__novel",
    "Graphic Novel",
    "Graphic Novels",
    NULL
);


INSERT INTO tag_web VALUES (
    "main",
    "graphic__novel"
);


INSERT INTO media_tags VALUES (
    "magazine",
    "Magazine",
    "Magazines",
    NULL
);


INSERT INTO tag_web VALUES (
    "main",
    "magazine"
);


INSERT INTO media_tags VALUES (
    "movie",
    "Movie",
    "Movies",
    NULL
);


INSERT INTO tag_web VALUES (
    "main",
    "movie"
);


INSERT INTO media_tags VALUES (
    "news",
    "News",
    "News",
    NULL
);


INSERT INTO tag_web VALUES (
    "main",
    "news"
);


INSERT INTO media_tags VALUES (
    "novel",
    "Novel",
    "Novels",
    NULL
);


INSERT INTO tag_web VALUES (
    "main",
    "novel"
);


INSERT INTO media_tags VALUES (
    "novella",
    "Novella",
    "Novellas",
    NULL
);


INSERT INTO tag_web VALUES (
    "main",
    "novella"
);


INSERT INTO media_tags VALUES (
    "podcast",
    "Podcast",
    "Podcasts",
    NULL
);


INSERT INTO tag_web VALUES (
    "main",
    "podcast"
);


INSERT INTO media_tags VALUES (
    "radio__show",
    "Radio Show",
    "Radio Shows",
    NULL
);


INSERT INTO tag_web VALUES (
    "main",
    "radio__show"
);


INSERT INTO media_tags VALUES (
    "serial",
    "Serial",
    "Serials",
    NULL
);


INSERT INTO tag_web VALUES (
    "main",
    "serial"
);


INSERT INTO media_tags VALUES (
    "series",
    "Series",
    "Series",
    NULL
);


INSERT INTO tag_web VALUES (
    "main",
    "series"
);


INSERT INTO media_tags VALUES (
    "short__film",
    "Short Film",
    "Short Films",
    NULL
);


INSERT INTO tag_web VALUES (
    "main",
    "short__film"
);


INSERT INTO media_tags VALUES (
    "short__story",
    "Short Story",
    "Short Stories",
    NULL
);


INSERT INTO tag_web VALUES (
    "main",
    "short__story"
);


INSERT INTO media_tags VALUES (
    "trading__card",
    "Trading Card",
    "Trading Cards",
    NULL
);


INSERT INTO tag_web VALUES (
    "main",
    "trading__card"
);


INSERT INTO media_tags VALUES (
    "video",
    "Video",
    "Videos",
    NULL
);


INSERT INTO tag_web VALUES (
    "main",
    "video"
);


INSERT INTO media_tags VALUES (
    "website",
    "Website",
    "Websites",
    NULL
);


INSERT INTO tag_web VALUES (
    "main",
    "website"
);


INSERT INTO media_tags VALUES (
    "web__story",
    "Web Story",
    "Web Stories",
    NULL
);


INSERT INTO tag_web VALUES (
    "main",
    "web__story"
);


/*
 * SUPPLEMENTAL MEDIA TYPES
 */


INSERT INTO media_tags VALUES (
    "art__book",
    "Art Book",
    "Art Books",
    NULL
);


INSERT INTO tag_web VALUES (
    "supplemental",
    "art__book"
);


INSERT INTO media_tags VALUES (
    "behind__the__scenes",
    "Behind the Scenes",
    "Behind the Scenes",
    NULL
);


INSERT INTO tag_web VALUES (
    "supplemental",
    "behind__the__scenes"
);


INSERT INTO media_tags VALUES (
    "booklet",
    "Booklet",
    "Booklets",
    NULL
);


INSERT INTO tag_web VALUES (
    "supplemental",
    "booklet"
);


INSERT INTO media_tags VALUES (
    "deleted__scene",
    "Deleted Scene",
    "Deleted Scenes",
    NULL
);


INSERT INTO tag_web VALUES (
    "supplemental",
    "deleted__scene"
);


INSERT INTO media_tags VALUES (
    "interview",
    "Interview",
    "Interviews",
    NULL
);


INSERT INTO tag_web VALUES (
    "supplemental",
    "interview"
);


INSERT INTO media_tags VALUES (
    "manual",
    "Manual",
    "Manuals",
    NULL
);


INSERT INTO tag_web VALUES (
    "supplemental",
    "manual"
);


/* Where to put CDs? Supplemental? Main?
 * Should preview DVDs go under advertisements?
 * What about style guides?
 */


/*****************
 * CONTENT SETUP *
 *****************/


/* The metadata table stores metadata for website content. */
CREATE TABLE IF NOT EXISTS shin_metadata(
    /* IDENTIFICATION STUFF */
    content_id BINARY(16),
    /* This UUID uniquely identifies each piece of content. */
    content_version int,
    /* This integer identifies the version of the content. If NULL, the information in this entry is assumed to apply to all versions. */
    content_language varchar(16),
    /* This is the language of the content in question, optionally followed by a country code. ISO 639-1 is preferred, since this is what's typically used for HTML <lang> attributes. If NULL, the information in this entry is assumed to apply to all languages. */

    /* RELEASE STUFF */
    release_date date,
    /* Self-explanatory. */
    completion_status int,
    /* 0 = not started, 1 = in progress, 2 = completed, 3 = cancelled. Leave NULL for ambiguous. */

    /* CHRONOLOGY STUFF */
    chronology int,
    /* Chronology position of this entry relative to neighboring entries. */
    spoiler_level int DEFAULT 1,
    /* Determines what information will display by default when reference modals are opened. */

    /* THEMING STUFF */
    content_theme_color varchar(6) DEFAULT "938170",
    /* Defines the theme_color <meta> tag. */
    content_header int NOT NULL DEFAULT 1,
    /* This defines which header will be displayed on the page for this content — for example, the regular BIONICLE logo is used for most Wall of History pages, but the 2002 version is used for pages of Beware the Bohrok. */

    /* KEY STUFF */
    PRIMARY KEY (id, content_version, content_language),
    /* Primary key for identifying all versions of the content. */
    FOREIGN KEY (content_header) REFERENCES shin_headers(header_id)
    /* Foreign key for identifying the header. */
);


/* The content table stores the "meat" of the website content. */
CREATE TABLE IF NOT EXISTS shin_content(
    /* IDENTIFICATION STUFF */
    content_id BINARY(16),
    /* UUID */
    content_version int DEFAULT 1,
    /* This integer identifies the version of the content… */
    version_title text,
    /* …and this text labels that version (for example, "Standard Edition"). Can be NULL if only one version exists. */
    content_language varchar(16) DEFAULT "en",
    /* Language (and optional country code) of the content. */

    /* DESCRIPTIVE STUFF */
    content_title text NOT NULL,
    /* Self-explanatory. Titles should be minimal — the first chapter of Tale of the Toa is simply titled “Tahu — Toa of Fire,” not "BIONICLE Chronicles #1: Tale of the Toa: “Tahu — Toa of Fire.”" */
    content_subtitle text,
    /* Self-explanatory. Will be displayed beneath title in content blocks. */
    content_snippet text,
    /* This is the descriptive text that will show up underneath the titles of pages in Google searches and on summary cards. For OGP queries, these will be shortened to 319 characters, with a "…" serving as the 320th (as this is Google's character limit for descriptions). */

    /* CONTENT STUFF */
    content_main longtext,
    /* The actual contents of the page (in Markdown/HTML) go here. */
    content_words int,
    /* Self-explanatory. */

    PRIMARY KEY (content_id, content_version, content_language),
    /* Primary key for identifying the content. */
);


CREATE TABLE IF NOT EXISTS shin_headers(
    header_id int PRIMARY KEY,
    /* Self-explanatory. */
    header_main text
    /* This is the actual content of the header, ideally in HTML (but Markdown is also allowed). */
);


CREATE TABLE IF NOT EXISTS shin_tags(
    content_id BINARY(16),
    /* Self-explanatory — these are the same tags used for metadata and content. */
    content_version int,
    /* Self-explanatory, and optional here. If NULL, the tag is assumed to apply to all versions. */
    content_language varchar(16),
    /* Self-explanatory, and optional here. If NULL, the tag is assumed to apply to all languages. */
    tag_type text,
    /* Examples: type, organizational, semantic, etc. */
    tag text,
    /* Examples:
     * Types: animation, blog, card, comic, diary, game, growing_reader, movie, novel, podcast, serial, short__story, web, etc.
     * Organizational: chapter, scene, etc.
     * Semantic: taleofthetoa, bioniclechronicles1, bioniclechronicles1taleofthetoa, etc.
     * Genres: action, comedy, horror, etc.
     * Rating: MPAA:PG-13, ESRB:E10+, Teen, etc.
     * Warnings: gore, sexual__violence, etc. 
     * These tags can be translated from these forms to more user-friendly forms (as well as plural forms) using the tag mappings in populate.php. These mappings can be expanded manually, or appended to without modification by adding a tags.php files to /mods/.
     * In general, these should not have spaces, so they can be used as class names for tag elements when necessary (such as highlighting certain warnings red). */
);


CREATE TABLE IF NOT EXISTS shin_adaptations(
    original_id BINARY(16) NOT NULL,
    /* The ID of the original. */
    original_version int,
    /* The specific version of the content this is an adaptation of (optional — if blank, it will count for all versions). */
    adaptation_id BINARY(16) NOT NULL,
    /* The ID of the adaptation. */
    adaptation_version int
    /* The specific version of the adaptation (optional — if blank, it will count for all versions). */
);


/* The equivalents table connects two entries that essentially convey the same story, when one is not a clear adaptation of the other — the Toa Kaita chapters of Tale of the Toa to the penultimate chapter of MNOG, for example. */
CREATE TABLE IF NOT EXISTS shin_equivalents(
    left_id BINARY(16) NOT NULL,
    /* Self-explanatory. */
    left_version int NOT NULL,
    /* Self-explanatory. */
    right_id BINARY(16) NOT NULL,
    /* Self-explanatory. */
    right_version int NOT NULL,
    /* Self-explanatory. */
);


/* Routes define paths through story content that involve multiple books, series, et cetera. They share an ID space with content entries, so either a main story or main route can be displayed on the homepage. */
CREATE TABLE IF NOT EXISTS shin_routes(
    route_id BINARY(16),
    /* UUID */
    route_name text NOT NULL,
    /* The name of the route. */
    route_description text,
    /* The description of the route. */
    route_main text NOT NULL,
    /* The route itself, in the format [id/s]:0,[id/s].2,[id/s]…
     * A .X specifies a particular verison (1 is default).
     * A :0 specifies that the entry in question is not recommended, and will be skipped.
     */
);


/*********************
 * END CONTENT SETUP *
 *********************/


/*******************
 * REFERENCE SETUP *
 *******************/


CREATE TABLE IF NOT EXISTS reference_subjects (
    /* This table is necessary because some reference entries (such as “Muaka & Kane-Ra” on BIONICLE.com) can cover multiple subjects at once. */
    subject_id BINARY(16),
    /* Subject IDs are used to identify entries as referring to the same character or concept, even if entries have slightly different names. */
    entry_id BINARY(16)
);


CREATE TABLE IF NOT EXISTS reference_metadata (
    /* IDENTIFICATION STUFF */
    entry_id BINARY(16),
    /* The ID can be any six character-long alphanumeric string. */
    entry_version int,
    /* Self-explanatory. BIONICLE Encyclopedia would be 1, Updated would be 2. */
    entry_language varchar(16),
    /* Self-explanatory. */

    /* RELEASE/CHRONOLOGY STUFF */
    release_date date,
    /* Self-explanatory. */
    chronology int,
    /* If pages of some reference material were in a particular order, this value can be used to order them on rendered pages. */
    spoiler_level int,
    /* Story items have spoiler levels as well, which allows you to prevent readers from reading about something they haven’t seen happen yet. */

    /* THEMING STUFF */
    content_theme_color varchar(6) DEFAULT "938170",
    /* Defines the theme_color <meta> tag. */
    content_header int NOT NULL DEFAULT 1,
    /* This defines which header will be displayed on the page for this content — for example, the regular BIONICLE logo is used for most Wall of History pages, but the 2002 version is used for pages of Beware the Bohrok. */

    PRIMARY KEY (entry_id, entry_version, entry_language)
);


CREATE TABLE IF NOT EXISTS reference_content (
    /* IDENTIFICATION STUFF */
    entry_id BINARY(16),
    /* Self-explanatory — it's the same ID as above. */
    entry_version int DEFAULT 1,
    /* This integer identifies the version of the content in the URL parameters… */
    version_title text,
    /* …and this string identifies the version (for example, "Updated"). */
    /* If no version titles, default to name of parents? */
    entry_language varchar(2) DEFAULT "en",
    /* …and this is the language of the content in question, in the form of a two-character ISO 639-1 code. */
    
    /* DESCRIPTIVE/CONTENT STUFF */
    snippet text,
    /* This is the descriptive text that will show up underneath the titles of pages in Google searches and on summary cards. Try to keep it brief — Google limits these to 320 characters. */
    main longtext,
    /* Identical functionality to woh_content main column. */
    word_count int
    /* Self-explanatory. */

    PRIMARY KEY (entry_id, entry_version, entry_language)
);


CREATE TABLE IF NOT EXISTS reference_titles (
    subject_id BINARY(16),
    /* Self-explanatory. */
    entry_id BINARY(16) NOT NULL,
    /* Self-explanatory. */
    title_version int DEFAULT 1,
    /* Self-explanatory. */
    title_language varchar(16) DEFAULT "en",
    /* Self-explanatory. */
    title text NOT NULL,
    /* If title only ever refers to one subject, ?s=[title] leads directly to compilation page for that subject. */
    /* If title refers to multiple subjects, ?s=[title] leads to a disambiguation page. */
    /* When jumping from a disambiguation page to a subject page, try to find a distinct title for that subject, or use the ID if there is none. */
    title_order int
    /* If an entry contains multiple images or titles, this value can be used to order them. For example, on the dedicated reference page, the highest title will be displayed first, ensuring an entry for Tahu NUVA doesn't display an image for Tahu MATA. */
);


CREATE TABLE IF NOT EXISTS reference_images (
    /* IDENTIFICATION STUFF */
    subject_id BINARY(16),
    /* Self-explanatory. */
    entry_id BINARY(16) NOT NULL,
    /* Can be entry ID or subject ID (for desirable images not used elsewhere). */

    /* IMAGE IDENTIFICATION STUFF */
    image_version int,
    /* If necessary (such as in the case of BEU) — can be NULL otherwise. */
    image_language varchar(16),
    /* If necessary — can be NULL otherwise. */
    image_path text NOT NULL,
    /* Can also be a video, actually. Order by type then spoiler level — images of 1, videos of 1, images of 2, and so on. */
    /* Be sure to use DISTINCT for compilation pages. */

    /* DESCRIPTIVE STUFF */
    image_order int,
    /* If an entry contains multiple images or titles, this value can be used to order them. For example, on the dedicated reference page, the highest image will be displayed first, ensuring an entry for Tahu NUVA doesn't display an image for Tahu MATA. */
    caption text
    /* Self-explanatory. Can be "OGP" for OGP images. OGP images will not be rendered on reference modals or pages. */
);


CREATE TABLE IF NOT EXISTS reference_quotes (
    subject_id text NOT NULL,
    /* Self-explanatory. */
    spoiler_level int,
    /* Self-explanatory. */
    quote text NOT NULL,
    /* This will be a quote from or about a character/thing. Subjects can have any number of quotes, and one will be chosen at random when a subject page (or reference modal, if the spoiler levels match) is loaded. */
    source text
    /* This is the source of the quote, in the form of an ID combo — i.e., ID.version.lang, ID.version, or ID — or semantic tag. */
);


CREATE TABLE IF NOT EXISTS reference_appearances (
    story_id BINARY(16) NOT NULL,
    /* This refers to a woh_metadata entry in which a character or object appears. */
    story_version int DEFAULT 1,
    /* Self-explanatory. */
    subject_id BINARY(16) NOT NULL,
    /* This refers to the character or object itself. */
    appearance_type boolean
    /* True if they actually appear, false if they're just mentioned. */
);


/* This table determines which content entries (and their children) are connected to which reference entries (and their children). An "all-to-all" flag can be set in config. */
CREATE TABLE IF NOT EXISTS reference_connections (
    content_id BINARY(16) NOT NULL,
    /* Self-explanatory. */
    reference_id BINARY(16) NOT NULL
    /* Self-explanatory. */
);


/***********************
 * END REFERENCE SETUP *
 ***********************/


/********************
 * SOUNDTRACK SETUP *
 ********************/


/* Table for soundtracks. Local hosting of soundtracks will work similarly to table of contents images — the relevant file will go in a folder named after the ID, or the file itself will have the ID for a filename (former preferred). */
CREATE TABLE IF NOT EXISTS soundtracks (
    soundtrack_id BINARY(16) PRIMARY KEY,
    /* UUID */
    soundtrack_title text NOT NULL,
    /* Self-explanatory. */
    spotify_id varchar(22),
    /* Self-explanatory. */
    youtube_id varchar(34),
    /* Can be a single video (11 characters) or a playlist (34 characters). */
    release_date date,
    /* Self-explanatory. */
);


CREATE TABLE IF NOT EXISTS music_videos (
    soundtrack_id BINARY(16) NOT NULL,
    /* UUID of the soundtrack. */
    youtube_id varchar(11) NOT NULL,
    /* Self-explanatory. */
);


/************************
 * END SOUNDTRACK SETUP *
 ************************/


/******************
 * CREATORS SETUP *
 ******************/


CREATE TABLE IF NOT EXISTS creators (
    creator_id BINARY(16) PRIMARY KEY,
    /* UUID */
    creator_name text NOT NULL,
    /* Self-explanatory. */
    creator_main text
    /* Descriptive text for the creator in question, in Markdown. */
);


CREATE TABLE IF NOT EXISTS creator_roles (
    creator_id BINARY(16) NOT NULL,
    /* UUID of the creator. */
    content_id BINARY(16) NOT NULL,
    /* UUID of the content. */
    creator_role text NOT NULL
    /* Self-explanatory — "writer," "illustrator," et cetera. These should be simple — if someone wrote something AND recorded themselves reading it, for example, there should be two entries — one for "writer" and one for "narrator." These will be concatenated properly at render — for example, "Written and Narrated by Greg Farshtey." */
);


CREATE TABLE IF NOT EXISTS creator_aliases (
    creator_id BINARY(16) NOT NULL,
    /* UUID of the creator. */
    creator_alias text NOT NULL
    /* Self-explanatory. Interpreter will translate an alias link, such as <a data-alias="GregF"></a>, into a link to the creator's main page. */
);


CREATE TABLE IF NOT EXISTS commentary (
    commentary_id BINARY(16) PRIMARY KEY,
    /* UUID */
    commentary_main longtext NOT NULL,
    /* Self-explanatory. */
    commentary_date date
    /* Self-explanatory. For commentaries that lasted longer than one day (such as forum conversations), this should be the start date. */
);


/**********************
 * END CREATORS SETUP *
 **********************/


/*****************
 * UPDATES SETUP *
 *****************/


CREATE TABLE IF NOT EXISTS original_copies (
    content_id BINARY(16) PRIMARY KEY,
    /* UUID of the content in question. Can also be a reference database entry. */
    content_version int,
    /* Version of the content in question this is an original for. If NULL, this original copy is assumed to apply to all versions. */
    content_language varchar(16),
    /* Language of the content in question this is an original for. If NULL, this original copy is assumed to apply to all languages. */
    original_main text,
    /* Original content in a Markdown/HTML format. */
    original_url text,
    /* URL of the original content. */
    differences text
    /* Differences between the original and the copy, to be displayed when hovering over links. */
);


CREATE TABLE IF NOT EXISTS id_update (
    old_id_text varchar(8),
    /* Old ID, pre 1.2. */
    old_id_uuid BINARY(16),
    /* Old ID, post 1.2. */
    old_id_version int,
    /* Old ID version. */
    new_id_uuid BINARY(16) NOT NULL,
    /* New ID. */
    new_id_version int NOT NULL,
    /* New ID version. */
);


/*********************
 * END UPDATES SETUP *
 *********************/


/********************
 * CONNECTION SETUP *
 ********************/


/* The web table connects everything to everything else — books to chapters, movies to trailers, and soundtracks to songs. */
CREATE TABLE IF NOT EXISTS shin_web(
    grandparent_id BINARY(16),
    /* Grandparent ID. USeful for disambiguating CSS paths if a parent has two parents of its own. Ultimately optional, however. */
    grandparent_version int,
    /* Grandparent version. Optional. */
    parent_id BINARY(16) NOT NULL,
    /* BIONICLE Chronicles #1: Tale of the Toa which is the parent to “Tahu — Toa of Fire.” If you put Tale of the Toa's ID here… */
    parent_version int,
    /* Version specificity here is necessary for things like graphic novels compiling comics that were originally published as separate works (especially if multiple graphic novels might contain the same comics). */
    child_id BINARY(16) NOT NULL,
    /* …you'd put the ID of “Tahu — Toa of Fire” here, then do the same with “Lewa — Toa of Air” — both of these are children of Tale of the Toa, as are the other fourteen chapters. */
    child_version int
    /* If version numbers are not specified, then all versions of the parent are implied to be connected to all versions of the child — for example, all Mask of Light trailers are advertisements for all versions of the movie. */
    hierarchal boolean
    /* Determines if the given connection is a true parent/child relationship (think chapter of a book) or a looser connection (think commercial for a game or behind the scenes video for a movie). */
);


CREATE TABLE IF NOT EXISTS shin_purchase_links(
    content_id BINARY(16) NOT NULL,
    /* UUID of the content or reference entry in question. */
    content_version int,
    /* Version of the content in question. */
    content_language varchar(16),
    /* Language of the content in question. */
    purchase_link_descriptor tinytext NOT NULL,
    /* Descriptor for the purchase link — "Amazon," "Barnes & Noble," "iTunes," et cetera. Keep it brief. */
    purchase_link text NOT NULL,
    /* Self-explanatory. */
);


/************************
 * END CONNECTION SETUP *
 ************************/