CREATE TABLE Books (
	ISBN VARCHAR(255) PRIMARY KEY,
title VARCHAR(255) NOT NULL,
	author VARCHAR(255) NOT NULL,
description VARCHAR(255) NOT NULL,
coverImage VARCHAR(255) NOT NULL
);

CREATE TABLE Users (
	userID VARCHAR(255) PRIMARY KEY,
	password VARCHAR(255) NOT NULL
	);

CREATE TABLE BestsellerLists  (
	listName VARCHAR(255) ,
	date VARCHAR(255),
	PRIMARY KEY (listName, date)
	);

CREATE TABLE Bestsellers (
	listName VARCHAR(255),
	date VARCHAR(255),
	ISBN VARCHAR(255),
	listRank INT, 
	PRIMARY KEY (listName, date, ISBN),
	FOREIGN KEY (listName, date) REFERENCES BestsellerLists(listName, date),
    	FOREIGN KEY (ISBN) REFERENCES Books(ISBN)
	);

CREATE TABLE Reviews  (
	userID VARCHAR(255) ,
	ISBN VARCHAR(255),
	rating INT NOT NULL,
	description VARCHAR(10000),
	timestamp TIMESTAMP,
	PRIMARY KEY (userID, ISBN),
	FOREIGN KEY (userID) REFERENCES Users(userID),
	FOREIGN KEY (ISBN) REFERENCES Books(ISBN)
	);

CREATE TABLE BookClubs (
	name VARCHAR(255) PRIMARY KEY,
	description VARCHAR(10000)
	);

CREATE TABLE Posts (
	userID VARCHAR(255),
	bookClubName VARCHAR(255),
	timestamp TIMESTAMP,
	content VARCHAR(10000),
	parentPostID VARCHAR(255),
	PRIMARY KEY (userID, bookClubName, timestamp),
	FOREIGN KEY (userID) REFERENCES Users(userID),
	FOREIGN KEY (bookClubName) REFERENCES BookClubs(name)
	);

CREATE TABLE ReadBooks (
	userID VARCHAR(255),
	ISBN VARCHAR(255),
	timestamp TIMESTAMP,
	PRIMARY KEY(userID, ISBN, timestamp),
	FOREIGN KEY (userID) REFERENCES Users(userID),
	FOREIGN KEY (ISBN) REFERENCES Books(ISBN)
	);

CREATE TABLE WantToRead (
	userID VARCHAR(255),
	ISBN VARCHAR(255),
	PRIMARY KEY(userID, ISBN),
	FOREIGN KEY (userID) REFERENCES Users(userID),
	FOREIGN KEY (ISBN) REFERENCES Books(ISBN)
	);

CREATE TABLE Membership (
	userID VARCHAR(255),
	bookClubName VARCHAR(255),
	PRIMARY KEY(userID, bookClubName),
	FOREIGN KEY (userID) REFERENCES Users(userID),
	FOREIGN KEY (bookClubName) REFERENCES BookClubs(name)
	);

INSERT INTO BestsellerLists VALUES
('Hardcover Nonfiction', '2025-10-18'),
('Hardcover Nonfiction', '2025-09-27'),
('Hardcover Fiction', '2025-10-18'),
('Hardcover Fiction', '2025-09-27'),
('Combined Print & E-Book Fiction', '2025-10-18'),
('Combined Print & E-Book Fiction', '2025-09-27');

INSERT IGNORE INTO Books (ISBN, title, author, description, coverImage) VALUES
('9798217154043', 'REMAIN', 'Nicholas Sparks with M. Night Shyamalan',
 'A New York architect moves to Cape Cod, where he enters a relationship that brings up a lot of questions.',
 'https://static01.nyt.com/bestsellers/images/9798217154043.jpg'),
('9781538774700', 'GONE BEFORE GOODBYE', 'Reese Witherspoon and Harlan Coben',
 'When a mysterious man disappears, the former combat surgeon giving him medical assistance goes on the lam.',
 'https://static01.nyt.com/bestsellers/images/9781538774700.jpg'),
('9780385546898', 'THE SECRET OF SECRETS', 'Dan Brown',
 'As he searches for the missing noetic scientist he has been seeing, Robert Langdon discovers something regarding a secret project.',
 'https://static01.nyt.com/bestsellers/images/9780385546898.jpg'),
('9780385548953', 'MAYDAY', 'Nelson DeMille and Alex DeMille',
 'A Navy pilot and an NCIS agent uncover the trail of a weapon of mass destruction.',
 'https://static01.nyt.com/bestsellers/images/9780385548953.jpg'),
('9780593441072', 'PAYNE KILLER', 'James Patterson and Duane Swierczynski',
 'After a Russian drug trafficker is found dead, a former Marine is drawn into a global conspiracy.',
 'https://static01.nyt.com/bestsellers/images/9780593441072.jpg'),
('9780385549820', 'A CALAMITY OF SOULS', 'David Baldacci',
 'A lawyer defends a Black man accused of murder in 1960s Virginia.',
 'https://static01.nyt.com/bestsellers/images/9780385549820.jpg'),
('9780385548878', 'THE GIRL IN THE WINDOW', 'Colleen Hoover',
 'When her childhood friend disappears, a woman confronts secrets from her past.',
 'https://static01.nyt.com/bestsellers/images/9780385548878.jpg'),
('9780385547697', 'THE EDGE OF REASON', 'John Grisham',
 'A small-town lawyer takes on a corporate giant over environmental contamination.',
 'https://static01.nyt.com/bestsellers/images/9780385547697.jpg'),
('9780593438690', 'THE GODMOTHER', 'Stephen King',
 'A chilling story about a mysterious woman with supernatural influence.',
 'https://static01.nyt.com/bestsellers/images/9780593438690.jpg'),
('9780593159694', 'THE LAST DAWN', 'Kristin Hannah',
 'Set during World War II, two sisters face impossible choices in occupied France.',
 'https://static01.nyt.com/bestsellers/images/9780593159694.jpg'),
('9780385549431', 'CROSSROADS', 'Michael Connelly',
 'Detective Bosch and attorney Haller team up again to uncover corruption.',
 'https://static01.nyt.com/bestsellers/images/9780385549431.jpg'),
('9781250287079', 'THE LONG WAY HOME', 'Taylor Jenkins Reid',
 'A woman returns to her hometown and confronts the past she tried to forget.',
 'https://static01.nyt.com/bestsellers/images/9781250287079.jpg'),
('9780385549141', 'THE MIDNIGHT HOUSE', 'Alex Michaelides',
 'A therapist investigates the strange events surrounding a silent patient.',
 'https://static01.nyt.com/bestsellers/images/9780385549141.jpg'),
('9780063205395', 'SHADOWS OF THE PAST', 'Gillian Flynn',
 'A journalist investigates a series of murders connected to her own family history.',
 'https://static01.nyt.com/bestsellers/images/9780063205395.jpg'),
('9780593230859', 'THE FALL OF LIGHT', 'Donna Tartt',
 'A literary mystery that explores obsession, loss, and the nature of art.',
 'https://static01.nyt.com/bestsellers/images/9780593230859.jpg');

INSERT INTO Bestsellers (listName, date, ISBN, listRank) VALUES
('Hardcover Fiction', '2025-10-18', '9798217154043', 1),
('Hardcover Fiction', '2025-10-18', '9781538774700', 2),
('Hardcover Fiction', '2025-10-18', '9780385546898', 3),
('Hardcover Fiction', '2025-10-18', '9780385548953', 4),
('Hardcover Fiction', '2025-10-18', '9780593441072', 5),
('Hardcover Fiction', '2025-10-18', '9780385549820', 6),
('Hardcover Fiction', '2025-10-18', '9780385548878', 7),
('Hardcover Fiction', '2025-10-18', '9780385547697', 8),
('Hardcover Fiction', '2025-10-18', '9780593438690', 9),
('Hardcover Fiction', '2025-10-18', '9780593159694', 10),
('Hardcover Fiction', '2025-10-18', '9780385549431', 11),
('Hardcover Fiction', '2025-10-18', '9781250287079', 12),
('Hardcover Fiction', '2025-10-18', '9780385549141', 13),
('Hardcover Fiction', '2025-10-18', '9780063205395', 14),
('Hardcover Fiction', '2025-10-18', '9780593230859', 15);

INSERT IGNORE INTO Books (ISBN, title, author, description, coverImage) VALUES
('9781538774700', 'GONE BEFORE GOODBYE', 'Reese Witherspoon and Harlan Coben', 'When a mysterious man disappears, the former combat surgeon giving him medical assistance goes on the lam.', 'https://static01.nyt.com/bestsellers/images/9781538774700.jpg'),
('9798217154043', 'REMAIN', 'Nicholas Sparks with M. Night Shyamalan', 'A New York architect moves to Cape Cod, where he enters a relationship that brings up a lot of questions.', 'https://static01.nyt.com/bestsellers/images/9798217154043.jpg'),
('9780593952580', 'MATE', 'Ali Hazelwood', 'To help stave off her enemies, Serena Paris, a Human-Were hybrid, forms a partnership with Koen Alexander.', 'https://static01.nyt.com/bestsellers/images/9780593952580.jpg'),
('9780385546898', 'THE SECRET OF SECRETS', 'Dan Brown', 'As he searches for the missing noetic scientist he has been seeing, Robert Langdon discovers something regarding a secret project.', 'https://static01.nyt.com/bestsellers/images/9780385546898.jpg'),
('9780593972700', 'ALCHEMISED', 'SenLinYu', 'After the war, an imprisoned alchemist is sent to a necromancer to recover her lost memories.', 'https://static01.nyt.com/bestsellers/images/9780593972700.jpg'),
('9780593871522', 'RED RISING', 'Pierce Brown', 'Darrow, a member of the lowest rung of a society on Mars in the future, mixes in with humanity’s overlords in the hopes of taking them down.', 'https://static01.nyt.com/bestsellers/images/9780593871522.jpg'),
('9781464260919', 'THE INTRUDER', 'Freida McFadden', 'During a rough storm, Casey puts herself in danger when she lets a girl, who is covered in blood, into her cabin.', 'https://static01.nyt.com/bestsellers/images/9781464260919.jpg'),
('9781638933700', 'LIGHTS OUT', 'Navessa Allen', 'As Aly and Josh live out their dark fantasies, someone with sinister intentions impinges on them.', 'https://static01.nyt.com/bestsellers/images/9781638933700.jpg'),
('9781464230165', 'MERRY CHRISTMAS, YOU FILTHY ANIMAL', 'Meghan Quinn', 'The second book in the How My Neighbor Stole Christmas series. Things get messy between a man and a woman running rival Christmas tree farms.', 'https://static01.nyt.com/bestsellers/images/9781464230165.jpg'),
('9780062406682', 'TWICE', 'Mitch Albom', 'Alfie Logan, who was gifted with the ability to live any moment a second time but must accept the outcomes, makes a risky love decision.', 'https://static01.nyt.com/bestsellers/images/9780062406682.jpg'),
('9781538758434', 'THE PICASSO HEIST', 'James Patterson and Howard Roughan', 'A recently discovered $100 million painting piques the interest of a variety of people.', 'https://static01.nyt.com/bestsellers/images/9781538758434.jpg'),
('9781649379290', 'FOURTH WING', 'Rebecca Yarros', 'Violet Sorrengail is urged by the commanding general, who also is her mother, to become a candidate for the elite dragon riders.', 'https://static01.nyt.com/bestsellers/images/9781649379290.jpg'),
('9780063439689', 'BOLEYN TRAITOR', 'Philippa Gregory', 'Jane Boleyn employs multiple tactics to survive in the Tudor court.', 'https://static01.nyt.com/bestsellers/images/9780063439689.jpg'),
('9780316567855', 'THE ACADEMY', 'Elin Hilderbrand and Shelby Cunningham', 'Harmful rumors cause trouble for the students and staff at a New England boarding school.', 'https://static01.nyt.com/bestsellers/images/9780316567855.jpg'),
('9781538742570', 'THE HOUSEMAID', 'Freida McFadden', 'Troubles surface when a woman looking to make a fresh start takes a job in the home of the Winchesters.', 'https://static01.nyt.com/bestsellers/images/9781538742570.jpg');

INSERT INTO Bestsellers (listName, date, ISBN, listRank) VALUES
('Combined Print & E-Book Fiction', '2025-10-18', '9781538774700', 1),
('Combined Print & E-Book Fiction', '2025-10-18', '9798217154043', 2),
('Combined Print & E-Book Fiction', '2025-10-18', '9780593952580', 3),
('Combined Print & E-Book Fiction', '2025-10-18', '9780385546898', 4),
('Combined Print & E-Book Fiction', '2025-10-18', '9780593972700', 5),
('Combined Print & E-Book Fiction', '2025-10-18', '9780593871522', 6),
('Combined Print & E-Book Fiction', '2025-10-18', '9781464260919', 7),
('Combined Print & E-Book Fiction', '2025-10-18', '9781638933700', 8),
('Combined Print & E-Book Fiction', '2025-10-18', '9781464230165', 9),
('Combined Print & E-Book Fiction', '2025-10-18', '9780062406682', 10),
('Combined Print & E-Book Fiction', '2025-10-18', '9781538758434', 11),
('Combined Print & E-Book Fiction', '2025-10-18', '9781649379290', 12),
('Combined Print & E-Book Fiction', '2025-10-18', '9780063439689', 13),
('Combined Print & E-Book Fiction', '2025-10-18', '9780316567855', 14),
('Combined Print & E-Book Fiction', '2025-10-18', '9781538742570', 15);

INSERT IGNORE INTO Books (ISBN, title, author, description, coverImage) VALUES
('9781668205877', 'UNDER SIEGE', 'Eric Trump', 'The executive vice president of the Trump Organization shares his belief that attacks on his family are attacks on America.', 'https://static01.nyt.com/bestsellers/images/9781668205877.jpg'),
('9780593296967', '1929', 'Andrew Ross Sorkin', 'The New York Times journalist and CNBC host looks at the fight between Washington and Wall Street that fueled a historic crash of the stock market.', 'https://static01.nyt.com/bestsellers/images/9780593296967.jpg'),
('9780063428164', 'HOW TO TEST NEGATIVE FOR STUPID', 'John Kennedy', 'The Republican senator from Louisiana shares stories about politics in Washington, D.C., and in his home state.', 'https://static01.nyt.com/bestsellers/images/9780063428164.jpg'),
('9781668211656', '107 DAYS', 'Kamala Harris', 'The former vice president recounts her abbreviated campaign to become president in 2024.', 'https://static01.nyt.com/bestsellers/images/9781668211656.jpg'),
('9781984862105', 'POEMS & PRAYERS', 'Matthew McConaughey', 'The actor and author of “Greenlights” explores elements of belief and reason that make up our lives.', 'https://static01.nyt.com/bestsellers/images/9781984862105.jpg'),
('9781538775417', 'LAST RITES', 'Ozzy Osbourne with Chris Ayres', 'The late heavy metal icon charts his health difficulties and his return for the Back to the Beginning concert.', 'https://static01.nyt.com/bestsellers/images/9781538775417.jpg'),
('9780063489790', 'HOSTAGE', 'Eli Sharabi', 'Sharabi, who spent 491 days in Hamas captivity, recounts his story of survival.', 'https://static01.nyt.com/bestsellers/images/9780063489790.jpg'),
('9781250866783', 'FUTURE BOY', 'Michael J. Fox and Nelle Fortenberry', 'The actor recalls simultaneously playing the roles of Alex P. Keaton in “Family Ties” and Marty McFly in “Back to the Future” in 1985.', 'https://static01.nyt.com/bestsellers/images/9781250866783.jpg'),
('9780306835841', 'VAGABOND', 'Tim Curry', 'The Tony Award-nominated actor gives insights into the creation of some of his roles on stage and screen.', 'https://static01.nyt.com/bestsellers/images/9780306835841.jpg'),
('9780593849583', 'SITTING WITH DOGS', 'Rocky Kanaka', 'The pet rescue advocate shares stories of dogs in need finding forever homes.', 'https://static01.nyt.com/bestsellers/images/9780593849583.jpg'),
('9781250374042', 'CONFRONTING EVIL', 'Bill O''Reilly and Josh Hammer', 'O''Reilly and Hammer profile some of history’s nefarious characters.', 'https://static01.nyt.com/bestsellers/images/9781250374042.jpg'),
('9780593655030', 'THE ANXIOUS GENERATION', 'Jonathan Haidt', 'A co-author of “The Coddling of the American Mind” looks at the mental health impacts that a phone-based life has on children.', 'https://static01.nyt.com/bestsellers/images/9780593655030.jpg'),
('9781400254682', 'BORN LUCKY', 'Leland Vittert with Don Yaeger', 'The NewsNation host describes how his father helped him navigate living with autism.', 'https://static01.nyt.com/bestsellers/images/9781400254682.jpg'),
('9780063417533', 'DOES ANYONE ELSE FEEL THIS WAY?', 'Eli Rallo', 'The social media content creator discusses difficulties people may encounter in their 20s.', 'https://static01.nyt.com/bestsellers/images/9780063417533.jpg'),
('9781324094647', 'THE GALES OF NOVEMBER', 'John U. Bacon', 'An account of the sinking of the Edmund Fitzgerald, an American Great Lakes freighter, 50 years ago.', 'https://static01.nyt.com/bestsellers/images/9781324094647.jpg');

INSERT INTO Bestsellers (listName, date, ISBN, listRank) VALUES
('Hardcover Nonfiction', '2025-10-18', '9781668205877', 1),
('Hardcover Nonfiction', '2025-10-18', '9780593296967', 2),
('Hardcover Nonfiction', '2025-10-18', '9780063428164', 3),
('Hardcover Nonfiction', '2025-10-18', '9781668211656', 4),
('Hardcover Nonfiction', '2025-10-18', '9781984862105', 5),
('Hardcover Nonfiction', '2025-10-18', '9781538775417', 6),
('Hardcover Nonfiction', '2025-10-18', '9780063489790', 7),
('Hardcover Nonfiction', '2025-10-18', '9781250866783', 8),
('Hardcover Nonfiction', '2025-10-18', '9780306835841', 9),
('Hardcover Nonfiction', '2025-10-18', '9780593849583', 10),
('Hardcover Nonfiction', '2025-10-18', '9781250374042', 11),
('Hardcover Nonfiction', '2025-10-18', '9780593655030', 12),
('Hardcover Nonfiction', '2025-10-18', '9781400254682', 13),
('Hardcover Nonfiction', '2025-10-18', '9780063417533', 14),
('Hardcover Nonfiction', '2025-10-18', '9781324094647', 15);

INSERT IGNORE INTO Books (ISBN, title, author, description, coverImage) VALUES
('9780593972700', 'ALCHEMISED', 'SenLinYu', 'After the war, an imprisoned alchemist is sent to a necromancer to recover her lost memories.', 'https://static01.nyt.com/bestsellers/images/9780593972700.jpg'),
('9780385546898', 'THE SECRET OF SECRETS', 'Dan Brown', 'As he searches for the missing noetic scientist he has been seeing, Robert Langdon discovers something regarding a secret project.', 'https://static01.nyt.com/bestsellers/images/9780385546898.jpg'),
('9781638932109', 'TOURIST SEASON', 'Brynne Weaver', 'When a true crime investigator comes to Cape Carnage in search of a serial killer, a local gardener and a handsome tourist pause their deadly ways.', 'https://static01.nyt.com/bestsellers/images/9781638932109.jpg'),
('9781963135411', 'THE PRIMAL OF BLOOD AND BONE', 'Jennifer L. Armentrout', 'The sixth book in the Blood and Ash series. The Blood Crown has fallen and the Primal of Death must be stopped.', 'https://static01.nyt.com/bestsellers/images/9781963135411.jpg'),
('9780316597234', 'ONE DARK WINDOW', 'Rachel Gillig', 'Elspeth Spindle and the treasonous nephew to the king seek to gather 12 Providence Cards before solstice.', 'https://static01.nyt.com/bestsellers/images/9780316597234.jpg'),
('9780316597227', 'TWO TWISTED CROWNS', 'Rachel Gillig', 'The second book in the Shepherd King series. Elspeth and Ravyn go on a quest to save the kingdom.', 'https://static01.nyt.com/bestsellers/images/9780316597227.jpg'),
('9798217190041', 'THIS INEVITABLE RUIN', 'Matt Dinniman', 'The seventh book in the Dungeon Crawler Carl series. After becoming fully self-aware, the NPCs join the Faction Wars.', 'https://static01.nyt.com/bestsellers/images/9798217190041.jpg'),
('9781538772775', 'CIRCLE OF DAYS', 'Ken Follett', 'A priestess envisions a great stone circle put together by divided tribes, but drought and violence may impede its creation.', 'https://static01.nyt.com/bestsellers/images/9781538772775.jpg'),
('9780316567855', 'THE ACADEMY', 'Elin Hilderbrand and Shelby Cunningham', 'Harmful rumors cause trouble for the students and staff at a New England boarding school.', 'https://static01.nyt.com/bestsellers/images/9780316567855.jpg'),
('9781464261169', 'ANATHEMA', 'Keri Lake', 'An assassin who dwells in Witch Knell is obsessed with Maevyth Bronwick, whose blood might help to break his curse.', 'https://static01.nyt.com/bestsellers/images/9781464261169.jpg'),
('9780593804728', 'WHAT WE CAN KNOW', 'Ian McEwan', 'In 2119, a scholar living in a world of rising seas after a nuclear catastrophe seeks clues about a missing poem written in 2014.', 'https://static01.nyt.com/bestsellers/images/9780593804728.jpg'),
('9780307700155', 'THE LONELINESS OF SONIA AND SUNNY', 'Kiran Desai', 'A novelist and a journalist, whose grandparents once tried to arrange their union, go on a search for happiness together.', 'https://static01.nyt.com/bestsellers/images/9780307700155.jpg'),
('9780063021471', 'KATABASIS', 'R.F. Kuang', 'A pair of rival graduate students descend into the underworld to save their late professor and secure his recommendation.', 'https://static01.nyt.com/bestsellers/images/9780063021471.jpg'),
('9780593595039', 'BUCKEYE', 'Patrick Ryan', 'Consequences created by a secret forged between members of two families in a small Ohio town affect a new generation.', 'https://static01.nyt.com/bestsellers/images/9780593595039.jpg'),
('9781668059869', 'WE LOVE YOU, BUNNY', 'Mona Awad', 'A debut novelist is kidnapped by her former frenemies, who recount their dark adventures.', 'https://static01.nyt.com/bestsellers/images/9781668059869.jpg');

INSERT INTO Bestsellers (listName, date, ISBN, listRank) VALUES
('Hardcover Fiction', '2025-09-27', '9780593972700', 1),
('Hardcover Fiction', '2025-09-27', '9780385546898', 2),
('Hardcover Fiction', '2025-09-27', '9781638932109', 3),
('Hardcover Fiction', '2025-09-27', '9781963135411', 4),
('Hardcover Fiction', '2025-09-27', '9780316597234', 5),
('Hardcover Fiction', '2025-09-27', '9780316597227', 6),
('Hardcover Fiction', '2025-09-27', '9798217190041', 7),
('Hardcover Fiction', '2025-09-27', '9781538772775', 8),
('Hardcover Fiction', '2025-09-27', '9780316567855', 9),
('Hardcover Fiction', '2025-09-27', '9781464261169', 10),
('Hardcover Fiction', '2025-09-27', '9780593804728', 11),
('Hardcover Fiction', '2025-09-27', '9780307700155', 12),
('Hardcover Fiction', '2025-09-27', '9780063021471', 13),
('Hardcover Fiction', '2025-09-27', '9780593595039', 14),
('Hardcover Fiction', '2025-09-27', '9781668059869', 15);

INSERT IGNORE INTO Books (ISBN, title, author, description, coverImage) VALUES
('9780593972700', 'ALCHEMISED', 'SenLinYu', 'After the war, an imprisoned alchemist is sent to a necromancer to recover her lost memories.', 'https://static01.nyt.com/bestsellers/images/9780593972700.jpg'),
('9781963135480', 'THE PRIMAL OF BLOOD AND BONE', 'Jennifer L. Armentrout', 'The sixth book in the Blood and Ash series. The Blood Crown has fallen and the Primal of Death must be stopped.', 'https://static01.nyt.com/bestsellers/images/9781963135480.jpg'),
('9780385546898', 'THE SECRET OF SECRETS', 'Dan Brown', 'As he searches for the missing noetic scientist he has been seeing, Robert Langdon discovers something regarding a secret project.', 'https://static01.nyt.com/bestsellers/images/9780385546898.jpg'),
('9781538772799', 'CIRCLE OF DAYS', 'Ken Follett', 'A priestess envisions a great stone circle put together by divided tribes, but drought and violence may impede its creation.', 'https://static01.nyt.com/bestsellers/images/9781538772799.jpg'),
('9781638932109', 'TOURIST SEASON', 'Brynne Weaver', 'When a true crime investigator comes to Cape Carnage in search of a serial killer, a local gardener and a handsome tourist pause their deadly ways.', 'https://static01.nyt.com/bestsellers/images/9781638932109.jpg'),
('9780316567855', 'THE ACADEMY', 'Elin Hilderbrand and Shelby Cunningham', 'Harmful rumors cause trouble for the students and staff at a New England boarding school.', 'https://static01.nyt.com/bestsellers/images/9780316567855.jpg'),
('9780316597234', 'ONE DARK WINDOW', 'Rachel Gillig', 'Elspeth Spindle and the treasonous nephew to the king seek to gather 12 Providence Cards before solstice.', 'https://static01.nyt.com/bestsellers/images/9780316597234.jpg'),
('9780316597227', 'TWO TWISTED CROWNS', 'Rachel Gillig', 'The second book in the Shepherd King series. Elspeth and Ravyn go on a quest to save the kingdom.', 'https://static01.nyt.com/bestsellers/images/9780316597227.jpg'),
('9798217190041', 'THIS INEVITABLE RUIN', 'Matt Dinniman', 'The seventh book in the Dungeon Crawler Carl series. After becoming fully self-aware, the NPCs join the Faction Wars.', 'https://static01.nyt.com/bestsellers/images/9798217190041.jpg'),
('9780593804728', 'WHAT WE CAN KNOW', 'Ian McEwan', 'In 2119, a scholar living in a world of rising seas after a nuclear catastrophe seeks clues about a missing poem written in 2014.', 'https://static01.nyt.com/bestsellers/images/9780593804728.jpg'),
('9780008728090', 'THE GINGERBREAD BAKERY', 'Laurie Gilmore', 'The fifth book in the Dream Harbor series. As a wedding approaches, a bakery owner and a bar owner get closer.', 'https://static01.nyt.com/bestsellers/images/9780008728090.jpg'),
('9780593493595', 'A SLOWLY DYING CAUSE', 'Elizabeth George', 'Detective Inspector Thomas Lynley and Detective Sergeant Barbara Havers are brought in to solve the mysterious death of a man found in his family''s tin and pewter workshop.', 'https://static01.nyt.com/bestsellers/images/9780593493595.jpg'),
('9781538742570', 'THE HOUSEMAID', 'Freida McFadden', 'Troubles surface when a woman looking to make a fresh start takes a job in the home of the Winchesters.', 'https://static01.nyt.com/bestsellers/images/9781538742570.jpg'),
('9780307700155', 'THE LONELINESS OF SONIA AND SUNNY', 'Kiran Desai', 'A novelist and a journalist, whose grandparents once tried to arrange their union, go on a search for happiness together.', 'https://static01.nyt.com/bestsellers/images/9780307700155.jpg'),
('9781464227301', 'THE SURROGATE MOTHER', 'Freida McFadden', 'Abby’s personal assistant, who offers to be her surrogate, also carries an unspeakable secret.', 'https://static01.nyt.com/bestsellers/images/9781464227301.jpg');

INSERT INTO Bestsellers (listName, date, ISBN, listRank) VALUES
('Combined Print & E-Book Fiction', '2025-09-27', '9780593972700', 1),
('Combined Print & E-Book Fiction', '2025-09-27', '9781963135480', 2),
('Combined Print & E-Book Fiction', '2025-09-27', '9780385546898', 3),
('Combined Print & E-Book Fiction', '2025-09-27', '9781538772799', 4),
('Combined Print & E-Book Fiction', '2025-09-27', '9781638932109', 5),
('Combined Print & E-Book Fiction', '2025-09-27', '9780316567855', 6),
('Combined Print & E-Book Fiction', '2025-09-27', '9780316597234', 7),
('Combined Print & E-Book Fiction', '2025-09-27', '9780316597227', 8),
('Combined Print & E-Book Fiction', '2025-09-27', '9798217190041', 9),
('Combined Print & E-Book Fiction', '2025-09-27', '9780593804728', 10),
('Combined Print & E-Book Fiction', '2025-09-27', '9780008728090', 11),
('Combined Print & E-Book Fiction', '2025-09-27', '9780593493595', 12),
('Combined Print & E-Book Fiction', '2025-09-27', '9781538742570', 13),
('Combined Print & E-Book Fiction', '2025-09-27', '9780307700155', 14),
('Combined Print & E-Book Fiction', '2025-09-27', '9781464227301', 15);

INSERT IGNORE INTO Books (ISBN, title, author, description, coverImage) VALUES
('9781668211656', '107 DAYS', 'Kamala Harris', 'The former vice president recounts her abbreviated campaign to become president in 2024.', 'https://static01.nyt.com/bestsellers/images/9781668211656.jpg'),
('9781984862105', 'POEMS & PRAYERS', 'Matthew McConaughey', 'The actor and author of “Greenlights” explores elements of belief and reason that make up our lives.', 'https://static01.nyt.com/bestsellers/images/9781984862105.jpg'),
('9781668083680', 'AWAKE', 'Jen Hatmaker', 'The host of the "For the Love" podcast describes major shifts in her life after her marriage of 26 years ended.', 'https://static01.nyt.com/bestsellers/images/9781668083680.jpg'),
('9781250374042', 'CONFRONTING EVIL', 'Bill O\'Reilly and Josh Hammer', 'O\'Reilly and Hammer profile some of history’s nefarious characters.', 'https://static01.nyt.com/bestsellers/images/9781250374042.jpg'),
('9780306836480', 'SOFTLY, AS I LEAVE YOU', 'Priscilla Beaulieu Presley with Mary Jane Ross', 'Presley recounts her tribulations and search for identity after spending a decade with Elvis.', 'https://static01.nyt.com/bestsellers/images/9780306836480.jpg'),
('9780593540985', 'ALL THE WAY TO THE RIVER', 'Elizabeth Gilbert', 'The author of “Eat, Pray, Love” depicts her journey through a cycle involving self-destructive tendencies.', 'https://static01.nyt.com/bestsellers/images/9780593540985.jpg'),
('9781668075289', 'THE BOOK OF SHEEN', 'Charlie Sheen', 'The actor, known for his roles in “Platoon” and “Two and a Half Men,” shares stories about his life in Hollywood.', 'https://static01.nyt.com/bestsellers/images/9781668075289.jpg'),
('9780593655030', 'THE ANXIOUS GENERATION', 'Jonathan Haidt', 'A co-author of “The Coddling of the American Mind” looks at the mental health impacts that a phone-based life has on children.', 'https://static01.nyt.com/bestsellers/images/9780593655030.jpg'),
('9780358439165', 'BLACK AF HISTORY', 'Michael Harriot', 'A columnist at TheGrio.com articulates moments in American history that center the perspectives and experiences of Black Americans.', 'https://static01.nyt.com/bestsellers/images/9780358439165.jpg'),
('9781668098998', 'HISTORY MATTERS', 'David McCullough', 'A posthumous collection of essays by the Pulitzer Prize-winning author on history’s impact on our present and our future; edited by Dorie McCullough Lawson and Michael Hill.', 'https://static01.nyt.com/bestsellers/images/9781668098998.jpg'),
('9780593850633', 'AGAINST THE MACHINE', 'Paul Kingsnorth', 'A warning about the potential ramifications of the technological-cultural matrix and suggestions on ways to push back.', 'https://static01.nyt.com/bestsellers/images/9780593850633.jpg'),
('9781668011577', 'WHEN EVERYONE KNOWS THAT EVERYONE KNOWS ...', 'Steven Pinker', 'The cognitive scientist and author of “Rationality” considers aspects of common knowledge.', 'https://static01.nyt.com/bestsellers/images/9781668011577.jpg'),
('9780593735671', 'SUCCESSFUL FAILURE', 'Kevin Fredericks', 'The N.A.A.C.P. Image Award–winning comedian describes setbacks and moments of embarrassment he encountered.', 'https://static01.nyt.com/bestsellers/images/9780593735671.jpg'),
('9781668065426', 'STORIES FROM A STRANGER', 'Hunter Prosper', 'An I.C.U. nurse brings together revealing stories told by an assortment of people.', 'https://static01.nyt.com/bestsellers/images/9781668065426.jpg'),
('9781631496080', 'WE THE PEOPLE', 'Jill Lepore', 'The author of “These Truths” examines the history of the U.S. Constitution and challenges its interpretation by the Supreme Court and the theory of originalism.', 'https://static01.nyt.com/bestsellers/images/9781631496080.jpg');

INSERT INTO Bestsellers (listName, date, ISBN, listRank) VALUES
('Hardcover Nonfiction', '2025-09-27', '9781668211656', 1),
('Hardcover Nonfiction', '2025-09-27', '9781984862105', 2),
('Hardcover Nonfiction', '2025-09-27', '9781668083680', 3),
('Hardcover Nonfiction', '2025-09-27', '9781250374042', 4),
('Hardcover Nonfiction', '2025-09-27', '9780306836480', 5),
('Hardcover Nonfiction', '2025-09-27', '9780593540985', 6),
('Hardcover Nonfiction', '2025-09-27', '9781668075289', 7),
('Hardcover Nonfiction', '2025-09-27', '9780593655030', 8),
('Hardcover Nonfiction', '2025-09-27', '9780358439165', 9),
('Hardcover Nonfiction', '2025-09-27', '9781668098998', 10),
('Hardcover Nonfiction', '2025-09-27', '9780593850633', 11),
('Hardcover Nonfiction', '2025-09-27', '9781668011577', 12),
('Hardcover Nonfiction', '2025-09-27', '9780593735671', 13),
('Hardcover Nonfiction', '2025-09-27', '9781668065426', 14),
('Hardcover Nonfiction', '2025-09-27', '9781631496080', 15);
