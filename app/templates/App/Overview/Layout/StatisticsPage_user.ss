<div class="section section--statistics">
    <div class="section_content">
        <h1>Statistics of $User.Nickname </h1>

        <% if $User.ProfilePrivacy == "Public" %>
            <hr>
            <h2>Most logged experience:</h2>
            <div class="statistics_list list--1">
                <div class="list_item statistics_card">
                    <% with $MostLoggedExperienceAllTime %>
                        <% include ExperienceCard ShowLogButton=true %>
                    <% end_with %>
                </div>
            </div>

            <hr>
            <h2>Most logged experience per year:</h2>
            <div class="statistics_list list--3">
                <% loop MostLoggedExperiencePerYear %>
                    <div class="list_item statistics_card card--value">
                    <p class="statistics_value">$experience.Title</p>
                    <p class="statistics_parent">in $experience.Parent.Title</p>
                    <p class="statistics_description">Most logged in $year</p>
                </div>
                <% end_loop %>
            </div>
        <% else_if $User.ProfilePrivacy == "Friends" %>
            <p>ProfilePrivacy is not yet supported for statistics...</p>
            <p>This Profile is for friends only.</p>
            <!--TODO: Add friend check-->
        <% else %>
            <p>ProfilePrivacy is not yet supported for statistics...</p>
            <p>This Profile is private.</p>
        <% end_if %>
    </div>
</div>
