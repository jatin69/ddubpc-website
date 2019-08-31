<?php

require_once 'connect.php';
if (isset($_POST['register'])) {
    $target_dir = "uploads/student/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $fileType = pathinfo($target_file, PATHINFO_EXTENSION);

// Check if file already exists
    if (file_exists($target_file)) {
        echo "<script>alert('Sorry, Target file already exists, Try renaming the file');</script>";
        $uploadOk = 0;
    }
// Check file size
    if ($_FILES["fileToUpload"]["size"] > 10097152) {
        echo "<script>alert('Sorry, your file is too large.');</script>";
        $uploadOk = 0;
    }

// Allow certain file formats
    if ($fileType != "pdf" && $fileType != "doc" && $fileType != "docx") {
        echo "<script>alert('Sorry, Your file was not uploaded. only Pdf, doc, docx files are allowed. Please RETRY !!');</script>";
        $uploadOk = 0;
    }

// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        // echo "Sorry, your file was not uploaded.";
        echo "<script>document.location.href='studentRegister.html'</script>";

        exit(1);
// if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            //  echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        } else {
            echo "<script>alert('Sorry,there was an error uploading your file.');</script>";
            echo "<script>document.location.href='studentRegister.html'</script>";

            exit(1);
        }
    }

    $reply = array();
    $file = '';

    $name = $_POST["name"];
    $email = $_POST["email"];
    $gender = $_POST["gender"];
    $dob = $_POST["dob"];
    $contact = $_POST["contact"];
    $address = $_POST["address"];
    $rollno = $_POST["rollno"];

    $query = "INSERT INTO student(`name`,`email`,`gender`,`dob`,`contact`,`address`,`rollno`,`file`) VALUES (?,?,?,?,?,?,?,?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssss", $name, $email, $gender, $dob, $contact, $address, $rollno, $target_file);
    if ($stmt->execute() == true) {
        echo "<script>alert('You have successfully registered !!');</script>";
        echo "<script>document.location.href='index.html'</script>";
        // $reply["success"]=1;
        //echo 1;
    } else {
        $reply["success"] = 0;
        $err = $conn->error;
        if (strpos($err, 'Duplicate') !== false) {
            echo "<script>alert('Already registered !!');</script>";
            echo "<script>document.location.href='index.html'</script>";
            //    $reply["error"]="Already registered.";
            //    echo 2;
        } else {
            echo "<script>alert('Registration error, Retry !!');</script>";
            echo "<script>document.location.href='studentRegister.html'</script>";

            //    $reply["error"]=$conn->error;
            //    echo 3;
        }
    }
    $stmt->close();
    $conn->close();
} else {
    echo 0;
}
