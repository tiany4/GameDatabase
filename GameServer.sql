-- drop stuff if any
drop table accounts;
drop table gamemodes;
drop table esrb;
drop table pegi;
drop table genres;
drop table characters;
drop table developers;
drop table publishers;
drop table mediareviews;
drop table userreviews;
drop table gamestats;
drop table games;
drop sequence game_sequence;
drop sequence dev_sequence;
drop sequence pub_sequence;
drop sequence stat_sequence;
drop sequence review_sequence;
-- end drop

create table Accounts (
    username VARCHAR(20),
    passwordhash VARCHAR(255) not null,
    accounttype NUMBER(1) default 0 not null CONSTRAINT account_type_check CHECK (accounttype IN (0,1)),
    ban NUMBER(1) default 0 CONSTRAINT account_ban_check CHECK (ban IN (0,1)),
    primary key (username)
);

create table Games (
    gameid  INTEGER primary key,
    title   VARCHAR(100) not null,
    releasedate DATE,
    series VARCHAR(100),
    engine VARCHAR(100),
    gamemode NUMBER(1),
    esrb NUMBER(1),
    pegi NUMBER(1),
    check (esrb in (0, 1, 2, 3, 4, 5, 6)),
    check (pegi in (0, 1, 2, 3, 4, 5)) 
);

create table GameModes (
    gamemode NUMBER(1),
    gamemodename varchar(22),
    primary key (gamemode),
    check (gamemodename in ('Single Player', 'Multiplayer', 'Single and Multiplayer'))
);

create table esrb (
    esrb NUMBER(1) primary key,
    esrbname varchar(20)
);

create table pegi (
    pegi NUMBER(1) primary key, 
    peginame varchar(20)
);

create table Genres (
    gameid INTEGER,
    genres VARCHAR(50),
    primary key (gameid, genres), 
    foreign key (gameid) references Games
);

create table Characters (
    gameid INTEGER,
    charactername VARCHAR(50),
    primary key (gameid, charactername),
    foreign key (gameid) references Games
);

create table Developers (
    developerid INTEGER,
    gameid INTEGER,
    developername VARCHAR(30) unique not null, 
    designer VARCHAR(30),
    producer VARCHAR(30),
    artist VARCHAR(30),
    composer VARCHAR(30),
    writer VARCHAR(30),
    primary key (gameid, developerid), 
    foreign key (gameid) references Games
);

create table Publishers (
    gameid INTEGER unique,
    publisherid INTEGER,
    publishername VARCHAR(30) not null, 
    primary key (gameid, publisherid), 
    foreign key (gameid) references Games
);

create table Mediareviews (
    reviewid INTEGER,
    gameid INTEGER,
    url VARCHAR(200),
    primary key (reviewid), 
    foreign key (gameid) references Games
);

create table Userreviews (
    reviewid INTEGER,
    username VARCHAR(20),
    gameid INTEGER,
    reviewtext VARCHAR(1000), 
    primary key (reviewid), 
    foreign key (gameid) references Games,
    foreign key (username) references accounts
);

create table Gamestats (
    gamestatsid INTEGER,
    gameid INTEGER,
    gamelength INTEGER,
    complexity INTEGER,
    difficulty INTEGER,
    primary key (gamestatsid), 
    foreign key (gameid) references Games,
    check(complexity in (1, 2, 3, 4, 5, 6, 7, 8, 9, 10)),
    check(difficulty in (1, 2, 3, 4, 5, 6, 7, 8, 9, 10)),
    check(gamelength in (1, 2, 3, 4, 5, 6, 7, 8, 9, 10))
);

-- Sequences and Triggers
ALTER SESSION SET PLSCOPE_SETTINGS = 'IDENTIFIERS:NONE';
CREATE SEQUENCE GAME_SEQUENCE START WITH 1;
CREATE OR REPLACE TRIGGER add_game
BEFORE INSERT ON GAMES
FOR EACH ROW
BEGIN
SELECT GAME_SEQUENCE.NEXTVAL
INTO :NEW.GAMEID
FROM dual;
END;
/

CREATE OR REPLACE TRIGGER add_genre
BEFORE INSERT ON GENRES
FOR EACH ROW
BEGIN
SELECT GAME_SEQUENCE.CURRVAL
INTO :NEW.GAMEID
FROM dual;
END;
/

CREATE OR REPLACE TRIGGER add_character
BEFORE INSERT ON CHARACTERS
FOR EACH ROW
BEGIN
SELECT GAME_SEQUENCE.CURRVAL
INTO :NEW.GAMEID
FROM dual;
END;
/

CREATE OR REPLACE TRIGGER delete_child
BEFORE DELETE ON GAMES
FOR EACH ROW
BEGIN 
DELETE USERREVIEWS WHERE GAMEID = :OLD.GAMEID;
DELETE GAMESTATS WHERE GAMEID = :OLD.GAMEID;
DELETE PUBLISHERS WHERE GAMEID = :OLD.GAMEID;
DELETE MEDIAREVIEWS WHERE GAMEID = :OLD.GAMEID;
DELETE CHARACTERS WHERE GAMEID = :OLD.GAMEID;
DELETE GENRES WHERE GAMEID = :OLD.GAMEID;
DELETE DEVELOPERS WHERE GAMEID = :OLD.GAMEID;
DELETE GAMESTATS WHERE GAMEID = :OLD.GAMEID;
END;
/

CREATE SEQUENCE DEV_SEQUENCE START WITH 1;
CREATE OR REPLACE TRIGGER add_dev
BEFORE INSERT ON developers
FOR EACH ROW
BEGIN
SELECT DEV_SEQUENCE.NEXTVAL
INTO :NEW.DEVELOPERID
FROM dual;
END;
/

CREATE SEQUENCE PUB_SEQUENCE START WITH 1;
CREATE OR REPLACE TRIGGER add_PUB
BEFORE INSERT ON PUBLISHERS
FOR EACH ROW
BEGIN
SELECT PUB_SEQUENCE.NEXTVAL
INTO :NEW.PUBLISHERID
FROM dual;
END;
/

CREATE SEQUENCE REVIEW_SEQUENCE START WITH 1;
CREATE OR REPLACE TRIGGER add_MEDIAREVIEWS
BEFORE INSERT ON MEDIAREVIEWS
FOR EACH ROW
BEGIN
SELECT REVIEW_SEQUENCE.NEXTVAL
INTO :NEW.REVIEWID
FROM dual;
END;
/

CREATE OR REPLACE TRIGGER add_USERREVIEWS
BEFORE INSERT ON USERREVIEWS
FOR EACH ROW
BEGIN
SELECT REVIEW_SEQUENCE.NEXTVAL
INTO :NEW.REVIEWID
FROM dual;
END;
/

CREATE SEQUENCE STAT_SEQUENCE START WITH 1;
CREATE OR REPLACE TRIGGER add_GAMESTATS
BEFORE INSERT ON GAMESTATS
FOR EACH ROW
BEGIN
SELECT STAT_SEQUENCE.NEXTVAL
INTO :NEW.GAMESTATSID
FROM dual;
END;
/

--Build Tables
insert into esrb(esrb, esrbname)
values(0, 'Does not exist');
insert into esrb(esrb, esrbname)
values(1, 'Early Childhood');
insert  into esrb(esrb, esrbname)
values(2, 'Everyone');
insert into esrb(esrb, esrbname)
values(3, 'Everyone 10+');
insert  into esrb(esrb, esrbname)
values(4, 'Teen');
insert into esrb(esrb, esrbname)
values(5, 'Mature');
insert  into esrb(esrb, esrbname)
values(6, 'Adults Only');
ALTER TABLE esrb READ ONLY;

insert into pegi(pegi, peginame)
values(0, 'Does not exist');
insert into pegi(pegi, peginame)
values(1, 'PEGI 3');
insert  into pegi(pegi, peginame)
values(2, 'PEGI 7');
insert into pegi(pegi, peginame)
values(3, 'PEGI 12');
insert  into pegi(pegi, peginame)
values(4, 'PEGI 16');
insert into pegi(pegi, peginame)
values(5, 'PEGI 18');
ALTER TABLE pegi READ ONLY;

insert into gamemodes
values (1, 'Single Player');
insert into gamemodes
values (2, 'Multiplayer');
insert into gamemodes
values (3, 'Single and Multiplayer');
ALTER TABLE gamemodes READ ONLY;

-- create views
CREATE OR REPLACE FORCE VIEW MAIN_GAMES_VIEW ("ID", "Game Title", "Release Date", "Game Mode", "ESRB Rating", "PEGI Rating") AS 
SELECT gameid AS "Game ID", title AS "Game Title", releasedate AS "Release Date", 
    gm.gamemodename AS "Game Mode", e.esrbname AS "ESRB Rating", p.peginame AS "PEGI Rating" 
FROM games g join esrb e on g.esrb = e.esrb
    join pegi p on g.pegi = p.pegi
    join gamemodes gm on g.gamemode = gm.gamemode
ORDER BY title ASC;

CREATE OR REPLACE FORCE VIEW MAIN_DEV_VIEW (developerid, gameid, "Developer Name", "Designer", "Producer", "Artist", "Composer", "Writer") AS
SELECT * FROM DEVELOPERS;

-- starcraft data entry
insert into GAMES(title,releasedate,series,engine,gamemode,esrb,pegi) 
values ('StarCraft',date '1998-3-31','StarCraft','Warcraft II Engine',3,2,3);

insert into PUBLISHERS(gameid, publishername)
values(1, 'Blizzard');

insert into DEVELOPERS(gameid, developername, designer, producer, artist,
composer, writer)
values(1, 'Blizzard', 'Chris Metzen', 'James Phinney', 'Samwise Didier', 
'Glenn Stafford', 'Chris Metzen');

insert into MEDIAREVIEWS(gameid, url)
values(1, 'http://www.ign.com/articles/2000/06/03/starcraft-2');

insert into USERREVIEWS(gameid, reviewtext)
values(1, 'Awesome game! Super addicting.');

insert into GAMESTATS(gameid, gamelength, complexity, difficulty)
values(1, 10, 8, 6);

insert into GENRES(genres)
values('Real-time strategy');

insert into CHARACTERS(gameid, charactername)
values(1, 'Arcturus Mengsk');
insert into CHARACTERS(gameid, charactername)
values(1, 'Sarah Kerrigan');
insert into CHARACTERS(gameid, charactername)
values(1, 'Jim Raynor');

-- End starcraft data

--Deadly premonition data entry
insert into GAMES(title,releasedate,series,engine,gamemode,esrb,pegi) 
values ('Deadly Premonition',date '2013-3-21','','',1,5,5);

insert into PUBLISHERS(gameid, publishername)
values(2, 'Rising Star Games');

insert into DEVELOPERS(gameid, developername, designer, producer, artist,
composer, writer)
values(2, 'Access Games', 'Hidetaka Suehiro', 'Tomio Kanazawa', 'Takuya Kobayashi', 
'Riyou Kinugasa', 'Hidetaka Suehiro');

insert into MEDIAREVIEWS(gameid, url)
values(2, 'https://www.destructoid.com/review-deadly-premonition-165168.phtml');

insert into USERREVIEWS(gameid, reviewtext)
values(2, 'pure cancer');

insert into GAMESTATS(gameid, gamelength, complexity, difficulty)
values(2, 4, 6, 4);

insert into GENRES(genres)
values('Survivior Horror');

insert into CHARACTERS(gameid, charactername)
values(2, 'Francis York Morgan');

-- End Deadly Premonition Data

-- Horizon Zero Dawn Data
insert into GAMES(title,releasedate,series,engine,gamemode,esrb,pegi) 
values ('Horizon Zero Dawn',date '2017-2-28', '','Decima',1,4,4);

insert into PUBLISHERS(gameid, publishername)
values(3, 'Sony Entertainment');

insert into DEVELOPERS(gameid, developername, designer, producer, artist,
composer, writer)
values(3, 'Guerrilla Games', 'Mathijs de Jonge', 'Lambert Muller', 
'Jan-Bart van Beek', 'Joris de Man', 'John Gonzalez');

insert into MEDIAREVIEWS(gameid, url)
values(3, 'https://www.gamespot.com/reviews/horizon-zero-dawn-review/1900-6416620/');

insert into USERREVIEWS(gameid, reviewtext)
values(3, 'Beautiful game with creative gameplay.');

insert into GAMESTATS(gameid, gamelength, complexity, difficulty)
values(3, 5, 8, 6);
insert into GAMESTATS(gameid, gamelength, complexity, difficulty)
values(3, 6, 8, 7);

insert into GENRES(genres)
values('Adventure');

insert into CHARACTERS(gameid, charactername)
values(3, 'Aloy');

insert into CHARACTERS(gameid, charactername)
values(3, 'Rost');

-- Halo 2
insert into GAMES(title,releasedate,series,engine,gamemode,esrb,pegi) 
values ('Halo 2',date '2011-11-09', 'Halo','Havok physics',2,5,5);

insert into PUBLISHERS(gameid, publishername)
values(4, 'Microsoft');

insert into DEVELOPERS(gameid, developername, designer, producer, artist,
composer, writer)
values(4, 'Bungie', 'Jason Jones', 'Michael Salvatori', 
'Marcus Lehto', 'Martin ODonnell', 'Joseph Staten');

insert into MEDIAREVIEWS(gameid, url)
values(4, 'http://www.ign.com/articles/2007/06/06/halo-2-review');

insert into USERREVIEWS(gameid, reviewtext)
values(4, 'Repeditive campaign but awesome multiplayer modes.');

insert into GAMESTATS(gameid, gamelength, complexity, difficulty)
values(4, 7, 8, 6);
insert into GAMESTATS(gameid, gamelength, complexity, difficulty)
values(4, 5, 8, 7);

insert into GENRES(genres)
values('FPS');

insert into CHARACTERS(gameid, charactername)
values(4, 'Master Chief');

insert into CHARACTERS(gameid, charactername)
values(4, 'Cortana');

-- End Halo2 

-- Wolfenstain II 
insert into GAMES(title,releasedate,series,engine,gamemode,esrb,pegi) 
values ('Wolfenstein II',date '2017-10-27', 'Wolfenstein','ID tech 6',2,5,5);

insert into PUBLISHERS(gameid, publishername)
values(5, '	Bethesda');

insert into DEVELOPERS(gameid, developername, designer, producer, artist,
composer, writer)
values(5, 'MachineGames', 'Fredrik Ljungdahl', 'Jerk Gustafsson', 
'Axel Torvenius', 'Mick Gordon', 'Martin Andersen');

insert into MEDIAREVIEWS(gameid, url)
values(5, 'http://www.ign.com/articles/2017/10/28/wolfenstein-2-the-new-colossus-review');

insert into USERREVIEWS(gameid, reviewtext)
values(5, 'Increadible game.');

insert into GAMESTATS(gameid, gamelength, complexity, difficulty)
values(5, 5, 8, 6);
insert into GAMESTATS(gameid, gamelength, complexity, difficulty)
values(5, 9, 8, 7);

insert into GENRES(genres)
values('FPS');

insert into CHARACTERS(gameid, charactername)
values(5, 'Blazkowicz');

-- End Wolfenstein II

-- Quake III Arena
insert into GAMES(title,releasedate,series,engine,gamemode,esrb,pegi) 
values ('Quake III Arena',date '1999-12-02', 'Quake','ID tech 3',2,5,0);

insert into PUBLISHERS(gameid, publishername)
values(6, '	Bethesda');

insert into DEVELOPERS(gameid, developername, designer, producer, artist,
composer, writer)
values(6, 'id Software', 'Graeme Devine', '', 
'Adrian Carmack', 'Sonic Mayhem', '');

insert into MEDIAREVIEWS(gameid, url)
values(6, 'http://www.eurogamer.net/articles/q3a2');

insert into USERREVIEWS(gameid, reviewtext)
values(6, 'I wasted my life on this');
insert into USERREVIEWS(gameid, reviewtext)
values(6, 'This game is too fast for me');
insert into USERREVIEWS(gameid, reviewtext)
values(6, 'What?');


insert into GAMESTATS(gameid, gamelength, complexity, difficulty)
values(6, 2, 8, 6);
insert into GAMESTATS(gameid, gamelength, complexity, difficulty)
values(6, 10, 8, 7);

insert into GENRES(genres)
values('FPS');

insert into CHARACTERS(gameid, charactername)
values(6, 'Ranger');
insert into CHARACTERS(gameid, charactername)
values(6, 'Xaero');

-- End Wolfenstein II