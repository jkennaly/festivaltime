<html>
<?php //phpinfo() ?>
<h1>System requirements</h1>
This system was developed on a server running Ubuntu 12.04. The following software was installed:
<ul>
<li>Apache/2.2.22</li>
<li> MySQL Server version 5.5.29-0ubuntu0.12.04.1-log</li>
<li>MySQL client version 5.5.29</li>
<li>PHP extension: mysqli</li>
<li>PHP Version5.3.10-1ubuntu3.5</li>
<li>mcrypt</li>
</ul>
<h1>Prepare the database</h1>
<p>You will need to create a database for the system to store data in. After creating the database and a user, open the variables/variables.php file and update the information there. This install assumes that the user has full privileges to the database, although this may not be strictly necessary.</p>
<p>In the db_install file, you will need to put the name of the database you created. Then run the sql script to create the tables.</p>
<p>mcrypt must be installed on the system for php to be able process the login info.</p>
<h1>Logging in for the first time.</h1>
<p>After the database tables have been created, there should be one user on the system with username and password admin/admin. I recommend starting off by creating anew admin user with a new password, and logging in and removing the admin account, or at least changing the password.</p>
<p>Once the access is set up, you should edit the info table directly, to set the festival timezone and any other useful information.</p>
<p>You're now ready to start configuring your festival. Start adding bands and stages and festival days.</p>
</html>
