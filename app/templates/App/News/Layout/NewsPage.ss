
<div class="section section--news">
    <div class="section_content">
        <h2 class="news_title">Experiencelogger News</h2>
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
