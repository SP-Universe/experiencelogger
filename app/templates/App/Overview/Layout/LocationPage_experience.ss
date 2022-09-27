<% with $Experience %>
    <div class="section section--locationsoverview">
        <div class="section_content">
            <a href="$Top.Link('location')/$Parent.Title">Zurück</a>
            <h1>$Title</h1>
            <div class="experiencedata_list">
                <% loop $ExperienceData %>
                    <div class="experiencedata_entry_content">
                        <% if $AlternativeTitle %>
                            <h2>$AlternativeTitle</h2>
                        <% else %>
                            <h2>$Type.Title</h2>
                        <% end_if %>
                        <p>$Description</p>
                        <% if $MoreInfo %>
                            <a href="$MoreInfo" class="moreinfo">More Info</a>
                        <% end_if %>
                        <% if $Source %>
                            <% if $SourceLink %>
                                <a href="$SourceLink" class="source">Source: $Source</a>
                            <% else %>
                                <p class="source">Source: $Source</p>
                            <% end_if %>
                        <% end_if %>
                    </div>
                <% end_loop %>
            </div>
        </div>
    </div>
<% end_with %>
