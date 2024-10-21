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
?>

<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>


<div class="wrap">
    <h1 class="text-center">Next Vaccines</h1>

    <br>

    <!-- all patients area -->
    <div>

        <?php
        $id = get_current_user_id();
        $conn = mysqli_connect($host, $user, $pass, $db);
        $phone = get_user_meta($id, 'phone', true);

        $table = $wpdb->prefix . "vaccinations";
        $sql = "SELECT * FROM $table WHERE userid='$id' ORDER BY vaccination_date ASC";
        $result = $wpdb->get_results($sql);
        ?>

        <table class=" table-success" id="datatable2">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone</th>
                    <th>Vaccine Name</th>
                    <th>Price</th>
                    <th>Doctor</th>
                    <th>Next Vaccination Date</th>
                    <th>Next Date</th>
                    <th>Interval</th>
                    <th>Prescription</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($result as $user) {
                    ?>

                    <tr>
                        <td><?php echo $user->firstname; ?></td>
                        <td><?php echo $user->lastname; ?></td>
                        <td><?php echo $user->phone; ?></td>
                        <td><?php echo $user->vaccine; ?></td>
                        <td><?php echo $user->price; ?></td>
                        <td><?php echo $user->doctor; ?></td>
                        <td><?php echo $user->vaccination_date; ?></td>
                        <td>
                            <?php if ($user->last_vaccination_date) {
                                echo $user->last_vaccination_date;
                            } else {
                                echo "Last Vaccine";
                            } ?>
                        </td>
                        <td>
                            <?php
                            if ($user->last_vaccination_date) {
                                $date1 = new DateTime($user->vaccination_date);
                                $date2 = new DateTime($user->last_vaccination_date);
                                $interval = $date1->diff($date2);
                                echo $interval->y . "Y " . $interval->m . "M " . $interval->d . "D";
                            } else {
                                echo " --- ";
                            }
                            ?>
                        </td>
                        <td>
                            <?php if ($user->file) { ?>
                                <a href="<?php echo get_site_url() . "/" . $user->file; ?>" target="_blank">view document</a>
                            <?php } else {
                                echo "No Documents";
                            } ?>
                        </td>
                        <td><?php echo $user->notes; ?></td>
                    </tr>

                    <?php
                }
                ?>

            </tbody>
        </table>
    </div>
    <!-- end of all patients area -->

</div>


<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('#datatable2').dataTable({
        });
    });   
</script>