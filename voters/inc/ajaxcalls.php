<?php
require_once("../../admin/inc/config.php");

if (isset($_POST['e_id'], $_POST['c_id'], $_POST['v_id'])) {
    $election_id = $_POST['e_id'];
    $candidate_id = $_POST['c_id'];
    $voter_id = $_POST['v_id'];
    $vote_date = date("Y-m-d");
    $vote_time = date("H:i:s");
    error_log("Voting process started");
    error_log("Election ID: " . $_POST['e_id']);
    error_log("Candidate ID: " . $_POST['c_id']);
    error_log("Voter ID: " . $_POST['v_id']);
    error_log($vote_date   );
    error_log($vote_time);
    $stmt = $conn->prepare("INSERT INTO votings (election_id, voters_id, candidate_id, vote_date, vote_time)
                            VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("iiiss", $election_id, $voter_id, $candidate_id, $vote_date, $vote_time);
        $stmt->execute();
        error_log("statement executed");
        $stmt->close();
        echo  "Success";
    } else {
        error_log("statement execution failed");
        echo "Failed";
        die("Prepare failed: " . $conn->error);
    }
}
?>
