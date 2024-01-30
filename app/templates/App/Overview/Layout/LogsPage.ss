<div class="section section--logspage">
    <h1>Your recent Trips:</h1>
    <div class="logs_buttons">
        <div class="logs_buttonwrap">
            <a class="logs_button" href="$Top.Link('')\/all">
                <h4>All</h4>
                <h4>Logs</h4>
                <p>$Logs.Count logged Experiences</p>
            </a>
        </div>

        <% loop $Logs.GroupedBy(VisitDateMonth) %>
            <div class="logs_buttonwrap">
                <a class="logs_button" href="$Top.Link('')\/month\/$VisitDateMonth">
                    <h4>$Children.First.VisitDateMonthText</h4>
                    <h4>$Children.First.VisitDateYearText</h4>
                    <p>$Children.GroupedBy(VisitDateLink).Count <% if $Children.GroupedBy(VisitDateLink).Count > 1 %>Trips<% else %>Trip<% end_if %></p>
                </a>
            </div>
        <% end_loop %>
    </div>
</div>
