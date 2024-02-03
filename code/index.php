<!DOCTYPE html>
<html lang="he" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/primaryPage.css" />
    <script src="header/loadHeaderAndFooter.js"></script>
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
    <title>BookMe Homepage</title>
  </head>
  <body>
    <div id="header-container"></div>

    <section>
      <form id="searchPanel" action="index.php" method="GET">
        <div>
          <label for="city">ערים למסירה:</label>
          <input type="text" id="city" name="city">
        </div>
        <div>
          <label for="author">שם סופר:</label>
          <input type="text" id="author" name="author">
        </div>
        <div>
          <label for="genre">ז'אנר:</label>
          <input type="text" id="genre" name="genre">
        </div>
        <div>
          <label for="deal_type">סוג עסקה:</label>
          <select id="deal_type" name="deal_type">
            <option value="">בחר סוג עסקה</option>
            <option value="1">החלפה</option>
            <option value="2">מכירה</option>
          </select>
        </div>
        <div>
          <label for="sort">סידור:</label>
          <select id="sort" name="sort">
            <option value="">בחר סידור</option>
            <option value="price_low_high">מחיר - מהנמוך לגבוה</option>
            <option value="price_high_low">מחיר - מהגבוה לנמוך</option>
          </select>
        </div>
        <div>
          <input type="submit" value="סנן ומיין">
        </div>
      </form>
      <?php
      include 'db/db.php';

      // SQL to fetch books data
      $sql = "SELECT * FROM books_users WHERE book_quantity >= 1 AND deal_type IN (1, 2)";
        // Append conditions based on user input
        if (!empty($_GET['city'])) {
            $sql .= " AND user_cities LIKE '%" . $conn->real_escape_string($_GET['city']) . "%'";
        }
        if (!empty($_GET['author'])) {
            $sql .= " AND book_author_name LIKE '%" . $conn->real_escape_string($_GET['author']) . "%'";
        }
        if (!empty($_GET['genre'])) {
            $sql .= " AND book_genre = '" . $conn->real_escape_string($_GET['genre']) . "'";
        }
        if (!empty($_GET['deal_type'])) {
            $sql .= " AND deal_type = " . intval($_GET['deal_type']);
          }
      if (!empty($_GET['sort'])) {
        if ($_GET['sort'] === 'price_low_high') {
            $sql .= " ORDER BY book_required_price ASC"; // Sort by price from low to high
        } else if ($_GET['sort'] === 'price_high_low') {
            $sql .= " ORDER BY book_required_price DESC"; // Sort by price from high to low
        }
    }
    
      $result = $conn->query($sql);
      echo "<div class='books-container'>"; 
      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
          echo "<div class='book-item'>";
          echo "<img src='" . $row["book_picture"] . "' alt='" . $row["book_name"] . "' />";
          echo "<h3>" . $row["book_name"] . "</h3>";
          echo '<span id="book_author_name">' . $row["book_author_name"] . "</span>";
          echo "<span>ז'אנר: " . $row["book_genre"] . "</span>";
          if (!empty($row["book_required_price"])) {
            echo "<span>מחיר: " . $row["book_required_price"] . "</span>";
          }
          if ($row["deal_type"] == 1) {
            echo "<span>ספר להחלפה</span>";
          }
          // echo "<p>כמות במלאי: " . $row["book_quantity"] . "</p>";
          echo "</div>";
        }
      } else {
        echo "0 results";
      }
      echo "</div>";
      $conn->close();
      ?>
    </section>

    <div id="footer-container"></div>
  </body>
</html>
