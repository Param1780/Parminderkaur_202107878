<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$title = $author = $content = $publicationdate = "";
$title_err = $author_err = $content_err = $publicationdate_err = "";

// Processing form data when form is submitted  
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    // Validate title
    $input_title = trim($_POST["title"]);
    if (empty($input_title)) {
        $title_err = "Please enter Artwork Title.";
    } else {
        $title = $input_title;
    }

    // Validate Author
    $input_author = trim($_POST["artistname"]);
    if (empty($input_author)) {
        $author_err = "Please enter name of Author.";
    } else {
        $author = $input_author; // Fixed variable assignment
    }

    // Validate Content
    $input_content = trim($_POST["content"]); // Corrected form field name
    if (empty($input_content)) {
        $content_err = "Please enter Content.";
    } else {
        $content = $input_content;
    }

    // Validate Publication Date
    $input_publicationdate = trim($_POST["publicationdate"]);
    if (empty($input_publicationdate)) {
        $publicationdate_err = "Please enter the Publication Date.";
    } else {
        $publicationdate = $input_publicationdate;
    }

    // Check input errors before inserting in database
    if (empty($title_err) && empty($author_err) && empty($content_err) && empty($publicationdate_err)) { // Fixed condition
        // Prepare an insert statement
        $sql = "INSERT INTO technology (title, author, content, publicationdate) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_title, $param_author, $param_content, $param_publicationdate); // Fixed parameter types

            // Set parameters
            $param_title = $title;
            $param_author = $author;
            $param_content = $content;
            $param_publicationdate = $publicationdate;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Informations created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
           
   </style>
     
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Information</h2>
                    <p>Please fill this form and submit to add technology Information to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                            <span class="invalid-feedback"><?php echo $title_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Author</label>
                            <input type="text" name="artistname" class="form-control <?php echo (!empty($author_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $author; ?>">
                            <span class="invalid-feedback"><?php echo $author_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Content</label>
                            <input type="text" name="content" class="form-control <?php echo (!empty($content_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $content; ?>">
                            <span class="invalid-feedback"><?php echo $content_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Publication Date</label>
                            <input type="text" name="publicationdate" class="form-control <?php echo (!empty($publicationdate_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $publicationdate; ?>">
                            <span class="invalid-feedback"><?php echo $publicationdate_err; ?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
