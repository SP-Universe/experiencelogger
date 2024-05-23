
<div class="section section--NewsPage">
    <div class="section_content">
        <% if $News.Count > 0 %>
            <div class="news_list">
                <% loop $News %>
                    <% include NewsCard %>
                <% end_loop %>
            </div>
        <% else %>
        <p>There are no news in the moment.</p>
        <% end_if %>
    </div>
</div>
