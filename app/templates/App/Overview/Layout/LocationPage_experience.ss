<% with $Experience %>
    <div class="section section--experienceentry">
        <div class="section_content">
        <div class="experience_block <% if not $Image %>noimage<% end_if %>">
                <a class="backbutton" href="$Top.Link('')">Zurück</a>
                <% if $Image %>
                    <div class="experienceimage">
                        $Image.FocusFill(1000, 600)
                    </div>
                <% end_if %>
                <h1>$Title</h1>
                <h4>$Type.Title <% if $Area %>in $Area.Title<% end_if %></h4>
                $Description
            </div>
            <div class="experiencedata_list">
                <% loop $ExperienceData %>
                    <div class="experiencedata_entry <% if $Type.IsLongText %>long<% end_if %>">
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
