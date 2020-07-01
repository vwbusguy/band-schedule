band-schedule
=============

**This project is no longer being maintained.  Consider [Planning Center](https://github.com/planningcenter) as an alternative.**

This app was created as a solution to a church music team as a way to schedule member availability after trying various other solutions that did not fit our needs.  As such, it is intended for ease of use and modularity.

##Requirements##

###Server###
* PHP 5.3 or newer - PHP 5.5 recommended
* MySQL 5.0+ compliant database - MariaDB 5.5 recommended
* Developed and tested on CentOS 6.  Linux recommended.

###Client###

* A modern browser that supports HTML5 (Mozilla Firefox recommended)
* No flash or java plugins are required
* Fully responsive - Works on most modern smart phones (Developed for Android, tested on iOS)

##Installing##

These steps should be automated by a script soon!

###Create the Database###
[Create a database](https://mariadb.com/kb/en/create-database/) and [database user](https://mariadb.com/kb/en/create-user/) with permissions.  For example:

```
CREATE DATABASE band_sched;
CREATE USER "foo" identified by "1337";
GRANT ALL on band-sched.* to "foo"@"localhost" identified by "1337";

```
Next, import the schema, from the sched.sql
```
mysql -u foo -p band_sched < examples/sched.sql
```
Create an initial user:
```
USE band_sched
INSERT INTO users (username,password,user_level,status) VALUES ('admin',MD5('a good password'),1,1);
```
One last step.  Make an .ini file somewhere outside of your web root like this:
```
[band-sched]
dbhost="localhost"
db="band-sched"
dbuser="foo"
dbpasswd="1337"
```
Now change line 11 on classes/db.class.php to point to this file:
```$this->config = parse_ini_file('/var/db/band-sched.ini', false);```

All done!  You should now be able to log in!

##Customizing##

The app uses common theming, so all your styling and customizing happens in the includes/ and styles/ folders.

###Styling###

The app uses the [Twitter Bootstrap UI](http://getbootstrap.com/).  All bootstrap related includes are completely vanilla and stored in /bootstrap (In other words, don't touch these - ever!) .  The styles/main.css contains all app-specific styles and bootstrap overrides.  

It is recommended that you create a custom.css here so that your changes to main.css won't get overwritten when upgrading band-sched.  You can then simply add it as an include in the head.

###Layout###
The app shares a common header, footer, and global nav throughout.  These are stored in includes/.  

The header.php contains all the styling and scripts.  The order in which these are called is important, so if you extend this, it is recommended you do so only at the bottom of the file, just before </head>.

The globalnav.php contains the responsive, bootstrap powered navigation at the top.  You can add menu elements here.  You should also change the site title at ```"<a class="brand" href="/">FCC Worship Center</a>"``` to whatever you wish to brand it.  (This will be handled better in a future release).

The footer.php is used to close any remaining tags.  This can be modified to include any common code or markup at the bottom of pages.

##User Levels##

By default, there are three user levels:

Member: This is the default user role.  A member can update their own profile ad password, and schedule themselves for events, but cannot add/modify events or other users.

Leaders: Leaders can add new members or leaders, but not admins.  In addition to the rights of members, leaders can add and modify events, as well as assign a leader for an event.

Admins: Admins have all the rights as a leader and can also manage other users' accounts.

As the database is fully normalized (see below), there is no option to delete users, per se, however users may be marked as "Away" or "Inactive".  Being "inactive" prevents login and prevents a leader from being selectable for a event.

##Scheduling Flow##

The general flow for users is :

Redirected to login page.  After authenticating, taken to the home page.  User clicks on event pages and is show events generated for the current month (able to select other months/years).  They can quicly sign up from the events view or click on a date to see the event detail page.  The event detail page shows who is leading, the set practice time, the event status (tentative, cancelled, etc.) and who was signed up currently.  The user is able to cancel their sign-up or make it tentative here.

Leaders and Admins can additionally add events from this page or from the Events drop down menu item.  

The events overview (events.php) and event details (event.php) are technically speaking the same page with data being drawn in by PHP on page load.  This means that as soon as an event is created, it is seen by users and an event detail page is already accessible.

#Technical Details for the Nerdy#

##Architecture##

Due to the goal for ease of use, this app is intented to be responsive on mobile devices.  As such, the UI is built on Twitter's Bootstrap v2 platform. All bootstrap specfic includes and plugins reside in /bootstrap.

As the goal is for this project to be modular, it is based on HTML and written with jQuery and PHP.  It is generally built on the MVC coding style, where classes provide the core functionality, and services provide an interface to access them - typically via ajax.  The front end work is initially PHP driven and then jQuery powered.  This is ideal for page effeciency in balancing ajax calls against page reloads.

With the exception of a few page-specific styles and scripts, the bulk are in styles/main.css and scripts/main.js.  This is for the purpose of reducing code and for making customization more simple.

Additionally, all pages share a similar template, which is drawn in from /includes.  This way if a script or style needs added, it only needs to be added in one place to take effect on all pages.  This also provides the UX function of loading all the includes on the login page in the background while the user is typing in their credentials, which makes the rest of the app much quicker by caching.

##Database##

The database is a fully normalized schema running in top of MariaDB (MySQL).  The DB class reads the connectivity parameters from a ini file not included in the repo for obvious reasons, though the necessary parameters for such a file should be evident from the class/db.class.php.

The database largely revolves around the "users" and "events" tables.  The other tables may be considered meta information for those.
