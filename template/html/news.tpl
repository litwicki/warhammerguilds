<div>

<div class="news_title">
<a href="<? echo $TOPIC_LINK; ?>"><? echo $m['topic_title']; ?></a>

<div class="news_icons">
   <a onclick="toggle_hide('topic_<? echo $m['topic_id']; ?>-short'); toggle_hide('topic_<? echo $m['topic_id'];?>');" title="WAR News">
    <img alt="Toggle Topic <? echo $m['topic_id']; ?>" class="icon16" src="<? echo HOME_PATH.IMAGE_PATH.WAR_ICON; ?>" />
   </a>
</div>
</div>
<!--<div class="post_avatar"><? echo $USER_ICON; ?></div>-->
<div class="post_info"><a href="<? echo $TOPIC_LINK; ?>"><? echo $C_ICON; ?></a> By: <strong><? echo ucfirst($m['username']); ?></strong> on <? echo date('m/d/Y',$m['topic_time']); ?></div>
<div id="topic_<? echo $m['topic_id']; ?>-short" class="news"><? echo $message; ?></div>
<div id="topic_<? echo $m['topic_id']; ?>" class="news" style="display: none;"><? echo $full_text; ?></div>
</div>