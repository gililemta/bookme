<!DOCTYPE html>
<html lang="he" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="css/primaryPage.css" />
    <script src="header/loadHeaderAndFooter.js"></script>
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
    <script src="js/script.js"></script>
    <title>BookMe Homepage</title>
  </head>
  <body>
    <div id="header-container"></div>
      <!-- Indication bar login -->
  <div class="indication-bar" id="indicationBar">
    התחברת בהצלחה!
    <span class="close-button" onclick="hideIndicationBar()">x</span>
  </div>
    <section>
      <form id="searchPanel" action="index.php" method="GET" autocomplete="false">
        <div>
          <label for="cities">עיר:</label>
          <input
            autocomplete="false"
            type="text"
            id="cities"
            name="cities"
            value="<?php echo $_GET['cities']; ?>"
          />
          <ul id="autocomplete-dropdown" style="display: none"></ul>
        </div>
        <div>
          <label for="author">שם הספר:</label>
          <input type="text" id="book" name="book" value="<?php echo $_GET['book']; ?>">
        </div>
        <div>
          <label for="author">שם סופר:</label>
          <input type="text" id="author" name="author" value="<?php echo $_GET['author']; ?>">
        </div>
        <div>
        <label for="genre">ז'אנר:</label>
          <select id="genre" name="genre">
              <option value="">בחר ז׳אנר</option>
              <option value="רומן" <?php if(isset($_GET['genre']) && $_GET['genre'] == 'רומן') echo 'selected'; ?>>רומן</option>
              <option value="מדע בדיוני" <?php if(isset($_GET['genre']) && $_GET['genre'] == 'מדע בדיוני') echo 'selected'; ?>>מדע בדיוני</option>
              <option value="קומיקס" <?php if(isset($_GET['genre']) && $_GET['genre'] == 'קומיקס') echo 'selected'; ?>>קומיקס</option>
              <option value="היסטוריה" <?php if(isset($_GET['genre']) && $_GET['genre'] == 'היסטוריה') echo 'selected'; ?>>היסטוריה</option>
              <option value="מסתורין" <?php if(isset($_GET['genre']) && $_GET['genre'] == 'מסתורין') echo 'selected'; ?>>מסתורין</option>
              <option value="ביוגרפיה" <?php if(isset($_GET['genre']) && $_GET['genre'] == 'ביוגרפיה') echo 'selected'; ?>>ביוגרפיה</option>
              <option value="ילדים" <?php if(isset($_GET['genre']) && $_GET['genre'] == 'ילדים') echo 'selected'; ?>>ילדים</option>
              <option value="פנטזיה" <?php if(isset($_GET['genre']) && $_GET['genre'] == 'פנטזיה') echo 'selected'; ?>>פנטזיה</option>
              <option value="שירה" <?php if(isset($_GET['genre']) && $_GET['genre'] == 'שירה') echo 'selected'; ?>>שירה</option>
              <option value="אחר" <?php if(isset($_GET['genre']) && $_GET['genre'] == 'אחר') echo 'selected'; ?>>אחר</option>
          </select>
        </div>
        <div>
          <label for="deal_type">סוג עסקה:</label>
          <select id="deal_type" name="deal_type">
              <option value="">בחר סוג עסקה</option>
              <option value="1" <?php if(isset($_GET['deal_type']) && $_GET['deal_type'] == '1') echo 'selected'; ?>>החלפה</option>
              <option value="2" <?php if(isset($_GET['deal_type']) && $_GET['deal_type'] == '2') echo 'selected'; ?>>מכירה</option>
          </select>
        </div>
        <div>
          <label for="sort">סידור:</label>
          <select id="sort" name="sort">
              <option value="">בחר סידור</option>
              <option value="price_low_high" <?php if(isset($_GET['sort']) && $_GET['sort'] == 'price_low_high') echo 'selected'; ?>>מחיר - מהנמוך לגבוה</option>
              <option value="price_high_low" <?php if(isset($_GET['sort']) && $_GET['sort'] == 'price_high_low') echo 'selected'; ?>>מחיר - מהגבוה לנמוך</option>
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
        if (!empty($_GET['cities'])) {
            $sql .= " AND user_cities LIKE '%" . $conn->real_escape_string($_GET['cities']) . "%'";
        }
        if (!empty($_GET['book'])) {
          $sql .= " AND book_name LIKE '%" . $conn->real_escape_string($_GET['book']) . "%'";
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
          echo "</div>";
        }
      } else {
        echo "0 results";
      }
      echo "</div>";
      $conn->close();
      ?>
    </section>

  </body>
</html>
