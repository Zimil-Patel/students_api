<?php
    session_start();
    include "config.php";
    $config = new Config();
    $config->connect_db();
    $edit_data = [
        'id'    => '',
        'name'  => '',
        'age'   => '',
        'city'  => '',
        'hobby' => '',
    ];
    $edit_mode = false;

    // if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_button"])) {
    //     $edit_mode = true;
    //     $id        = $_POST["id"];

    //     $student = $config->get_single_student($id);

    //     if ($student) {
    //         $edit_data = $student;
    //     }
    // }

    // Add record on submit button press
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_button"])) {
        $name  = $_POST["name"];
        $age   = $_POST["age"];
        $city  = $_POST["city"];
        $hobby = $_POST["hobby"];

        if (empty($name)) {
            $_SESSION["error"] = "Please enter your Name";
        } elseif (empty($age)) {
            $_SESSION["error"] = "Please enter your Age";
        } elseif (empty($city)) {
            $_SESSION["error"] = "Please enter your City";
        } elseif (empty($hobby)) {
            $_SESSION["error"] = "Please enter your Hobby";
        } else {
            $result = $config->insert_record($name, $age, $city, $hobby);
            if ($result) {
                $_SESSION["success"] = "Record added successfully";
            } else {
                $_SESSION["error"] = "Failed to insert record";
            }
        }
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit();

    }

    // Delete record method
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['delete_button'])) {
        $id = $_POST["id"];

        $result = $config->delete_record($id);
        if ($result) {
            $_SESSION["success"] = "Record deleted successfully";
            header("Location: " . $_SERVER["PHP_SELF"]);
            exit();
        } else {
            $error = "Failed to delete record";
        }
    }

    // // Update record on form submit
    // if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_button"])) {
    //     $id    = $_POST["id"];
    //     $name  = $_POST["name"];
    //     $age   = $_POST["age"];
    //     $city  = $_POST["city"];
    //     $hobby = $_POST["hobby"];

    //     if (empty($name) || empty($age) || empty($city) || empty($hobby)) {
    //         $_SESSION["error"] = "Please fill all fields to update.";
    //     } else {
    //         $result = $config->update_record($id, $name, $age, $city, $hobby);
    //         if ($result) {
    //             $_SESSION["success"] = "Record updated successfully.";
    //         } else {
    //             $_SESSION["error"] = "Failed to update record.";
    //         }
    //     }

    //     header("Location: " . $_SERVER["PHP_SELF"]);
    //     exit();
    // }
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


    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION["error"] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php unset($_SESSION["error"]);endif; ?>

    <?php if (isset($_SESSION["success"])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION["success"] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
    <?php endif;
        unset($_SESSION["success"]);
    ?>

    <h2 class="mb-4" style="color:white">
        <?php echo $edit_mode ? 'Edit Student Record' : 'Add New Student'; ?>
    </h2>
    <form class="row g-3" method="post">
        <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
        <div class="col-md-3">
            <input type="text" class="form-control" placeholder="Name" name="name" value="<?php echo $edit_data['name']; ?>">
        </div>
        <div class="col-md-2">
            <input type="number" class="form-control" placeholder="Age" name="age" value="<?php echo $edit_data['age']; ?>">
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control" placeholder="City" name="city" value="<?php echo $edit_data['city']; ?>">
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control" placeholder="Hobby" name="hobby" value="<?php echo $edit_data['hobby']; ?>">
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-<?php echo $edit_mode ? 'success' : 'primary'; ?> w-100" name="<?php echo $edit_mode ? 'update_button' : 'add_button'; ?>">
                <?php echo $edit_mode ? 'Update' : 'Add'; ?>
            </button>

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
        ?>

        <?php foreach ($students as $student): ?>

            <?php if ($student["id"] != 1): ?>
                <tr>
                    <td colspan="6">
                        <hr style="margin-left: 4rem; margin-right: 10rem; margin-top: 10px; margin-bottom: 10px;">
                    </td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="6">
                        <hr class="border-0 mt-2 mb-0">
                    </td>
                </tr>
            <?php endif; ?>

            <tr>
                <td><?php echo $student['id'] ?></td>
                <td><?php echo $student['name'] ?></td>
                <td><?php echo $student['age'] ?></td>
                <td><?php echo $student['city'] ?></td>
                <td><?php echo $student['hobby'] ?></td>

                <td>
                    <div class="d-flex justify-content-center gap-2">
                        <form method="get" action="edit.php">
                            <input type="hidden" name="id" value="<?php echo $student['id'] ?>">
                            <button class="btn btn-sm btn-warning ps-4 pe-4" name="edit_button">Edit</button>
                        </form>

                         <!-- <form method="post">
                            <input type="hidden" name="id" value="<?php echo $student['id'] ?>">
                            <button class="btn btn-sm btn-warning me-2 ps-4 pe-4" name="edit_button">Edit</button>
                        </form> -->

                        <form method="post">
                            <input type="hidden" name="id" value="<?php echo $student['id'] ?>">
                            <button class="btn btn-sm btn-danger ps-3 pe-3" name="delete_button">Delete</button>
                        </form>
                    </div>
                </td>




            </tr>

        <?php endforeach; ?>


        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
