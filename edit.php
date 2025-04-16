<?php
    session_start();
    include "config.php";
    $config = new Config();
    $config->connect_db();

    $id      = $_GET['id'] ?? null;
    $student = null;
    $error   = null;

    // Fetch student data
    if ($id) {
        $data = json_decode($config->get_single_student($id), true);
        if ($data['status'] == 404) {
            $error = "Student not found";
        } else {
            $student = $data['data'];
        }
    }

    // Handle update form
    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['update_button'])) {
        $name  = $_POST["name"];
        $age   = $_POST["age"];
        $city  = $_POST["city"];
        $hobby = $_POST["hobby"];

        $result = $config->update_record($id, $name, $age, $city, $hobby);
        if ($result) {
            $_SESSION["success"] = "Student updated successfully";
            header("Location: index.php");
            exit();
        } else {
            $error = "Failed to update student";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
<div class="container mt-5">

    <h2>Edit Student</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($student): ?>
        <form method="post" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="name" class="form-control" value="<?php echo $student['name']; ?>" placeholder="Name">
            </div>
            <div class="col-md-2">
                <input type="number" name="age" class="form-control" value="<?php echo $student['age']; ?>" placeholder="Age">
            </div>
            <div class="col-md-3">
                <input type="text" name="city" class="form-control" value="<?php echo $student['city']; ?>" placeholder="City">
            </div>
            <div class="col-md-3">
                <input type="text" name="hobby" class="form-control" value="<?php echo $student['hobby']; ?>" placeholder="Hobby">
            </div>
            <div class="col-md-1">
                <button type="submit" name="update_button" class="btn btn-success w-100">Update</button>
            </div>
        </form>
    <?php endif; ?>

</div>
</body>
</html>
