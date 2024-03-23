<% with $News %>
    <div class="section section--news">
        <div class="section_content">
            <% if $Image %><img class="section_news_image" src="$Image.FocusFill(1200,500).Url"><% end_if %>
            <h1 class="section_news_title">$Title</h1>
            <h3>$FormattedDate | $FormattedCategories</h3>
            <div class="section_news_content">
                $Content
            </div>
        </div>
    </div>
<% end_with %>
