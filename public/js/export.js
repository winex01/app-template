// Function to retrieve array of excluded column IDs from the cookie
function getExcludedColumnIdsFromCookie() {
  const cookies = document.cookie.split(';').map(cookie => cookie.trim());
  const excludeColumnsCookie = cookies.find(cookie => cookie.startsWith('excludeColumns='));

  if (excludeColumnsCookie) {
    const cookieValue = excludeColumnsCookie.split('=')[1];
    return JSON.parse(cookieValue);
  } else {
    return [];
  }
}

function handleExportButtonClick() {
  let uncheckedCheckboxes = document.querySelectorAll('.dropdown-column-menu input[type="checkbox"]:not(:checked)');
  
  let uncheckedIds = [];
  uncheckedCheckboxes.forEach(function(checkbox) {
    uncheckedIds.push(checkbox.getAttribute('id'));
  });

  if (uncheckedIds.length > 0) {
    const cookieValue = JSON.stringify(uncheckedIds);
    document.cookie = `excludeColumns=${cookieValue}; path=/`;
    console.log('Stored in excludeColumns cookie:', uncheckedIds);
  }
}

function attachExportButtonClickHandler() {
  let exportButton = document.getElementById('btn-export');
  if (exportButton) {
    exportButton.removeEventListener('click', handleExportButtonClick);
    exportButton.addEventListener('click', handleExportButtonClick);
  }
}

function updateExportButtonClickHandler() {
  attachExportButtonClickHandler();
  const excludedColumnIds = getExcludedColumnIdsFromCookie();
  console.log('Excluded Column IDs from cookie:', excludedColumnIds);
}

document.addEventListener('turbo:load', function() {
  updateExportButtonClickHandler();
});

document.addEventListener('DOMContentLoaded', function() {
  updateExportButtonClickHandler();
});

// Additional code to handle checkbox changes
document.querySelectorAll('.dropdown-column-menu input[type="checkbox"]').forEach(function(checkbox) {
  checkbox.addEventListener('change', function() {
    updateExportButtonClickHandler();
  });
});
