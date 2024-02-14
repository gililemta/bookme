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
          const requestItem = document.createElement("div");
          requestItem.classList.add("deal-item");

          // Create and populate elements for each deal
          // const sellerEmail = document.createElement("p");
          // sellerEmail.textContent = "Seller Email: " + deal.seller_mail;
          // requestItem.appendChild(sellerEmail);

          const buyerEmail = document.createElement("dealDetail");
          buyerEmail.textContent = "שם הקונה:" + deal.buyer_mail;
          requestItem.appendChild(buyerEmail);

          const requeststatus = document.createElement("p");
          requeststatus.textContent = "סטטוס עסקה:" + deal.deal_status;
          requestItem.appendChild(requeststatus);

          const paymentStatus = document.createElement("p");
          paymentStatus.textContent = "סטטוס תשלום:" + deal.payment_status;
          requestItem.appendChild(paymentStatus);

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
