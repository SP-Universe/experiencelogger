<% with $Location %>
    <div class="section section--experiencesoverview">
        <div class="section_content">
            <a href="$Top.Link('')">Zurück</a>
            <div class="location_sidebar">
                <div class="location_title">
                    <div class="backgroundimage">$Image.FocusFill(400,200)</div>
                    <p>$Type.Title</p>
                    <h1>$Title</h1>
                </div>
                <p>$Experiences.Filter("ParentID", $ID).Count Experiences</p>
                <% if $Experiences.Filter("State", "Active").Count > 0 %><p class="sideinfo">$Experiences.Filter("State", "Active").Count Active</p><% end_if %>
                <% if $Experiences.Filter("State", "In Maintenance").Count > 0 %><p class="sideinfo">$Experiences.Filter("State", "In Maintenance").Count In Maintenance</p><% end_if %>
                <% if $Experiences.Filter("State", "Defunct").Count > 0 %><p class="sideinfo">$Experiences.Filter("State", "Defunct").Count Defunct</p><% end_if %>
                <% if $Experiences.Filter("State", "Other").Count > 0 %><p class="sideinfo">$Experiences.Filter("State", "Other").Count Other</p><% end_if %>
                <hr/>
                <% loop $ExperienceTypes %>
                    <p>$Up.Experiences.Filter("TypeID", $ID).Count $PluralName</p>
                <% end_loop %>
                <hr/>
                $Description
            </div>
            <div class="location_experiences">
                <div class="search_bar" id="search-experience-bar">
                    <input type="text" name="search" id="search-experience" placeholder="Search for a experience" />
                </div>
                <div class="experience_list">
                    <% loop $Experiences %>
                        <a href="$Top.Link('experience')/$FormattedName" class="experience_entry">
                            <div class="experience_entry_image" style="background-image: url($Image.FocusFill(400,200).Url)">
                            </div>
                            <div class="experience_entry_content">
                                <h2 class="experience_title">$Title</h2>
                                <div class="flex_part">
                                    <h4 class="experience_type" data-filter="$Type.Title" data-status="$State">$Type.Title</h4>
                                    <% if $Area %> <span>in $Area.Title </span><% end_if %>
                                </div>
                                <p>$State</p>
                            </div>
                        </a>
                    <% end_loop %>
                </div>
            </div>
        </div>
    </div>
<% end_with %>
