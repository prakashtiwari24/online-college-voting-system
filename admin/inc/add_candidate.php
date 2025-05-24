<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Add New Candidate</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .candidate_photo {
            max-width: 80px;
            height: auto;
        }
    </style>
</head>

<body>
    <div class="container my-4">

        <?php
        if (isset($_GET['added'])) {
            ?>
            <div class="alert alert-success my-3" role="alert">
                Candidate has been added successfully.
            </div>
            <?php
        } else if (isset($_GET['largeFile'])) {
            ?>
                <div class="alert alert-danger my-3" role="alert">
                    Candidate image is too large, please upload a smaller file (up to 2MB).
                </div>
            <?php
        } else if (isset($_GET['invalidFile'])) {
            ?>
                    <div class="alert alert-danger my-3" role="alert">
                        Invalid image type (only .jpg, .png, .jpeg files are allowed).
                    </div>
            <?php
        } else if (isset($_GET['failed'])) {
            ?>
                        <div class="alert alert-danger my-3" role="alert">
                            Image uploading failed, please try again.
                        </div>
            <?php
        } else if (isset($_GET['delete_id'])) {
            $d_id = $_GET['delete_id'];
            mysqli_query($conn, "DELETE FROM candidate_details WHERE id='" . $d_id . "'") or die(mysqli_error($conn));
            mysqli_query($conn, "DELETE FROM votings WHERE candidate_id='" . $d_id . "'") or die(mysqli_error($conn));
            ?>
                            <div class="alert alert-danger my-3" role="alert">
                                Candidate has been deleted successfully.
                            </div>
            <?php
        }
        ?>

        <div class="row my-4">
            <div class="col-md-4">
                <h3>Add New Candidate</h3>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <select class="form-control" name="election_id" required>
                            <option value="">Select Election</option>
                            <?php
                            $fetchingElections = mysqli_query($conn, "SELECT * FROM elections") or die(mysqli_error($conn));
                            $isAnyElectionAdded = mysqli_num_rows($fetchingElections);
                            if ($isAnyElectionAdded > 0) {
                                while ($row = mysqli_fetch_assoc($fetchingElections)) {
                                    $election_id = $row['id'];
                                    $election_name = $row['election_topic'];
                                    $allowed_candidates = $row['no_of_candidates'];
                                    $fetchingCandidate = mysqli_query($conn, "SELECT * FROM candidate_details WHERE election_id='" . $election_id . "'") or die(mysqli_error($conn));
                                    $added_candidates = mysqli_num_rows($fetchingCandidate);
                                    if ($added_candidates < $allowed_candidates) {
                                        echo '<option value="' . $election_id . '">' . htmlspecialchars($election_name) . '</option>';
                                    }
                                }
                            } else {
                                echo '<option value="">Please add election first</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <input type="text" name="candidate_name" placeholder="Candidate Name" class="form-control"
                            required />
                    </div>

                    <div class="form-group mb-3">
                        <input type="file" name="candidate_photo" class="form-control" required />
                    </div>

                    <div class="form-group mb-3">
                        <input type="text" name="candidate_details" placeholder="Candidate Details" class="form-control"
                            required />
                    </div>

                    <div class="mb-3">
                        <input type="submit" value="Add Candidate" name="addCandidateBtn"
                            class="btn btn-success w-100" />
                    </div>
                </form>
            </div>

            <div class="col-md-8">
                <h3>Candidate Details</h3>
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">S.No</th>
                            <th scope="col">Photo</th>
                            <th scope="col">Name</th>
                            <th scope="col">Details</th>
                            <th scope="col">Election</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $fetchingData = mysqli_query($conn, "SELECT * FROM candidate_details") or die(mysqli_error($conn));
                        $isAnyCandidateAdded = mysqli_num_rows($fetchingData);
                        if ($isAnyCandidateAdded > 0) {
                            $sno = 1;
                            while ($row = mysqli_fetch_assoc($fetchingData)) {
                                $election_id = $row['election_id'];
                                $candidate_id = $row['id'];
                                $fetchingElectionName = mysqli_query($conn, "SELECT * FROM elections WHERE id='" . $election_id . "'") or die(mysqli_error($conn));
                                $execFetchingElectionNameQuery = mysqli_fetch_assoc($fetchingElectionName);
                                $election_name = $execFetchingElectionNameQuery['election_topic'];
                                $candidate_photo = $row['candidate_photo'];
                                ?>
                                <tr>
                                    <td><?php echo $sno++; ?></td>
                                    <td><img src="<?php echo htmlspecialchars($candidate_photo); ?>"
                                            class="candidate_photo img-thumbnail" alt="Candidate Photo" /></td>
                                    <td><?php echo htmlspecialchars($row['candidate_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['candidate_details']); ?></td>
                                    <td><?php echo htmlspecialchars($election_name); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-danger"
                                            onclick="DeleteData(<?php echo $candidate_id; ?>)">Delete</button>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="6" class="text-center">No Candidate has been added yet.</td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const DeleteData = (e_id) => {
            if (confirm("Are you sure you want to delete it?")) {
                location.assign("index.php?addCandidatePage=1&delete_id=" + e_id);
            }
        }
    </script>

    <!-- Bootstrap JS Bundle CDN (Popper + Bootstrap JS) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php
    if (isset($_POST['addCandidateBtn'])) {
        $election_id = mysqli_real_escape_string($conn, $_POST['election_id']);
        $candidate_name = mysqli_real_escape_string($conn, $_POST['candidate_name']);
        $candidate_details = mysqli_real_escape_string($conn, $_POST['candidate_details']);
        $inserted_by = $_SESSION['username'];
        $inserted_on = date("Y-m-d");

        // photograph logic starts
        $targetted_folder = "../assets/images/candidate_photo/";
        $candidate_photo = $targetted_folder .
            rand(1111111111, 9999999999) . "_" . rand(1111111111, 9999999999) . $_FILES['candidate_photo']['name'];
        $candidate_photo_tmp_name = $_FILES['candidate_photo']['tmp_name'];
        $candidate_photo_type = strtolower(pathinfo($candidate_photo, PATHINFO_EXTENSION));
        $allowed_types = array("jpg", "png", "jpeg");
        $image_size = $_FILES['candidate_photo']['size'];

        if ($image_size < 2000000) {
            if (in_array($candidate_photo_type, $allowed_types)) {
                if (move_uploaded_file($candidate_photo_tmp_name, $candidate_photo)) {
                    // inserting query
                    mysqli_query($conn, "INSERT INTO candidate_details(election_id,candidate_name,candidate_details,candidate_photo,inserted_by,inserted_on) VALUES('" . $election_id . "','" . $candidate_name . "','" . $candidate_details . "','" . $candidate_photo . "','" . $inserted_by . "','" . $inserted_on . "')") or die(mysqli_error($conn));
                    echo "<script>location.assign('index.php?addCandidatePage=1&added=1');</script>";
                } else {
                    echo "<script>location.assign('index.php?addCandidatePage=1&failed=1');</script>";
                }
            } else {
                echo "<script>location.assign('index.php?addCandidatePage=1&invalidFile=1');</script>";
            }
        } else {
            echo "<script>location.assign('index.php?addCandidatePage=1&largeFile=1');</script>";
        }

    }