<div class="train_visualizer">
    <% loop $ExperienceTrains() %>
        <div class="train <% if $PageController.ExperienceTrains.Count <= 1 %>active<% end_if %> $Up.Traintype" data-train="$SortOrder" data-type="train">
            <p class="trainname">$Up.Traintype $Title</p>

            <% loop $SortedSeats %>
                <div class="wagon">
                    Test
                    <% loop $Children %>
                        <div class="row">
                            <p>$Row</p>

                            <% loop $Up.Up.Up.Children.GroupedBy("Seat") %>
                                <div class="seat" data-behaviour="seat_selector" data-train="$Up.Up.SortOrder" data-wagon="$Wagon" data-row="$Row" data-seat="$Seat">
                                    <% if $Up.Children.Count > 1 %><p>$Seat</p><% end_if %>
                                    <% if $Up.Children.Count < 2 %><p>$Row</p><% end_if %>
                                    <p class="count">1</p>
                                </div>
                            <% end_loop %>
                        </div>
                    <% end_loop %>
                </div>
            <% end_loop %>
        </div>
    <% end_loop %>


    <div class="hidden_datafields">
        <label for="train">Train</label>
        <input type="text" id="train" name="train">

        <label for="wagon" min="0" max="99">Wagon</label>
        <input type="number" id="wagon" name="wagon">

        <label for="row" min="0" max="99">Row</label>
        <input type="number" id="row" name="row">

        <label for="seat" min="0" max="99">Seat</label>
        <input type="number" id="seat" name="seat">
    </div>
</div>
