<?php
require_once("admin/inc/config.php");

$fetchingElections = mysqli_query($conn, "SELECT * FROM elections") or die(mysqli_error($conn));
while ($data = mysqli_fetch_assoc($fetchingElections)) {
    $starting_date = $data['starting_date'];
    $ending_date = $data['ending_date'];
    $curr_date = date("Y-m-d");
    $election_id = $data['id'];
    $status = $data['status'];

    // active = expire = ending date
    // inactive = active = starting date
    if ($status == "Active") {
        $date1 = date_create($curr_date);
        $date2 = date_create($ending_date);
        $diff = date_diff($date1, $date2);
        if ((int) $diff->format("%R%a") < 0) {
            // update status to Expired
            mysqli_query($conn, "UPDATE elections SET status ='Expired' WHERE id='" . $election_id . "'") or die(mysqli_error($conn));
        }
    } else if ($status == "InActive") {
        $date1 = date_create($curr_date);
        $date2 = date_create($starting_date);
        $diff = date_diff($date1, $date2);
        if ((int) $diff->format("%R%a") <= 0) {
            // update status to Active
            mysqli_query($conn, "UPDATE elections SET status ='Active' WHERE id='" . $election_id . "'") or die(mysqli_error($conn));
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Voting System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="container h-100">
        <div class="d-flex justify-content-center h-100">
            <div class="user_card">
                <div class="d-flex justify-content-center">
                    <div class="brand_logo_container">
                        <img src="assets/images/logo.jpeg" class="brand_logo" alt="Logo">
                    </div>
                </div>

                <?php if (isset($_GET['sign-up'])): ?>
                    <div class="d-flex justify-content-center form_container">
                        <form method="POST">
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" name="su_username" class="form-control input_user" placeholder="Username" required />
                            </div>

                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="text" name="su_contact_no" class="form-control input_pass" placeholder="Contact" required />
                            </div>

                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" name="su_password" class="form-control input_pass" placeholder="Password" required />
                            </div>

                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" name="su_retype_password" class="form-control input_pass" placeholder="Retype Password" required />
                            </div>

                            <div class="d-flex justify-content-center mt-3 login_container">
                                <button type="submit" name="sign_up_btn" class="btn login_btn">Sign Up</button>
                            </div>
                        </form>
                    </div>

                    <div class="d-flex justify-content-center mt-3 bg-dark text-white px-3 py-2 rounded">
                        Already Created Account? <a href="index.php" class="ms-2 text-blue text-decoration-none">Sign In</a>
                    </div>

                <?php else: ?>
                    <div class="d-flex justify-content-center form_container">
                        <form method="POST">
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" name="contact_no" class="form-control input_user" placeholder="Contact No" required />
                            </div>

                            <div class="input-group mb-2">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" name="password" class="form-control input_pass" placeholder="Password" required />
                            </div>

                            <div class="d-flex justify-content-center mt-3 login_container">
                                <button type="submit" name="loginBtn" class="btn login_btn">Login</button>
                            </div>
                        </form>
                    </div>

                    <div class="mt-4">
                        <div class="d-flex justify-content-center links text-white">
                            Don't have an account? <a href="?sign-up=1" class="ms-2">Sign Up</a>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['registered'])): ?>
                    <span class="bg-white text-success text-center my-3 d-block">Your account has been created successfully!</span>
                <?php elseif (isset($_GET['invalid'])): ?>
                    <span class="bg-white text-danger text-center my-3 d-block">Passwords mismatched, please try again!</span>
                <?php elseif (isset($_GET['not_registered'])): ?>
                    <span class="bg-white text-warning text-center my-3 d-block">Sorry, you are not registered!</span>
                <?php elseif (isset($_GET['invalid_access'])): ?>
                    <span class="bg-white text-danger text-center my-3 d-block">Invalid username or password!</span>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script> -->
</body>
</html>

<?php
require_once("admin/inc/config.php");

if (isset($_POST['sign_up_btn'])) {
    $su_username = mysqli_real_escape_string($conn, $_POST['su_username']);
    $su_contact_no = mysqli_real_escape_string($conn, $_POST['su_contact_no']);
    $su_password = mysqli_real_escape_string($conn, sha1($_POST['su_password']));
    $su_retype_password = mysqli_real_escape_string($conn, sha1($_POST['su_retype_password']));
    $user_role = "Voter";

    // password checking it's same or not
    if ($su_password == $su_retype_password) {
        // insert user
        mysqli_query($conn, "INSERT INTO users(username, contact_no, password, user_role)
            VALUES('" . $su_username . "','" . $su_contact_no . "','" . $su_password . "','" . $user_role . "')") or die(mysqli_error($conn));
        ?>
        <script>location.assign("index.php?sign-up=1&registered=1");</script>
        <?php
    } else {
        ?>
        <script>location.assign("index.php?sign-up=1&invalid=1");</script>
        <?php
    }
} else if (isset($_POST['loginBtn'])) {
    $contact_no = mysqli_real_escape_string($conn, $_POST['contact_no']);
    $password = mysqli_real_escape_string($conn, sha1($_POST['password']));

    // Query fetch
    $fetchingData = mysqli_query($conn, "SELECT * FROM users WHERE contact_no='" . $contact_no . "'") or die(mysqli_error($conn));

    if (mysqli_num_rows($fetchingData) > 0) {
        $data = mysqli_fetch_assoc($fetchingData);

        if ($contact_no == $data['contact_no'] && $password == $data['password']) {
            session_start();
            $_SESSION['user_role'] = $data['user_role'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['user_id'] = $data['id'];

            if ($data['user_role'] == "Admin") {
                $_SESSION['key'] = "AdminKey";
                ?>
                <script>location.assign("admin/index.php?homepage=1");</script>
                <?php
            } else {
                $_SESSION['key'] = "VotersKey";
                ?>
                <script>location.assign("voters/index.php");</script>
                <?php
            }
        } else {
            ?>
            <script>location.assign("index.php?invalid_access=1");</script>
            <?php
        }
    } else {
        ?>
        <script>location.assign("index.php?sign-up=1&not_registered=1");</script>
        <?php
    }
}
?>
