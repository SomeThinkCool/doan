<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TTG Shop Clone</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../style/styleh.css">
<body>
  <header class="header">
    <div class="container">
      <!-- Logo -->
      <div class="logo">
        <a href="../index.php">
          <img src="https://theme.hstatic.net/1000288298/1001020793/14/logo.png?v=1437" alt="TTG Logo">
        </a>
      </div>

      <!-- Search bar -->
      <div class="search-bar">
  <form action="../search.php" method="GET">
    <input type="text" name="keyword" placeholder="Tìm kiếm sản phẩm..." required>
    <button type="submit"><i class="fas fa-search"></i></button>
  </form>
</div>  

      <!-- Icons -->
      <div class="header-icons">
        <a href="../login.php"><i class="fas fa-user"></i></a>
        <a href="../cart.php"><i class="fas fa-shopping-cart"></i></a>
      </div>
    </div>
  </header>
</body>
</html>