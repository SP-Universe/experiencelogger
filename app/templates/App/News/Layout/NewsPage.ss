
<div class="section section--news">
    <div class="section_content">
        <h2 class="news_title">Experiencelogger News</h2>
        <% if $News.Count > 0 %>
            <div class="news_list">
                <% loop $News %>
                    <a href="$Link" class="news_entry">
                        <% if $Image %>
                            <img class="news_entry_image" src="$Image.FocusFill(600,400).Url" alt="$Image.Title" />
                        <% else %>
                            <div class="news_entry_image"></div>
                        <% end_if %>
                        <div class="news_entry_text">
                            <h3 class="news_entry_text_title">$Title</h3>
                            <h4 class="news_entry_text_date">$FormattedDate | $FormattedCategories</h4>
                            <p>$ShortDescription</p>
                        </div>
                    </a>
                <% end_loop %>
            </div>
        <% else %>
        <p>There are no news in the moment.</p>
        <% end_if %>
    </div>
</div>
