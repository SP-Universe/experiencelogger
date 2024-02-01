<% with $Experience %>
    <div class="section section--experience_statistics">
        <div class="section_content">

            <% if $Logs.Count > 0 %>
                <p>You logged $Title <b>$CurrentUser.getRideCounterPerYear($ID).Count <% if $CurrentUser.getRideCounterPerYear($ID).Count > 1 %>Years </b> in <% loop $CurrentUser.getRideCounterPerYear($ID) %><% if $FirstLast == "last" %> and <b>{$year}</b><% else_if $FromEnd == 2%> <b>{$year}</b><% else %><b>{$year}</b>,<% end_if %><% end_loop %><% else %>Year</b> in <b><% loop $CurrentUser.getRideCounterPerYear($ID) %>$year</b><% end_loop %><% end_if %></p>
                <p><b>$Logs.Count <% if $Logs.Count > 1 %>Logs<% else %>Log<% end_if %></b> in total</p>
                <p>All users together logged this experience <b>$TotalLogCount</b> <% if $TotalLogCount > 1 %>times<% else %>time<% end_if %><p>
                <p>You make up <b>{$Top.PercentOfLogs}%</b> of all that logs</p>
                <p>You log this experience on average <b>{$Top.AverageLogsPerVisit}</b> <% if $Top.AverageLogsPerVisit = 1 %>time<% else %>times<% end_if %> per visit</p>
                <hr>
                <h2>Your Logs per year:</h2>
                <div class="progress_list">
                    <% loop $CurrentUser.getRideCounterPerYear($ID) %>
                        <div class="progress_entry_wrap">
                            <div class="progress_entry">
                                <p class="progress_value">$logs</p>
                                <p class="progress_year"><% if $logs == 1 %>Log<% else %>Logs<% end_if %> in $year</p>
                            </div>
                        </div>
                    <% end_loop %>
                </div>

                <% if $StatisticsLink %>
                    <a href="$StatisticsLink" class="button experience_statistics_button">Extended Experience Statistics →</a>
                <% end_if %>
            <% else %>
                <p>You have not yet logged this experience.</p>
            <% end_if %>
        </div>
    </div>
<% end_with %>
