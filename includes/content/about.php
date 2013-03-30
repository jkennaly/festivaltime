<div id="content">

<?php
If(empty($band)) $band="";
?>

<div id="how">
<h2>How should this website be used?</h2>

<p>
I've written a guide for how to use this site, which you can see <a href="<?php echo $basepage; ?>?disp=guide">here</a>. If you want to just dive in, scroll to the bottom of the Home page and click your favorite band. This will bring up the Pregame Band View page for that band. If you click on the band name in this screen, it will automatically take you to a a Youtube page with videos of your band. From here you can add a web link for others to check out, and a comment on the band, or rate the band. The comments and ratings you put in (as well as those all the others put in) will easily accessible to you through the gametime mobile site, so you'll never think get confused between Deer Hunter and Deer Tick again!
</p>
</div>

<div id="why">
<h2>Why did I make this website?</h2>

<p>
For me, the smartphone era started in 2009, with my Palm Pre (which was basically destroyed at Coachella in 2010). That was the first time I really was able to experience the internet away from a desk. I saw right away that there waspotential there to be unlocked at a music festival, and waited anxiously for someone to do so.
</p>

<p>
So now it's been over three years, and the mobile internet has taken off. Aplications like foursquare have changed how people interact in the world, and games like Ingress have brought new types of games outside. But when I go to a music festival, I still rely almost entirely on a schedule that I was handed when I walk in the gate on Day 1. There are festival apps, but none of them really accomplish what I think a good festival app needs to do.
</p>

<p>
Just a quick note about the site-this whole thing is still very much in development. Sometimes features may not work, or I might be working on something so it is currently not functioning as expected. Just let me know if you are having a problem, and I'll try and fix it. Also, some of the details I mention here might change over time, and I may forget to update this. Anything major will be reflected here.
</p>
</div>

<div id="what">

<h2>Why do I want out of a festival app?</h2>

<p>
I've put a lot of thought into what an ideal festival app should do. First, I divide it into three sections: pregame, gametime and postgame.
</p>

<p>
Pregame is all the activities that go into the festival before the show starts. Festival tickets, travel arrangements, band research, lodging, and anything else that has to be handled before the show starts.
</p>

<p>
Gametime is what happens from the first entry through security to the final bleary-eyed exit from the grounds. This is the main focus of the festival, and maximizing gametime is the whole purpose of the app.
</p>

<p>
Postgame is everything that happens after the show is over, such as settling expenses and discussing whether or not Blur was the worst headliner ever.
</p>

<p>
This app is only focused on gametime itself, and on the pregame activities that have a direct bearing on gametime. Maybe at some point in the future, logisitical capabilities could be added, making it more useful for the broader pregame and postgame activities, but for now, it's all about the music.
</p>
</div>

<div id="pregame">
<h2>Pregame</h2>

<p>
For Coachella 2013 Weekend 1, pregame will last until April 12, 2013. For the purposes of this app, pregame is designed to make it easy to find bands that you might like that you don't know about, and give yourself some clues that you can reference at gametime so you don't forget about a band that you really liked.
</p>
</div>

<div id="discovery">
<h3>Discovery</h3>

<p>
The pregame app has several features to make it easier to discover new bands. It relies heavily on leveraging the community to help narrow the search.
</p>
</div>

<div id="home">
<h4>Home</h4>

<p>
The Home age is the starting point for all the pregame activity. From the Home page, you can see what others have been doing, see what bands people have recommended to you, and look for new bands to listen to.
</p>

<div id="discussions">
<h5>Bands that have new discussion activity</h5>

<p>
When you are looking at the home page, the top of the page shows all discussions that have been updated since you last visited them.
</p>
<a class="helplink" href="<?php echo $basepage; ?>?disp=home#discussions">Click here to return to this section</a>
</div>

<div id="comments">
<h5>Bands that have recent comments</h5>

<p>
When you are looking at the home page, the page shows the nine bands that have most recently had comments added. This shows what the other users are thinking about right now. Clicking on any of these links will take you to the Pregame Band View.
</p>
<a class="helplink" href="<?php echo $basepage; ?>?disp=home#comments">Click here to return to this section</a>
</div>

<div id="recommended">
<h5>Bands that have been recommended to you</h5>

<p>
The next section in the home page is recommendations. This will show you bands that have been recommended to you by other users. Once you click a link here, the link will disappear from the home page. To keep from getting spammed, you can only be recommended the same band one time, so don't forget who you've clicked!
</p>

<p>
Note: the recommended section is only visible if you have been recommended a band and have not clicked on the link yet.
</p>

<a class="helplink" href="<?php echo $basepage; ?>?disp=home#recommended">Click here to return to this section</a>
</div>

<div id="filters">
<h5>Filters</h5>

<p>
Coachella 2013 has 167 bands announced for it, so figuring out who to listen to is a huge undertaking. The filter section helps narrow this down. Exactly what filters are available will increase over time-for instance, it does not make sense to filter by stage or by time until the stages and times are announced.
</p>

<p>
The current set of filters is focused on building up a good base of info on every band, and helping you to find it. To use the filters, just click on the checkbox next to the filters you want. You can check as many as all of them or as few as none of them.
</p>

<p>
Let's take a look at how the filters work. First, there are two basic types of filters. There are the filters like day and stage. If you click Friday and Saturday, for example, you will get all the bands playing on Friday and Saturday, but none from Sunday. Stages work the same way-if you click the Gobi and the Sahara, you will get all bands playing the Gobi or the Sahara, but not the other stages. If you click Friday and Gobi, you will get all the bands playing thr Gobi on Friday.
</p>

<p>
The second type of filters is for the user-entered data. To pass the filter here, a band must meet all of the requirements. So if you check "Bands I HAVE rated" and "Bands I HAVE NOT rated", you will not get any results.
</p>

<p>
Don't worry too much about picking the "right" filter-it's pretty intuitive, and if you don't get enough results, just try it again with fewer checkboxes selected. If you get too many, just add more checkboxes.
</p>

<p>
Once you have selected the filters you want, click the "Show my bands" button and see the results!
</p>

<p>
After the filter is run, the options you selected are displayed beneath the "Show my bands" button, in case you forgot what you selected.
</p>
<a class="helplink" href="<?php echo $basepage; ?>?disp=home#filter">Click here to return to this section</a>
</div>

<div id="bands">
<h5>The bands</h5>

<p>
Without a filter applied, this section shows all the bands scheduled for Coachella. With a filter applied, this section shows all the bands that meet the criteria for that filter.
</p>

<p>
Clicking on any band in this list will take you to the Pregame Band View page for that band.
</p>
<a class="helplink" href="<?php echo $basepage; ?>?disp=home#bandlist">Click here to return to this section</a>
</div>

</div>

<div id="viewband">
<h4>Pregame View Band</h4>

<p>
The View Band Page is the basis of the pregame website. From here, you can see the vital details about the selected band, such as stage, day, and set time. Clicking the name of the band will take you to the Youtube search results page for the band name. You can recommend the band to other users. You can see all the comments and ratings that others have left on the band, follow any links they have added, and see what rating they gave the band. You can add your own comments, links, and ratings. Finally, you can see the band's avergae rating, how many times a link associated with the band has been clicked, and how many times the band has been recommended to other users.
</p>

<p>
If no band has been selected, the PRegame View Band page just shows a selector with which to choose a band. Pick any band and hit "Submit" to bring up the details for that band and add your comments, ratings, links and recommendations!.
</p>
<a class="helplink" href="<?php echo $basepage."?disp=view_band&band=$band"; ?>">Click here to return to this section</a>

<div id="recommending">
<h5>Recommending bands to others</h5>

<p>
This feature allows you to recommend bands to others. When they view the home page, any band you recommend you recommend to a user will appear at the top, until they click on it.
</p>

<p>
To recommend a band, youmust be on the details page for the band you wish to recommend. There is always a link at the top "Choose from all bands" if you want to change the details page to a specific band. Once you are on the details page for the band you wish to recommend, simply select the user name from the pick list and click the "Recommend this band to user" button next to it.
</p>
<a class="helplink" href="<?php echo $basepage."?disp=view_band&band=$band"; ?>">Click here to return to this section</a>
</div>

<p>
</p>

</div>

<div id="commenting">
<h5>Commenting on, linking and rating bands</h5>

<p>
This feature allows you to make comments and ratings for each band, as well as provide a link to the band for others to follow.
</p>

<p>
The layout of this page and the gametime version are similar, except that the gametime version does not have the links. This is because at gametime, the entire screen is one big hyperlink to signal that you will be at that band, so there cannot be other links on the page. But the information presented on the pregame version is the same, so anything you see here pregame will also be visible at gametime.
</p>

<p>
If you have added a rating, link or comment for this band already, your information is presented in a grid. If you have not added any information yet, there is just a series of links to add a link, rating or comment. Your grid will always be at the top, both pregame and gametime.
</p>

<p>
Each user has their own grid on this page, so you can scroll down and see what everyone is saying about the band, check out their ratings, and follow the links they post.
</p>

<p>
Other people can change their comments! If there is something you want ot be 100% sure you see at gametime, make sure it is in your own comment.
</p>
<a class="helplink" href="<?php echo $basepage."?disp=view_band&band=$band#userinfo"; ?>">Click here to return to this section</a>
</div>

<p>
</p>

</div>


</div> <!-- end #content -->
