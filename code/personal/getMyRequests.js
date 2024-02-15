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

          // Create and populate elements for each deal
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

          // Add book name
          requestItem.appendChild(createProp("שם הספר", deal.book_name));

          // Add buyer name
          requestItem.appendChild(createProp("שם הקונה", deal.buyer_name));

          // Add deal status
          requestItem.appendChild(createProp("סטטוס עסקה", deal.deal_status));

          // Add payment status
          requestItem.appendChild(
            createProp("סטטוס תשלום", deal.payment_status)
          );

          // Conditionally add "מחיר" or "ספרים מוצעים" based on deal_type
          if (deal.deal_type === 2) {
            // Add suggested price
            requestItem.appendChild(
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

            requestItem.appendChild(suggestedBooks);
          }

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
