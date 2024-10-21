<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// getting database auth details
// $path = $_SERVER['DOCUMENT_ROOT'];
// include_once $path . '/wp-config.php';

global $wpdb;
$host = $wpdb->dbhost;
$user = $wpdb->dbuser;
$pass = $wpdb->dbpassword;
$db = $wpdb->dbname;

$conn = mysqli_connect($host, $user, $pass, $db);
$phone = get_user_meta(get_current_user_id(), 'phone', true);
$table = $wpdb->prefix . "vaccinations";
$sql = "SELECT * FROM $table WHERE phone='$phone'";
$query = mysqli_query($conn, $sql);
?>

<div class="wrap">
    <h1 class="text-center">All Patients Vaccines</h1>

    <br>



    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Vaccine</th>
                        <th scope="col">Price</th>
                        <th scope="col">Doctor</th>
                        <th scope="col">Last Vaccine Date</th>
                        <th scope="col">Next Date</th>
                        <th scope="col">Interval</th>
                        <th scope="col">Prescription</th>
                        <th scope="col">Note</th>
                    </tr>
                </thead>
                <tbody>

                    <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                        <tr>
                            <td><?php echo $row['firstname']; ?></td>
                            <td><?php echo $row['lastname']; ?></td>
                            <td><?php echo $row['vaccine']; ?></td>
                            <td><?php echo $row['price']; ?></td>
                            <td><?php echo $row['doctor']; ?></td>
                            <td><?php echo $row['vaccination_date']; ?></td>
                            <td><?php echo $row['last_vaccination_date']; ?></td>
                            <td>
                                <?php
                                if ($row['last_vaccination_date']) {
                                    $date1 = new DateTime($row['vaccination_date']);
                                    $date2 = new DateTime($row['last_vaccination_date']);
                                    $interval = $date1->diff($date2);
                                    echo $interval->y . "Y " . $interval->m . "M " . $interval->d . "D";
                                } else {
                                    echo "---";
                                }
                                ?>
                            </td>
                            <td>
                                <?php if ($row['file']) { ?>
                                    <a href="<?php echo get_site_url() . "/" . $row['file']; ?>" target="_blank">view
                                        document</a>
                                <?php } else {
                                    echo "No Documents";
                                } ?>
                            </td>
                            <td><?php echo $row['notes']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
        <div class="col-1"></div>
    </div>

</div>