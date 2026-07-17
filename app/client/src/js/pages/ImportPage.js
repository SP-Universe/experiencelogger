// CSV import review: grows a textarea to fit its content instead of it
// being a fixed 3-row box - most values (e.g. "45 m") only need one line,
// while longer notes need more.
function autoResizeImportTextarea(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
}

const importTextareas = document.querySelectorAll('.import_edit_textarea');
if (importTextareas.length) {
    importTextareas.forEach(textarea => {
        // Rows still hidden behind a "+" control have no layout box yet
        // (scrollHeight reads 0) - sized when revealed instead, see below.
        if (!textarea.closest('[hidden]')) {
            autoResizeImportTextarea(textarea);
        }
        textarea.addEventListener('input', () => autoResizeImportTextarea(textarea));
    });
}

// CSV import review: each attraction/autofill group heading can carry a "+"
// control next to its title, letting staff reveal one of the pre-rendered
// but hidden "extra" field rows (fields the CSV left blank for that row) to
// fill in manually, instead of always showing every possible field.
const importAddFieldControls = document.querySelectorAll('[data-behaviour="import_addfield"]');
if (importAddFieldControls.length) {
    importAddFieldControls.forEach(control => {
        const button = control.querySelector('.import_addfield_button');
        const select = control.querySelector('.import_addfield_select');
        if (!button || !select) return;

        button.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            select.hidden = !select.hidden;
            if (!select.hidden) {
                select.focus();
            }
        });

        select.addEventListener('click', function (e) {
            e.stopPropagation();
        });

        select.addEventListener('change', function () {
            const rowId = select.value;
            if (!rowId) return;

            const row = document.getElementById(rowId);
            if (row) {
                row.hidden = false;
                const textarea = row.querySelector('.import_edit_textarea');
                if (textarea) autoResizeImportTextarea(textarea);
                const field = row.querySelector('textarea, input:not([type="checkbox"]), select');
                if (field) field.focus();
            }

            const chosenOption = select.querySelector('option[value="' + rowId + '"]');
            if (chosenOption) chosenOption.remove();
            select.value = '';
            select.hidden = true;

            // No more fields left to add - hide the "+" control entirely.
            if (select.options.length <= 1) {
                control.hidden = true;
            }
        });
    });

    document.addEventListener('click', function (e) {
        importAddFieldControls.forEach(control => {
            if (!control.contains(e.target)) {
                const select = control.querySelector('.import_addfield_select');
                if (select) select.hidden = true;
            }
        });
    });
}

// CSV import review: clicking an attraction's name in the Creates/AutoFills
// tables collapses or expands its field rows, so staff can shrink long lists
// down to just the headings while scanning through many attractions.
const importGroupToggles = document.querySelectorAll('[data-behaviour="import_group_toggle"]');
if (importGroupToggles.length) {
    importGroupToggles.forEach(toggle => {
        const tbody = toggle.closest('tbody');
        if (!tbody) return;

        toggle.addEventListener('click', function () {
            const collapsed = tbody.classList.toggle('import_group--collapsed');
            toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
        });
    });
}
