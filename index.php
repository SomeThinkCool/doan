<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('db_connect.php');

// Lấy tất cả sản phẩm có khuyến mãi
$promotions = [];
if ($conn) {
    $sql = "SELECT * FROM products WHERE discount_percentage > 0 ORDER BY discount_percentage DESC";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $promotions[] = $row;
        }
    } else {
        echo "Lỗi cơ sở dữ liệu (khuyến mãi): " . mysqli_error($conn);
    }

    // Lấy 10 sản phẩm bán chạy
    $bestSellers = [];
    $sqlBestSellers = "SELECT * FROM products ORDER BY sold_quantity DESC LIMIT 10";
    $resultBestSellers = mysqli_query($conn, $sqlBestSellers);
    if ($resultBestSellers) {
        while ($row = mysqli_fetch_assoc($resultBestSellers)) {
            $bestSellers[] = $row;
        }
    } else {
        echo "Lỗi khi lấy sản phẩm bán chạy: " . mysqli_error($conn);
    }

    // Lấy sản phẩm khuyến mãi hot nhất
    $hotPromoProduct = null;
    $sqlHotPromo = "SELECT * FROM products WHERE discount_percentage > 0 ORDER BY discount_percentage DESC, sold_quantity DESC LIMIT 1";
    $resultHotPromo = mysqli_query($conn, $sqlHotPromo);
    if ($resultHotPromo && mysqli_num_rows($resultHotPromo) > 0) {
        $hotPromoProduct = mysqli_fetch_assoc($resultHotPromo);
    } else {
        echo "Lỗi khi lấy sản phẩm khuyến mãi hot nhất: " . mysqli_error($conn);
    }
} else {
    echo "Không thể kết nối cơ sở dữ liệu.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trang chủ | PC Store</title>
    <meta name="description" content="Mua PC Gaming, Workstation, linh kiện giá rẻ, khuyến mãi lớn tại PC Store.">
    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />

</head>
<body>
<?php include('./handf/header.php'); ?>
    <div class="main-body">
        <div class="sidebar-category">
            <h3>Danh mục sản phẩm</h3>
            <ul>
                <li><a href="./danhmuc/all_pc.php">PC</a></li>
                <li><a href="./danhmuc/pc_workstation.php">PC Workstation</a></li>
                <li><a href="">Tự Build Cấu Hình PC</a></li>
                <li><a href="./danhmuc/pc_office.php">PC Văn Phòng</a></li>
                <li><a href="./danhmuc/pc_amd.php">PC AMD Gaming</a></li>
                <li><a href="./danhmuc/pc_core_ultra.php">PC Core Ultra</a></li>
                <li><a href="./danhmuc/pc_gia_lap.php">PC Giả Lập - Ảo Hóa</a></li>
                <li><a href="./danhmuc/pc_mini.php">PC Mini</a></li>
                <li><a href="#">Linh kiện máy tính</a></li>
                <li><a href="#">Màn hình</a></li>   
                <li><a href="#">+ Xem thêm</a></li>
            </ul>
        </div>
      
    <div class="main-banner-area">
        <a href="./danhmuc/all_pc.php" style="display:block;">
        <div class="main-slider swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide"><img src="https://theme.hstatic.net/1000288298/1001020793/14/slide_1_img.jpg?v=1437" alt="Slide 1"></div>
                <div class="swiper-slide"><img src="https://theme.hstatic.net/1000288298/1001020793/14/slide_2_img.jpg?v=1437" alt="Slide 2"></div>
            </div>
            <!-- Banner chính -->
            <div class="swiper-button-prev main-prev">
                
            </div>
            <div class="swiper-button-next main-next"></div>
        </div>
        </a>
    </div>
          
      
    <div class="banner-right">
        <a href="./danhmuc/pc_gaming.php"><img src="https://theme.hstatic.net/1000288298/1001020793/14/banner_top_1_img_large.jpg?v=1437" alt="PC Gaming" /></a>
        <a href="./danhmuc/pc_amd.php"><img src="https://theme.hstatic.net/1000288298/1001020793/14/banner_top_2_img_large.jpg?v=1437" alt="Banner 2" /></a>
        <a href="./danhmuc/pc_workstation.php"><img src="https://theme.hstatic.net/1000288298/1001020793/14/banner_top_3_img_large.jpg?v=1437" alt="Banner 3" /></a>
        </div>
    </div>
    
    <div class="sub-banner-row">
        <img src="https://theme.hstatic.net/1000288298/1001020793/14/categorybanner_1_img.jpg?v=1437" alt="Tư Vấn Build PC">
        <a href="./danhmuc/pc_gia_lap.php"><img src="https://theme.hstatic.net/1000288298/1001020793/14/categorybanner_2_img.jpg?v=1437" alt="PC Giả Lập Ảo Hóa"></a>
        <a href="./danhmuc/pc_office.php"><img src="https://theme.hstatic.net/1000288298/1001020793/14/categorybanner_3_img.jpg?v=1437" alt="Máy Tính Văn Phòng"></a>
        <img src="https://theme.hstatic.net/1000288298/1001020793/14/categorybanner_4_img.jpg?v=1437" alt="Màn Hình Gaming">
    </div>
      

<div class="promo-section">
    <div class="promo-header">
        <span>⚡ KHUYẾN MẠI SHOCK NHẤT 🔥</span>
    </div>

    <div class="swiper promo-swiper">
    <div class="swiper-wrapper">
    <?php foreach ($promotions as $product): 
$originalPrice = $product['original_price'];
$discount = $product['discount_percentage'];
$newPrice = $originalPrice * (1 - $discount / 100);
?>
    <div class="swiper-slide">
        <div class="promo-card">
            <a href="product_detail.php?id=<?= $product['id']; ?>" class="product-link">
                <img src="<?= htmlspecialchars($product['product_image']); ?>" alt="<?= htmlspecialchars($product['product_name']); ?>">
            </a>
            <div class="promo-info">
                <h4><a href="product_detail.php?id=<?= $product['id']; ?>"><?= htmlspecialchars($product['product_name']); ?></a></h4>
                <div class="price-box">
                    <span class="new-price"><?= number_format($newPrice, 0); ?>₫</span>
                    <span class="old-price"><?= number_format($originalPrice, 0); ?>₫</span>
                    <span class="discount">-<?= $discount; ?>%</span>
                </div>
                <form method="post" action="add_to_cart.php">
                    <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                    <button type="submit" class="add-to-cart">
                        <i class="fa fa-shopping-bag"></i> Thêm vào giỏ
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

    </div>
    <!-- Promo Swiper -->
<div class="swiper-button-prev promo-prev"></div>
<div class="swiper-button-next promo-next"></div>
</div>

</div>
<div class="bestseller-section">
    <div class="promo-header">
        <span>🔥 SẢN PHẨM BÁN CHẠY NHẤT</span>
    </div>

    <div class="swiper bestseller-swiper">
        <div class="swiper-wrapper">
            <?php foreach ($bestSellers as $product): 
                $originalPrice = $product['original_price'];
                $discount = $product['discount_percentage'];
                $newPrice = $originalPrice * (1 - $discount / 100);
            ?>
                <div class="swiper-slide">
                    <div class="promo-card">
                        <a href="product_detail.php?id=<?= $product['id']; ?>" class="product-link">
                            <img src="<?= htmlspecialchars($product['product_image']); ?>" alt="<?= htmlspecialchars($product['product_name']); ?>">
                        </a>
                        <div class="promo-info">
                            <h4><a href="product_detail.php?id=<?= $product['id']; ?>"><?= htmlspecialchars($product['product_name']); ?></a></h4>
                            <div class="price-box">
                                <span class="new-price"><?= number_format($newPrice, 0); ?>₫</span>
                                <?php if ($discount > 0): ?>
                                    <span class="old-price"><?= number_format($originalPrice, 0); ?>₫</span>
                                    <span class="discount">-<?= $discount; ?>%</span>
                                <?php endif; ?>
                            </div>
                            <form method="post" action="add_to_cart.php">
                                <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                                <button type="submit" class="add-to-cart">
                                    <i class="fa fa-shopping-bag"></i> Thêm vào giỏ
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="swiper-button-prev bestseller-prev"></div>
        <div class="swiper-button-next bestseller-next"></div>
    </div>
</div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script src="./indexscript/main-banner.js"></script>
    <script src="./indexscript/promo.js"></script>
    <script src="./indexscript/bestseller.js"></script>

    
<!-- Nút bấm mở form chat -->
<button id="chatButton" onclick="toggleChat()">💬 Gửi tin</button>

<!-- Form gửi tin nhắn -->
<div id="chatForm">
    <form method="POST" action="send_message.php">
        <textarea name="message" placeholder="Nhập tin nhắn..."></textarea>
        <button type="submit">Gửi cho Admin</button>
    </form>
</div>

<script>
function toggleChat() {
    var chatForm = document.getElementById('chatForm');
    chatForm.style.display = chatForm.style.display === 'none' ? 'block' : 'none';
}
</script>
<?php include('./handf/footer.php'); ?>


</body>
</html>
<!-- Y4VEG4YKJCJ25RLTPEXUOJRVH3HK3SRB -->