{include file='sites/all/rating/style.php'}

<center>
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<!-- Link BOTTOM PowerliftingBlog -->
	<ins class="adsbygoogle"
		 style="display:inline-block;width:728px;height:15px"
		 data-ad-client="ca-pub-3589435131109855"
		 data-ad-slot="3500599159"></ins>
	<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>

</center>
<table class='MyTable'>
	<tr><th colspan='2'>Троеборье</th></tr>
	<tr><td colspan='2'>
		<center>
			<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<!-- HEAD Rating -->
			<ins class="adsbygoogle"
				 style="display:block"
				 data-ad-client="ca-pub-3589435131109855"
				 data-ad-slot="5272531152"
				 data-ad-format="auto"></ins>
			<script>
			(adsbygoogle = window.adsbygoogle || []).push({});
			</script>
		</center>
	</td></tr>
	<tr>
		<td>
			<table>
			<tr><th colspan='5'>Женщины. Троеборье</th></tr>   
			{$iw = 1}
			{foreach key=key item=i from=$women} 
				<tr>
					<td>{$iw++}.  <a href='/athlete/{$i.athlete_id}'>{$i.name}</a></td>				
					<td class="sm">{$i.age}</td>
					<td class="sm"> {$i.region}</td>
					<td class="sm"><strong>{$i.wilks} <br/> {$i.total}/{$i.weight}</strong></td>
					<td class="sm"> {$i.comp} {$i.date}</td>
				</tr>
			{/foreach}
			</table>
		</td>
		<td>
			<table>
			<tr><th colspan='5'>Мужчины. Троеборье</th></tr>
			{$im = 1}
			{foreach key=key item=i from=$men} 
			  <tr>
				<td>{$im++}.  <a href='/athlete/{$i.athlete_id}'>{$i.name}</a></td>				
				<td class="sm">{$i.age}</td>
				<td class="sm"> {$i.region}</td>
				<td class="sm"><strong>{$i.wilks} <br/> {$i.total}/{$i.weight}</strong></td>
				<td class="sm"> {$i.comp} {$i.date}</td>
			  </tr>
			{/foreach}
			</table>
		</td>
	</tr>	
	<tr><th colspan='2'>Троеборье классическое</th></tr>
	<tr><td colspan='2'>
		<center>
			<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<!-- HEAD PowerliftingRating -->
			<ins class="adsbygoogle"
				 style="display:inline-block;width:728px;height:90px"
				 data-ad-client="ca-pub-3589435131109855"
				 data-ad-slot="6305874791"></ins>
			<script>
			(adsbygoogle = window.adsbygoogle || []).push({});
			</script>
		</center>
	</td></tr>
	<tr>
		<td>
			<table>
				<tr><th colspan='5'>Женщины. Классическое троеборье</th></tr>
				{$iwr = 1}
				{foreach key=key item=i from=$women_raw} 
				  <tr>
					<td>{$iwr++}.  <a href='/athlete/{$i.athlete_id}'>{$i.name}</a></td>				
					<td class="sm">{$i.age}</td>
					<td class="sm"> {$i.region}</td>
					<td class="sm"><strong>{$i.wilks} <br/> {$i.total}/{$i.weight}</strong></td>
					<td class="sm"> {$i.comp} {$i.date}</td>
				  </tr>
				{/foreach}
			</table>
		</td>
		<td>
			<table>
				<tr><th colspan='5'>Мужчины. Классическое троеборье</th></tr>
				{$imr = 1}
				{foreach key=key item=i from=$men_raw} 
				  <tr>
					<td>{$imr++}.  <a href='/athlete/{$i.athlete_id}'>{$i.name}</a></td>				
					<td class="sm">{$i.age}</td>
					<td class="sm"> {$i.region}</td>
					<td class="sm"><strong>{$i.wilks} <br/> {$i.total}/{$i.weight}</strong></td>
					<td class="sm"> {$i.comp} {$i.date}</td>
				  </tr>
				{/foreach}
			</table>
		</td>
	</tr>
</table>