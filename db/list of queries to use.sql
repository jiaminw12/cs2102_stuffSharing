/**
 * Some useful queries
 **/ 

/*******************************************************************************/
--> Login check: 1 if email and password exist, else 0
SELECT CAST(1 AS BIT) AS valid_login
FROM users u
WHERE u.email = 'zhanghong2011@msn.com' AND
	  u.password = 'FSNFLDNF';

/*******************************************************************************/
--> Search item by name
/*******************************************************************************/
SELECT i.name, i.description, i.category, i.startBidpt, u.name, i.location
FROM item i, users u
WHERE i.name LIKE '%shaver%' AND
	  i.owner = u.email;

/*******************************************************************************/
--> Search item by category
/*******************************************************************************/
SELECT i.name, i.description, i.category, i.startBidpt, u.name, i.location
FROM item i, users u
WHERE i.category = 'SPORTS' AND
	  i.owner = u.email;

/*******************************************************************************/
--> Search for user
/*******************************************************************************/
SELECT u.name
FROM users u
WHERE u.name LIKE '%zh%';

/*******************************************************************************/
--> check if bidder have enough bid points
/*******************************************************************************/



/*******************************************************************************/
--> For owners who wants to see what items they have:
/*******************************************************************************/
SELECT i.name, i.description, i.category, i.startBidpt, i.available
FROM ITEM i
WHERE i.owner = 'zhanghong2011@msn.com';


/*******************************************************************************/
--> Notification of the time and location to pick up the item
/*******************************************************************************/
SELECT b.location, b.meetTime
FROM borrow b
WHERE b.owner = 'fanghan2011@hotmail.com' AND
	  b.borrower = 'zhengzhemin1991@yahoo.com' AND
	  b.item = 'A000000000000007' AND 
	  b.startDate = '20160314';

