<% with $Location %>
    <div class="section section--locationsoverview">
        <div class="section_content">
            <a href="$Top.Link('')">Zurück</a>
            <h1>$Title</h1>
            <div class="search_bar" id="search-experience-bar">
                <input type="text" name="search" id="search-experience" placeholder="Search for a experience" />
            </div>
            <div class="experience_list">
                <% loop $Experiences.Filter("ParentID", $ID) %>
                    <a href="$Top.Link('experience')/$FormattedName" class="experience_entry">
                        <div class="experience_entry_image" style="background-image: url($Image.FocusFill(400,200).Url)">
                        </div>
                        <div class="experience_entry_content">
                            <h2 class="experience_title">$Title</h2>
                            <h4>$Type</h4>
                            <p>$State</p>
                        </div>
                    </a>
                <% end_loop %>
            </div>
        </div>
    </div>
<% end_with %>