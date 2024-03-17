// JavaScript function to update label text and show success message and uploaded image
function updateLabel() {
  var label = document.getElementById("fileInputLabel");
  label.textContent = "הקובץ הועלה בהצלחה";
  label.style.backgroundColor = "green";

  // Display success message
  var successMessage = document.getElementById("uploadSuccessMessage");
  successMessage.textContent = "התמונה הועלתה בהצלחה!";

  // Display uploaded image
  var uploadedImage = document.getElementById("uploadedImage");
  uploadedImage.src = URL.createObjectURL(
    document.getElementById("bookPicture").files[0]
  );
  uploadedImage.style.display = "block";
}

// JavaScript function to toggle the price field based on the selected dealType
function togglePriceField() {
  var dealType = document.getElementById("dealType").value;
  var priceField = document.getElementById("priceField");

  if (dealType === "2") {
    priceField.style.display = "inline";
  } else {
    priceField.style.display = "none";
  }
}
// JavaScript function to reset all filters
function resetFilters() {
  // Reset text inputs
  document.getElementById('cities').value = '';
  document.getElementById('book').value = '';
  document.getElementById('author').value = '';
  
  // Reset select elements to their first option
  document.getElementById('genre').selectedIndex = 0;
  document.getElementById('deal_type').selectedIndex = 0;
  document.getElementById('sort').selectedIndex = 0;

  document.getElementById('searchPanel').submit();
}

console.log('Cities will load!');
// JavaScript function to autoconplete city name in city field
document.addEventListener("DOMContentLoaded", function () {
  console.log('Cities loaded!');
  const cityInput = document.getElementById("cities");
  const autocompleteDropdown = document.getElementById("autocomplete-dropdown");

  cityInput.addEventListener("input", function () {
    console.log('Cities input change!');
    const inputValue = cityInput.value.trim();

    if (inputValue.length > 2) {
      // Fetch data from the API
      fetchCityData(inputValue)
        .then((data) => {
          displayAutocomplete(inputValue, data);
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
        });
    } else {
      clearAutocomplete();
    }
  });

  function fetchCityData(query) {
    const apiUrl = `https://data.gov.il/api/3/action/datastore_search?resource_id=5c78e9fa-c2e2-4771-93ff-7f400a12f7ba`;
    return fetch(apiUrl)
      .then((response) => response.json())
      .then((data) => data.result.records)
      .catch((error) => {
        console.error("Error fetching city data:", error);
        throw error;
      });
  }

  function displayAutocomplete(inputValue, data) {
    autocompleteDropdown.innerHTML = "";
    data
      .filter((city) => {
        return city["שם_ישוב"].indexOf(inputValue) > -1;
      })
      .forEach((city) => {
        const listItem = document.createElement("li");
        listItem.textContent = city["שם_ישוב"];
        listItem.addEventListener("click", function () {
          cityInput.value = city["שם_ישוב"].trim();
          clearAutocomplete();
        });
        autocompleteDropdown.appendChild(listItem);
      });

    // Display the dropdown
    autocompleteDropdown.style.display = "block";
  }

  function clearAutocomplete() {
    autocompleteDropdown.innerHTML = "";
    // Hide the dropdown
    autocompleteDropdown.style.display = "none";
  }
});
