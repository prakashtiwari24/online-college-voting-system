<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Election Form</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- ✅ Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<?php
if (isset($_GET['added'])) {
    echo '<div class="alert alert-success my-3" role="alert">Election has been added successfully.</div>';
} elseif (isset($_GET['delete_id'])) {
    $d_id = $_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM elections WHERE id='$d_id'") or die(mysqli_error($conn));
    mysqli_query($conn, "DELETE FROM candidate_details WHERE election_id='$d_id'") or die(mysqli_error($conn));
    mysqli_query($conn, "DELETE FROM votings WHERE election_id='$d_id'") or die(mysqli_error($conn));
    echo '<div class="alert alert-danger my-3" role="alert">Election has been deleted successfully.</div>';
}
?>

<div class="row my-4">
    <div class="col-md-4">
        <h3>Add New Election</h3>
        <form method="POST">
            <div class="mb-3">
                <input type="text" name="election_topic" placeholder="Election Topic" class="form-control" required />
            </div>

            <div class="mb-3">
                <input type="number" name="number_of_candidates" placeholder="No of Candidates" class="form-control"
                    required />
            </div>

            <div class="mb-3">
                <input type="text" onfocus="this.type='date'" name="starting_date" placeholder="Starting Date"
                    class="form-control" required />
            </div>

            <div class="mb-3">
                <input type="text" onfocus="this.type='date'" name="ending_date" placeholder="Ending Date"
                    class="form-control" required />
            </div>

            <input type="submit" value="Add Election" name="addElectionBtn" class="btn btn-success w-100" />
        </form>

    </div>

    <div class="col-md-8">
        <h3>Upcoming Elections</h3>
        <table class="table table-bordered table-hover table-striped text-white bg-dark">
            <thead class="table-dark">
                <tr>
                    <th>S.No</th>
                    <th>Election Name</th>
                    <th># Candidates</th>
                    <th>Starting Date</th>
                    <th>Ending Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $fetchingData = mysqli_query($conn, "SELECT * FROM elections") or die(mysqli_error($conn));
                $isAnyElectionAdded = mysqli_num_rows($fetchingData);
                if ($isAnyElectionAdded > 0) {
                    $sno = 1;
                    while ($row = mysqli_fetch_assoc($fetchingData)) {
                        $election_id = $row['id'];
                        echo "<tr>
                            <td>{$sno}</td>
                            <td>{$row['election_topic']}</td>
                            <td>{$row['no_of_candidates']}</td>
                            <td>{$row['starting_date']}</td>
                            <td>{$row['ending_date']}</td>
                            <td>{$row['status']}</td>
                            <td><button class='btn btn-sm btn-danger' onclick='DeleteData($election_id)'>Delete</button></td>
                        </tr>";
                        $sno++;
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>No elections have been added yet.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    const DeleteData = (e_id) => {
        if (confirm("Are you sure you want to delete this election?")) {
            location.assign("index.php?addElectionPage=1&delete_id=" + e_id);
        }
    }
</script>

<?php
if (isset($_POST['addElectionBtn'])) {
    $election_topic = mysqli_real_escape_string($conn, $_POST['election_topic']);
    $number_of_candidates = mysqli_real_escape_string($conn, $_POST['number_of_candidates']);
    $starting_date = mysqli_real_escape_string($conn, $_POST['starting_date']);
    $ending_date = mysqli_real_escape_string($conn, $_POST['ending_date']);
    $inserted_by = $_SESSION['username'];
    $inserted_on = date("Y-m-d");

    $diff = date_diff(date_create($inserted_on), date_create($starting_date));
    $status = ((int) $diff->format("%R%a") > 0) ? "InActive" : "Active";

    mysqli_query($conn, "INSERT INTO elections (election_topic, no_of_candidates, starting_date, ending_date, status, inserted_by, inserted_on)
    VALUES ('$election_topic', '$number_of_candidates', '$starting_date', '$ending_date', '$status', '$inserted_by', '$inserted_on')") or die(mysqli_error($conn));
    echo "<script>location.assign('index.php?addElectionPage=1&added=1');</script>";
}
?>
<!-- ✅ Bootstrap JS (optional, for interactivity) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>