<?php
// Include config file
require_once "config.php"; 

// Define variables and initialize with empty values
$title = $author = $content = $publictiondate = "";
$title_err = $author_err = $content_err = $publictiondate_err = "";

// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Get hidden input value
    $id = $_POST["id"];

     // Validate Title
     $input_title = trim($_POST["title"]);
     if (empty($input_title)) {
         $title_err = "Please enter the Title.";
     } else {
         $title = $input_title;
     }

    // Validate Author
    $input_author = trim($_POST["author"]);
    if (empty($input_author)) {
        $author_err = "Please enter a Author.";
    } else {
        $author = $input_author;
    }

    // Validate Content
    $input_content = trim($_POST["content"]);
    if (empty($input_content)) {
        $content_err = "Please enter an Content.";
    } else {
        $content = $input_content;
    }

    // Validate Publication date
    $input_publictiondate = trim($_POST["publictiondate"]);
    if (empty($input_publictiondate)) {
        $publictiondate_err = "Please enter the Publication date.";
    } else {
        $publictiondate = $input_publictiondate;
    }

    // Check input errors before inserting in database
    if (empty($title_err) && empty($author_err) && empty($content_err) && empty($publictiondate_err)) {
        // Prepare an update statement
$sql = "UPDATE technology SET title=?, author=?, content=?, publictiondate=? WHERE id=?";

if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "ssssi", $param_title, $param_author, $param_content, $param_publictiondate, $param_id);

    // Set parameters
    $param_title = $title;
    $param_author = $author;
    $param_content = $content;
    $param_publictiondate = $publictiondate;
    $param_id = $id;

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        // Records updated successfully. Redirect to landing page
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
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM technology WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $title = $row["title"];
                    $author = $row["author"];
                    $content = $row["content"];
                    $publicationdate = $row["publicationdate"];
                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);
    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the technology Record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                            <span class="invalid-feedback"><?php echo $title_err; ?></span>
                        </div>   
                    <div class="form-group">
                            <label>Author</label>
                            <input type="text" name="author" class="form-control <?php echo (!empty($author_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $author; ?>">
                            <span class="invalid-feedback"><?php echo $author_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Content</label>
                            <input type="text" name="content" class="form-control <?php echo (!empty($content_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $content; ?>">
                            <span class="invalid-feedback"><?php echo $content_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Publication date</label>
                            <input type="text" name="publictiondate" class="form-control <?php echo (!empty($publictiondate_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $publictiondate; ?>">
                            <span class="invalid-feedback"><?php echo $publictiondate_err; ?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>