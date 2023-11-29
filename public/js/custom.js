function attachBulkButtonListener() {
    let isChecked = false;

    document.addEventListener('change', function(event) {
        if (event.target.matches('#bulkButton')) {
            handleBulkButtonClick(event.target.checked);
        }
    });

    function handleBulkButtonClick(checked) {
        let checkboxes = document.querySelectorAll('.form-check-input');
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = checked;
        });

        isChecked = checked;

        if (isChecked) {
            console.log(`All checkboxes checked.`);
        } else {
            console.log(`All checkboxes unchecked.`);
        }
    }
}

document.addEventListener('turbo:load', () => {
    // Append checkbox to the first column header
    const firstHeader = document.querySelector('.table thead tr th:first-child');
    const checkboxHTML = '<input class="form-check-input" type="checkbox" id="bulkButton">';
    firstHeader.innerHTML = checkboxHTML + firstHeader.innerHTML; // Prepend checkbox
    attachBulkButtonListener();
});

document.addEventListener('DOMContentLoaded', () => {
    // Append checkbox to the first column header
    const firstHeader = document.querySelector('.table thead tr th:first-child');
    const checkboxHTML = '<input class="form-check-input" type="checkbox" id="bulkButton">';
    firstHeader.innerHTML = checkboxHTML + firstHeader.innerHTML; // Prepend checkbox
    attachBulkButtonListener();
});
