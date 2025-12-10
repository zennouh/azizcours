<?php

require_once "modal.php";


class Cours extends Modal
{
    public static  $table = 'cours';

    private static $targetDir = "../src/upload/";

    public static function create(array $data)
    {
        $pdo = static::database();
        $table = 'cours';

        $file = $data["image"];
        $title = $data["title"];
        $desc  = $data["description"];
        $level = $data["level"];
        $imageName = time() . "_" . basename($file["name"]);

        $params = [
            ':title' => $title,
            ':description' => $desc,
            ':levele' => $level,
            ':image' => $imageName
        ];


        $sql = "INSERT INTO `{$table}` (section_title, section_content, position, image, levele)
                    VALUES (:title, :description, :position, :image, :levele)";
        $stmt = $pdo->prepare($sql);
        $ok = $stmt->execute($params);
        if ($ok) {

            // target = "../src/upload/"
            $targetPath = self::$targetDir . $imageName;

            move_uploaded_file($file["tmp_name"], $targetPath);
        }
        return $ok;
    }



    /**
     * @return Cours[]
     */
    public static function all()
    {
        $pdo = static::database();
        $table = 'cours';

        $stmt = $pdo->query("SELECT * FROM `{$table}`");
        $coursResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $courses = [];
        foreach ($coursResult as $row) {
            $courses[] = [
                'cours_id' => $row['cours_id'],
                'title' => $row['title'],
                'level' => $row['levele'],
                'image' => "http://localhost/azizcours/src/upload/" . $row['image']
            ];
        }
        return $courses;
    }

    public static function update($id): bool
    {
        $id = $_POST["cours_id"];
        $title = $_POST["title"];
        $desc = $_POST["description"];
        $level = $_POST["level"];
        $params = [
            ':title' => $title,
            ':description' => $desc,
            ':levele' => $level,
            ':id' => $id,
        ];

        $pdo = parent::database();

        $stmt = null;
        if (!empty($_FILES["image"]["name"])) {
            $imgName = time() . "_" . $_FILES["image"]["name"];
            $path = "../src/upload/" . $imgName;
            move_uploaded_file($_FILES["image"]["tmp_name"], $path);

            $sql = "UPDATE `cours` 
                    SET `title` = :title, `description` = :description, `levele` = :levele, `image` = :image
                    WHERE `cours_id` = :id";

            $stmt = $pdo->prepare($sql);
            $params[":image"] = $imgName;
        } else {

            $sql = "UPDATE `cours` 
                    SET `title` = :title, `description` = :description, `levele` = :levele
                    WHERE `cours_id` = :id";

            $stmt = $pdo->prepare($sql);
        }

        return $stmt->execute($params);
    }

    public static function delete($id, $path)
    {
        // $table = 'cours';
        // unlink
        $pdo = static::database();
        $stmt = $pdo->prepare('DELETE FROM cours WHERE cours_id = :id');
        return $stmt->execute([":id" => $id]);
    }

    public static function detail($id)
    {
        $table = 'cours';
        $pdo = static::database();

        $sql = "SELECT c.cours_id,
                   c.title,
                   c.description,
                   c.levele AS level,   
                   c.image,
                   COALESCE(MAX(s.position), 0) AS max_position
            FROM {$table} c
            LEFT JOIN sections s ON c.cours_id = s.cours_id
            WHERE c.cours_id = :id
            GROUP BY c.cours_id, c.title, c.description, c.levele, c.image
            LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => (int)$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $imageBase = 'http://localhost/azizcours/src/upload/';
        $imagePath =  $imageBase . $row['image'];

        $course = [
            'cours_id'     => $row['cours_id'],
            'title'        => $row['title'],
            'description'  => $row['description'],
            'level'        => $row['level'],
            'max_position' => $row['max_position'],
            'image'        => $imagePath
        ];

        return $course;
    }
}





// function make_connection()
// {
//     $servername = "localhost";
//     $username   = "root";
//     $password   = "";
//     $database   = "aziz_cours";

//     $conn = mysqli_connect($servername, $username, $password, $database);

//     if (!$conn) {
//         die("Connection failed: " . mysqli_connect_error());
//     }

//     return $conn;
// }

// function get_courses_list()
// {
//     $conn = make_connection();

//     $sql = "SELECT * FROM cours ";
//     $result = mysqli_query($conn, $sql);

//     $courses = [];

//     if ($result && mysqli_num_rows($result) > 0) {
//         while ($row = mysqli_fetch_assoc($result)) {
//             $courses[] = [
//                 'cours_id' => $row['cours_id'],
//                 'title' => $row['title'],
//                 'level' => $row['levele'],
//                 'image' => "http://localhost/azizcours/src/upload/" . $row['image']
//             ];
//         }
//     }
//     mysqli_close($conn);
//     return $courses;
// }

// function delete_cours($id): bool
// {

//     $conn = make_connection();
//     $q = 'DELETE FROM cours WHERE cours_id =' . $id;
//     $result = mysqli_query($conn, $q);
//     if ($result) {
//         mysqli_close($conn);
//         sleep(1);
//         return true;
//     } else {
//         return false;
//     }
// }

// function get_cours_detail($id)
// {
//     $conn = make_connection();
//     $id = intval($id);

//     // We get the max position of sections directly here
//     $sql = "SELECT c.cours_id, c.title, c.description, c.levele, c.image,
//                    COALESCE(MAX(s.position),0) AS max_position
//             FROM cours c
//             LEFT JOIN sections s ON c.cours_id = s.cours_id
//             WHERE c.cours_id = $id
//             GROUP BY c.cours_id, c.title, c.description, c.levele, c.image";

//     $result = mysqli_query($conn, $sql);

//     $courses = [];

//     if ($result && mysqli_num_rows($result) > 0) {
//         $row = mysqli_fetch_assoc($result);

//         $courses[] = [
//             'cours_id'   => $row['cours_id'],
//             'title'      => $row['title'],
//             'description' => $row['description'],
//             'level'      => $row['levele'],
//             'max_position' => $row['max_position'],
//             'image'      => "http://localhost/azizcours/src/upload/" . $row['image']
//         ];
//     }

//     mysqli_close($conn);
//     return $courses;
// }

// function add_cours()
// {
//     try {
//         $conn = make_connection();

//         $file = $_FILES["image"];
//         $title = $_POST["title"];
//         $desc  = $_POST["description"];
//         $level = $_POST["level"];
//         $imageName = time() . "_" . basename($file["name"]);



//         $query = "INSERT INTO `cours` (`title`, `levele`, `image`, `description`) VALUES ('$title', '$level', '$imageName', '$desc')";

//         $result = mysqli_query($conn, $query);

//         if ($result) {

//             $targetDir = "../src/upload/";
//             $targetPath = $targetDir . $imageName;

//             $isMove = move_uploaded_file($file["tmp_name"], $targetPath);
//             mysqli_close($conn);
//             return true;
//         }
//         return false;
//     } catch (\Throwable $th) {
//         echo $th->getMessage();
//         return false;
//     }
// }

// function update_cours()
// {
//     if ($_SERVER["REQUEST_METHOD"] === "POST") {
//         $id = $_POST["cours_id"];
//         $title = $_POST["title"];
//         $desc = $_POST["description"];
//         $level = $_POST["level"];
//         $conn = make_connection();

//         echo $id;

//         // $sql = "";
//         if (!empty($_FILES["image"]["name"])) {
//             $imgName = time() . "_" . $_FILES["image"]["name"];
//             $path = "../src/upload/" . $imgName;
//             move_uploaded_file($_FILES["image"]["tmp_name"], $path);
//             $sql = "UPDATE `cours` SET `title`='$title', `description`='$desc', `levele`='$level', `image`='$imgName' WHERE `cours_id`=$id";
//         } else {
//             $sql = "UPDATE `cours` SET `title`='$title', `description`='$desc', `levele`='$level' WHERE `cours_id`=$id";
//         }

//         try {
//             $result =  mysqli_query($conn, $sql);
//             mysqli_close($conn);
//             return $result;
//         } catch (Exception $e) {
//             echo $e->getMessage();
//             return false;
//         }
//     }
// }
