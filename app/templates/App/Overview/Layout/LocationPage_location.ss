<% with $Location %>
    <div class="section section--experiencesoverview">
        <div class="section_content">
            <a class="backbutton" onclick="window.history.back();">Zurück</a>
            <div class="location_sidebar">
                <div class="location_title">
                    <div class="backgroundimage">$Image.FocusFill(400,200)</div>
                    <p>$Type.Title</p>
                    <h1>$Title</h1>
                </div>
                <% if $Top.CurrentUser %>
                    <a href="$Top.Link('changeFavourite')/$ID" class="location_favouritemarker">
                        <% if $IsFavourite %>
                            <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path fill="currentColor" d="m24 41.95-2.05-1.85q-5.3-4.85-8.75-8.375-3.45-3.525-5.5-6.3T4.825 20.4Q4 18.15 4 15.85q0-4.5 3.025-7.525Q10.05 5.3 14.5 5.3q2.85 0 5.275 1.35Q22.2 8 24 10.55q2.1-2.7 4.45-3.975T33.5 5.3q4.45 0 7.475 3.025Q44 11.35 44 15.85q0 2.3-.825 4.55T40.3 25.425q-2.05 2.775-5.5 6.3T26.05 40.1Z"/></svg>
                        <% else %>
                            <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path fill="currentColor" d="m24 41.95-2.05-1.85q-5.3-4.85-8.75-8.375-3.45-3.525-5.5-6.3T4.825 20.4Q4 18.15 4 15.85q0-4.5 3.025-7.525Q10.05 5.3 14.5 5.3q2.85 0 5.275 1.35Q22.2 8 24 10.55q2.1-2.7 4.45-3.975T33.5 5.3q4.45 0 7.475 3.025Q44 11.35 44 15.85q0 2.3-.825 4.55T40.3 25.425q-2.05 2.775-5.5 6.3T26.05 40.1ZM24 38q5.05-4.65 8.325-7.975 3.275-3.325 5.2-5.825 1.925-2.5 2.7-4.45.775-1.95.775-3.9 0-3.3-2.1-5.425T33.5 8.3q-2.55 0-4.75 1.575T25.2 14.3h-2.45q-1.3-2.8-3.5-4.4-2.2-1.6-4.75-1.6-3.3 0-5.4 2.125Q7 12.55 7 15.85q0 1.95.775 3.925.775 1.975 2.7 4.5Q12.4 26.8 15.7 30.1 19 33.4 24 38Zm0-14.85Z"/></svg>
                        <% end_if %>
                    </a>
                <% end_if %>
                <div class="numbers" data-behaviour="showhide">
                    <p>$Experiences.Filter("ParentID", $ID).Count Experiences</p>
                    <% if $Experiences.Filter("State", "Active").Count > 0 %><p class="sideinfo">$Experiences.Filter("State", "Active").Count Active</p><% end_if %>
                    <% if $Experiences.Filter("State", "In Maintenance").Count > 0 %><p class="sideinfo">$Experiences.Filter("State", "In Maintenance").Count In Maintenance</p><% end_if %>
                    <% if $Experiences.Filter("State", "Defunct").Count > 0 %><p class="sideinfo">$Experiences.Filter("State", "Defunct").Count Defunct</p><% end_if %>
                    <% if $Experiences.Filter("State", "Other").Count > 0 %><p class="sideinfo">$Experiences.Filter("State", "Other").Count Other</p><% end_if %>
                    <hr/>
                    <% loop $GroupedExperiences %>
                        <p>$Children.Count $Children.First.Type.PluralName</p>
                    <% end_loop %>
                    <hr/>
                    $Description
                </div>
            </div>
            <div class="location_experiences">
                <div class="search_bar" id="search-experience-bar">
                    <input type="text" name="search" id="search-experience" placeholder="Search for a experience" />
                </div>
                <div class="experience_list">
                    <% loop $Experiences %>
                        <% include ExperienceCard %>
                    <% end_loop %>
                </div>
            </div>
        </div>
    </div>
<% end_with %>
