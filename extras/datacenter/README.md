# How to Create an AirPi Datacenter

Do you have several AirPi stations? Do you want to collect all the data
into a **single database** and access them from a **single web server**?

* Get an Apache + PHP + Database host. The PHP code uses the MDB2 library.
* Create a database and initialize it with the required tables and indexes.
* Populate the "stations" table with name, login and password for each
station.
* Install the update.php script into the datacenter host; it will receive
data from the AirPi stations.
* On each station configure the HTTP_REQUEST_URL, login and password.

## Asyncronous and Incremental Data Sumbission

Each station is not required to be on-line 24h/24h to submit data.
To make an example: you can give internet connection to a station only once
a day, by using a WiFi adapter and a mobile phone with tethering enabled.
When the station tries to upload its data to the datacenter (default
is every 5 minutes) it will flush all the data measured an stored locally.

## Files

* **update.php** This is the script which will collect the data from the
stations. The **db_dsn.php** snippet contains the database credentials.
The same script is called by the station to know how much of past data 
is to be uploaded. The station must point its configuration parameter
**HTTP_REQUEST_URL** to the URL for this script; e.g. https://host/update.php.
* **db-initialize-pgsql.sql** Use this SQL script to create the tables
and the indexes into the database. This is for a PostgreSQL database, you
should adapt for other engines, like MySQL, etc.
