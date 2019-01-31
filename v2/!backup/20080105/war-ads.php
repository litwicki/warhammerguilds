<?

srand((float) microtime() * 10000000);
srand((float) microtime() * 10000000);
if( isset($_GET['size']) )
{
	if( $_GET['size'] == "large" )
	{
		# -------------------------------
		# BUILD ARRAY OF IMAGES AND URLS
		# -------------------------------
		//$ad[0]['url'] = 'http://www.amazon.com/gp/product/B000TD3IA2?ie=UTF8&amp;amp;tag=warguilds-20&amp;amp;linkCode=as2&amp;amp;camp=1789&amp;amp;creative=9325&amp;amp;creativeASIN=B000TD3IA2';
		$ad[0]['img'] = 'war/img/banners/banner_wide_gamestop.png';
		$ad[0]['url'] = 'http://click.linksynergy.com/fs-bin/click?id=v9gyv0a4Kkc&amp;offerid=120801.64363&amp;type=2&amp;subid=0';
		$num = array_rand($ad);

		echo '<a href="'.$ad[$num]['url'].'"><img alt="BUY WARHAMMER" src="'.$ad[$num]['img'].'" /></a>';
	}
	elseif( $_GET['size'] == "bfglarge" )
	{
		$ad[0]['url'] = 'http://click.linksynergy.com/fs-bin/click?id=v9gyv0a4Kkc&amp;offerid=102327.3485042&amp;type=2&amp;subid=0';
		$ad[1]['url'] = 'http://click.linksynergy.com/fs-bin/click?id=v9gyv0a4Kkc&amp;offerid=102327.2935587&amp;type=2&amp;subid=0';
		$ad[2]['url'] = 'http://click.linksynergy.com/fs-bin/click?id=v9gyv0a4Kkc&amp;offerid=102327.3510472&amp;type=2&amp;subid=0';
		$ad[3]['url'] = 'http://click.linksynergy.com/fs-bin/click?id=v9gyv0a4Kkc&amp;offerid=102327.2842059&amp;type=2&amp;subid=0';
		$ad[4]['url'] = 'http://click.linksynergy.com/fs-bin/click?id=v9gyv0a4Kkc&amp;offerid=102327.3002832&amp;type=2&amp;subid=0';
		$ad[5]['url'] = 'http://click.linksynergy.com/fs-bin/click?id=v9gyv0a4Kkc&amp;offerid=102327.2842055&amp;type=2&amp;subid=0';
		$ad[6]['url'] = 'http://click.linksynergy.com/fs-bin/click?id=v9gyv0a4Kkc&amp;offerid=102327.3244234&amp;type=2&amp;subid=0';
		$ad[7]['url'] = 'http://click.linksynergy.com/fs-bin/click?id=v9gyv0a4Kkc&amp;offerid=102327.2935588&amp;type=2&amp;subid=0';

		$num = array_rand($ad);
		echo '<a class="Tips1" title="Never worry about lagging or low frame rates ever again. BFG video cards offer lifetime warranty, unmatched performance, and guaranteed supreme quality!" href="'.$ad[$num]['url'].'"><img alt="BFG - Extreme Gaming Performance!" src="war/img/banners/bfg_skyscraper.jpg" /></a>';

	}
	elseif( $_GET['size'] == "skyscraper" )
	{
		$ad[0]['img'] = "war/img/banners/160x600_01.jpg";
		$ad[1]['img'] = "war/img/banners/160x600_02.jpg";
		$ad[2]['img'] = "war/img/banners/160x600_03.jpg";
		$ad[3]['img'] = "war/img/banners/160x600_04.jpg";

		$viewsonic_url = 'http://click.linksynergy.com/fs-bin/click?id=v9gyv0a4Kkc&amp;offerid=102327.3424386&amp;type=2&amp;subid=0';
		$url = 'http://click.linksynergy.com/fs-bin/click?id=v9gyv0a4Kkc&amp;offerid=120801.64363&amp;type=2&amp;subid=0';

		$num = array_rand($ad);
		echo '<a class="Tips1" title="BUY WARHAMMER ONLINE NOW!" href="'.$url.'"><img alt="WARGuilds" src="'.$ad[$num]['img'].'" /></a>';
	}
}
?>