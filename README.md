band-schedule
=============

This app was created as a solution to a church music team as a way to schedule member availability after trying various other solutions that did not fit our needs.  As such, it is intended for ease of use and modularity.


==User Levels==

By default, there are three user levels:

Member: This is the default user role.  A member can update their own profile ad password, and schedule themselves for events, but cannot add/modify events or other users.

Leaders: Leaders can add new members or leaders, but not admins.  In addition to the rights of members, leaders can add and modify events, as well as assign a leader for an event.

Admins: Admins have all the rights as a leader and can also manage other users' accounts.

As the database is fully normalized (see below), there is no option to delete users, per se, however users may be marked as "Away" or "Inactive".  Being "inactive" prevents login and prevents a leader from being selectable for a event.


==Architecture==

Due to the goal for ease of use, this app is intented to be responsive on mobile devices.  As such, the UI is built on Twitter's Bootstrap v2 platform. All bootstrap specfic includes and plugins reside in /bootstrap.

As the goal is for this project to be modular, it is based on HTML and written with jQuery and PHP.  It is generally built on the MVC coding style, where classes provide the core functionality, and services provide an interface to access them - typically via ajax.  The front end work is initially PHP driven and then jQuery powered.  This is ideal for page effeciency in balancing ajax calls against page reloads.

With the exception of a few page-specific styles and scripts, the bulk are in styles/main.css and scripts/main.js.  This is for the purpose of reducing code and for making customization more simple.

Additionally, all pages share a similar template, which is drawn in from /includes.  This way if a script or style needs added, it only needs to be added in one place to take effect on all pages.  This also provides the UX function of loading all the includes on the login page in the background while the user is typing in their credentials, which makes the rest of the app much quicker by caching.

==Database==

The database is a fully normalized schema running in top of MariaDB (MySQL).  The DB class reads the connectivity parameters from a ini file not included in the repo for obvious reasons, though the necessary parameters for such a file should be evident from the class/db.class.php.

The database largely revolves around the "users" and "events" tables.  The other tables may be considered meta information for those.

==Scheduling Flow==

The general flow for users is :

Redirected to login page.  After authenticating, taken to the home page.  User clicks on event pages and is show events generated for the current month (able to select other months/years).  They can quicly sign up from the events view or click on a date to see the event detail page.  The event detail page shows who is leading, the set practice time, the event status (tentative, cancelled, etc.) and who was signed up currently.  The user is able to cancel their sign-up or make it tentative here.

Leaders and Admins can additionally add events from this page or from the Events drop down menu item.  

The events overview (events.php) and event details (event.php) are technically speaking the same page with data being drawn in by PHP on page load.  This means that as soon as an event is created, it is seen by users and an event detail page is already accessible.
