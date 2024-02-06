<div class="section section--statistics">
    <div class="section_content">
        <h1>Your Statistics of $Location.Title</h1>
        <hr>
        <h2>Visits per year</h2>
        <div class="statistics_list list--5">
            <% loop $VisitsPerYear %>
                <div class="list_item statistics_card card--value">
                    <p class="statistics_value">$logs</p>
                    <% if $logs == 1 %>
                        <p class="statistics_description">Visit in $year</p>
                    <% else %>
                        <p class="statistics_description">Visits in $year</p>
                    <% end_if %>
                </div>
            <% end_loop %>
        </div>

        <hr>
        <h2>Averages</h2>
        <div class="statistics_list list--5">
            <div class="list_item statistics_card card--value">
                <p class="statistics_value">$AverageVisitsPerYear</p>
                <p class="statistics_description">Average Visits Per Year</p>
            </div>
            <div class="list_item statistics_card card--value">
                <p class="statistics_value">$AverageLogsPerVisit</p>
                <p class="statistics_description">Average Logs per visit</p>
            </div>
        </div>

        <hr>
        <h2>Most logged experience per visit:</h2>
        <div class="statistics_list list--1">
            <div class="list_item statistics_card">
                <% with $MostLoggedExperiencePerVisitAllTime %>
                    <% include ExperienceCard ShowLogButton=true %>
                <% end_with %>
            </div>
        </div>

        <hr>
        <h2>Most logged experience per year:</h2>
        <div class="statistics_list list--3">
            <% loop MostLoggedExperiencePerVisitPerYear %>
                <div class="list_item statistics_card card--value">
                <p class="statistics_value">$experience.Title</p>
                <p class="statistics_description">Most logged in $year</p>
            </div>
            <% end_loop %>
        </div>
    </div>
</div>
