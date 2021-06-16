USE test;

CREATE TABLE reference_metadata (
    id varchar(6) PRIMARY KEY,
    snippet text,
    small_image text,
    publication_date date
);

CREATE TABLE reference_content (
    id varchar(6) PRIMARY KEY,
    css int,
    header int NOT NULL,
    main longtext,
    word_count int
);

CREATE TABLE reference_titles (
    id varchar(6) NOT NULL,
    title text NOT NULL
);

CREATE TABLE reference_images (
    id varchar(6) NOT NULL,
    image_path text NOT NULL
);

CREATE TABLE reference_web (
    parent_id varchar(6) NOT NULL,
    child_id varchar(6) NOT NULL
);

/* NOTES:
For Greg discussion questions, maybe each question/answer with the title text in it should be a dot, which can be hovered over to reveal the original question/answer? And clicked on for a permalink?
Possible scraper: https://realpython.com/beautiful-soup-web-scraper-python/
*/

CREATE TABLE woh_greg (
    posted datetime PRIMARY KEY, /* Bad idea? */
    question text NOT NULL,
    answer text NOT NULL,
    permalink text
);

INSERT INTO wall_of_history_reference
VALUES
    (1, "Onua", "<section data-spoiler='1'>
    <img src='/img/reference/Onua 1.png' alt='Onua'>
    <h1>Onua</h1>
    <p>Toa of Earth, whose claw-like hands let him dig great tunnels. He wears the Kanohi Pakari, the Mask of Strength, granting him the power of many.</p>
    <p class='source'>Source: <em><a href='/read/?id=99'>BIONICLE #1: The Coming of the Toa</a></em></p>
</section>
<section data-spoiler='4'>
    <hr>
    <h1>Onua Nuva</h1>
    <p style='text-align: center;'>Pronunciation: oh-NOO-ah NOO-vah</p>
    <p><strong>Color:</strong> Black</p>
    <p><strong>Element:</strong> Earth</p>
    <p><strong>Village:</strong> Onu-Koro</p>
    <p><strong>Powers:</strong></p>
    <ul list-style-type: circle;>
        <li>Can tunnel through any substance</li>
        <li>Can cause earthquakes</li>
        <li>Night vision</li>
    </ul>
    <p><strong>Tools:</strong> Two quake-breakers that can tunnel through earth and rock; can be attached to his feet and used as all-terrain treads</p>
    <p><strong>Mask:</strong> Kanohi Pakari Nuva, the Great Mask of Strength</p>
    <p><strong>Onua Nuva</strong> is the wisest of the Toa. He speaks only when he has something important to say and is always willing to help his friends. He is trusted and respected by all.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/The%20Official%20Guide%20to%20Bionicle.pdf'>The Official Guide to BIONICLE</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (2, "Kopaka", "<section data-spoiler='1'>
    <img src='/img/reference/Kopaka 1.png' alt='Kopaka'>
    <h1>Kopaka</h1>
    <p>Toa of Ice, he wields a sword that can freeze anything it touches. He wears the Kanohi Akaku, the Mask of X-Ray Vision, which allows him to see what is hidden from others.</p>
    <p class='source'>Source: <em><a href='/read/?id=99'>BIONICLE #1: The Coming of the Toa</a></em></p>
</section>
<section data-spoiler='4'>
    <hr>
    <h1>Kopaka Nuva</h1>
    <p style='text-align: center;'>Pronunciation: koh-PAH-kah NOO-vah</p>
    <p><strong>Color:</strong> Silver</p>
    <p><strong>Element:</strong> Ice</p>
    <p><strong>Village:</strong> Ko-Koro</p>
    <p><strong>Powers:</strong></p>
    <ul list-style-type: circle;>
        <li>Can create storms of snow or ice</li>
        <li>Can freeze any substance</li>
        <li>Can withstand extreme cold</li>
    </ul>
    <p><strong>Tools:</strong> Ice blade that channels his power; can be split in two and used as power ice-skates</p>
    <p><strong>Mask:</strong> Kanohi Akaku Nuva, the Great Mask of X-Ray Vision</p>
    <p><strong>Kopaka Nuva</strong> does not like being part of a team. He is very clever and likes to deal with problems on his own. The other Toa Nuva think he is unfriendly, but they also respect his intelligence and his instincts.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/The%20Official%20Guide%20to%20Bionicle.pdf'>The Official Guide to BIONICLE</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (3, "Tahu", "<section data-spoiler='1'>
    <img src='/img/reference/Tahu 1.png' alt='Tahu'>
    <h1>Tahu</h1>
    <p>Toa of Fire and wielder of the Sword of Fire. He wears the Kanohi Hau, the Mask of Shielding, which protects him from many attacks.</p>
    <p class='source'>Source: <em><a href='/read/?id=99'>BIONICLE #1: The Coming of the Toa</a></em></p>
</section>
<section data-spoiler='4'>
    <hr>
    <h1>Tahu Nuva</h1>
    <p style='text-align: center;'>Pronunciation: TAH-hoo NOO-vah</p>
    <p><strong>Color:</strong> Red</p>
    <p><strong>Element:</strong> Fire</p>
    <p><strong>Village:</strong> Ta-Koro</p>
    <p><strong>Powers:</strong></p>
    <ul list-style-type: circle;>
        <li>Can create fire</li>
        <li>Can melt any substance</li>
        <li>Can withstand extreme heat</li>
    </ul>
    <p><strong>Tools:</strong> Two magma swords that channel his power; can be joined together to form a lava board, for surfing on molten magma</p>
    <p><strong>Mask:</strong> Kanohi Hau Nuva, the Great Mask of Shielding</p>
    <p><strong>Tahu Nuva’s</strong> bravery and strength have made him the leader of the Toa. His temper is as legendary as his powers, but he tries hard to keep it in check. He is fearless and will challenge any foe to protect his village and his people.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/The%20Official%20Guide%20to%20Bionicle.pdf'>The Official Guide to BIONICLE</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (4, "Lewa", "<section data-spoiler='1'>
    <img src='/img/reference/Lewa 1.png' alt='Lewa'>
    <h1>Lewa</h1>
    <p>Toa of Air, who wields a mighty hatchet to cut through the thick foliage in the treetops. He wears the Kanohi Miru, the Mask of Levitation, which lets him glide gently to the surface from any height.</p>
    <p class='source'>Source: <em><a href='/read/?id=99'>BIONICLE #1: The Coming of the Toa</a></em></p>
</section>
<section data-spoiler='4'>
    <hr>
    <h1>Lewa Nuva</h1>
    <p style='text-align: center;'>Pronunciation: lay-WAH NOO-vah</p>
    <p><strong>Color:</strong> Green</p>
    <p><strong>Element:</strong> Air</p>
    <p><strong>Village:</strong> Le-Koro</p>
    <p><strong>Powers:</strong></p>
    <ul list-style-type: circle;>
        <li>Can control wind, creating tornadoes and hurricanes</li>
        <li>Commands the air – can calm windstorms</li>
        <li>Can combine his power with Gali Nuva to create thunderstorms</li>
    </ul>
    <p><strong>Tools:</strong> Two sharp blades that can cut through dense jungle growth; can also be used as glider wings, allowing him to soar above the trees</p>
    <p><strong>Mask:</strong> Kanohi Miru Nuva, the Great Mask of Levitation</p>
    <p><strong>Lewa Nuva</strong> is rash, bold, and often plunges into situations without considering the danger. His experiences have given him respect for the perils of Mata Nui. But he still loves adventure and exploration.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/The%20Official%20Guide%20to%20Bionicle.pdf'>The Official Guide to BIONICLE</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (5, "Gali", "<section data-spoiler='1'>
    <img src='/img/reference/Gali 1.png' alt='Gali'>
    <h1>Gali</h1>
    <p>Toa of Water, her hooked hands allow her to cling to even slippery stones. She wears the Kanohi Kaukau, the Mask of Water-Breathing, and so can breathe freely underwater.</p>
    <p class='source'>Source: <em><a href='/read/?id=99'>BIONICLE #1: The Coming of the Toa</a></em></p>
</section>
<section data-spoiler='4'>
    <hr>
    <h1>Gali Nuva</h1>
    <p style='text-align: center;'>Pronunciation: GAH-lee NOO-vah</p>
    <p><strong>Color:</strong> Blue</p>
    <p><strong>Element:</strong> Water</p>
    <p><strong>Village:</strong> Ga-Koro</p>
    <p><strong>Powers:</strong></p>
    <ul list-style-type: circle;>
        <li>Commands water – can create tidal waves, whirlpools and floods</li>
        <li>Can swim faster than any known underwater creature</li>
        <li>Can sense changes in the natural world</li>
    </ul>
    <p><strong>Tools:</strong> Two aqua axes, powerful enough to slice through the toughest undersea obstacles; can also be used as scuba fins for faster speeds underwater</p>
    <p><strong>Mask:</strong> Kanohi Kaukau Nuva, the Great Mask of Water Breathing</p>
    <p><strong>Gali Nuva</strong> is the only female Toa. She is gentle, kind, and peaceful – but if the people of Ga-Koro are threatened, she will not hesitate to use all of her awesome powers in their defence.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/The%20Official%20Guide%20to%20Bionicle.pdf'>The Official Guide to BIONICLE</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (6, "Pohatu", "<section data-spoiler='1'>
    <img src='/img/reference/Pohatu 1.png' alt='Pohatu'>
    <h1>Pohatu</h1>
    <p>Toa of Stone, his mighty kicks can send boulders flying. He wears the Kanohi Kakama, the Mask of Speed, which lets him travel great distances rapidly.</p>
    <p class='source'>Source: <em><a href='/read/?id=99'>BIONICLE #1: The Coming of the Toa</a></em></p>
</section>
<section data-spoiler='4'>
    <hr>
    <h1>Pohatu Nuva</h1>
    <p style='text-align: center;'>Pronunciation: koh-PAH-kah NOO-vah</p>
    <p><strong>Color:</strong> Brown</p>
    <p><strong>Element:</strong> Stone</p>
    <p><strong>Village:</strong> Po-Koro</p>
    <p><strong>Powers:</strong></p>
    <ul list-style-type: circle;>
        <li>Enormous strength</li>
        <li>Can smash rocks with his fists</li>
        <li>Can throw giant boulders at his enemies</li>
    </ul>
    <p><strong>Tools:</strong> Two climbing claws, which help him scale the rocky peaks near his home; can be combined to form a ball</p>
    <p><strong>Mask:</strong> Kanohi Kakama Nuva, the Great Mask of Speed</p>
    <p><strong>Pohatu Nuva</strong> is loyal, noble and trustworthy, and considers all the other Toa Nuva to be his friends. He is the strongest of all the Toa and can be relied upon in any situation.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/The%20Official%20Guide%20to%20Bionicle.pdf'>The Official Guide to BIONICLE</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (7, "Whenua", "<section data-spoiler='1'>
    <img src='/img/reference/Whenua 1.png' alt='Whenua'>
    <h1>Whenua</h1>
    <p>Turaga Whenua is the leader of the underground village of Onu-Koro and master of the island’s complex tunnels.</p>
    <p class='source'>Source: <em><a href='/read/?id=99'>BIONICLE #1: The Coming of the Toa</a></em></p>
</section>
<section data-spoiler='4'>
    <hr>
    <p style='text-align: center;'>Pronunciation: too-RAH-gah wen-NOO-ah</p>
    <p><strong>Color:</strong> Black</p>
    <p><strong>Village:</strong> Onu-Koro</p>
    <p><strong>Mask:</strong> Kanohi Ruru, the Noble Mask of Night Vision</p>
    <p><strong>Tool:</strong> Drill of Onua</p>
    <p><strong>Turaga Whenua</strong> is known for his honesty. As he often says, ‘It serves no purpose to be false, for the earth cannot be deceived.’</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/The%20Official%20Guide%20to%20Bionicle.pdf'>The Official Guide to BIONICLE</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (8, "Nuju", "<section data-spoiler='1'>
    <img src='/img/reference/Nuju 1.png' alt='Nuju'>
    <h1>Nuju</h1>
    <p>Turaga Nuju, leader of the icy village of Ko-Koro, is famous for his storytelling abilities.</p>
    <p class='source'>Source: <em><a href='/read/?id=99'>BIONICLE #1: The Coming of the Toa</a></em></p>
</section>
<section data-spoiler='4'>
    <hr>
    <p style='text-align: center;'>Pronunciation: too-RAH-gah NOO-joo</p>
    <p><strong>Color:</strong> Silver</p>
    <p><strong>Village:</strong> Ko-Koro</p>
    <p><strong>Mask:</strong> Kanohi Matatu, the Noble Mask of Telekinesis</p>
    <p><strong>Tool:</strong> Great ice pick</p>
    <p><strong>Turaga Nuju</strong> is an excellent storyteller, although he never speaks. Nuju communicates only through whistles and gestures. A Matoran named Matoro stays by his side to interpret.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/The%20Official%20Guide%20to%20Bionicle.pdf'>The Official Guide to BIONICLE</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (9, "Vakama", "<section data-spoiler='1'>
    <img src='/img/reference/Vakama 1.png' alt='Vakama'>
    <h1>Vakama</h1>
    <p>Turaga Vakama protects the legends of Tahu, and leads the village of Ta-Koro.</p>
    <p class='source'>Source: <em><a href='/read/?id=99'>BIONICLE #1: The Coming of the Toa</a></em></p>
</section>
<section data-spoiler='4'>
    <hr>
    <p style='text-align: center;'>Pronunciation: too-RAH-gah vah-KAH-mah</p>
    <p><strong>Color:</strong> Red</p>
    <p><strong>Village:</strong> Ta-Koro</p>
    <p><strong>Mask:</strong> Kanohi Huna, the Noble Mask of Concealment</p>
    <p><strong>Tool:</strong> Fire staff</p>
    <p><strong>Turaga Vakama</strong> is known for his great courage and his hot temper.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/The%20Official%20Guide%20to%20Bionicle.pdf'>The Official Guide to BIONICLE</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (10, "Matau", "<section data-spoiler='1'>
    <img src='/img/reference/Matau 1.png' alt='Matau'>
    <h1>Matau</h1>
    <p>Turaga Matau, one of the wisest of the Turaga, rules the treetop village of Le-Koro.</p>
    <p class='source'>Source: <em><a href='/read/?id=99'>BIONICLE #1: The Coming of the Toa</a></em></p>
</section>
<section data-spoiler='4'>
    <hr>
    <p style='text-align: center;'>Pronunciation: too-RAH-gah mah-TOW</p>
    <p><strong>Color:</strong> Green</p>
    <p><strong>Village:</strong> Le-Koro</p>
    <p><strong>Mask:</strong> Kanohi Mahiki, the Noble Mask of Illusion</p>
    <p><strong>Tool:</strong> Kau Kau staff</p>
    <p><strong>Turaga Matau</strong> is always calm in a crisis. He is famous all over the island for his sense of humour.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/The%20Official%20Guide%20to%20Bionicle.pdf'>The Official Guide to BIONICLE</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (11, "Nokama", "<section data-spoiler='1'>
    <img src='/img/reference/Nokama 1.png' alt='Nokama'>
    <h1>Nokama</h1>
    <p>Turaga Nokama is the only female member of the Turaga Council, and leads the floating village of Ga-Koro.</p>
    <p class='source'>Source: <em><a href='/read/?id=99'>BIONICLE #1: The Coming of the Toa</a></em></p>
</section>
<section data-spoiler='4'>
    <hr>
    <p style='text-align: center;'>Pronunciation: too-RAH-gah mah-TOW</p>
    <p><strong>Color:</strong> Green</p>
    <p><strong>Village:</strong> Le-Koro</p>
    <p><strong>Mask:</strong> Kanohi Mahiki, the Noble Mask of Illusion</p>
    <p><strong>Tool:</strong> Kau Kau staff</p>
    <p><strong>Turaga Matau</strong> is always calm in a crisis. He is famous all over the island for his sense of humour.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/The%20Official%20Guide%20to%20Bionicle.pdf'>The Official Guide to BIONICLE</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (12, "Onewa", "<section data-spoiler='1'>
    <img src='/img/reference/Onewa 1.png' alt='Onewa'>
    <h1>Onewa</h1>
    <p>Turaga Onewa relates the legends of Pohatu, and leads the desert village of Po-Koro.</p>
    <p class='source'>Source: <em><a href='/read/?id=99'>BIONICLE #1: The Coming of the Toa</a></em></p>
</section>
<section data-spoiler='4'>
    <hr>
    <p style='text-align: center;'>Pronunciation: too-RAH-gah oh-NEE-wah</p>
    <p><strong>Color:</strong> Brown</p>
    <p><strong>Village:</strong> Po-Koro</p>
    <p><strong>Mask:</strong> Kanohi Komau, the Noble Mask of Mind Control</p>
    <p><strong>Tool:</strong> Great stone hammer</p>
    <p><strong>Turaga Onewa</strong> is nicknamed the Referee for his swift decisions and willingness to stand behind them.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/The%20Official%20Guide%20to%20Bionicle.pdf'>The Official Guide to BIONICLE</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (13, "Miru", "<section data-spoiler='1'>
    <h1>Miru</h1>
    <h2>The Great Mask of Levitation</h2>
    <p>Allows the user to float and glide on air.</p>
    <p class='source'>Source: <em><a href='/read/?id=116'>BIONICLE #2: Deep Into Darkness</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (14, "Hau", "<section data-spoiler='1'>
    <h1>Hau</h1>
    <h2>The Great Mask of Shielding</h2>
    <p>Provides protection against any attack the user is aware of, but not against ambush.</p>
    <p class='source'>Source: <em><a href='/read/?id=116'>BIONICLE #2: Deep Into Darkness</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (15, "Kakama", "<section data-spoiler='1'>
    <h1>Kakama</h1>
    <h2>The Great Mask of Speed</h2>
    <p>Allows the user to cover great distances in the blink of an eye.</p>
    <p class='source'>Source: <em><a href='/read/?id=116'>BIONICLE #2: Deep Into Darkness</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (16, "Kaukau", "<section data-spoiler='1'>
    <h1>Kaukau</h1>
    <h2>The Great Mask of Water-Breathing</h2>
    <p>Allows the user to breathe underwater.</p>
    <p class='source'>Source: <em><a href='/read/?id=116'>BIONICLE #2: Deep Into Darkness</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (17, "Pakari", "<section data-spoiler='1'>
    <h1>Pakari</h1>
    <h2>The Great Mask of Strength</h2>
    <p>Increases the user’s brute physical power.</p>
    <p class='source'>Source: <em><a href='/read/?id=116'>BIONICLE #2: Deep Into Darkness</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (18, "Akaku", "<section data-spoiler='1'>
    <h1>Akaku</h1>
    <h2>The Great Mask of X-Ray Vision</h2>
    <p>Allows the user to see through walls and discover that which is hidden.</p>
    <p class='source'>Source: <em><a href='/read/?id=116'>BIONICLE #2: Deep Into Darkness</a></em></p>
</section>
<section data-spoiler='9'>
    <hr>
    <p>(ah-KAH-koo)</p>
    <p>The Mask of X-Ray Vision, which allowed the wearer to see through solid objects. Many Akaku were fitted with special lenses that allowed for telescopic vision as well.</p>
    <h1>AKAKU NUVA (ah-KAH-koo NOO-vah)</h1>
    <p>The more powerful Mask of X-Ray Vision worn by KOPAKA NUVA. He used this mask to spot the damage done to KO-KORO by the RAHKSHI. Like all KANOHI NUVA masks, its power could be shared by the user with those in close proximity. See KANOHI NUVA.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (19, "Mahiki", "<section data-spoiler='1'>
    <h1>Mahiki</h1>
    <h2>The Noble Mask of Illusion</h2>
    <p>Allows the user to create illusions to deceive an opponent or escape detection.</p>
    <p class='source'>Source: <em><a href='/read/?id=116'>BIONICLE #2: Deep Into Darkness</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (20, "Huna", "<section data-spoiler='1'>
    <h1>Huna</h1>
    <h2>The Noble Mask of Concealment</h2>
    <p>Allows the user to turn invisible.</p>
    <p class='source'>Source: <em><a href='/read/?id=116'>BIONICLE #2: Deep Into Darkness</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (21, "Komau", "<section data-spoiler='1'>
    <h1>Komau</h1>
    <h2>The Noble Mask of Mind Control</h2>
    <p>Allows the user to command others to do the user’s bidding!</p>
    <p class='source'>Source: <em><a href='/read/?id=116'>BIONICLE #2: Deep Into Darkness</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (22, "Rau", "<section data-spoiler='1'>
    <h1>Rau</h1>
    <h2>The Noble Mask of Translation</h2>
    <p>Allows the user to read ancient languages, runes, and symbols.</p>
    <p class='source'>Source: <em><a href='/read/?id=116'>BIONICLE #2: Deep Into Darkness</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (23, "Ruru", "<section data-spoiler='1'>
    <h1>Ruru</h1>
    <h2>The Noble Mask of Night Vision</h2>
    <p>Gives the user the power to see in any level of darkness.</p>
    <p class='source'>Source: <em><a href='/read/?id=116'>BIONICLE #2: Deep Into Darkness</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (24, "Matatu", "<section data-spoiler='1'>
    <h1>Matatu</h1>
    <h2>The Noble Mask of Telekinesis</h2>
    <p>Allows the user to move objects and project force by thought.</p>
    <p class='source'>Source: <em><a href='/read/?id=116'>BIONICLE #2: Deep Into Darkness</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (25, "Rahi", "<section data-spoiler='1'>
    <h1>Rahi</h1>
    <h2>Beware the Rahi!</h2>
    <p>The Rahi are evil beasts who aid Makuta! Powerful and dangerous, they’re out to stop the Toa from collecting the Masks of Power. Each Rahi has a special attack function designed to rob the Toa of their masks. Only by removing the Rahi’s infected masks can the Toa hope to tame them.</p>
    <p class='source'>Source: <em><a href='/read/?id=116'>BIONICLE #2: Deep Into Darkness</a></em></p>
</section>
<section data-spoiler='4'>
    <p>Powerful beasts who serve Makuta</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/The%20Official%20Guide%20to%20Bionicle.pdf'>The Official Guide to BIONICLE</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (26, "Tarakava", "<section data-spoiler='1'>
    <img src='/img/reference/Tarakava 1.png' alt='Tarakava'>
    <h1>Tarakava</h1>
    <p>The hunters of the sea, these huge creatures use their mighty arms to conquer prey!</p>
    <p class='source'>Source: <em><a href='/read/?id=116'>BIONICLE #2: Deep Into Darkness</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (27, "Muaka", "<section data-spoiler='1'>
    <img src='/img/reference/Muaka and Kane-Ra 1.png' alt='Muaka and Kane-Ra'>
    <h1>Muaka</h1>
    <p>The deadly jaws of Muaka… the sharp horns of Kane-Ra… together, they are a threat to all who live on Mata Nui!</p>
    <p class='source'>Source: <em><a href='/read/?id=116'>BIONICLE #2: Deep Into Darkness</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (28, "Kane-Ra", "<section data-spoiler='1'>
    <img src='/img/reference/Muaka and Kane-Ra 1.png' alt='Muaka and Kane-Ra'>
    <h1>Kane-Ra</h1>
    <p>The deadly jaws of Muaka… the sharp horns of Kane-Ra… together, they are a threat to all who live on Mata Nui!</p>
    <p class='source'>Source: <em><a href='/read/?id=116'>BIONICLE #2: Deep Into Darkness</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (29, "Nui-Rama", "<section data-spoiler='1'>
    <img src='/img/reference/Nui-Rama 1.png' alt='Nui-Rama'>
    <h1>Nui-Rama</h1>
    <p>From out of the sky come these fast, powerful insects, bringing destruction!</p>
    <p class='source'>Source: <em><a href='/read/?id=116'>BIONICLE #2: Deep Into Darkness</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (30, "Nui-Jaga", "<section data-spoiler='1'>
    <img src='/img/reference/Nui-Jaga 1.png' alt='Nui-Jaga'>
    <h1>Nui-Jaga</h1>
    <p>Fierce claws and stinging tails make these giant scorpion-like creatures a menace to Mata Nui!</p>
    <p class='source'>Source: <em><a href='/read/?id=116'>BIONICLE #2: Deep Into Darkness</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (31, "Huki", "<section data-spoiler='1'>
    <img src='/img/reference/Huki 1.png' alt='Huki'>
    <h1>Huki</h1>
    <p>Huki comes from the village of Po-Koro and wants to learn all he can from Toa Pohatu.</p>
    <p class='source'>Source: <em><a href='/read/?id=116'>BIONICLE #2: Deep Into Darkness</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (32, "Onepu", "<section data-spoiler='1'>
    <img src='/img/reference/Onepu 1.png' alt='Onepu'>
    <h1>Onepu</h1>
    <p>Onepu is incredibly strong for a Tohunga and can lift many times his weight.</p>
    <p class='source'>Source: <em><a href='/read/?id=116'>BIONICLE #2: Deep Into Darkness</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (33, "Maku", "<section data-spoiler='1'>
    <img src='/img/reference/Maku 1.png' alt='Maku'>
    <h1>Maku</h1>
    <p>Maku is a female villager from Ga-Koro and an excellent swimmer.</p>
    <p class='source'>Source: <em><a href='/read/?id=116'>BIONICLE #2: Deep Into Darkness</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (34, "Matoro", "<section data-spoiler='1'>
    <img src='/img/reference/Matoro 1.png' alt='Matoro'>
    <h1>Matoro</h1>
    <p>Matoro loves to climb steep mountains and slide down icy slopes, throwing his discs as he goes.</p>
    <p class='source'>Source: <em><a href='/read/?id=116'>BIONICLE #2: Deep Into Darkness</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (35, "Jala", "<section data-spoiler='1'>
    <img src='/img/reference/Jala 1.png' alt='Jala'>
    <h1>Jala</h1>
    <p>Tough and smart, Jala leads the guard in the village of Ta-Koro.</p>
    <p class='source'>Source: <em><a href='/read/?id=116'>BIONICLE #2: Deep Into Darkness</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (36, "Kongu", "<section data-spoiler='1'>
    <img src='/img/reference/Kongu 1.png' alt='Kongu'>
    <h1>Kongu</h1>
    <p>Kongu can move through the trees at astonishing speed and is among the most clever of the villagers.</p>
    <p class='source'>Source: <em><a href='/read/?id=116'>BIONICLE #2: Deep Into Darkness</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (37, "Manas", "<section data-spoiler='1'>
    <img src='/img/reference/Manas 1.png' alt='Manas'>
    <h1>Manas</h1>
    <h2>Challenge the Might of the Manas!</h2>
    <p>The Manas are the most powerful guardians of the evil Makuta! No single Toa can hope to withstand this crab-like beast’s fierce claws — only by combining their powers and skills can the Toa hope to prevail. When they aren’t fighting the enemies of Makuta, the Manas stay combat-ready by challenging each other to awesome battles!</p>
    <p>Like all Rahi, the Manas can be defeated by knocking off their infected masks. No one knows if a mask-less Manas can be tamed, however, because no one has ever survived an encounter with Makuta’s guardians.</p>
    <p class='source'>Source: <em><a href='/read/?id=158'>BIONICLE #3: Triumph of the Toa</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (38, "Vahi", "<section data-spoiler='1'>
    <img src='/img/reference/Vahi 1.png' alt='Vahi'>
    <h1>Kanohi Vahi</h1>
    <h2>The Great Mask of Time</h2>
    <p>The wearer of this mask is able to slow the passage of time, in order to move faster than his opponents.</p>
    <p class='source'>Source: <em><a href='/read/?id=158'>BIONICLE #3: Triumph of the Toa</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (39, "Gold Masks", "<section data-spoiler='1'>
    <h1>Gold Masks</h1>
    <p>The gold masks are easily the most powerful Kanohi, containing the energies of Kanohi Miru, Akaku, Kaukau, Hau, Pakari, and Kakama. A Toa wearing a gold mask can access all of the powers of the Kanohi masks at will.</p>
    <p class='source'>Source: <em><a href='/read/?id=158'>BIONICLE #3: Triumph of the Toa</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (40, "Infected Mask", "<section data-spoiler='1'>
    <h1>Infected Mask</h1>
    <p>Infected Kanohi masks are tainted by the evil power of Makuta. They appear scarred, rusted, and pitted. Anyone who dons an infected mask becomes a servant of Makuta and will obey his will until the mask is removed.</p>
    <p class='source'>Source: <em><a href='/read/?id=158'>BIONICLE #3: Triumph of the Toa</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (41, "Copper Mask of Victory", "<section data-spoiler='1'>
    <h1>Copper Mask of Victory</h1>
    <p>This ceremonial mask is awarded to the champion in village games. As such, it is highly prized by all the islanders of Mata Nui. In an effort to crush the morale of those who resist his power, Makuta has ordered his Rahi to gather the copper masks and conceal them. No one is quite sure where they can be found!</p>
    <p class='source'>Source: <em><a href='/read/?id=158'>BIONICLE #3: Triumph of the Toa</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (42, "Toa", "<section data-spoiler='4'>
    <h1>Toa</h1>
    <p>Six mighty heroes who protect the island; now known as <strong>Toa Nuva</strong></p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/The%20Official%20Guide%20to%20Bionicle.pdf'>The Official Guide to BIONICLE</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (43, "Turaga", "<section data-spoiler='4'>
    <h1>Turaga</h1>
    <p>Each of the six villages on Mata Nui is ruled by an elder called a Turaga. As the oldest and wisest member of the village, the Turaga’s job is to be sure the ancient legends are not forgotten. The Turaga work together to protect the knowledge of Mata Nui’s past, as well as the prophecies about its future.</p>
    <h2>The Secrets of the Turaga</h2>
    <p>Since the Toa arrived on Mata Nui, the Turaga have been there to provide them with important information. The Toa learned about the Kanohi masks and the Bohrok swarms from the Turaga, and they have come to rely on the wisdom of the village elders.</p>
    <p>But often it seems that the Turaga know more than they are telling. There are caverns that they have forbidden any Matoran to enter, but they will not say why. Some Toa Nuva have questioned why they were not told about the Bohrok before the swarms appeared on the island. It may be that the Turaga keep many more secrets than anyone knows…</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/The%20Official%20Guide%20to%20Bionicle.pdf'>The Official Guide to BIONICLE</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (44, "Matoran", "<section data-spoiler='4'>
    <h1>Matoran</h1>
    <p>Brave and hardworking villagers</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/The%20Official%20Guide%20to%20Bionicle.pdf'>The Official Guide to BIONICLE</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (45, "Makuta", "<section data-spoiler='4'>
    <h1>Makuta</h1>
    <p>Master of shadows; the Toa’s worst enemy</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/The%20Official%20Guide%20to%20Bionicle.pdf'>The Official Guide to BIONICLE</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (46, "Bohrok", "<section data-spoiler='4'>
    <h1>Bohrok</h1>
    <p>Insect-like beings who threaten Mata Nui in swarms</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/The%20Official%20Guide%20to%20Bionicle.pdf'>The Official Guide to BIONICLE</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (47, "Bohrok-Kal", "<section data-spoiler='4'>
    <h1>Bohrok-Kal</h1>
    <p>A special squad of ultrapowerful Bohrok</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/The%20Official%20Guide%20to%20Bionicle.pdf'>The Official Guide to BIONICLE</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (48, "Rahkshi", "<section data-spoiler='4'>
    <h1>Rahkshi</h1>
    <p>Six dangerous hunters who do Makuta’s will</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/The%20Official%20Guide%20to%20Bionicle.pdf'>The Official Guide to BIONICLE</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (49, "Onu-Koro", "<section data-spoiler='4'>
    <h1>Onu-Koro</h1>
    <p><strong>Toa</strong>: Onua Nuva</p>
    <p><strong>Turaga</strong>: Whenua</p>
    <p><strong>Villagers</strong>: Onu-Matoran</p>
    <p>Onu-Koro is a village of dark tunnels, caves and mines that plunge deep beneath the surface of the island. The Onu-Matoran mine protodermis, compete in Ussal crab races, and listen closely for the vibrations that warn of danger approaching. When the Matoran are in danger, Onu-Koro is a good place to hide. In the worst of times, Onu-Koro tunnels have been used to get messages from one village to another. Onu-Matoran can see in the dark and their eyes are sensitive to bright light.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/The%20Official%20Guide%20to%20Bionicle.pdf'>The Official Guide to BIONICLE</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (50, "Ta-Koro", "<section data-spoiler='4'>
    <h1>Ta-Koro</h1>
    <p><strong>Toa</strong>: Tahu Nuva</p>
    <p><strong>Turaga</strong>: Vakama</p>
    <p><strong>Villagers</strong>: Ta-Matoran</p>
    <p>The village of Ta-Koro rises from the Lake of Fire near the mighty Mangai volcano. Its people live within dwellings made of cooled lava and work the lava fields to the north A small lava stream flows through the village, providing it with heat. When they are not at work, the Ta-Matoran surf the lava rapids.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/The%20Official%20Guide%20to%20Bionicle.pdf'>The Official Guide to BIONICLE</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (51, "Protodermis", "<section data-spoiler='4'>
    <h1>Protodermis</h1>
    <p>Very little is known about the strange substance known as protodermis. The Matoran of Onu-Koro have been mining it for many years as a source of energy, but even they are uncertain of what it is or where it comes from.</p>
    <p>After a huge underground battle, the Toa fell into large tubes filled with protodermis. They returned to the surface with new armour, new tools and new powers. The Toa are now more powerful than ever. This is why they have given themselves a new name: Toa Nuva.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/The%20Official%20Guide%20to%20Bionicle.pdf'>The Official Guide to BIONICLE</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (52, "Acid Shield", "<section data-spoiler='9'>
    <h1>Acid Shield</h1>
    <p>Tool carried by the LEHVAK. This shield secreted a special acid that could eat through any substance on MATA NUI (2) in a matter of seconds.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (53, "Ahkmou", "<section data-spoiler='9'>
    <h1>Ahkmou (OCK-moo)</h1>
    <p>MATORAN carver originally from PO-METRU. His rivalry with ONEWA led him into a deal with the DARK HUNTER NIDHIKI to obtain the six GREAT DISKS. Frustrated in this and captured by Onewa, he reluctantly joined the TOA METRU in their efforts to find the disks and defeat the MORBUZAKH plant. He was briefly a part of the merged being MATORAN NUI, but split off to pursue his own destiny.</p>
    <p>Ahkmou was one of the six Matoran initially rescued from the COLISEUM by the Toa Metru. During the voyage to MATA NUI (2), Ahkmou’s sphere was lost. It lay on the bottom of an underground river until found by MAKUTA. By that time, evidence had been discovered by the Toa Metru that Ahkmou may have been destined to become a Toa of Stone. That evidence later proved to be false. Ahkmou would later reappear on Mata Nui as a trader, selling KOLHII BALLS infected with Makuta’s darkness.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (54, "Air Katana", "<section data-spoiler='9'>
    <h1>Air Katana</h1>
    <p>LEWA NUVA’s tools, which allowed him to glide on air currents. One of these was later broken in combat with REIDAK, but eventually fixed by the MATORAN inventor VELIKA.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (55, "Airship", "<section data-spoiler='9'>
    <img src='/img/reference/Airship 1.png' alt='Airships'>
    <h1>Airship</h1>
    <p>Matoran flying vehicles used to transport cargo in METRU NUI. Airships were operated using a complicated system of KANOKA DISKS with the levitation and increase weight powers. When pulleys caused the levitation disks to strike the framework, the craft rose. When another set of pulleys brought the increase weight disks into contact with the frame, the craft would lose altitude. Forward thrust was provided by a portion of CHUTE capped at both ends, with only a small amount of liquid PROTODERMIS under high pressure allowed to jet from the back. Airships were built, maintained, and piloted by LE-MATORAN.</p>
    <p>The VISORAK destroyed all of the airships in the MOTO-HUB when they invaded the city. Later, the TOA HORDIKA constructed six vessels to carry the sleeping MATORAN to the island of MATA NUI (2). Upon arrival, the ships were broken down and the parts were used to help build the island’s six villages.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (56, "Airwatcher", "<section data-spoiler='9'>
    <h1>Airwatcher</h1>
    <p>Winged DARK HUNTER who patrols the mountainous northern regions of ODINA. More powerful than he is intelligent, he has been known to attack rocks, trees, and even other Dark Hunters. His chest-mounted launcher projects energy webs and his staff fires acid.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (57, "Airweed", "<section data-spoiler='9'>
    <h1>Airweed</h1>
    <p>Plant that grows in the undersea FIELDS OF AIR near MAHRI NUI. These plants produce pure air, which can be harvested from inside them by HYDRUKA or released due to significant impact tremors. The air produced by these plants made it possible for the MATORAN to survive underwater.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (58, "Akamai", "<section data-spoiler='9'>
    <h1>Akamai (OCK-kah-MY)</h1>
    <p>A TOA KAITA, the result of the merging to Toa TAHU, Toa POHATU, and Toa ONUA. Akamai fought the MANAS, MAKUTA’s monstrous crablike guardians. Akamai wore the KANOHI AKI.</p>
    <h1>Akamai Nuva (OCK-kah-MY NOO-vah)</h1>
    <p>A TOA KAITA NUVA that could have been formed by TAHU NUVA, ONUA NUVA, and POHATU NUVA. There is no record that this being was ever brought into existence.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (59, "Aki", "<section data-spoiler='9'>
    <h1>Aki (OCK-kee)</h1>
    <p>The Gold Mask of Valor, possessed of the powers of Shielding, Speed, and Strength.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (60, "Akilini", "<section data-spoiler='9'>
    <h1>Akilini (AH-kih-LEE-nee)</h1>
    <p>The major sport of METRU NUI. Akilini was said to have been created by a MATORAN named KODAN, with the original version featuring a ball (see KODAN BALL). Later, it evolved into a popular sport in which players launched KANOKA DISKS through hoops. Akilini was played on small fields throughout Metru Nui and in the COLISEUM. The disks used by the winning team in a tournament would be sent to TA-METRU and be turned into KANOHI masks.</p>
    <p>RULES OF AKILINI</p>
    <ol>
        <li>Akilini matches are played between more than one, but not more than four, teams.</li>
        <li>An akilini team consists of at least two, but not more than six, players.</li>
        <li>At least one player on each team must serve as a defender. A defender is forbidden to take shots and may only launch disks to deflect the shots of opponents. A maximum of two players may serve as defenders.</li>
        <li>At least one player on each team must be a launcher. If the team has more than two players, a maximum of four players may serve as launchers.</li>
        <li>One point is scored for every disk that passes cleanly through and opposing team’s hoop. Disks that strike the hoop are not considered goals.</li>
        <li>Akilini tournament play ends when one team reaches 21 goals.</li>
        <li>Disks in play may be retrieved by any launcher or defender from the launching team but may not be recovered by opposing players.</li>
        <li>Disks that leave the playing field are considered to be open to all and may be retrieved by players from any team.</li>
        <li>Players must keep at least one foot on their transport disk at all times. Transport disks may not be launched. Launching disks may not be used for transport.</li>
        <li>Players who go more than one bio outside of the field of play on any side will be considered out of bounds. However, players may go as far above or below the field of play as they wish and still be considered in bounds.</li>
        <li>Players may not make physical contact with a defender at any time.</li>
        <li>Players may make physical contact with launchers, but only after their disk has been launched. Striking, tackling, or otherwise making physical contact with a launcher in the process of making a shot is considered “rocking the launcher” and will cost the offending team one launching disk.</li>
    </ol>
    <p>At its most basic, akilini was played on a round field surrounded by posts upon which hoops were mounted. The field was not free of obstacles — in fact, the prevailing wisdom was: the more, the better. Matoran would surf on Kanoka disks up and down structures, through chutes, and even through tunnels in the ARCHIVES, popping up only long enough to make a shot. “Street akilini” became so popular, and such a menace to pedestrians, that the VAHKI eventually had to crack down on it.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (61, "Amaja Circle", "<section data-spoiler='9'>
    <h1>Amaja Circle (ah-MAH-yah)</h1>
    <p>A circular sandpit at KINI-NUI used by the TURAGA to tell stories using stones.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (62, "Amana Volo Sphere", "<section data-spoiler='9'>
    <h1>Amana Volo Sphere (ah-MAH-nah VOH-loh)</h1>
    <p>A powerful globe of dark energy discharged from certain RAHI upon the removal of an infected mask. These free-floating balls of energy could be absorbed by other beings and could restore health.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (63, "Amphibax", "<section data-spoiler='9'>
    <h1>Amphibax (am-fi-BACKS)</h1>
    <p>Amphibious DARK HUNTER whose primary occupation is raiding BROTHERHOOD OF MAKUTA vessels and the coastlines of MATORAN-held islands. His weapons are sharp claws on one hand and a spiny whip in place of the other hand. He is a skilled swimmer, runner, and tree-climber. He was, at one time, a member of EHLEK’s army, and is rumored to have lost his hand in a futile attempt to search for, and rescue, his leader after the failed rebellion against MATA NUI (1).</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (64, "Ancient", "<section data-spoiler='9'>
    <h1>Ancient</h1>
    <p>Veteran warrior who, along with the SHADOWED ONE, formed the DARK HUNTERS. He now serves as one of the Shadowed One’s most trusted operatives. Ancient was responsible for recruiting both VEZOK and HAKANN into the Dark Hunter organization. In addition to his great strength and formidable armor, he possesses boots fitted with levitation disks that enable him to rise into the air. His modified RHOTUKA SPINNER robs targets of all physical coordination.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (65, "Antidermis", "<section data-spoiler='9'>
    <h1>Antidermis</h1>
    <p>Name given by the PIRAKA to the green-black virus they used to enslave the MATORAN of VOYA NUI. In fact, the “virus” was actually the life essence of the MAKUTA of METRU NUI. The vat containing the antidermis was shattered by AXONN, forcing Makuta to search for another vessel to house his energy or risk being permanently dispersed into the atmosphere.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (66, "Aqua Axes", "<section data-spoiler='9'>
    <h1>Aqua Axes</h1>
    <p>GALI NUVA’s tools. In addition to their cutting edge, they doubled as fins for swimming at great speed.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (67, "Archives", "<section data-spoiler='9'>
    <h1>Archives</h1>
    <p>The living museum of ONU-METRU, which extended far below the surface and below many other metru as well. Here could be found virtually every tool, artifact, and creature of METRU NUI’s past. Staffed by a large number of ONU-MATORAN, it was once the workplace of WHENUA before his transformation into a TOA METRU.</p>
    <p>RAHI kept in the Archives were held in stasis for safety purposes. Each Rahi was placed inside an inner stasis tube which was surrounded by a clear casing. While in the stasis tube, their life processes were slowed to an extreme degree. They were alive, but not aware, and could remain in that state for thousands of years. Damage to the outer case would not affect the Rahi, but damage to the inner case would cause it to awaken.</p>
    <p>The Archives were known to contain levels, sublevels, and maintenance tunnels. Some sublevels featured creatures considered too dangerous to exhibit publicly. The museum could be accessed through a number of entrances, all of which were guarded. The locking mechanisms was a series of hidden switches that had to be hit in a certain order.</p>
    <p>Creatures known to have been in the Archives included RAHKSHI, the TWO-HEADED TARAKAVA, the USSAL hybrid, a NUI-RAMA, a MUAKA, and the ARCHIVES BEAST. KRAHKA and other creatures lived in the maintenance tunnels beneath the Archives.</p>
    <p>The Rahaga used the Archives as a base for years prior to the coming of the VISORAK and his the AVOHKII there. A massive earthquake later shattered the Archives, releasing all of its exhibits into the city.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (68, "Archives Beast", "<section data-spoiler='9'>
    <h1>Archives Beast</h1>
    <p>A strange creature housed in the ONU-METRU ARCHIVES and believed to have some connection to the reconstitutes at random KANOKA DISK power. When encountered by TOA NUJU and Toa WHENUA, it had taken on the appearance of an empty room. Its attempts to trap the Toa were frustrated by a blizzard created by Nuju.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (69, "Archives Moles", "<section data-spoiler='9'>
    <h1>Archives Moles</h1>
    <p>Small, harmless creatures who migrated from PO-METRU to the ARCHIVES. They fed on insects and microscopic protodites and were known for their ability to cooperate with each other. These RAHI later made their way to LE-WAHI on MATA NUI (2).</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (70, "Artakha", "<section data-spoiler='9'>
    <h1>Artakha (arr-TOCK-ah)</h1>
    <p>A place of peace and contentment for all MATORAN. Its location has never been discovered, and many Matoran now believe it to be a myth. The first mention of Artakha is in an ancient legend which speaks of a trouble-free site to which skilled Matoran workers were allowed to migrate.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (71, "Artakha Bull", "<section data-spoiler='9'>
    <h1>Artakha Bull (arr-TOCK-ah)</h1>
    <p>A swift, strong, plant-eating RAHI who relied on its sharp horns for defense. The Artakha bull was considered to be one of the earliest RAHI known to MATORAN and was rumored to be extremely intelligent. The species lived in LE-METRU on METRU NUI, but none were ever seen on MATA NUI (2).</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (72, "Arthron", "<section data-spoiler='9'>
    <h1>Arthron (arr-thronn)</h1>
    <p>KANOHI Mask of Sonar worn by TOA MAHRI JALLER. This mask allows the wearer to locate beings or objects through echolocation.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (73, "Ash Bear", "<section data-spoiler='9'>
    <h1>Ash Bear</h1>
    <p>A large ursine creature known for its powerful teeth and claws. The TOA METRU encountered a wounded member of this species in the ARCHIVES and combined their powers to heal the she-creature. Hundreds of years later, this same ash bear would briefly menace JALLER and TAKUA in LE-WAHI, before being subdued and calmed by LEWA NUVA.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (74, "Assembler’s Village", "<section data-spoiler='9'>
    <h1>Assembler’s Village</h1>
    <p>Small settlements scattered throughout PO-METRU on METRU NUI, home to the crafters who assemble Matoran goods. Parts were shipped to these villages via CHUTE, boat, AIRSHIP, and USSAL cart, and these were then painstakingly assembled by skilled PO-MATORAN. Due to the relative isolation of these villages, they were extremely vulnerable to RAHI attack. In the past, assembler’s villages had been menaced by STONE RATS, KINLOKA, ROCK RAPTORS, and scores of other beasts. The TOA METRU fought NIDHIKI and KREKKA here, in a village later destroyed by a KIKANALO stampede.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (75, "Av-Matoran", "<section data-spoiler='9'>
    <h1>Av-Matoran</h1>
    <p>Name given to a MATORAN of Light. The dwelling place of these Matoran remains a mystery, as does the number in existence. TAKUA, unknown to himself and all those around him, was an Av-Matoran disguised as a TA-MATORAN. The Av-Matoran tribe includes both males and females. Their affinity with the element of light allows them to subtly alter its frequency so that they can change their color. Av-Matoran were “seeded” on various islands in disguise by the ORDER OF MATA NUI during the TIME SLIP to protect them from possible future actions by the BROTHERHOOD OF MAKUTA.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (76, "Avak", "<section data-spoiler='9'>
    <h1>Avak (ay-VACK)</h1>
    <p>One of the six PIRAKA who attempted to steal the KANOHI IGNIKA. Avak was originally employed as a jailer on ZAKAZ, where he used his powers to imprison DARK HUNTERS, among others. He was recruited into the Dark Hunter organization shortly after that. Avak possesses the elemental power of stone, X-ray and telescopic vision, and the ability to create the perfect prison for any target out of thin air using only his mind. He carries a ZAMOR SPHERE LAUNCHER and a seismic pickaxe/jackhammer. Avak is a skilled inventor who designed the Zamor Sphere Launchers for his fellow Piraka. He was ambushed and defeated by DALU and later joined with the other Piraka to battle the TOA NUVA and TOA INIKA. When last seen, he was with the Piraka on Voya Nui still scheming to get the Kanohi Ignika, unaware that his body was being affected by an earlier exposure to mutagenic seawater.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (77, "Avohkii", "<section data-spoiler='9'>
    <h1>Avohkii (ah-VOH-kee)</h1>
    <p>KANOHI Mask of Light worn by TOA TAKANUVA.</p>
    <p>The Mask of Light was originally forged on the island of ARTAKHA and intended for use by a prophesied Toa of Light. Many thousands of years later, the Brotherhood of Makuta stole the mask from its creators and hit it in one of their fortresses in an effort to prevent this Toa from ever coming to be. Later, after discovering the Brotherhood’s treachery, the TOA HAGAH raided the fortress and stole the mask themselves. Even after being turned into RAHAGA, they were able to successfully spirit the mask away.</p>
    <p>The Rahaga hid the mask in the ARCHIVES in METRU NUI. The compartment in which it was concealed could only be opened using six MAKOKI STONES, which the Rahaga hid around the city to keep them from the VISORAK. The TOA HORDIKA retrieved all six stones and found the Avohkii, which gave off a bright glow. Fearing that the glow would attract the attention of the Visorak, Toa Hordika ONEWA used his RHOTUKA SPINNER to conceal the mask in a block of stone.</p>
    <p>The TOA METRU later transported that stone to the island of MATA NUI (2) and hid it in a lava cave. Legend stated that the HERALD of the Toa of Light would have to find the mask, it could not be given to him, and so the location of it was kept secret. A MATORAN named TAKUA would eventually stumble upon the Mask of Light while exploring the cavern.</p>
    <p>Takua and JALLER were charged by the TURAGA with the duty of finding the Toa of Light. Their adventure took them to many places on the island and exposed them to danger from RAHI and RAHKSHI. Takua later donned the mask and transformed into Takanuva, the Toa of Light. (How the mask was able to trigger such a transformation when normal Great Masks do not has not yet been revealed. It is rumored that the mask may have been infused with Toa power during its creation, and so acted like a TOA STONE.)</p>
    <p>The Avohkii can project powerful beams of light energy and banish the darkness. It also brings understanding, turning anger into peace and enemies into allies.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (78, "Axe", "<section data-spoiler='9'>
    <h1>Axe</h1>
    <p>TOA tool carried by LEWA, used to focus his air power.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (79, "Axonn", "<section data-spoiler='9'>
    <h1>Axonn (AXE-on)</h1>
    <p>Member of the ORDER OF MATA NUI and former member of the HAND OF ARTAKHA. After the Hand was disbanded, Axonn wandered for some time, using his powers for petty conquests. He was eventually recruited by the Order, who offered to put his talents to good use. Axonn was assigned to VOYA NUI, to guard the KANOHI IGNIKA, and was there when the landmass split off from its continent and rocketed to the surface of the ocean. Axonn and his partner, BRUTAKA, defeated a number of enemies who attempted to claim the Mask of Life, until the PIRAKA arrived. Brutaka betrayed Axonn and allied himself with the Piraka, forcing a confrontation between between the two. In the battle, Axonn defeated Brutaka and destroyed the vat containing the ANTIDERMIS. Later, Axonn opened the way to the CORD so the TOA INIKA could travel to MAHRI NUI.</p>
    <p>Axonn wears the Kanohi RODE, the GREAT MASK of Truth, which can pierce any disguise and spot any deception. His axe can fire energy blasts and cleave virtually any substance. In addition, Axonn has massive strength and a number of special powers, among them the ability to heal insanity with a touch of his hand.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (80, "Bahrag", "<section data-spoiler='9'>
    <h1>Bahrag (BAH-rag)</h1>
    <p>CAHDOK and GAHDOK, the twin queens of the BOHROK swarms. The Bahrag directed the rampages of the Bohrok, unaware that there were MATORAN living on the surface of MATA NUI (2). They possessed the elemental powers of the six Bohrok, plus the ability to create lifelike illusions. They created and were in contact with the KRANA that inhabited each Bohrok. The Bahrag were defeated by the TOA NUVA and imprisoned in a cage of solid PROTODERMIS. The Bahrag were later the subject of a failed rescue attempt by the BOHROK-KAL. The Toa Nuva recently released the Bahrag as part of their preparations for awakening the GREAT SPIRIT MATA NUI (1).</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (81, "Balta", "<section data-spoiler='9'>
    <h1>Balta (BALL-tah)</h1>
    <p>TA-MATORAN member of the RESISTANCE on VOYA NUI. Balta was a skilled tinkerer, able to make a weapon out of whatever materials were at hand. When crossed, his twin repeller tools could block any enemy attack and strike back with equal force. Balta helped steal one of the ZAMOR SPHERE LAUNCHERS belonging to the PIRAKA, was saved from a cave trap by AXONN, and later organized the rescue of GARAN from the PIRAKA.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");

INSERT INTO wall_of_history_reference
VALUES
    (82, "Barraki", "<section data-spoiler='9'>
    <h1>Barraki (BUH-rock-KEE)</h1>
    <p>Six powerful rulers who attempted to overthrow MATA NUI (1) and were condemned to the PIT for their crimes. The Barraki (Matoran for “warlord”) were originally allied in the LEAGUE OF SIX KINGDOMS and ruled a significant portion of the known universe. Desiring more, they attempted a revolution 80,000 years ago, but were defeated by an army led by the MAKUTA of METRU NUI. Before they could be executed, they were spirited away to the Pit by BOTAR. There they remained until the Pit was shattered by the GREAT CATACLYSM. The Barraki escaped but found that they were at the bottom of a strange ocean. Since only one of the six breathed water naturally, it appeared they were doomed.</p>
    <p>But the mutagenic seawater of the region affected all the Barraki, severely mutating them and making it possible for all to survive at great depths. No longer able to breathe air, they carved out what empires they could beneath the waves and dreamed of revenge against Mata Nui (1) and the BROTHERHOOD OF MAKUTA. After the appearance of MAHRI NUI in their midst, they periodically hunted MATORAN for the fun of it.</p>
    <p>All of that changed when the KANOHI IGNIKA drifted down into the Pit. The Barraki knew its power could transform them back to the perfect physical specimens they once were and make it possible for them to reclaim their kingdoms on the surface. They seized the mask, but then lost it after entrusting it to NOCTURN. The Barraki were then tricked by the TOA MAHRI into warring among themselves, while the Toa took advantage of the opportunity to search for the mask themselves.</p>
    <p>The Barraki have since realized their error and have banded together once again to battle the Toa Mahri for possession of the Ignika. The six Barraki are PRIDAK, EHLEK, KALMAH, TAKADOX, CARAPAR, and MANTAX.</p>
    <p class='source'>Source: <em><a href='http://biomediaproject.com/bmp/files/story/books/guides/Bionicle%20Encyclopedia%20-%20Updated.pdf'>BIONICLE Encyclopedia Updated</a></em></p>
</section>");