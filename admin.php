<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CoffeeMenuDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// وظيفة لإنشاء المجلدات إذا لم تكن موجودة
function createDirectoryIfNotExists($path) {
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }
}

// معالجة إضافة فئة جديدة
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addCategory'])) {
    $categoryName = $conn->real_escape_string($_POST['categoryName']);
    
    $sql = "INSERT INTO Categories (CategoryName) VALUES ('$categoryName')";
    $conn->query($sql);
}

// معالجة تعديل فئة
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editCategory'])) {
    $categoryID = intval($_POST['categoryID']);
    $categoryName = $conn->real_escape_string($_POST['categoryName']);
    
    if (!empty($categoryID) && !empty($categoryName)) {
        $sql = "UPDATE Categories SET CategoryName='$categoryName' WHERE CategoryID=$categoryID";
        if ($conn->query($sql) === TRUE) {
            echo "Category updated successfully.";
        } else {
            echo "Error updating category: " . $conn->error;
        }
    } else {
        echo "All fields must not be empty.";
    }
}

// معالجة إضافة منتج جديد
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addCoffee'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $categoryID = intval($_POST['categoryID']);
    $price = floatval($_POST['price']);
    $imageURL = '';

    $coffeeDir = 'uploads/coffees';
    createDirectoryIfNotExists($coffeeDir);

    if (isset($_FILES['coffeeImage']) && $_FILES['coffeeImage']['error'] == UPLOAD_ERR_OK) {
        $imageName = $_FILES['coffeeImage']['name'];
        $imageTmpName = $_FILES['coffeeImage']['tmp_name'];
        $imageDest = $coffeeDir . '/' . basename($imageName);

        if (move_uploaded_file($imageTmpName, $imageDest)) {
            $imageURL = $imageDest;
        } else {
            echo "Failed to move uploaded file.";
        }
    }

    $sql = "INSERT INTO CoffeeMenu (Name, Description, CategoryID, Price, ImageURL) VALUES ('$name', '$description', $categoryID, $price, '$imageURL')";
    $conn->query($sql);
}

// معالجة تعديل منتج
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editCoffee'])) {
    $coffeeID = intval($_POST['coffeeID']);
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $categoryID = intval($_POST['categoryID']);
    $price = floatval($_POST['price']);
    $imageURL = '';

    $coffeeDir = 'uploads/coffees';
    createDirectoryIfNotExists($coffeeDir);

    // تحديث الصورة إذا تم تحميل واحدة جديدة
    if (isset($_FILES['coffeeImage']) && $_FILES['coffeeImage']['error'] == UPLOAD_ERR_OK) {
        $imageName = $_FILES['coffeeImage']['name'];
        $imageTmpName = $_FILES['coffeeImage']['tmp_name'];
        $imageDest = $coffeeDir . '/' . basename($imageName);

        if (move_uploaded_file($imageTmpName, $imageDest)) {
            $imageURL = $imageDest;
        } else {
            echo "Failed to move uploaded file.";
        }
    }

    if (!empty($coffeeID) && !empty($name) && !empty($description) && !empty($categoryID) && !empty($price)) {
        $sql = "UPDATE CoffeeMenu SET Name='$name', Description='$description', CategoryID=$categoryID, Price=$price" . ($imageURL ? ", ImageURL='$imageURL'" : "") . " WHERE CoffeeID=$coffeeID";
        if ($conn->query($sql) === TRUE) {
            echo "Coffee updated successfully.";
        } else {
            echo "Error updating coffee: " . $conn->error;
        }
    } else {
        echo "All fields must not be empty.";
    }
}

// معالجة حذف منتج
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteCoffee'])) {
    $coffeeID = intval($_POST['coffeeID']);
    $sql = "DELETE FROM CoffeeMenu WHERE CoffeeID=$coffeeID";
    $conn->query($sql);
}

// معالجة حذف فئة
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteCategory'])) {
    $categoryID = intval($_POST['categoryID']);
    $sql = "DELETE FROM Categories WHERE CategoryID=$categoryID";
    $conn->query($sql);
}

// جلب القائمة الحالية
$categories = $conn->query("SELECT * FROM Categories");
$menu = $conn->query("SELECT CoffeeMenu.*, Categories.CategoryName FROM CoffeeMenu INNER JOIN Categories ON CoffeeMenu.CategoryID = Categories.CategoryID");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Coffee Menu</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        h1 { color: #333; text-align: center; }
        form { margin-bottom: 20px; }
        input[type="text"], input[type="number"], input[type="file"], select { padding: 10px; margin: 5px; }
        input[type="submit"] { padding: 10px; background-color: #5cb85c; color: white; border: none; cursor: pointer; }
        input[type="submit"]:hover { background-color: #4cae4c; }
        table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { color: red; }
        .search { margin-bottom: 20px; }
    </style>
</head>
<body>

<h1>Admin - Coffee Menu</h1>

<!-- نموذج لإضافة فئة جديدة -->
<form action="" method="POST">
    <h2>Add New Category</h2>
    <input type="text" name="categoryName" placeholder="Category Name" required>
    <input type="submit" name="addCategory" value="Add Category">
</form>

<!-- نموذج لإضافة منتج جديد -->
<form action="" method="POST" enctype="multipart/form-data">
    <h2>Add New Coffee</h2>
    <input type="text" name="name" placeholder="Coffee Name" required>
    <input type="text" name="description" placeholder="Description" required>
    <select name="categoryID" required>
        <option value="">Select Category</option>
        <?php while($row = $categories->fetch_assoc()): ?>
            <option value="<?php echo $row['CategoryID']; ?>"><?php echo $row['CategoryName']; ?></option>
        <?php endwhile; ?>
    </select>
    <input type="number" name="price" step="0.01" placeholder="Price" required>
    <input type="file" name="coffeeImage" accept="image/*">
    <input type="submit" name="addCoffee" value="Add Coffee">
</form>

<!-- نموذج البحث -->
<form action="" method="GET" class="search">
    <h2>Search Coffee</h2>
    <input type="text" name="search" placeholder="Search by name or description" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <input type="submit" value="Search">
</form>

<!-- عرض قائمة الفئات الحالية -->
<h2>Categories</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Actions</th>
    </tr>
    <?php
    // إعادة استعلام الفئات بعد التعديل
    $categories = $conn->query("SELECT * FROM Categories");
    while($row = $categories->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['CategoryID']; ?></td>
        <td><?php echo $row['CategoryName']; ?></td>
        <td>
            <form action="" method="POST" style="display:inline;">
                <input type="hidden" name="categoryID" value="<?php echo $row['CategoryID']; ?>">
                <input type="text" name="categoryName" value="<?php echo $row['CategoryName']; ?>" required>
                <input type="submit" name="editCategory" value="Edit">
            </form>
            <form action="" method="POST" style="display:inline;">
                <input type="hidden" name="categoryID" value="<?php echo $row['CategoryID']; ?>">
                <input type="submit" name="deleteCategory" value="Delete" onclick="return confirm('Are you sure you want to delete this category?');">
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<!-- عرض قائمة المنتجات الحالية -->
<h2>Coffee Menu</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Description</th>
        <th>Category</th>
        <th>Price</th>
        <th>Image</th>
        <th>Actions</th>
    </tr>
    <?php
    // إضافة عملية البحث إذا كانت هناك طلبات بحث
    $searchQuery = '';
    if (isset($_GET['search'])) {
        $searchTerm = $conn->real_escape_string($_GET['search']);
        $searchQuery = " WHERE CoffeeMenu.Name LIKE '%$searchTerm%' OR CoffeeMenu.Description LIKE '%$searchTerm%'";
    }
    $menuQuery = "SELECT CoffeeMenu.*, Categories.CategoryName FROM CoffeeMenu INNER JOIN Categories ON CoffeeMenu.CategoryID = Categories.CategoryID" . $searchQuery;
    $menu = $conn->query($menuQuery);
    
    while($row = $menu->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['CoffeeID']; ?></td>
        <td><?php echo $row['Name']; ?></td>
        <td><?php echo $row['Description']; ?></td>
        <td><?php echo $row['CategoryName']; ?></td>
        <td><?php echo $row['Price']; ?></td>
        <td><img src="<?php echo $row['ImageURL']; ?>" alt="Coffee Image" width="100"></td>
        <td>
            <form action="" method="POST" enctype="multipart/form-data" style="display:inline;">
                <input type="hidden" name="coffeeID" value="<?php echo $row['CoffeeID']; ?>">
                <input type="text" name="name" value="<?php echo $row['Name']; ?>" required>
                <input type="text" name="description" value="<?php echo $row['Description']; ?>" required>
                <select name="categoryID" required>
                    <option value="">Select Category</option>
                    <?php
                    // إعادة استعلام الفئات لتحديث قائمة الفئات
                    $categories = $conn->query("SELECT * FROM Categories");
                    while($cat = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $cat['CategoryID']; ?>" <?php if ($cat['CategoryID'] == $row['CategoryID']) echo 'selected'; ?>><?php echo $cat['CategoryName']; ?></option>
                    <?php endwhile; ?>
                </select>
                <input type="number" name="price" step="0.01" value="<?php echo $row['Price']; ?>" required>
                <input type="file" name="coffeeImage" accept="image/*">
                <input type="submit" name="editCoffee" value="Edit">
            </form>
            <form action="" method="POST" style="display:inline;">
                <input type="hidden" name="coffeeID" value="<?php echo $row['CoffeeID']; ?>">
                <input type="submit" name="deleteCoffee" value="Delete" onclick="return confirm('Are you sure you want to delete this coffee?');">
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php $conn->close(); // إغلاق الاتصال هنا ?>

</body>
</html>
