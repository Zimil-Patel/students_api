<?php

class Config
{
    public $connection;
    public $response;

    // CONNECT TO LOCAL DATABASE
    public function connect_db()
    {

        $serverName = "localhost";
        $username   = "root";
        $password   = "";
        $dbName     = "students";

        // MAKE CONNECTION
        $this->connection = new mysqli($serverName, $username, $password, $dbName);

        // CHECK CONNECTION
        if ($this->connection->connect_error) {
            $this->response = 500;
        } else {
            $this->response = 200;
        }
    }

    // FETCH STUDENTS RECORD
    public function fetch_student_records()
    {

        try {
            // Check if connection is valid
            if (! $this->connection) {
                throw new Exception("Database connection not established.");
            }

            $records = [];
            $query   = "SELECT * FROM data";
            $result  = $this->connection->query($query);
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $records[] = $row;
                }
                header("HTTP/1.0 200 Fetched");
                return json_encode(
                    [
                        "status"  => $this->response,
                        "message" => "Student Records Fetched Successfully",
                        "data"    => $records,
                    ]
                );
            } else {
                $data = [
                    "status"  => 404,
                    "message" => "No Customer Found!",
                ];
                header("HTTP/1.0 404 No Customer Found");
                return json_encode($data);
            }

        } catch (Exception $e) {
            echo json_encode([
                "status"  => 500,
                "message" => "Error: " . $e->getMessage(),
            ]);
        }
    }

    // INSERT RECORD
    public function insert_record($name, $age, $city, $hobby)
    {
        $query  = "INSERT INTO data( name, age, city, hobby) VALUES ('$name','$age','$city','$hobby')";
        $result = $this->connection->query($query);
        return $result;
    }

    // DELETE RECORD
    public function delete_record($id)
    {
        $query  = "DELETE FROM data WHERE id = $id";
        $result = $this->connection->query($query);
        return $result;
    }

    // UPDATE RECORD
    public function update_record($id, $name, $age, $city, $hobby)
    {
        $query  = "UPDATE data SET name = '$name', age = $age, city = '$city', hobby = '$hobby' WHERE id = $id";
        $result = $this->connection->query($query);
        return $result;
    }

    // CLOSE CONNECTION
    public function close_connection()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    // GET SINGLE STUDENT RECORD
    public function get_single_student($id)
    {
        $query  = "SELECT * FROM data WHERE id = $id";
        $result = $this->connection->query($query);
        return $result->fetch_assoc();
    }
}
