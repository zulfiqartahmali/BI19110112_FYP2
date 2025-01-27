<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Residential College Asset Management System</title>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <link rel="stylesheet" href="include/style/style.css">
    <style>
        /* The Modal (background) */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
        }

        /* Close Button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Center the image */
        .modal-img {
            width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>

<header>
    <div id="navbar">
        <div class="logo">
            <a href="#">Asset Management</a>
        </div>
       <header>
    <!-- Navigation Bar -->
    <?php include 'include/navbar/navbar.php'; ?>
</header
        </div>
    </div>
</header>

<main>
    <section id="main-title">
        <h1>Residential College Asset Management System</h1>
        <p>Efficiently manage your assets with real-time tracking.</p>
    </section>

    <section id="filter-section">
        <h2>Filter Assets</h2>
        <div id="filterContainer">
            <input type="text" placeholder="Search by Asset Name..." id="searchBox">
        </div>
    </section>
    <section id="main-title">
        <h1>Add New Asset</h1>
       
    </section>
    <section id="add-asset-form">
        <form method="POST" action="index.php" class="booking-form">
           
            <div>
                <label for="name">Asset Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="description">Asset Description</label>
                <input type="text" id="description" name="description" required>
            </div>
            <div>
                <label for="location">Location</label>
                <input type="text" id="location" name="location" required>
            </div>
            <div>
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="Available">Available</option>
                    <option value="Booked">Booked</option>
                </select>
            </div>
            <button type="submit" name="add_asset" class="submit-button">Add Asset</button>
        </form>
    </section>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_asset'])) {
        $conn = new mysqli("localhost", "root", "", "asset_management");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $name = $conn->real_escape_string($_POST['name']);
        $description = $conn->real_escape_string($_POST['description']);
        $location = $conn->real_escape_string($_POST['location']);
        $status = $conn->real_escape_string($_POST['status']);
        $barcodeData = uniqid(); // Barcode data is generated

        $sql = "INSERT INTO asset (AssetName, AssetDescription, Location, Status, Barcode) 
                VALUES ('$name', '$description', '$location', '$status', '$barcodeData')";

        if ($conn->query($sql) === TRUE) {
            header("Location: index.php");
            exit();
        } else {
            echo "<p style='color: red; text-align: center;'>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }

        $conn->close();
    }
    ?>

    <section id="asset-list">
        <div class="table-container">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Barcode</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $conn = new mysqli("localhost", "root", "", "asset_management");

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $sql = "SELECT AssetID, AssetName, AssetDescription, Location, Status, Barcode FROM asset";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $barcode = $row['Barcode'];
                            $barcodeURL = "https://barcode.tec-it.com/barcode.ashx?data=" . urlencode("http://localhost/fyp/view_items.php?id=" . $row['AssetID']); // Update URL to use AssetID
                            echo "<tr>
                                <td>" . $row['AssetID'] . "</td>
                                <td>" . $row['AssetName'] . "</td>
                                <td>" . $row['AssetDescription'] . "</td>
                                <td>" . $row['Location'] . "</td>
                                <td>" . $row['Status'] . "</td>
                                <td>
                                    <button class='show-barcode-btn' onclick='openModal(" . $row['AssetID'] . ")'>Show Barcode</button>
                                </td>
                                <td>
                                    <a href='edit_assets.php?id=" . $row['AssetID'] . "'>Edit</a> |
                                    <a href='view_items.php?id=" . $row['AssetID'] . "'>View</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No assets found.</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<footer>
    <p>&copy; 2024 Your Website. All rights reserved.</p>
</footer>

<!-- Modal for barcode image -->
<div id="barcodeModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <img id="barcodeImg" class="modal-img" src="" alt="Barcode">
    </div>
</div>

<script>
    // Open the modal to show the barcode
    function openModal(assetID) {
        var modal = document.getElementById('barcodeModal');
        var barcodeImg = document.getElementById('barcodeImg');
        
        var barcodeURL = "https://barcode.tec-it.com/barcode.ashx?data=" + encodeURIComponent("http://localhost/fyp/view_items.php?id=" + assetID);
        barcodeImg.src = barcodeURL;
        
        modal.style.display = "block";
    }

    // Close the modal when the 'X' button is clicked
    function closeModal() {
        var modal = document.getElementById('barcodeModal');
        modal.style.display = "none";
    }
</script>

</body>
</html>
