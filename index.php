<?php
    session_start();
    include "config.php";
    $config = new Config();
    $config->connect_db();
    $error = null;

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_button"])) {
        $name  = $_POST["name"];
        $age   = $_POST["age"];
        $city  = $_POST["city"];
        $hobby = $_POST["hobby"];

        if (empty($name)) {
            $error = "Please enter your Name";
        } elseif (empty($age)) {
            $error = "Please enter your Age";
        } elseif (empty($city)) {
            $error = "Please enter your City";
        } elseif (empty($hobby)) {
            $error = "Please enter your Hobby";
        } else {
            $result = $config->insert_record($name, $age, $city, $hobby);
            if ($result) {
                $_SESSION["success"] = "Record added successfully";
                header("Location: " . $_SERVER["PHP_SELF"]);
                exit();
            } else {
                $error = "Failed to insert record";
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark">

<div class="container mt-5">
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION["success"])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION["success"] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION["success"]); ?>
    <?php endif; ?>
    <h2 class="mb-4" style="color:white">Add New Student</h2>
    <form class="row g-3" method="post">
        <div class="col-md-3">
            <input type="text" class="form-control" placeholder="Name" name="name">
        </div>
        <div class="col-md-2">
            <input type="number" class="form-control" placeholder="Age" name="age">
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control" placeholder="City" name="city">
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control" placeholder="Hobby" name="hobby">
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary w-100" name="add_button">Add</button>
        </div>
    </form>

    <hr class="my-4", style="color:white">

    <h3 class="mb-3 mt-5 text-start" style="color:white">Student Records</h3>
    <table class="table text-center table-borderless table-sm">
        <thead>
            <tr class="table-dark table-active">
                <th class="pt-3 pb-3" style="border-top-left-radius: 10px;">ID</th>
                <th class="pt-3 pb-3">Name</th>
                <th class="pt-3 pb-3">Age</th>
                <th class="pt-3 pb-3" >City</th>
                <th class="pt-3 pb-3">Hobby</th>
                <th class="pt-3 pb-3" style="border-top-right-radius: 10px;">Actions</th>
            </tr>
        </thead>
        <tbody class="table-dark">
            <?php
                $response = json_decode($config->fetch_student_records(), true);
                $students = $response["data"];

                foreach ($students as $student) {

                    if ($student["id"] != 1) {
                        echo "
                    <tr>
                    <td colspan=6><hr style=\"margin-left: 4rem; margin-right: 10rem; margin-top: 10px; margin-bottom: 10px;\"></td>
                    </tr>";
                    } else {
                        echo "<tr><td colspan=6><hr class=\"border-0 mt-2 mb-0\"></td></tr>";
                    }
                    echo "
                    <tr>
                    <td>{$student['id']}</td>
                    <td>{$student['name']}</td>
                    <td>{$student['age']}</td>
                    <td>{$student['city']}</td>
                    <td>{$student['hobby']}</td>
                    <td>
                        <button class='btn btn-sm btn-warning me-2 ps-4 pe-4'>Edit</button>
                        <button class='btn btn-sm btn-danger ps-3 pe-3'>Delete</button>
                    </td>
                </tr>";
                }
            ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
