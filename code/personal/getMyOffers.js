// Function to fetch offers
function fetchOffers() {
  fetch("getMyOffers.php")
    .then((response) => response.json())
    .then((data) => {
      const offersContainer = document.getElementById("offers-container");

      // Clear previous content
      offersContainer.innerHTML = "";

      if (data && data.length > 0) {
        data.forEach((deal) => {
          // Create the offer item container
          const offerItem = document.createElement("div");
          offerItem.classList.add("deal-item");

          // Function to create title-value pairs and add them to the offer item
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

          // Add seller email
          propsContainer.appendChild(createProp("שם המוכר", deal.seller_name));

          // Add deal status
          propsContainer.appendChild(
            createProp("סטטוס העסקה", deal.deal_status)
          );

          // Add payment status
          propsContainer.appendChild(
            createProp("סטטוס התשלום", deal.payment_status)
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

          // Add reject button
          const rejectButton = document.createElement("button");
          rejectButton.textContent = "ביטול";
          rejectButton.classList.add("reject-button");
          rejectButton.addEventListener("click", () => {
            // Add logic for rejecting the deal here
            console.log("Deal rejected:", deal);
          });
          buttonsContainer.appendChild(rejectButton);

          // Append the props and buttons containers to the offer item
          offerItem.appendChild(propsContainer);
          offerItem.appendChild(buttonsContainer);

          // Append the offer item to the container
          offersContainer.appendChild(offerItem);
        });
      } else {
        const noOffersMessage = document.createElement("p");
        noOffersMessage.textContent = "No offers found.";
        offersContainer.appendChild(noOffersMessage);
      }
    })
    .catch((error) => console.error("Error fetching offers:", error));
}
