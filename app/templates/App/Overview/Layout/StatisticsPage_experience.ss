<div class="section section--statistics">
    <div class="section_content">
        <h1>Your Statistics of $Experience.Title in $Location.Title</h1>

        <hr>

        <h2>Logs</h2>
        <div class="statistics_list list--5">
            <% loop $LogsPerYear %>
                <div class="list_item statistics_card card--value">
                    <p class="statistics_value">$logs</p>
                    <p class="statistics_description">Log<% if $logs > 1 %>s<% end_if %> in $year</p>
                </div>
            <% end_loop %>
        </div>   

        <h2>Averages</h2>
        <div class="statistics_list list--2">
            <div class="list_item statistics_card card--value">
                <p class="statistics_value">$AverageLogsPerVisit</p>
                <p class="statistics_description">Average Logs Per Visit</p>
            </div>          
            <% if $AverageScore > 0 %>  
                <div class="list_item statistics_card card--value">
                    <p class="statistics_value">$AverageScore</p>
                    <p class="statistics_description">Average Score</p>
                </div>
            <% end_if %>
        </div>        
        <% if $HighestScoreOfExperienceAllTime.score > 0 %>  
            <h2>Scores</h2>
            <div class="statistics_list list--5">         
                <div class="list_item statistics_card card--value">
                    <% with $HighestScoreOfExperienceAllTime %>
                        <p class="statistics_value">$score</p>
                        <p class="statistics_train">$trainname</p>
                        <p class="statistics_description">Best Score of all time</p>
                    <% end_with %>
                </div>
                <% loop $HighestScoreOfExperiencePerYear %>
                    <div class="list_item statistics_card card--value">
                        <p class="statistics_value">$score</p>
                        <p class="statistics_train">$trainname</p>
                        <p class="statistics_description">Best Score in $year</p>
                    </div>
                <% end_loop %>
            </div>
        <% end_if %>
    </div>
</div>
