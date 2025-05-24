<?php
require_once("inc/header.php");
require_once("inc/navigation.php");
function console_log($message)
{
    echo "<script>console.log(" . json_encode($message) . ");</script>";
}
?>
<div class="row my-3">
    <div class="col-12">
        <h3>Voters Panel</h3>
        <?php
        $fetchingActiveElections = mysqli_query($conn, "SELECT * FROM elections
WHERE status ='Active'") or die(mysqli_error($conn));
        $totalActiveElections = mysqli_num_rows($fetchingActiveElections);
        if ($totalActiveElections > 0) {
            while ($data = mysqli_fetch_assoc($fetchingActiveElections)) {
                $election_id = $data['id'];
                $election_topic = $data['election_topic'];
                ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="4" class="bg-success text-white">
                                <h5>ELECTION TOPIC :
                                    <?php echo strtoupper($election_topic); ?>
                                </h5>
                            </th>
                        </tr>
                        <tr>
                            <th>Photo</th>
                            <th>Candidate Details</th>
                            <th># No of Voters</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $fetchingCandidates = mysqli_query($conn, "SELECT * FROM
candidate_details WHERE election_id='" . $election_id . "'") or
                            die(mysqli_error($conn));
                        while ($candidateData = mysqli_fetch_assoc($fetchingCandidates)) {
                            $candidate_id = $candidateData['id'];
                            $candidate_photo = $candidateData['candidate_photo'];
                            //fetching candidates votes
                            $fetchingVotes = mysqli_query($conn, "SELECT * FROM votings WHERE
candidate_id='" . $candidate_id . "'") or die(mysqli_error($conn));
                            $totalVotes = mysqli_num_rows($fetchingVotes);
                            ?>
                            <tr>
                                <td><img src="<?php echo $candidate_photo; ?>" class="candidate_photo"></td>
                                <td><?php echo "<b>" . $candidateData['candidate_name'] .
                                    "</b><br/>" . $candidateData['candidate_details']; ?></td>
                                <td><?php echo $totalVotes; ?></td>
                                <td>
                                    <?php
                                    $checkIfVoteCasted = mysqli_query($conn, "SELECT * FROM votings
WHERE voters_id ='" . $_SESSION['user_id'] . "' AND election_id = '" . $election_id . "'") or
                                        die(mysqli_error($conn));
                                    $isVoteCasted = mysqli_num_rows($checkIfVoteCasted);
                                    if ($isVoteCasted > 0) {
                                        console_log("vote not casted");
                                        $voteCastedData = mysqli_fetch_assoc($checkIfVoteCasted);
                                        $voteCastedToCandidate = $voteCastedData['candidate_id'];
                                        if ($voteCastedToCandidate == $candidate_id) {
                                            ?>
                                            <!-- <img src="../assets/images/vote.jpeg" alt="voteImage" width="100px;"> -->
                                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                                <i class="fas fa-check-circle me-2"></i>
                                                Your vote has been submitted! ✅
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <button class="btn btn-md btn-success" onclick="castVote(<?php
                                        echo $election_id; ?>,<?php echo $candidate_id; ?>,<?php echo
                                                 $_SESSION['user_id']; ?>)">Vote</button>
                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <?php
            }
        } else {
            echo "No any active election.";
        }
        ?>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    console.log("casting votes....");
    const castVote = (election_id, customer_id, voters_id) => {
        console.log("election_id: " + election_id);
        console.log("customer_id: " + customer_id);
        console.log("voters_id: " + voters_id);
        $.ajax({
            type: "POST",
            url: "inc/ajaxcalls.php",
            data: "e_id=" + election_id + "&c_id=" + customer_id + "&v_id=" + voters_id,
            success: function (response) {
                console.log("response is "+response);
                console.log("Vote casting response received: " + response);
                if (response == "Success") {
                    location.assign("index.php?voteCasted=1");
                } else {
                    console.log("Vote casting response received: " + response);
                    location.assign("index.php?voteNotCasted=1");
                }
            },
            error: function (xhr, status, error) {
            console.error("AJAX error: " + error);
            console.error("Status: " + status);
            console.error("Response: " + xhr.responseText);
        }
        });
    }
</script>
<?php
require_once("inc/footer.php");
?>