<% if $MissingItems %>
    <div class="import_block import_block--missing">
        <h2>Missing from import <span class="import_count">{$MissingItems.Count}</span></h2>
        <p class="import_intro">These attractions exist for this place but were not found in the uploaded file. Select the ones that should be marked as defunct.</p>
        <table class="import_missing_table">
            <thead>
                <tr>
                    <th class="import_missing_col--title">Attraction</th>
                    <th class="import_missing_col--state">Current status</th>
                    <th class="import_missing_col--action">Mark as defunct</th>
                </tr>
            </thead>
            <tbody>
                <% loop $MissingItems %>
                    <tr<% if $HasError %> class="import_row--error"<% end_if %>>
                        <td data-label="Attraction" class="import_missing_col--title">
                            $Title
                            <% if $HasError %><span class="import_field_error">$ErrorMessage</span><% end_if %>
                        </td>
                        <td data-label="Current status" class="import_missing_col--state">$State</td>
                        <td data-label="Mark as defunct" class="import_missing_col--action">
                            <label class="import_toggle">
                                <input type="checkbox" name="markDefunct_{$Index}" value="1">
                                <span>Mark as defunct</span>
                            </label>
                        </td>
                    </tr>
                <% end_loop %>
            </tbody>
        </table>
    </div>
<% end_if %>
