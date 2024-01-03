// Get the data table slug
function getDataTableSlug() {
    const element = document.querySelector('[data-controller="table"]');
    if (element) {
      const dataTableSlug = element.getAttribute('data-table-slug');
      return dataTableSlug;
    } else {
      return 'Element not found';
    }
  }
  
  // Function to retrieve local storage value
  function getLocalStorageValue() {
    let dataTableSlug = getDataTableSlug();
    return dataTableSlug;
  }
  
  // Function to configure columns
  function configureColumns() {
    let dataTableSlugValue = getLocalStorageValue();
    let dataFromLocalStorage = localStorage.getItem(dataTableSlugValue);
  
    if (dataFromLocalStorage) {
      console.log('Data retrieved from local storage:', dataFromLocalStorage);
      // Do something with the retrieved data
      return dataFromLocalStorage;
    } else {
      console.log('No data found in local storage for the given key.');
      return null;
    }
  }

//   TODO:: wip
  
