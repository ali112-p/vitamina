<?php
// اتصال بقاعدة البيانات
$servername = "localhost";
$username = "root"; // اسم المستخدم الخاص بقاعدة البيانات
$password = ""; // كلمة المرور الخاصة بقاعدة البيانات
$dbname = "CoffeeMenuDB"; // اسم قاعدة البيانات

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

// جلب الفئات من قاعدة البيانات
$categories_sql = "SELECT * FROM Categories";
$categories_result = $conn->query($categories_sql);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قائمة القهوة</title>
    <link rel="stylesheet" href="styles.css"> <!-- رابط لملف CSS -->
    <style>
        /* بعض التنسيقات الأساسية */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5; /* خلفية ناعمة */
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 15px;
            text-align: center;
            position: relative;
            height: 250px; /* ارتفاع الرأس */
            overflow: hidden;
        }
        .header-images {
            position: absolute;
            width: 100%;
            height: 100%;
            display: flex;
            animation: slide 20s infinite alternate; /* تأثير الانزلاق */
        }
        .header-images img {
            width: 100%;
            height: auto;
            flex: 0 0 100%;
            object-fit: cover;
        }
        @keyframes slide {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }
        .navbar {
            background-color: #ffffff; /* خلفية بيضاء */
            border-bottom: 2px solid #3b5998; /* خط تحت القائمة */
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
        }
        .navbar ul li {
            margin: 0;
        }
        .navbar ul li a {
            display: block;
            color: #3b5998; /* لون النص */
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            transition: background-color 0.3s, color 0.3s; /* تأثير التغيير */
            border-radius: 4px;
        }
        .navbar ul li a:hover {
            background-color: #3b5998;
            color: #fff; /* تغيير لون النص عند التحويم */
        }
        .content {
            margin: 20px auto;
            max-width: 1200px; /* تحديد عرض المحتوى */
            padding: 20px;
        }
        .menu-category {
            margin: 20px 0;
            padding: 20px;
            background-color: #ffffff; /* خلفية بيضاء */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .menu-category:hover {
            transform: scale(1.05); /* تكبير العنصر عند التحويم */
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
        .menu-category h2 {
            background-color: #3b5998; /* خلفية داكنة */
            color: #fff;
            padding: 15px;
            margin: 0;
            border-radius: 8px 8px 0 0;
            font-size: 22px;
        }
        .menu-item {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            transition: background-color 0.3s ease;
        }
        .menu-item:hover {
            background-color: #f0f8ff; /* تغيير لون الخلفية عند التحويم */
        }
        .menu-item:last-child {
            border-bottom: none;
        }
        .menu-item h3 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }
        .menu-item p {
            margin: 5px 0;
            color: #666;
        }
    </style>
</head>
<body>
    <header>
        
        <div class="">
            <img src="aa.jpeg" width="600px">
       
        </div>
    </header>

    <div class="navbar">
        <ul>
            <?php
            // عرض الفئات كروابط
            if ($categories_result->num_rows > 0) {
                while($category = $categories_result->fetch_assoc()) {
                    echo "<li><a href='#" . $category['CategoryID'] . "'>" . $category['CategoryName'] . "</a></li>";
                }
            } else {
                echo "<li><a href='#'>لا توجد فئات</a></li>";
            }
            ?>
        </ul>
    </div>

    <div class="content">
        <?php
        // عرض الفئات والعناصر
        if ($categories_result->num_rows > 0) {
            // إعادة مؤشر البيانات إلى البداية
            $categories_result->data_seek(0);
            while($category = $categories_result->fetch_assoc()) {
                echo "<div class='menu-category' id='" . $category['CategoryID'] . "'>";
                echo "<h2>" . $category['CategoryName'] . "</h2>";

                $menu_sql = "SELECT * FROM CoffeeMenu WHERE CategoryID=" . $category['CategoryID'];
                $menu_result = $conn->query($menu_sql);

                if ($menu_result->num_rows > 0) {
                    while($item = $menu_result->fetch_assoc()) {
                        if (!empty($item['ImageURL'])) {
                        echo "<div class='menu-item'>";
                        echo"<table border=0>";
                        echo"<tr>";
                        echo "<td><h3>" . $item['Name'] . "</h3></td>";
                          // عرض صورة القهوة إذا كانت موجودة
                    
                          echo "<td rowspan='3'><img src='" . $item['ImageURL'] . "' alt='" . $item['Name'] . "width='500' height='90''></td>";
                          echo"</tr>";
                        echo"<tr>";
                        echo "<td>" . $item['Description'] . "</td>";
                        echo"</tr>";
                        echo"<tr>";
                        echo "<td>السعر: $" . $item['Price'] . "</td>";
                        echo"</tr>";
                        echo"</table>";
                        }
                        else{
                            echo "<div class='menu-item'>";
                            echo"<table border=0>";
                            echo"<tr>";
                            echo "<td><h3>" . $item['Name'] . "</h3></td>";
                              echo"</tr>";
                            echo"<tr>";
                            echo "<td>" . $item['Description'] . "</td>";
                            echo"</tr>";
                            echo"<tr>";
                            echo "<td>السعر: $" . $item['Price'] . "</td>";
                            echo"</tr>";
                            echo"</table>";
                        }
  
                        

                        
                        
                        echo "</div>";
                    }
                } else {
                    echo "<p>لا توجد عناصر في هذه الفئة.</p>";
                }

                echo "</div>";
            }
        } else {
            echo "<p>لا توجد فئات.</p>";
        }

        // إغلاق الاتصال بقاعدة البيانات
        $conn->close();
        ?>
    </div>
</body>
</html>
