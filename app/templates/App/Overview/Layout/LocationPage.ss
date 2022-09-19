<div class="section section--locationsoverview">
    <div class="section_content">
        <h1>Places</h1>
        <div class="location_list">
            <% loop $Locations %>
                <a href="$Top.Link('location')/$FormattedName" class="location_entry">
                    <div class="location_entry_image" style="background-image: url($Image.Url)">
                    </div>
                    <div class="location_entry_content">
                        <h2>$Title</h2>
                        <h4>$Type</h4>
                        <p>$Address</p>
                        <p>$Experiences.Count Experiences</p>
                    </div>
                </a>
            <% end_loop %>
        </div>
    </div>
</div>
