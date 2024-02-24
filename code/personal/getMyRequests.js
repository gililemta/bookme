// Function to fetch requests
function fetchRequests() {
  fetch("getMyRequests.php")
    .then((response) => response.json())
    .then((data) => {
      const requestsContainer = document.getElementById("requests-container");

      // Clear previous content
      requestsContainer.innerHTML = "";

      if (data && data.length > 0) {
        data.forEach((deal) => {
          // Create the deal item container
          const requestItem = document.createElement("div");
          requestItem.classList.add("deal-item");

          // Function to create title-value pairs and add them to the deal item
          function createProp(title, value) {
            const propContainer = document.createElement("div");
            propContainer.classList.add("deal-item-prop");

            const titleElement = document.createElement("span");
            titleElement.textContent = title + ": ";
            titleElement.classList.add("deal-item-title");

            const valueElement = document.createElement("span");
            valueElement.textContent = value;
            valueElement.classList.add("deal-item-value");

            propContainer.appendChild(titleElement);
            propContainer.appendChild(valueElement);

            return propContainer;
          }

          // Create the props container
          const propsContainer = document.createElement("div");
          propsContainer.classList.add("deal-fields");
          propsContainer.style.flex = "5";

          // Add book name
          propsContainer.appendChild(createProp("שם הספר", deal.book_name));

          // Add buyer name
          propsContainer.appendChild(createProp("שם הקונה", deal.buyer_name));

          // Add deal status
          propsContainer.appendChild(
            createProp("סטטוס עסקה", deal.deal_status)
          );

          // Add payment status
          propsContainer.appendChild(
            createProp("סטטוס תשלום", deal.payment_status)
          );

          // Conditionally add "מחיר" or "ספרים מוצעים" based on deal_type
          if (deal.deal_type === 2) {
            // Add suggested price
            propsContainer.appendChild(
              createProp("מחיר", deal.book_suggested_price)
            );
          } else if (deal.deal_type === 1) {
            // Add suggested books
            const suggestedBooks = document.createElement("div");
            suggestedBooks.classList.add("deal-item-prop");
            suggestedBooks.classList.add("suggested-books");

            const titleElement = document.createElement("span");
            titleElement.textContent = "ספרים מוצעים: ";
            titleElement.classList.add("deal-item-title");

            const booksList = document.createElement("span");
            booksList.textContent = deal.suggested_books.join(", ");
            booksList.classList.add("deal-item-value");

            suggestedBooks.appendChild(titleElement);
            suggestedBooks.appendChild(booksList);

            propsContainer.appendChild(suggestedBooks);
          }

          // Create the buttons container
          const buttonsContainer = document.createElement("div");
          buttonsContainer.classList.add("deal-actions");
          buttonsContainer.style.flex = "1";

          // Add confirm button
          const confirmButton = document.createElement("button");
          confirmButton.textContent = "אישור";
          confirmButton.classList.add("confirm-button");
          confirmButton.addEventListener("click", () => {
            // Add logic for confirming the deal here
            console.log("Deal confirmed:", deal);
            // Create a new XMLHttpRequest object
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'postConfirm.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Set content type to form-encoded
            
            xhr.onload = function () {
              if (xhr.status == 200) {
                console.log(xhr.responseText);
              } else {
                console.error('Request failed. Status: ' + xhr.status);
              }
            };
            
            xhr.onerror = function () {
              console.error('Request failed');
            };
            
            xhr.timeout = 2000; 
            
            // Construct the form-encoded data
            var formData = 'id=' + encodeURIComponent(deal.deal_id) + '&status=' + encodeURIComponent('אושר');
            
            // Send the request with the form-encoded data
            xhr.send(formData);

            xhr.send(JSON.stringify(requestData));
          });
          buttonsContainer.appendChild(confirmButton);
 
          // Add reject button
          const rejectButton = document.createElement("button");
          rejectButton.textContent = "דחייה";
          rejectButton.classList.add("reject-button");
          rejectButton.addEventListener("click", () => {
            // Add logic for rejecting the deal here
            console.log("Deal rejected:", deal);
          });
          buttonsContainer.appendChild(rejectButton);

          // Append the props and buttons containers to the deal item
          requestItem.appendChild(propsContainer);
          requestItem.appendChild(buttonsContainer);

          // Append the deal item to the container
          requestsContainer.appendChild(requestItem);
        });
      } else {
        const noRequestsMessage = document.createElement("p");
        noRequestsMessage.textContent = "No requests found.";
        requestsContainer.appendChild(noRequestsMessage);
      }
    })
    .catch((error) => console.error("Error fetching requests:", error));
}
