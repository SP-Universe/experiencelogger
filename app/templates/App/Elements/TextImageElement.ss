<div class="section section--textimage $Highlight $Variant $ImgWidth">
    <% if $Image %>
        <div class="textimage_image">
            $Image.ScaleWidth(800)
        </div>
    <% end_if %>

    <div class="textimage_text">
        <div class="textimage_text_content">
            <% if $ShowTitle %>
                <h2 class="textimage_text_title">$Title</h2>
            <% end_if %>
            $Text
            <% if $ButtonText && $ButtonLink %>
                <a href="$ButtonLink" class="textimage_text_button readmore">$ButtonText</a>
            <% end_if %>
        </div>
    </div>
</div>