<div class="foodcard_wrap">
    <div class="foodcard">
        <% if $Image %>
            <a class="section_image" href="$Image.FitMax(2000,2000).URL" data-gallery="gallery" data-glightbox="description: $Title" data-caption="$Title">
                <img src="$Image.FocusFill(200, 200).Url" alt="$Title">
            </a>
        <% else %>
            <div class="section_image"></div>
        <% end_if %>
        <div class="section_text">
            <h4>$Title</h4>
            <p>$Description</p>
        </div>
    </div>
</div>
