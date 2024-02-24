document.addEventListener("DOMContentLoaded", function () {
  // Fetch data from get_books.php
  fetch("getBooks.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.books && data.books.length > 0) {
        // Update the DOM with book information
        const booksContainer = document.querySelector(".books-container");

        data.books.forEach((book) => {
          const bookItem = document.createElement("div");
          bookItem.classList.add("book-item");

          if (book.book_picture) {
            const img = document.createElement("img");
            img.src = book.book_picture;
            img.alt = book.book_name;
            bookItem.appendChild(img);
          } else {
            const img = document.createElement("img");
            img.src = "/bookMeLogo.png";
            bookItem.appendChild(img);
          }

          const h3 = document.createElement("h3");
          h3.textContent = book.book_name;
          bookItem.appendChild(h3);

          const authorSpan = document.createElement("span");
          authorSpan.id = "book_author_name";
          authorSpan.textContent = book.book_author_name;
          bookItem.appendChild(authorSpan);

          const genreSpan = document.createElement("span");
          genreSpan.textContent = "ז'אנר: " + book.book_genre;
          bookItem.appendChild(genreSpan);

          if (book.book_required_price) {
            const priceSpan = document.createElement("span");
            priceSpan.textContent = "מחיר: " + book.book_required_price;
            bookItem.appendChild(priceSpan);
          }

          if (book.deal_type == 1) {
            const dealSpan = document.createElement("span");
            dealSpan.textContent = "ספר להחלפה";
            bookItem.appendChild(dealSpan);
          }

          const quantitySpan = document.createElement("span");
          quantitySpan.id = "book_quantity";
          quantitySpan.textContent ="כמות: " + book.book_quantity;
          bookItem.appendChild(quantitySpan);

          booksContainer.appendChild(bookItem);
        });
      } else {
        // No results
        console.log("0 results");
      }
    })
    .catch((error) => console.error("Error fetching data:", error));
});
