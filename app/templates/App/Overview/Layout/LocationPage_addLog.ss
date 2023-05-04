<% with $Experience %>
    <div class="section section--loggingform">
        <div class="section_content">
            <h1>Adding Log for $Title!</h1>

            <form action="$Top.Link('finishLog')/$LinkTitle" class="logging_form" method="get">
                <h2>Weather Condition</h2>
                <form-group class="logging_group">
                    <div class="group_entry">
                        <label for="sunny">
                            <svg width="100%" height="100%" viewBox="0 0 48 48"><path fill="currentColor" d="M22.5 9.5V2h3v7.5Zm12.8 5.3-2.1-2.1 5.3-5.35 2.1 2.15Zm3.2 10.7v-3H46v3ZM22.5 46v-7.5h3V46Zm-9.85-31.25L7.4 9.5l2.1-2.1 5.3 5.3Zm25.9 25.85-5.35-5.3 2.05-2.05 5.4 5.2ZM2 25.5v-3h7.5v3Zm7.55 15.1L7.4 38.5l5.25-5.25 1.1 1 1.1 1.05ZM24 36q-5 0-8.5-3.5T12 24q0-5 3.5-8.5T24 12q5 0 8.5 3.5T36 24q0 5-3.5 8.5T24 36Z"/></svg>
                            <p>Sunny</p>
                        </label>
                        <input type="checkbox" id="sunny" name="weather[]" value="sunny"/>
                    </div>
                    <div class="group_entry">
                        <label for="cloudy">
                            <svg width="100%" height="100%" viewBox="0 0 48 48"><path fill="currentColor" d="M12.55 40q-4.4 0-7.475-3.075Q2 33.85 2 29.45q0-3.9 2.5-6.85 2.5-2.95 6.35-3.55 1-4.85 4.7-7.925T24.1 8.05q5.6 0 9.45 4.075Q37.4 16.2 37.4 21.9v1.2q3.6-.1 6.1 2.325Q46 27.85 46 31.55q0 3.45-2.5 5.95T37.55 40Z"/></svg>
                            <p>Cloudy</p>
                        </label>
                        <input type="checkbox" id="cloudy" name="weather[]" value="cloudy"/>
                    </div>
                    <div class="group_entry">
                        <label for="rainy">
                            <svg width="100%" height="100%" viewBox="0 0 48 48"><path fill="currentColor" d="M27.9 43.85q-.55.25-1.175.05t-.875-.75l-3.45-6.9q-.25-.55-.075-1.175t.725-.875q.55-.25 1.175-.05t.875.75l3.45 6.9q.25.55.075 1.175t-.725.875Zm12-.05q-.55.25-1.175.05t-.875-.75l-3.45-6.9q-.25-.55-.075-1.175t.725-.875q.55-.25 1.175-.05t.875.75l3.45 6.9q.25.55.075 1.175t-.725.875Zm-24 0q-.55.25-1.175.075t-.875-.725l-3.45-6.9q-.25-.55-.05-1.175t.75-.875q.55-.25 1.175-.075t.875.725l3.45 6.95q.25.55.05 1.15-.2.6-.75.85ZM14.5 31q-4.35 0-7.425-3.075T4 20.5q0-3.95 2.825-7.05 2.825-3.1 7.025-3.4 1.6-2.8 4.225-4.425Q20.7 4 24 4q4.55 0 7.625 2.875T35.4 14q3.95.2 6.275 2.675Q44 19.15 44 22.5q0 3.5-2.475 6T35.5 31Z"/></svg>
                            <p>Rainy</p>
                        </label>
                        <input type="checkbox" id="rainy" name="weather[]" value="rainy"/>
                    </div>
                    <div class="group_entry">
                        <label for="foggy">
                            <svg width="100%" height="100%" viewBox="0 0 48 48"><path fill="currentColor" d="M36 37.75q-.7 0-1.225-.525Q34.25 36.7 34.25 36q0-.7.525-1.225.525-.525 1.225-.525.7 0 1.225.525.525.525.525 1.225 0 .7-.525 1.225-.525.525-1.225.525ZM14 44q-.7 0-1.225-.525-.525-.525-.525-1.225 0-.7.525-1.225Q13.3 40.5 14 40.5q.7 0 1.225.525.525.525.525 1.225 0 .7-.525 1.225Q14.7 44 14 44Zm-2-6.25q-.7 0-1.225-.525Q10.25 36.7 10.25 36q0-.7.525-1.225.525-.525 1.225-.525h18q.7 0 1.225.525.525.525.525 1.225 0 .7-.525 1.225-.525.525-1.225.525ZM20 44q-.7 0-1.225-.525-.525-.525-.525-1.225 0-.7.525-1.225Q19.3 40.5 20 40.5h14q.7 0 1.225.525.525.525.525 1.225 0 .7-.525 1.225Q34.7 44 34 44Zm-5.5-13q-4.35 0-7.425-3.075T4 20.5q0-3.95 2.825-7.05 2.825-3.1 7.025-3.4 1.6-2.8 4.225-4.425Q20.7 4 24 4q4.55 0 7.625 2.875T35.4 14q3.95.2 6.275 2.675Q44 19.15 44 22.5q0 3.5-2.475 6T35.5 31Z"/></svg>
                            <p>Foggy</p>
                        </label>
                        <input type="checkbox" id="foggy" name="weather[]" value="foggy"/>
                    </div>
                    <div class="group_entry">
                        <label for="snowy">
                            <svg width="100%" height="100%" viewBox="0 0 48 48"><path fill="currentColor" d="M12 38q-.7 0-1.225-.525-.525-.525-.525-1.225 0-.7.525-1.225Q11.3 34.5 12 34.5q.7 0 1.225.525.525.525.525 1.225 0 .7-.525 1.225Q12.7 38 12 38Zm24 0q-.7 0-1.225-.525-.525-.525-.525-1.225 0-.7.525-1.225Q35.3 34.5 36 34.5q.7 0 1.225.525.525.525.525 1.225 0 .7-.525 1.225Q36.7 38 36 38Zm-18 8q-.7 0-1.225-.525-.525-.525-.525-1.225 0-.7.525-1.225Q17.3 42.5 18 42.5q.7 0 1.225.525.525.525.525 1.225 0 .7-.525 1.225Q18.7 46 18 46Zm6-8q-.7 0-1.225-.525-.525-.525-.525-1.225 0-.7.525-1.225Q23.3 34.5 24 34.5q.7 0 1.225.525.525.525.525 1.225 0 .7-.525 1.225Q24.7 38 24 38Zm6 8q-.7 0-1.225-.525-.525-.525-.525-1.225 0-.7.525-1.225Q29.3 42.5 30 42.5q.7 0 1.225.525.525.525.525 1.225 0 .7-.525 1.225Q30.7 46 30 46ZM14.5 31q-4.35 0-7.425-3.075T4 20.5q0-3.95 2.825-7.05 2.825-3.1 7.025-3.4 1.6-2.8 4.225-4.425Q20.7 4 24 4q4.55 0 7.625 2.875T35.4 14q3.95.2 6.275 2.675Q44 19.15 44 22.5q0 3.5-2.475 6T35.5 31Z"/></svg>
                            <p>Snowy</p>
                        </label>
                        <input type="checkbox" id="snowy" name="weather[]" value="snowy"/>
                    </div>
                    <div class="group_entry">
                        <label for="windy">
                            <svg width="100%" height="100%" viewBox="0 0 48 48"><path fill="currentColor" d="M23.25 40q-2.7 0-4.275-1.4-1.575-1.4-1.575-4.25h3.4q0 1.3.575 1.975T23.25 37q1.35 0 1.925-.6t.575-2.05q0-1.45-.575-2.125t-1.925-.675H4v-3h19.25q2.7 0 4.1 1.4 1.4 1.4 1.4 4.4 0 2.85-1.4 4.25t-4.1 1.4ZM4 19.6v-3h27.4q1.85 0 2.7-.875.85-.875.85-2.925t-.85-2.925Q33.25 9 31.4 9q-1.9 0-2.75 1.025-.85 1.025-.85 2.575h-3q0-2.9 1.75-4.75T31.4 6q3.05 0 4.8 1.775t1.75 5.025q0 3.25-1.75 5.025-1.75 1.775-4.8 1.775Zm33.6 16.5v-3q1.75 0 2.575-.975Q41 31.15 41 29.3q0-1.9-.925-2.75-.925-.85-2.675-.85H4v-3h33.4q3.1 0 4.85 1.75Q44 26.2 44 29.3q0 3.2-1.65 5-1.65 1.8-4.75 1.8Z"/></svg>
                            <p>Windy</p>
                        </label>
                        <input type="checkbox" id="windy" name="weather[]" value="windy"/>
                    </div>
                    <div class="group_entry">
                        <label for="night">
                        <svg height="100%" width="100%" viewBox="0 0 48 48"><path fill="currentColor" d="M12 27.5q2.55 0 4.675 1.375T19.8 32.65l.35.85h.95q2.25 0 3.825 1.6Q26.5 36.7 26.5 39t-1.625 3.9Q23.25 44.5 21 44.5h-9q-3.5 0-6-2.5t-2.5-6q0-3.55 2.5-6.025Q8.5 27.5 12 27.5ZM22.5 6q-.9 4.9.55 9.625 1.45 4.725 5 8.275 3.55 3.55 8.275 5.025Q41.05 30.4 46 29.5q-1.3 7.2-6.9 11.85Q33.5 46 26.2 46h-.4q1.75-1.2 2.725-3.05.975-1.85.975-3.95 0-3.3-2.1-5.65-2.1-2.35-5.25-2.75-1.5-2.8-4.2-4.45-2.7-1.65-5.95-1.65-1.6 0-3.125.425T6 26.2v-.4q0-7.3 4.65-12.875T22.5 6Z"/></svg>
                            <p>Night</p>
                        </label>
                        <input type="checkbox" id="night" name="weather[]" value="night"/>
                    </div>
                    <div class="group_entry">
                        <label for="breakdown">
                        <svg height="100%" width="100%" viewBox="0 0 48 48"><path fill="currentColor" d="M18 24.15V16H8q0-4.15 2.925-7.075T18 6h12v7.1L37 6h3v17.2h-3l-7-7.1v8.05ZM19.7 42q-.8 0-1.25-.675Q18 40.65 18 39.8V27.15h12V39.8q0 .9-.65 1.55T27.8 42Z"/></svg>
                            <p>Breakdown</p>
                        </label>
                        <input type="checkbox" id="breakdown" name="weather[]" value="breakdown"/>
                    </div>
                </form-group>
                <% if $HasGeneralSeats %>
                        <h2>Seat</h2>

                        <% if $ExperienceTrains() %>
                            <form-group class="logging_group train">

                                <% if $ExperienceTrains.Count > 1 %>
                                    <div class="train_selector">
                                        <select name="traindropdown" id="traindropdown" onchange="change_train(this)">
                                            <option value="-1">Select a <% if $CustomTrainType %>$CustomTrainType<% else %><% if $Traintype != "None" %>$Traintype<% else %>Thing<% end_if %><% end_if %></option>
                                            <% loop $ExperienceTrains() %>
                                                <option value="$SortOrder"><% if $Up.CustomTrainType %>$Up.CustomTrainType <% else %><% if $Up.Traintype != "None" %>$Up.Traintype: <% end_if %><% end_if %>$Title</option>
                                            <% end_loop %>
                                        </select>
                                    </div>
                                <% else %>
                                    <div class="train_selector hidden">
                                        <select name="traindropdown" id="traindropdown" onchange="change_train(this)">
                                            <% loop $ExperienceTrains() %>
                                                <option value="$SortOrder"><% if $Up.CustomTrainType %>$Up.CustomTrainType <% else %><% if $Up.Traintype != "None" %>$Up.Traintype: <% end_if %><% end_if %>$Title</option>
                                            <% end_loop %>
                                        </select>
                                    </div>
                                <% end_if %>

                                <% include TrainVisualizer PageController=$Top, PlaceOrientation=$SeatOrientation %>

                            </form-group>
                        <% else %>
                            <form-group class="logging_group">
                                <% if $Traintype != 'None' %>
                                    <label for="train"><% if $CustomTrainType %>$CustomTrainType<% else %>$Traintype<% end_if %></label>
                                    <input type="text" id="train" name="train">
                                <% end_if %>
                                <% if $HasWagons %>
                                    <label for="wagon" min="0" max="99">Wagon</label>
                                    <input type="number" id="wagon" name="wagon">
                                <% end_if %>
                                <% if $HasRows %>
                                    <label for="row" min="0" max="99">Row</label>
                                    <input type="number" id="row" name="row">
                                <% end_if %>
                                <% if $HasSeats %>
                                    <label for="seat" min="0" max="99">Seat</label>
                                    <input type="number" id="seat" name="seat">
                                <% end_if %>
                            </form-group>
                        <% end_if %>
                <% end_if %>
                <% if $Variants.Count > 0 || $Versions.Count > 0 %>
                    <h2>Variant</h2>
                    <form-group class="logging_group">
                        <% if $Variants.Count > 0 %>
                            <select name="variant" id="variant">
                                <option value="-1">Select a Variant</option>
                                <% loop $Variants %>
                                    <option value="$Title">$Title <% if $Defunct %>(Defunct)<% end_if %></option>
                                <% end_loop %>
                            </select>
                        <% end_if %>
                        <% if $Versions.Count > 0 %>
                            <select name="version" id="version">
                                <option value="-1">Select a Version</option>
                                <% loop $Versions %>
                                    <option value="$Title">$Title <% if $Defunct %>(Defunct)<% end_if %></option>
                                <% end_loop %>
                            </select>
                        <% end_if %>
                    </form-group>
                <% end_if %>
                <% if not $HasScore == "No Score" || $HasPodest %>
                    <h2>Score</h2>
                    <form-group class="logging_group">
                        <% if $HasScore == "Numeric Score" %>
                            <label for="score">Score</label>
                            <input type="numeric" id="score" name="score">
                        <% else_if $HasScore == "Text Score" %>
                            <label for="score">Score</label>
                            <input type="text" id="score" name="score">
                        <% else_if $HasScore == "Time Score" %>
                            <label for="score">Time</label>
                            <input type="time" id="score" name="score">
                        <% end_if %>
                        <% if $HasPodest %>
                            <select name="podest" id="podest">
                                <option value="-1">Select a Podest Place</option>
                                <option value="1">1st place</option>
                                <option value="2">2nd place</option>
                                <option value="3">3rd place</option>
                                <option value="4">4th place</option>
                                <option value="5">5th place</option>
                                <option value="6">6th place</option>
                                <option value="7">7th place</option>
                                <option value="8">8th place</option>
                                <option value="9">9th place</option>
                                <option value="10">10th place</option>
                                <option value="11">11th place</option>
                                <option value="12">12th place</option>
                                <option value="13">13th place</option>
                                <option value="14">14th place</option>
                                <option value="15">15th place</option>
                                <option value="16">16th place</option>
                                <option value="17">17th place</option>
                                <option value="18">18th place</option>
                                <option value="19">19th place</option>
                                <option value="20">20th place</option>
                            </select>
                        <% end_if %>
                    </form-group>
                <% end_if %>
                <h2>Notes</h2>
                <form-group class="logging_group">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes"></textarea>
                </form-group>

                <% if $CurrentUser.AutoLog %>
                    <% if $Stage || $Area %>
                        <div class="connected_logging">
                            <hr/>
                            <p>This will also auto-log the following:</p>
                            <br/>
                            <% if $Stage %>
                                <div class="experience_card state-{$Stage.State}" data-behaviour="experiencecard">
                                    <div class="experience_entry">
                                        <div class="experience_entry_image" style="background-image: url($Stage.PhotoGalleryImages.First.Image.FocusFill(200,200).Url)"></div>
                                        <div class="experience_entry_content">
                                            <h2 class="experience_title" style="text-align: left; margin: 0;">$Stage.Title</h2>
                                            <h4 class="experience_type">$Stage.Type.Title</h4>
                                        </div>
                                    </div>
                                </div>
                            <% end_if %>
                            <% if $Area %>
                                <div class="experience_card state-{$Area.State}" data-behaviour="experiencecard">
                                    <div class="experience_entry">
                                        <div class="experience_entry_image" style="background-image: url($Area.PhotoGalleryImages.First.Image.FocusFill(200,200).Url)"></div>
                                        <div class="experience_entry_content">
                                            <h2 class="experience_title" style="text-align: left; margin: 0;">$Area.Title</h2>
                                            <h4 class="experience_type">$Area.Type.Title</h4>
                                        </div>
                                    </div>
                                </div>
                            <% end_if %>
                        </div>
                    <% end_if %>
                <% else %>
                    <p>Auto-Logging of connected experiences is currently disabled. You can enable it in your <a href="profile">account settings</a>.</p>
                <% end_if %>

                <input data-behaviour="addlog_button" type="submit">
                <% include XPLLogo %>
            </form>
        </div>
    </div>


    <script>
        //Log-SeatSelection
        const trainselector = document.querySelector('.train_visualizer');
        const trains = trainselector.querySelectorAll('.train');
        const seatSelectors = trainselector.querySelectorAll('[data-behaviour="seat_selector"]');
        const trainField = document.querySelector('#train');
        const wagonField = document.querySelector('#wagon');
        const rowField = document.querySelector('#row');
        const seatField = document.querySelector('#seat');

        document.addEventListener("DOMContentLoaded", function (event) {
            if (seatSelectors) {
                seatSelectors.forEach(seat => {
                    seat.addEventListener('click', function() {
                        seatSelectors.forEach(otherSeat => {
                            otherSeat.classList.remove('selected');
                        });
                        seat.classList.toggle('selected');
                        trainField.value = seat.getAttribute('data-train') + "";
                        console.log(trainField.value);
                        wagonField.value = seat.getAttribute('data-wagon');
                        rowField.value = seat.getAttribute('data-row');
                        seatField.value = seat.getAttribute('data-seat');
                    });
                });
            }
        });

        function change_train(e){
            if(trainselector && trains){
                trains.forEach(train => {
                    train.classList.remove('active');
                    if(train.getAttribute('data-train') == e.value){
                        train.classList.add('active');
                    }
                });
            }
        }

    </script>

<% end_with %>
