{include file='sites/all/rating/style.php'}

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
		<td>{if FALSE !== $women}
			<table>
			<tr><th colspan='6'>Женщины</th></tr>
			{$iw = 1}
			{foreach key=key item=i from=$women} 
			  <tr>
				<td class="sm">{$iw++}. <a href='/athlete/{$i.athlete_id}'>{$i.name}</a></td>
				<td class="sm">{$i.age}</td>
				<td class="sm">{$i.subject_rf}</td>
				<td class="sm"><strong>{$i.wilks}</strong></td>
				<td class="sm">{$i.total}<br />{$i.weight}</td>
				<td class="sm">{$i.date}</td>
			  </tr>
			{/foreach}
			</table>
			{/if}
		</td>
		<td>
			<table>
				<tr><th colspan='6'>Мужчины</th></tr>
				{$im = 1}
				{foreach key=key item=i from=$men} 
				  <tr>
					<td class="sm">{$im++}. <a href='/athlete/{$i.athlete_id}'>{$i.name}</a></td>
					<td class="sm">{$i.age}</td>
					<td class="sm">{$i.subject_rf}</td>
					<td class="sm"><strong>{$i.wilks}</strong></td>
					<td class="sm">{$i.total}<br />{$i.weight}</td>
					<td class="sm">{$i.date}</td>
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
			{if FALSE !== $women_raw}
			<table>
			<tr><th colspan='6'>Женщины</th></tr>
			{$iwr = 1}
			{foreach key=key item=i from=$women_raw} 
			  <tr>
				<td class="sm">{$iwr++}. <a href='/athlete/{$i.athlete_id}'>{$i.name}</a></td>
					<td class="sm">{$i.age}</td>
					<td class="sm">{$i.subject_rf}</td>
					<td class="sm"><strong>{$i.wilks}</strong></td>
					<td class="sm">{$i.total}<br />{$i.weight}</td>
					<td class="sm">{$i.date}</td>
			  </tr>
			{/foreach}
			</table>
			{/if}
		</td>
		<td>
		{if FALSE !== $men_raw}
			<table>
			<tr><th colspan='7'>Мужчины</th></tr>
			{$imr = 1}
			{foreach key=key item=i from=$men_raw} 
			  <tr>
				<td class="sm">{$imr++}. <a href='/athlete/{$i.athlete_id}'>{$i.name}</a></td>
					<td class="sm">{$i.age}</td>
					<td class="sm">{$i.subject_rf}</td>
					<td class="sm"><strong>{$i.wilks}</strong></td>
					<td class="sm">{$i.total}<br />{$i.weight}</td>
					<td class="sm">{$i.date}</td>
			  </tr>
			{/foreach}
			</table>
			{/if}
		</td>
	</tr>
</table>