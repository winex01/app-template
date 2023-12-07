function attachBulkButtonListener() {
    const bulkButton = document.querySelector('#bulkButton');
    if (bulkButton) {
        bulkButton.addEventListener('change', function(event) {
            handleBulkButtonClick(event.target.checked);
        });
    }

    const checkboxes = document.querySelectorAll('.table .form-check-input');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateButtonClass();
        });
    });
}

function handleBulkButtonClick(checked) {
    let checkboxes = document.querySelectorAll('.table .form-check-input');
    checkboxes.forEach(checkbox => {
        checkbox.checked = checked;
    });

    updateButtonClass();
}

function updateButtonClass() {
    const anyChecked = Array.from(document.querySelectorAll('.table .form-check-input'))
        .some(checkbox => checkbox.checked);

    const deleteButton = document.querySelector('.btn-delete');
    const restoreButton = document.querySelector('.btn-restore'); // Assuming this class exists for the restore button

    if (anyChecked) {
        deleteButton.classList.add('btn-danger');
        restoreButton.classList.add('btn-success');
    } else {
        deleteButton.classList.remove('btn-danger');
        restoreButton.classList.remove('btn-success');
    }
}


function addColumnHeaderCheckbox() {
    const tableRowsWithCheckboxes = document.querySelectorAll('.table tbody tr .form-check-input');
    if (tableRowsWithCheckboxes.length > 0) {
        const firstHeader = document.querySelector('.table thead tr th:first-child');
        const checkboxHTML = '<input class="form-check-input" type="checkbox" id="bulkButton">';
        firstHeader.innerHTML = checkboxHTML + firstHeader.innerHTML; // Prepend checkbox
        attachBulkButtonListener();
    }
}

// Load the column header checkbox on page load and Turbo load
document.addEventListener('DOMContentLoaded', addColumnHeaderCheckbox);
document.addEventListener('turbo:load', addColumnHeaderCheckbox);

// Update button class on initial load
document.addEventListener('DOMContentLoaded', updateButtonClass);
document.addEventListener('turbo:load', updateButtonClass);
