<p>
<strong>FOUNDER:</strong> Jake Litwicki - jake.litwicki@gmail.com<br /><br />
<strong>PURPOSE:</strong> Open Source Warhammer Online Guild CMS<br /><br />
<strong>PROJECT NAME:</strong> WARGuilds, WARHAMMERGUILDS.NET<br /><br />
<strong>WEBSITE URL:</strong> http://www.warhammerguilds.net<br /><br />
</p>

<h2>Project Scope Defined</h2>
The goal of WARGuilds is to provide an open-source, content management system for all Warhammer Online (WAR) related guilds.
This software will be available through a controlled release available through www.warhammerguilds.net only. If applicable, or
necessary, additional mirror downloads through source-forge may be viable. The reason for this being liability of modified
versions on mirror sites, and/or malicious file manipulations on non-sponsored websites.

<br /><br />
<strong>The differentiation of WARHAMMERGUILDS.NET</strong><br /><br />
<ol>
<li>Video Hosting - Hosting and discussion on community contributed videos, with restrictions only to file-type (.wmv, .avi, .mov).</li>
<li>Community News - Quality community driven news, written for, and by, recognized members of the community.</li>
<li>Community Forums - The most highly available forum community, from the broadest, to the narrowest of topics involving WAR.</li>
<li>Guild Rankings - User driven guild ranking based on specified criteria.</li>
</ol>

<strong>Software Features</strong> (Details Below)<br /><br />
<ol>
<li>AJAX Rendered Real-Time Screenshot Gallery - Member driven</li>
<li>Members Database - With additional data parsed from XML Data Feed from Warhammer Herald.</li>
<li>Application System - Validation required, database driven guild application system. With global moderation</li>
<li>phpBB News - With phpBB backend, parse custom forums into news, allowable with attachments and images.</li>
<li>Completely CSS/XHTML</li>
</ol>

<br /><br />

<h1>Features Broken Down</h2>
To avoid project creep, each feature is explained below with its intended 1.1 Production Launch features.

<h2>AJAX Screenshot Gallery</h2>
A screenshot gallery with member-only admin functionality, building upon the features of lightbox, and including server-side backend, with
real-time browsing and "enlarging" of selected images. Also will include watermark of WARGuilds logo and brief description of image from the
contributing user. Images will all be converted to .jpg 100% quality resized to 800x600 for "enlarge" view and 150x100 for "thumbnail" view. 
Additional sizes and proportions for widescreen monitors will be within those limits.

<h2>Members Database</h2>
A standalone database to the phpBB members database, this database will include all parsed data from the Warhammer Herald, 
as well as several custom fields:
<br /><br />
<ul>
<li>Avatar</li>
<li>Direct link to WARHerald member and/or guild page</li>
<li>References to contributed news/screenshots/videos to WARGuilds</li>
<li>Include duplicate functionality for alternate characters (max of 2)</li>
</ul>
<br /><br />
The above list is not exhaustive.

<h2>Application System</h2>
Guild Leader administered application system that will process and record all guild applications into a database. A web interface
will allow all selected users (from a designated phpBB group) access to review or delete applications. Upon "review" the application
system will send a user generated email with guild-specific information to the applicant. Upon submittal the application will be generated
into phpBB format for the user to create a forum post for further guild review.

<h2>phpBB News</h2>
Converts recent posts from specified forums into news on the guild's home page. This news is parsed to include images, 
file attachments (.zip, .rar) and all written content of the post. Unlimited categories (forum_ids) and news.php navigation of each
category at the users disposal. An RSS feed will be generated and include all requested categories based on a configuration by each guild.
<h2>CSS/XHML Compliant</h2>
The entire site in its release form will be compliant to W3C Standards for both XHTML 1.0 (Transitional) and CSS 2.0. The entire project
will be based off includes of .tpl files to generate the templates, and all CSS documents will be completely up to the specific user. Each
site will come loaded with the same default template, and PSD files to customize the header, navigation, and footer images.

<h1>Competition &amp; Urgency</h1>
Our main competition will of course be guildportal, WarCry, WARVault, and all other community websites. Our focus and differentiation will be
on our video content and focus specifically on guild videos and rankings. Ranking criteria will be determined by WARGuilds Community Leaders, 
and all ranking will be done exclusively by the community. Cheating, allowing cheating, or turning a blind eye to cheating, and any guild involved
with any "illegal" activity, will result in their immediate removal from the ranking system, and a complete ban from the website's community features.

<h1>Development &amp; Administration Needs</h1>
For its immediate release, two additional developers will be needed to guarantee the security and integrity of all data within the WARGuilds software
 that is not directly linked to the phpBB database. Additional resources to develop the AJAX Screenshot Gallery, and further scalability will be 
 necessary for its future releases, but portability and predictability will be essential.<br /><br />

 <h2>Development Needs</h2>
 <br />
 <ul>
 <li><strong>MySQL Database Admin</strong> - Primary focus on writing front and backend queries to automate much of the video processing and user input data. Queing of all
 submitted files, and scanning files for malicious attachments will also be essential. Additionally, scalability towards future bandwidth needs, as well
 as properly maximizing advertiser revenue through data collection of community users.</li>
 <br />
 <li><strong>PHP/AJAX Developer</strong> - Primary focus on continued development and maintenance of current and future releases. Primary focus on
 screenshot gallery with AJAX functionality, and "progress bar" uploads for all users to minimize additional database usage.</li>
 </ul>
 <br />
 <h2>Community &amp; Administration Needs</h2>
 <br />
 <ul>
 <li><strong>Moderators</strong> - Moderators of extensive forum community. Primary focus on maintaining maturity and civility amongst community members on WARGuilds website.</li>
 <br />
 <li><strong>Writiers/Contributors</strong> - Primary focus on generating content for WARGuilds website in news, analysis, reviews, discussions, polls, screenshots, videos, etc.</li>
 </ul>

 <h1>Project Goals</h1>
 Aggressive project goals include..
 <br /><br />
 <ol>
 <li>Official Beta 1.0 Launch - November 1, 2007.</li>
 <li>Official Beta 2.0 Launch - December 1, 2007.</li>
 <li>Official 1.1 Stable Launch - January 1, 2008.</li>
 <li>Officially recognized by EA Mythic - February 1, 2008</li>
 <li>50 WARGuilds - March 1, 2008</li>
 </ol>
