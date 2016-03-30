
SET DATEFORMAT MDY;		--set the date format to  yyyy-mm-dd

-- list of users that has signed up
CREATE TABLE users(
	email VARCHAR(64) PRIMARY KEY,			-- uniquely identify the owner
	password VARCHAR(16),					-- to login into the account
	name VARCHAR(32),						-- name of the user, no need for first and last name -> they are not useful?
	phone VARCHAR(16),						-- contact no. for owners and borrowers to contact each other
	bidPoints INT,							-- amount of available bid points for the user to bid for items
	administrator VARCHAR(5) CHECK(administrator = 'TRUE' OR administrator = 'FALSE')	-- whether the user is an administrator
);

-- list of items that users have shared
CREATE TABLE item(	
	itemId CHAR(16) PRIMARY KEY,				-- uniquely identify the item
	owner VARCHAR(64) REFERENCES users(email),	-- owner of the item
	name VARCHAR(32),							-- name of item
	description VARCHAR(512),					-- short description of the item
	category VARCHAR(32),						-- category of item, should be listed in dropdown list
	location VARCHAR(128),						-- location where the owner wants to meet the borrower
	startBidpt INT,								-- starting bid points for the item, decided by owner
	available VARCHAR(5) CHECK(available = 'TRUE' OR available = 'FALSE')		-- if 'TRUE' then the item will be displayed for bidding, else only the owner can see his item. Become 'TRUE' when owner choose to share the item
);

-- table to keep track of bids 
CREATE TABLE bid(
	owner VARCHAR(64),							-- owner, [email]
	bidder VARCHAR(64) REFERENCES users(email),	-- bidder, [email]
	item CHAR(16) REFERENCES item(itemId),		-- id of the item, [itemId]
	bidpts INT,									-- amount of points bidder willing to pay [integer]
	startDate DATE,								-- starting date that the bidder want to start borrowing [yyyy-mm-dd]
	endDate DATE,								-- starting date that the bidder want to return [yyyy-mm-dd]
	PRIMARY KEY(owner, bidder, item)
);

-- list of paired owners and borrowers, and the status of the item sharing
CREATE TABLE borrow(
	owner VARCHAR(64),								-- owner of the item [email]
	borrower VARCHAR(64) REFERENCES users(email),	-- borrower [email]
	item CHAR(16) REFERENCES item(itemId),			-- id of item [itemId]
	location VARCHAR(128),							-- location of where to pass the item [address]
	meetTime TIME,									-- time to pass the item
	startDate DATE,									-- start date of the item shared
	endDate DATE,									-- date when the item have to be returned
	status VARCHAR(8) CHECK(status = 'pending' OR	-- check the status of the of the item sharing
							status = 'borrowed' OR
							status = 'returned' OR
							status = 'overdue' ),

	PRIMARY KEY (borrower, owner, item, startDate)
);



---------------------------------------------------------------------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------------------------------------------

-- basic table query
DROP TABLE users;		-- delete whole table
DELETE FROM users;		-- delete all entries
SELECT * FROM users;

DROP TABLE item;
DELETE FROM item;
SELECT * FROM item;

DROP TABLE bid;
DELETE FROM bid;
SELECT * FROM bid;

DROP TABLE borrow;
DELETE FROM borrow;
SELECT * FROM borrow;




---------------------------------------------------------------------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------------------------------------------

-- Example application:

-- 1: users register for an account
		INSERT INTO users VALUES ('zhanghong2011@msn.com','FSNFLDNF','ZHANG HONG','79746729', 10000, 'FALSE');
		INSERT INTO users VALUES ('gohhuiying1989@gmail.com','dfhaJDdf','GOH HUI YING','56839374', 10000, 'FALSE');
		INSERT INTO users VALUES ('fanghan2011@hotmail.com','684HFKDD','FANG HAN','65847394', 10000, 'FALSE');
		INSERT INTO users VALUES ('dingkuanchong2010@msn.com','asdfhjkl','DING KUAN CHONG','64937594', 10000, 'FALSE');
		INSERT INTO users VALUES ('ngoogekping1990@hotmail.com','HINldjfi','NGOO GEK PING','64956303', 10000, 'FALSE');
		INSERT INTO users VALUES ('zhengzhemin1991@yahoo.com','HE#HInd8','ZHENG ZHEMIN','69447394', 10000, 'FALSE');
		INSERT INTO users VALUES ('liuzhanpeng2011@msn.com','DO!L994d','LIU ZHANPENG','97364836', 10000, 'FALSE');


		SELECT * FROM users;	-- check






-- 2: users add an item to share
		-----------------------
		-- Before inserting value:
		-- 1) generate item ID: 16 char/digit, (0 to 9 to a to z to A to Z) --> can it be done in php?
		 -----------------------
		INSERT INTO item VALUES ('A000000000000000', 'zhanghong2011@msn.com', 'Shaver','Brand new shaver, never used before','HEALTH', 'Hillview Avenue', '300', 'TRUE');
		INSERT INTO item VALUES ('A000000000000001', 'zhanghong2011@msn.com', 'DVD player','Panesonic DVD player, works on HDMI','MEDIA','Hillview Avenue','800', 'TRUE');
		INSERT INTO item VALUES ('A000000000000002', 'zhanghong2011@msn.com', 'Sony Microwave Oven','New, clean and safe to use','KITCHEN WARE','Hillview Avenue','500', 'TRUE');
		INSERT INTO item VALUES ('A000000000000003', 'gohhuiying1989@gmail.com', 'Slicke electric shaver','Brand new shaver for the best shaver experience','HEALTH','6th Avenue','360', 'TRUE');
		INSERT INTO item VALUES ('A000000000000004', 'gohhuiying1989@gmail.com', 'BBQ pit','Black BBQ pit, portable and clean','OUTDOOR LIFESTYLE','6th Avenue','300', 'TRUE');
		INSERT INTO item VALUES ('A000000000000005', 'gohhuiying1989@gmail.com', 'Frying Pan','Stainless steel, non-sticky','KITCHEN WARE','6th Avenue','300', 'TRUE');
		INSERT INTO item VALUES ('A000000000000006', 'fanghan2011@hotmail.com', 'Thermal oven','Electric thermal-coil oven. Great for baking cakes!','KITCHEN WARE','Bedok','500', 'TRUE');
		INSERT INTO item VALUES ('A000000000000007', 'fanghan2011@hotmail.com', 'Panosonic Air fryer','Perfect for a healthier lifetyle without oil','KITCHEN WARE','Bedok','800', 'TRUE');
		INSERT INTO item VALUES ('A000000000000008', 'dingkuanchong2010@msn.com', 'Baseball set','A set of baseball equipments: 10 balls, 3 bats, 10 gloves and 2 helmets','SPORTS','Jurong East','900', 'TRUE');
		INSERT INTO item VALUES ('A000000000000009', 'dingkuanchong2010@msn.com', 'Mahjong set','Mahjong with table','RECREATIONAL','Jurong East','450', 'TRUE');
		INSERT INTO item VALUES ('A00000000000000a', 'dingkuanchong2010@msn.com', 'Ping pong equipments','4 ping pong bats in prestige condition and a tube of 10 ping pong balls','SPORTS','Jurong East','200', 'TRUE');
		INSERT INTO item VALUES ('A00000000000000b', 'ngoogekping1990@hotmail.com','Ultimate frisbee disk','Frisbee disk in good condition, used less than twice','SPORTS','Clementi','300', 'TRUE');
		INSERT INTO item VALUES ('A00000000000000c', 'ngoogekping1990@hotmail.com','Flying Kites','Plastic flying kites, for the ultimate kite flying experience','OUTDOOR LIFESTYLE','Clementi','300', 'TRUE');
		INSERT INTO item VALUES ('A00000000000000d', 'ngoogekping1990@hotmail.com','Buffet food trays','Buffet style food trays, made from stainless steel. Capacity of 10000ml.','KITCHEN WARE','Clementi','300', 'TRUE');


		SELECT * FROM item;		-- check






-- 3: Some users may start bidding. Bid points will be deducted.
		-----------------------
		-- bidding process
		-- 1) users will bid
		-- 2) owners will enter to choose who he/she wants to share
		-- 3) upon choosing the borrower, appropriate values will be inserted into the borrow table
		-- 4) delete losing bids entries by startDate? Since different users may want to borrow at different periods of time, 
		--	  we cannot simply delete away bidders who were not choosen
		-----------------------
		-----------------------
		-- Before inserting value:
		-- 1) find who are the owner and borrower
		-- 2) ensure owner != borrower
		-- 3) bidPoints > item(startingBidPoint)
		-- 4) check startDate < endDate
		-----------------------
		INSERT INTO bid VALUES ('fanghan2011@hotmail.com', 'ngoogekping1990@hotmail.com', 'A000000000000007', '810', '20160310','20160316');		
		INSERT INTO bid VALUES ('fanghan2011@hotmail.com', 'liuzhanpeng2011@msn.com', 'A000000000000007', '810', '20160301','20160309');	
		INSERT INTO bid VALUES ('fanghan2011@hotmail.com', 'zhengzhemin1991@yahoo.com', 'A000000000000007', '810', '20160314','20160317');	

		SELECT * FROM bid;	--check

		-- 3.1: deduct bidpoints [810]:
				UPDATE users
				SET bidPoints= (SELECT bidPoints FROM users WHERE email = 'ngoogekping1990@hotmail.com') - 810
				WHERE email = 'ngoogekping1990@hotmail.com';

				UPDATE users
				SET bidPoints= (SELECT bidPoints FROM users WHERE email = 'liuzhanpeng2011@msn.com') - 810
				WHERE email = 'liuzhanpeng2011@msn.com';

				UPDATE users
				SET bidPoints= (SELECT bidPoints FROM users WHERE email = 'zhengzhemin1991@yahoo.com') - 810
				WHERE email = 'zhengzhemin1991@yahoo.com';

				SELECT * FROM users;	-- check






-- 4: Owner will login and choose which bidder to share his/her item. He/she will receive some bid points
		-----------------------
		-- Before inserting value:
		-- 1) this query is activated only when user has selected the winning bidder
		-- 2) copy the owner, borrower, itemId, location, startdate, endDate from the bid table
		-- 3) by default, immediately after the owner choose the borrower, borrow.status = 'pending'
		-----------------------
		INSERT INTO borrow VALUES ('fanghan2011@hotmail.com', 'zhengzhemin1991@yahoo.com', 'A000000000000007', 'Bedok', '12:00', '20160314','20160317', 'pending');

		SELECT * FROM borrow;


		-- 4.1: item availability will be set to 'false'
				UPDATE item
				SET available = 'FALSE'
				WHERE itemId = 'A000000000000007';


				SELECT * FROM item;
				 
		-- 4.2: owner will receive the bidpoints:
				UPDATE users
				SET bidPoints= (SELECT bidPoints FROM users WHERE email = 'fanghan2011@hotmail.com') + 810
				WHERE email = 'fanghan2011@hotmail.com';

				SELECT * FROM users;	--check

		-- 4.3: bidder who are not choosen AND the startDate is already over will be deleted. Points return to them.
				UPDATE users
				SET bidPoints= (SELECT bidPoints FROM users WHERE email = 'ngoogekping1990@hotmail.com') + 810
				WHERE email = 'ngoogekping1990@hotmail.com';

				SELECT * FROM users;	-- check

				DELETE FROM	bid
				WHERE owner = 'fanghan2011@hotmail.com' AND bidder = 'ngoogekping1990@hotmail.com' AND item = 'A000000000000007';

				SELECT * FROM bid;



-- 5: when the borrower received the item, he/she will need to update the item sharing status.
		UPDATE borrow
		SET status = 'borrowed'
		WHERE borrower = 'zhengzhemin1991@yahoo.com' AND
			  owner = 'fanghan2011@hotmail.com'	AND
			  item = 'A000000000000007' AND
			  startDate = '20160314';


		SELECT * FROM borrow;






-- 6: when the item has been returned, the owner will need to update the item sharing status. 
		UPDATE borrow
		SET status = 'returned'
		WHERE borrower = 'zhengzhemin1991@yahoo.com' AND
			  owner = 'fanghan2011@hotmail.com'	AND
			  item = 'A000000000000007' AND
			  startDate = '20160314';


		SELECT * FROM borrow;	-- check







-- 7: if the item has not been returned, the owner will need to update the item sharing status
		UPDATE borrow
		SET status = 'overdue'
		WHERE borrower = 'zhengzhemin1991@yahoo.com' AND
			  owner = 'fanghan2011@hotmail.com'	AND
			  item = 'A000000000000007' AND
			  startDate = '20160314';


		SELECT * FROM borrow;	-- check

		-- *** should we block the borrower temporary from borrowing stuffs as a penalty?





-- 8: after item has been returned, the availability of item will remain as false, until user choose to share it again.






---------------------------------------------------------------------------------------------------------------------------
-- Some useful queries

-- For owners who wants to see what items they have:
SELECT i.name, i.description, i.category, i.startBidpt, i.available
FROM ITEM i
WHERE i.owner = 'zhanghong2011@msn.com';


-- For users who are searching for a particular category of items:
SELECT i.name, i.description, i.category, i.startBidpt
FROM ITEM i
WHERE i.category = 'KITCHEN WARE';


-- Notification of the time and location to pick up the item


