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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


<div class="wrap">
    <h1 class="text-center">All Patients Vaccines</h1>

    <br>

    <!-- patients vaccine booking list area -->
    <div>
        <?php
        $id = get_current_user_id();
        $conn = mysqli_connect($host, $user, $pass, $db);
        $phone = get_user_meta($id, 'phone', true);

        $table = $wpdb->prefix . "vaccinations";
        $sql = "SELECT * FROM $table WHERE userid='$id'";
        $res = $wpdb->get_results($sql);
        ?>

        <table class="table-success" id="datatable1">
            <thead>
                <tr>
                    <th>Serial No.</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone</th>
                    <th>Vaccine Name</th>
                    <th>Price</th>
                    <th>Doctor</th>
                    <th>Last Vaccination Date</th>
                    <th>Next Date</th>
                    <th>Interval</th>
                    <th>Prescription</th>
                    <th>Notes</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                //  while ($row = mysqli_fetch_assoc($res)) {
                foreach ($res as $user) {
                    ?>
                    <tr>
                        <td> <?php echo $i; ?> </td>
                        <td><?php echo $user->firstname; ?></td>
                        <td><?php echo $user->lastname; ?></td>
                        <td><?php echo $user->phone; ?></td>
                        <td><?php echo $user->vaccine; ?></td>
                        <td><?php echo $user->price; ?></td>
                        <td><?php echo $user->doctor; ?></td>
                        <td><?php echo $user->vaccination_date; ?></td>
                        <td><?php if ($user->last_vaccination_date) {
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
                                echo "---";
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
                        <td>

                            <button type="submit" onclick="updateInfoModal(<?php echo $user->id; ?>)"
                                style="border: 2px solid white; background-color:white;" data-bs-toggle="modal"
                                data-bs-target="#updateModal"> <i class="bi bi-pen"></i>
                            </button>

                        </td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- end of patient vaccine lists -->


</div>


<!-- update modal box -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Update Form</h1>
                <button type="button" id="closeModalIcon" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <form id="form1">
                    <input type="hidden" name="recordid" id="recordid">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label required">Phone Number</label>
                        <input type="number" readonly class="form-control" name="phone" id="phone"
                            placeholder="enter patient phone number" required>
                    </div>
                    <div class="mb-3" id="email_sec">
                        <label for="exampleInputEmail1" class="form-label required">Email</label>
                        <input type="email" readonly class="form-control" name="email" id="email"
                            placeholder="enter patient email id" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label required">Vaccine Name</label>
                        <input type="text" class="form-control" name="vaccine_name" id="vaccine_name"
                            placeholder="enter patient vaccine name" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label required">Vaccine Price</label>
                        <input type="number" class="form-control" name="vaccine_price" id="vaccine_price"
                            placeholder="enter patient vaccine price" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label required ">First Name</label>
                        <input type="email" readonly class="form-control" name="fname" id="fname"
                            placeholder="enter patient first name" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label required">Last Name</label>
                        <input type="email" readonly class="form-control" name="lname" id="lname"
                            placeholder="enter patient last name" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label required">Doctor Name</label>
                        <input type="text" class="form-control" name="doctor" id="doctor"
                            placeholder="enter patient doctor name" required>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="last_vac_btn">
                        <label class="form-check-label">Is this last vaccine ?</label>
                    </div>
                    <br>
                    <div class="mb-3" id="last_vac_date_sec">
                        <div class="row">
                            <div class="col-4">
                                <label class="form-check-label">Vaccine interval year</label>
                                <select class="form-select" name="interval_year" id="interval_year"
                                    aria-label="Default select example">
                                    <option value="0" selected>0 Year</option>
                                    <option value="1">1 Year</option>
                                    <option value="2">2 Year</option>
                                    <option value="3">3 Year</option>
                                    <option value="4">4 Year</option>
                                    <option value="5">5 Year</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="form-check-label">Vaccine interval month</label>
                                <select class="form-select" name="interval_month" id="interval_month"
                                    aria-label="Default select example">
                                    <option value="0" selected>0 month</option>
                                    <option value="1">1 month</option>
                                    <option value="2">2 month</option>
                                    <option value="3">3 month</option>
                                    <option value="4">4 month</option>
                                    <option value="5">5 month</option>
                                    <option value="6">6 month</option>
                                    <option value="7">7 month</option>
                                    <option value="8">8 month</option>
                                    <option value="9">9 month</option>
                                    <option value="10">10 month</option>
                                    <option value="11">11 month</option>
                                    <option value="12">12 month</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="form-check-label">Vaccine interval day</label>
                                <select class="form-select" name="interval_day" id="interval_day"
                                    aria-label="Default select example">
                                    <option value="0" selected>0 days</option>
                                    <option value="1">1 day</option>
                                    <option value="2">2 days</option>
                                    <option value="3">3 days</option>
                                    <option value="4">4 days</option>
                                    <option value="5">5 days</option>
                                    <option value="6">6 days</option>
                                    <option value="7">7 days</option>
                                    <option value="8">8 days</option>
                                    <option value="9">9 days</option>
                                    <option value="10">10 days</option>
                                    <option value="11">11 days</option>
                                    <option value="12">12 days</option>
                                    <option value="13">13 days</option>
                                    <option value="14">14 days</option>
                                    <option value="15">15 days</option>
                                    <option value="16">16 days</option>
                                    <option value="17">17 days</option>
                                    <option value="18">18 days</option>
                                    <option value="19">19 days</option>
                                    <option value="20">20 days</option>
                                    <option value="21">21 days</option>
                                    <option value="22">22 days</option>
                                    <option value="23">23 days</option>
                                    <option value="24">24 days</option>
                                    <option value="25">25 days</option>
                                    <option value="26">26 days</option>
                                    <option value="27">27 days</option>
                                    <option value="28">28 days</option>
                                    <option value="29">29 days</option>
                                    <option value="30">30 days</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3" id="vac_date_sec">
                        <label for="exampleInputEmail1" class="form-label required">Select Vaccination Date</label>
                        <input type="date" class="form-control" name="vac_date" id="vac_date">
                    </div>

                    <div class="mb-3">
                        <label for="formFile" class="form-label">Select Prescription (supports only PDF, JPG, JPEG,
                            PNG)</label>
                        <br>
                        <a href="" id="view_document" target="_blank">view document</a>
                        <a id="remove_document">
                            <i class="bi bi-x-square-fill" style="color:red;"></i>
                        </a>
                        <br>
                        <input class="form-control" type="file" name="formFile" id="formFile">
                    </div>

                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Raction Notes</label>
                        <input type="text" class="form-control" name="notes" id="notes">
                    </div>


                    <button type="submit" id="updte_btn" class="btn btn-primary">Update</button>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" id="closebtn" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
    const doamin = "<?php echo get_site_url(); ?>";

    // helps to set select box default value
    function selectElement(id, valueToSelect) {
        let element = document.getElementById(id);
        element.value = valueToSelect;
    }

    // on select new formfile old document will remove
    jQuery("#formFile").change(function () {
        jQuery('#view_document').hide();
        jQuery('#view_document').val("");
        jQuery('#remove_document').hide();
    });

    jQuery('#remove_document').click(function () {
        jQuery('#view_document').hide();
        jQuery('#remove_document').hide();
        jQuery("#formFile").val("");
    });


    jQuery('#closebtn').click(function () {
        jQuery('#formFile').val("");
    });
    jQuery('#closeModalIcon').click(function () {
        jQuery('#formFile').val("");
    });


    // fetch user info for updating user record
    function updateInfoModal(id) {
        jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: 'fetch_person',
                id: id
            },
            success: function (response) {
                var data = response.data;
                let doc_path = doamin + "/" + data.file;
                if (data.file) {
                    jQuery('#view_document').show();
                    jQuery('#remove_document').show();
                    jQuery('#view_document').attr("href", doc_path);
                } else {
                    jQuery('#view_document').hide();
                    jQuery('#view_document').val("");
                    jQuery('#remove_document').hide();
                }

                jQuery('#recordid').val(data.id);
                jQuery('#phone').val(data.phone);
                jQuery('#email').val(data.email);
                jQuery('#vaccine_name').val(data.vaccine);
                jQuery('#vaccine_price').val(data.price);
                jQuery('#fname').val(data.firstname);
                jQuery('#lname').val(data.lastname);
                jQuery('#doctor').val(data.doctor);
                selectElement('interval_year', data.interval_year);
                selectElement('interval_month', data.interval_month);
                selectElement('interval_day', data.interval_day);
                selectElement('vac_date', data.vaccination_date);
                jQuery('#notes').val(data.notes);
            },
            error: function (response) {
                alert("Error Occured! Try again!");
            },
        });
    }


    // update user info    
    jQuery('#document').ready(function () {

        jQuery("#last_vac_date_sec").show();
        jQuery('#last_vac_btn').click(function () {
            if (jQuery(this).is(":checked")) {
                jQuery("#last_vac_date_sec").hide();
                jQuery('#interval_year').val("");
                jQuery('#interval_month').val("");
                jQuery('#interval_day').val("");
            } else {
                jQuery("#last_vac_date_sec").show();
            }
        });
        jQuery("#formFile").change(function () {
            var fileExtension = ['pdf', 'jpeg', 'jpg', 'png'];
            if (jQuery.inArray(jQuery(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                alert("Only formats are allowed : " + fileExtension.join(', '));
                jQuery("#formFile").val("");
            }
        });

        // update vaccine info
        jQuery('#updte_btn').click(function (e) {
            e.preventDefault();

            var formData = new FormData(document.getElementById('form1'));
            console.log(formData);
            formData.append('action', 'update_vaccine');
            jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    console.warn(response);
                    if (response == "success") {
                        alert("information updated successfully!");
                        window.location = "<?php echo get_site_url(); ?>/dashboard";
                    } else if (response == "failed") {
                        alert("update failed");
                    }
                },
                error: function (response) {
                    alert("update failed!");
                }
            });

        });

    });

    jQuery('#datatable1').dataTable({
    });
</script>