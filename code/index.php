<!DOCTYPE html>
<html lang="he" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <script src="header/loadHeaderAndFooter.js"></script>
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
    <title>BookMe Homepage</title>
  </head>
  <body>
    <div id="header-container"></div>

    <section>
      <h2>Welcome to our website!</h2>
      <form action="" method="GET">
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
            <option value="0">אין עסקה/ביטול</option>
            <option value="1">בתהליך</option>
            <option value="2">אי קבלה</option>
          </select>
        </div>
        <div>
          <label for="sort">סידור:</label>
          <select id="sort" name="sort">
            <option value="">בחר סידור</option>
            <option value="no_deal">אין עסקה/ביטול</option>
            <option value="in_process">בתהליך</option>
            <option value="no_acceptance">אי קבלה</option>
            <option value="price_low_high">מחיר - מהנמוך לגבוה</option>
            <option value="price_high_low">מחיר - מהגבוה לנמוך</option>
          </select>
        </div>
        <div>
          <input type="submit" value="סנן ומיין">
        </div>
      </form>
      <?php
      include 'db.php';

      // SQL to fetch books data
      $sql = "SELECT * FROM books_users WHERE book_quantity >= 1";
        // Append conditions based on user input
      if (isset($_GET['city']) && $_GET['city'] != '') {
        $sql .= " AND user_cities LIKE '%" . $conn->real_escape_string($_GET['city']) . "%'";
      }
      if (isset($_GET['author']) && $_GET['author'] != '') {
        $sql .= " AND book_author_name = '" . $conn->real_escape_string($_GET['author']) . "'";
      }
      if (isset($_GET['genre']) && $_GET['genre'] != '') {
        $sql .= " AND book_genre = '" . $conn->real_escape_string($_GET['genre']) . "'";
      }
      if (isset($_GET['deal_type']) && $_GET['deal_type'] != '') {
        $sql .= " AND deal_type = " . intval($_GET['deal_type']);
      }
      if (isset($_GET['sort'])) {
        switch ($_GET['sort']) {
          case 'no_deal':
              $sql .= " ORDER BY deal_type = 0 DESC";
              break;
          case 'in_process':
              $sql .= " ORDER BY deal_type = 1 DESC";
              break;
          case 'no_acceptance':
              $sql .= " ORDER BY deal_type = 2 DESC";
              break;
          case 'price_low_high':
              $sql .= " ORDER BY book_required_price ASC"; 
              break;
          case 'price_high_low':
              $sql .= " ORDER BY book_required_price DESC"; 
              break;
        }
      }
    
      $result = $conn->query($sql);
      echo "<div class='books-container'>"; 
      if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
          echo "<div class='book-item'>";
          echo "<h3>" . $row["book_name"] . " של " . $row["book_author_name"] . "</h3>";
          echo "<p>ז'אנר: " . $row["book_genre"] . "</p>";
          echo "<p>מחיר: " . $row["book_required_price"] . "</p>";
          $deal_type_text = '';
          switch($row["deal_type"]) {
              case 0:
                  $deal_type_text = 'אין עסקה/ביטול';
                  break;
              case 1:
                  $deal_type_text = 'בתהליך';
                  break;
              case 2:
                  $deal_type_text = 'אי קבלה';
                  break;
              default:
                  $deal_type_text = 'לא ידוע'; 
          }
          echo "<p>סוג עסקה: " . $deal_type_text . "</p>";
          echo "<p>כמות במלאי: " . $row["book_quantity"] . "</p>";
          echo "<img src='" . $row["book_picture"] . "' alt='" . $row["book_name"] . "' />";
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
