USE test;
/* Replace with your own database. */

/* CONTENT SETUP */

CREATE TABLE IF NOT EXISTS WoH_metadata(
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

CREATE TABLE IF NOT EXISTS WoH_content(
    id varchar(6),
    /* Self-explantory — it's the same ID as above. */
    content_version int DEFAULT 1,
    /* This integer identifies the version of the content in the URL parameters... */
    version_title text,
    /* ...and this string identifies the version (for example, "standard"). */
    content_language varchar (2) DEFAULT "en",
    /* This is the language of the content in question, in the form of a two-character ISO 639-1 code. */
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
    word_count int,
    /* Can be ignored — as with the publication date, the front end doesn't do anything with this yet. */
    PRIMARY KEY (id, content_language, content_version)
);

CREATE TABLE IF NOT EXISTS WoH_headers(
    header_id int PRIMARY KEY,
    /* Self-explantory. */
    html mediumtext
    /* Self-explanatory. */
);

CREATE TABLE IF NOT EXISTS WoH_tags(
    id varchar(6),
    /* Self-explanatory — these are the same tags used for metadata and content. */
    tag_type text,
    /* Examples: Type, Language, Organizational, Author, etc. */
    tag text,
    /* Examples: */
    /* Types: animation, blog, card, comic, diary, game, growing_reader, movie, novel, podcast, serial, short_story, web_fiction */
    /* Organizational: chapter */
    /* Authors: C.A. Hapka, Greg Farshtey, Carlos D’Anda, Staurt Sayger, etc. */
    detailed_tag text
    /* This is the only part of this database design that's liable to change — this is a more descriptive version of the tag that will be displayed to users. For example, if you put “author” and “Carlos D’Anda” above, you would put “Illustrated by Carlos D’Anda” here. */
);

/* To be used eventually. Examples: "cover", "banner", "OGP"
CREATE TABLE WoH_images(
    content_id varchar(6),
    content_language varchar(2),
    content_version int,
    image_type text,
    image_url text
);
*/

CREATE TABLE IF NOT EXISTS WoH_web(
    parent_id varchar(6) NOT NULL,
    /* This is the shit that really matters right here — the web that connects all the nested tables of contents. BIONICLE Chronicles is the parent to Tale of the Toa, which is the parent to “Tahu — Toa of Fire.” If you put Tale of the Toa's ID here… */
    parent_version int DEFAULT 1,
    /* Version specificity here is necessary for things like graphic novels compiling comics that were originally published as separate works (especially if multiple graphic novels might contain the same comics). */
    child_id varchar(6) NOT NULL,
    /* You'd put the ID of “Tahu — Toa of Fire” here, then do the same with “Lewa — Toa of Air” — both of these are children of Tale of the Toa, as are the other fourteen chapters. */
    child_version int DEFAULT 1
);

CREATE TABLE IF NOT EXISTS WoH_adaptations(
    original_id varchar(6) NOT NULL,
    adaptation_id varchar(6) NOT NULL
);

/* REFERENCE SETUP
 * Multiple guidebooks were released for the BIONICLE universe, with many having entries on the same characters and concepts.
 * As such, this table treats each entry from each guidebook as a unique entity.
 * Entities can have multiple names attached to them, such as in the case of “Tahu” and “Tahu Nuva.”
 * On a rendered reference page for a name, all entries for that name will be displayed, along with all associated images.
 */

CREATE TABLE IF NOT EXISTS reference_metadata (
    subject_id varchar(6),
    /* Subject IDs are used to identify entries as referring to the same character or concept, even if entries have slightly different names. */
    entry_id varchar(6) PRIMARY KEY,
    /* The ID can be any six character-long alphanumeric string. */
    snippet text,
    /* This is the descriptive text that will show up underneath the titles of pages in Google searches and on summary cards. Try to keep it brief — Google limits these to 320 characters. */
    small_image text,
    /* Self-explanatory. */
    publication_date date
    /* These data entries are for individual sections of reference works, such as one entry from the BIONICLE Encyclopedia. As such, they can have individual publication dates. */
);

CREATE TABLE IF NOT EXISTS reference_content (
    entry_id varchar(6) PRIMARY KEY,
    css int,
    header int NOT NULL,
    main longtext,
    word_count int
);

CREATE TABLE IF NOT EXISTS reference_titles (
    entry_id varchar(6) NOT NULL,
    spoiler_level int,
    /* Necessary? Would be cool to keep “Tahu Nuva” a secret… */
    /* On compilation pages, only display titles below current spoiler level. */
    /* Speaking of which, put spoiling entries under hide/show thingies. */
    title text NOT NULL
    /* If title only ever refers to one subject, ?ref=[title] leads directly to compilation page for that subject. */
    /* If title refers to multiple subjects, ?ref=[title] leads to a disambiguation page. */
    /* When jumping from a disambiguation page to a subject page, try to find a distinct title for that subject, or use the ID if there is none. */
);

CREATE TABLE IF NOT EXISTS reference_images (
    entry_id varchar(6) NOT NULL,
    spoiler_level int,
    /* Necessary? */
    image_path text NOT NULL
    /* Be sure to use DISTINCT for compilation pages. */
);

CREATE TABLE IF NOT EXISTS reference_web (
    parent_id varchar(6) NOT NULL,
    child_id varchar(6) NOT NULL
);

CREATE TABLE IF NOT EXISTS reference_quotes (
    subject_id text NOT NULL,
    quote text NOT NULL
);

CREATE TABLE IF NOT EXISTS reference_appearances (
    story_id varchar(6) NOT NULL,
    subject_id varchar(6) NOT NULL,
    appearance_type boolean
    /* True if they actually appear, false if they're just mentioned. */
)

CREATE TABLE IF NOT EXISTS reference_greg (
    posted datetime PRIMARY KEY,
    /* Bad idea? */
    question text,
    /* Most of Greg’s forum posts are question/answer pairs, but some of them (such as the Earth Tribe explanation) are not. */
    answer text NOT NULL,
    /* But there’s no content if Greg didn’t say anything, so the answer column can’t be null. */
    permalink text
);
