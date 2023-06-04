<div class="section section--logspage">
    <h1>Your Logs in $MonthText $Year:</h1>
    <div class="logs_buttons">
        <% loop $getLogsForMonth($Month, $Year).GroupedBy(VisitDateLink) %>
            <div class="logs_buttonwrap">
                <a class="logs_button" href="$Top.Link('')\date\/$VisitDateLink">
                    <h4>$Children.First.VisitDate</h4>
                    <p>$Children.Count Experiences</p>
                </a>
            </div>
        <% end_loop %>
    </div>
</div>
