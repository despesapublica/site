<ul class="sf-menu">
	<% control Menu(1) %>
		<% if Children %>
			<li>
                <a href="$Link" title="Go to the $Title.XML page" class="$LinkingMode"><span class="menuItemWrapper">$MenuTitle.XML</span></a>
				<ul>
					<% control Children %>
						<li><a href="$Link" title="Go to the $Title.XML page" class="$LinkingMode"><span class="menuItemWrapper">$MenuTitle.XML</span></a></li>
					<% end_control %>
				</ul>
			</li>
		<% else %>
			<li><a href="$Link" title="Go to the $Title.XML page" class="$LinkingMode"><span class="menuItemWrapper">$MenuTitle.XML</span></a></li>
		<% end_if %>
	<% end_control %>
</ul>