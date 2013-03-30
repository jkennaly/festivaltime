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
<h1>Copy the files</h1>
<p>Copy the project files into the target directory. The project is available as festivaltime on github.</p>
<p>In the variables directory, copy the variables.txtfile into variables.php. You will need to set the variables as follows:</p>
<ol>
    <li>Enter a site name. This name will appear in the page header when no festival is active.</li>
    <li>Enter the hostname for the database.</li>
    <li>Enter the URL for the site base page in the format shown in the template.</li>
    <li>Enter the root in the local filesystem as shown in the template format.</li>
    <li>Enter the name to be used for the master database. This database coordinates information among the festival dbs.</li>
    <li>Enter the master db user name and password. This user must have full access to the master database.</li>
    <li>Enter the second dataabse user and password. This user was originally developed with root access, but must have access to create, modify and drop databases, as well as full access to the master, and all created databases.</li>
    <li>Enter the address to use as the from address for emails.</li>
</ol>

<p>Once the variables.php file has been updated, create the users and master database in the database server. When that is done, click the following button, and a new site user with admin access and a username/password of admin will be created.</p>


</html>
