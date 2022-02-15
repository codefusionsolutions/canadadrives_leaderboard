
Steps to run application:

1) Run all the queries located in the db.sql file to populate sample database for API application.
2) Run a local development environment to enable a localhost on your machine and ensure that Apache & MySQL servers are running.
An example environment would be to run XAMPP or MAMP, place the leaderboard folder and all of its contents into the htdocs folder.
3) To test out each end point using the Postman application, please view the following:

a) To retrieve the leaderboard of users, make a POST call to http://localhost/leaderboard/api/user/leaderboard.php.

b) To create a new user, make a POST call to http://localhost/leaderboard/api/user/create.php with the following raw Body 
payload as a sample:
{
	"name": "Rod",
	"age": "35",
	"street": "231 River Ave",
	"city": "Windsor",
	"state": "Ontario",
	"country": "Canada",
	"zip": "N1F5P7"
}

c) To delete a user, make a POST call to http://localhost/leaderboard/api/user/delete.php with the following raw Body payload
as a sample:
{
	"id": 1
}

d) To view a suer, make a GET call to http://localhost/leaderboard/api/user/get_user.php?id=3, where id is the user to read.

e) To update the points of a user, make a POST call to http://localhost/leaderboard/api/user/update_points.php with the following
raw Body payload as a sample:
{
	"id": 5,
	"action": "increment"
}
