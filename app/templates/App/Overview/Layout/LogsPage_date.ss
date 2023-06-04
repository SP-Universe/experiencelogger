<div class="section section--logspage">
    <h1>Your Logs:</h1>
    <% loop $getLogsForDay($Day, $Month, $Year).GroupedBy(VisitDate) %>
        <div class="logs_date">
            <h4>$VisitDate <span>$Children.Count Experiences</span></h4>
            <% loop $Children %>
                <% include LogCard %>
            <% end_loop %>
        </div>
    <% end_loop %>
</div>
