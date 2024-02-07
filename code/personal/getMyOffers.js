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
          const offerItem = document.createElement("div");
          offerItem.classList.add("deal-item");

          // Create and populate elements for each deal
          const sellerEmail = document.createElement("p");
          sellerEmail.textContent = "Seller Email: " + deal.seller_mail;
          offerItem.appendChild(sellerEmail);

          const buyerEmail = document.createElement("p");
          buyerEmail.textContent = "Buyer Email: " + deal.buyer_mail;
          offerItem.appendChild(buyerEmail);

          const offerstatus = document.createElement("p");
          offerstatus.textContent = "Deal Status: " + deal.deal_status;
          offerItem.appendChild(offerstatus);

          const paymentStatus = document.createElement("p");
          paymentStatus.textContent = "Payment Status: " + deal.payment_status;
          offerItem.appendChild(paymentStatus);

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
