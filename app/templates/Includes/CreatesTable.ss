<% if $Creates %>
    <div class="import_block import_block--creates">
        <h2>Will be created <span class="import_count">{$Creates.Count}</span></h2>
        <p class="import_intro">Check "Skip" for any field you don't want to import, or "Skip whole attraction" to leave one out entirely.</p>
        <table class="import_creates_table">
            <thead>
                <tr>
                    <th class="import_creates_col--field">Field</th>
                    <th class="import_creates_col--value">Value</th>
                    <th class="import_creates_col--action">Skip</th>
                </tr>
            </thead>
            <% loop $Creates %>
                <tbody class="import_creates_group">
                    <tr class="import_creates_heading">
                        <th>
                            <button type="button" class="import_group_toggle" data-behaviour="import_group_toggle" aria-expanded="true">
                                <span class="import_group_toggle_icon" aria-hidden="true"></span>
                                $Title
                            </button>
                            <span class="import_creates_meta">$FieldCount fields</span>
                            <% if $ExtraFields %>
                                <span class="import_addfield" data-behaviour="import_addfield">
                                    <button type="button" class="import_addfield_button" title="Feld hinzufügen" aria-label="Feld hinzufügen">+</button>
                                    <select class="import_addfield_select" hidden>
                                        <option value="">Feld wählen…</option>
                                        <% loop $ExtraFields %>
                                            <option value="{$RowId}">$FieldLabel</option>
                                        <% end_loop %>
                                    </select>
                                </span>
                            <% end_if %>
                        </th>
                        <th class="import_creates_col--action" colspan="2">
                            <label class="import_toggle">
                                <input type="checkbox" name="skipCreate_{$Index}" value="1">
                                <span>Skip whole attraction</span>
                            </label>
                        </th>
                    </tr>
                    <% loop $Fields %>
                        <tr>
                            <td data-label="Field" class="import_creates_col--field">$FieldLabel</td>
                            <td data-label="Value" class="import_creates_col--value">$ValueControl.RAW</td>
                            <td data-label="Skip" class="import_creates_col--action">
                                <label class="import_toggle">
                                    <input type="checkbox" name="skipCreateField_{$Index}_{$FieldIndex}" value="1"<% if $DefaultSkip %> checked<% end_if %>>
                                    <span>Skip</span>
                                </label>
                            </td>
                        </tr>
                    <% end_loop %>
                    <% loop $ExtraFields %>
                        <tr id="{$RowId}" class="import_creates_extra_row" hidden>
                            <td data-label="Field" class="import_creates_col--field">$FieldLabel</td>
                            <td data-label="Value" class="import_creates_col--value">$ValueControl.RAW</td>
                            <td data-label="Skip" class="import_creates_col--action">
                                <label class="import_toggle">
                                    <input type="checkbox" name="skipCreateField_{$Index}_{$FieldIndex}" value="1"<% if $DefaultSkip %> checked<% end_if %>>
                                    <span>Skip</span>
                                </label>
                            </td>
                        </tr>
                    <% end_loop %>
                </tbody>
            <% end_loop %>
        </table>
    </div>
<% end_if %>
