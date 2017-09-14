<div class="video">
	<% if VideoSource == "YouTube" %>
		<iframe width="$Width" height="$Height" src="https://www.youtube.com/embed/{$YoutubeCode}?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>
	<% else_if VideoSource == "Vimeo" %>
		<iframe width="$Width" height="$Height" src="https://player.vimeo.com/video/{$VimeoCode}?byline=0&portrait=0&badge=0" frameborder="0" allowfullscreen></iframe>
	<% else %>
		<video class="afterglow" id="video-$ID" width="$Width" height="$Height" controls  data-autoresize="fit" preload="metadata">
			<% loop SortedVideoVersions %>
			<source type="$Format" src="$File.URL"<% if $ResolutionLabel %> data-quality="$ResolutionLabel"<% end_if %>/>
			<% end_loop %>
		</video>
	<% end_if %>
</div>