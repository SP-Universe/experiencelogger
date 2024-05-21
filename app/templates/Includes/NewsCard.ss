<a href="$Link" class="news_card">
    <div class="news_entry_image">
        <h4 class="news_entry_image_date">$FormattedDate | $FormattedCategories</h4>
        <% if $Image %>
            <img class="news_entry_image_img" src="$Image.FocusFill(600,400).Url" alt="$Image.Title" />
        <% end_if %>
    </div>
    <div class="news_entry_text">
        <h3 class="news_entry_text_title">$Title</h3>
        <p class="news_entry_text_content">$ShortDescription</p>
    </div>
</a>
