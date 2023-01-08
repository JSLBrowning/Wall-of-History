ALTER TABLE woh_content DROP COLUMN small_image, DROP COLUMN large_image;
DROP TABLE story_content;
RENAME TABLE woh_content TO story_content;


DROP TABLE story_reference_web;
RENAME TABLE woh_web to story_reference_web;


DROP TABLE story_tags;
RENAME TABLE woh_tags TO story_tags;


DROP TABLE story_headers;
RENAME TABLE woh_headers TO story_headers;


DROP TABLE story_metadata;
RENAME TABLE woh_metadata TO story_metadata;


/* Update content_language column of story_content, changing "en" to "eng." */
ALTER TABLE story_content MODIFY COLUMN content_language VARCHAR(3) DEFAULT "eng";
UPDATE story_content SET content_language = 'eng' WHERE content_language = 'en';
UPDATE story_content SET content_language = 'spa' WHERE content_language = 'es';
UPDATE story_content SET content_language = 'fin' WHERE content_language = 'fi';
UPDATE story_content SET content_language = 'fra' WHERE content_language = 'fr';
UPDATE story_content SET content_language = 'nld' WHERE content_language = 'nl';
UPDATE story_content SET content_language = 'por' WHERE content_language = 'pt';
UPDATE story_content SET content_language = 'kor' WHERE content_language = 'ko';
/* SELECT DISTINCT content_language FROM story_content; */


UPDATE story_metadata SET publication_date = NULL WHERE id = "Q2N8NX" OR id = "JBTY4O" OR id = "ECOHYW" OR id = "LHIBBZ" OR id = "UELI6L" OR id = "08H6CX" OR id = "7M5DDC" OR id = "CU4QW2" OR id = "FG9EM8" OR id = "ZTL9M8" OR id = "0NN5ZH";
UPDATE story_content SET version_title = NULL WHERE id = "Q2N8NX" OR id = "JBTY4O" OR id = "ECOHYW" OR id = "LHIBBZ" OR id = "UELI6L" OR id = "08H6CX" OR id = "7M5DDC" OR id = "CU4QW2" OR id = "FG9EM8" OR id = "ZTL9M8" OR id = "0NN5ZH";