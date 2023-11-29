function attachBulkButtonListener() {
    let toggleCheckboxesButtons = document.querySelectorAll('#bulkButton');
    let isChecked = false;

    toggleCheckboxesButtons.forEach(button => {
        button.addEventListener('click', event => {
            event.preventDefault();
            handleBulkButtonClick();
        });
    });

    function handleBulkButtonClick() {
        let checkboxes = document.querySelectorAll('.form-check-input');
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = !isChecked; // Toggle checkbox state
        });

        isChecked = !isChecked;

        if (isChecked) {
            console.log(`All checkboxes checked.`);
        } else {
            console.log(`All checkboxes unchecked.`);
        }
    }
}

document.addEventListener('turbo:load', () => {
    attachBulkButtonListener();
});

document.addEventListener('DOMContentLoaded', () => {
    attachBulkButtonListener();
});
