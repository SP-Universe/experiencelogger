<div class="section section--logspage">
    <h1>Your recent Logs:</h1>
    <% loop $Logs().GroupedBy(VisitDate) %>
        <div class="logs_date">
            <h4>$VisitDate <span>$Children.Count Experiences</span></h4>
            <% loop $Children %>
                <% include LogCard %>
            <% end_loop %>
        </div>
    <% end_loop %>
</div>
