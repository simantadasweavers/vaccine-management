<?php
/*
 * Plugin Name:       Vaccine
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       vaccine management plugin
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Shimanta Das(AbhimanYu Saha Team)
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       vaccine-management
 * Icon: icon-256x256.png
 */

if (!defined('ABSPATH')) {
   die("you are not allow to access this resource");
}

// enqueue css files
function enqueue_styles()
{
   $plugin_url = plugin_dir_url(__FILE__);
   wp_enqueue_style('bootstrap-css', $plugin_url . "assets/bootstrap.css");
   wp_enqueue_style('custom-css', $plugin_url . "assets/custom.css");
}
add_action('admin_print_styles', 'enqueue_styles');
// eneueue js files
function enqueue_scripts()
{
   $plugin_url = plugin_dir_url(__FILE__);
   wp_enqueue_script('bootstrap-js', $plugin_url . "assets/bootstrap.js", array('jquery'), null, true);
   wp_enqueue_script('custom-js', $plugin_url . "assets/custom.js", array('jquery'), null, true);

   // Enqueue jQuery & custom js
   wp_enqueue_script('jquery');
   wp_enqueue_script('vaccine-custom-js', $plugin_url . 'assets/js/custom.js', array('jquery'), null, true);

   // Localize the script with new data
   wp_localize_script(
      'vaccine-custom-js',
      'vaccine_ajax_object',
      array(
         'ajax_url' => admin_url('admin-ajax.php')
      )
   );
}
add_action('admin_enqueue_scripts', 'enqueue_scripts');


// Activation hook
function vaccine_plugin_activate()
{
   // require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

   // // getting database auth details
   // $path = $_SERVER['DOCUMENT_ROOT'];
   // include_once $path . '/wp-config.php';

   global $wpdb;
   $host = $wpdb->dbhost;
   $user = $wpdb->dbuser;
   $pass = $wpdb->dbpassword;
   $db = $wpdb->dbname;

   $conn = mysqli_connect($host, $user, $pass, $db);
   // Code to execute on plugin activation
   global $wpdb;
   $table_name = $wpdb->prefix . 'vaccinations';

   $sql = "CREATE TABLE $table_name (
      `id` bigint NOT NULL,
      `phone` bigint NOT NULL,
      `firstname` varchar(100) NOT NULL,
      `lastname` varchar(100) NOT NULL,
      `vaccine` varchar(100) NOT NULL,
      `price` int NOT NULL,
      `doctor` varchar(100) NOT NULL,
      `vaccination_date` varchar(100) NOT NULL,
      `last_vaccination_date` varchar(100) DEFAULT NULL,
      `notes` varchar(200) DEFAULT NULL,
      `file` varchar(250) DEFAULT NULL,
      `userid` bigint NOT NULL
    );
    ";
   mysqli_query($conn, $sql);

   $sql = "ALTER TABLE $table_name
   ADD PRIMARY KEY (`id`);";
   mysqli_query($conn, $sql);

   $sql = "ALTER TABLE $table_name
   MODIFY `id` bigint NOT NULL AUTO_INCREMENT;";
   mysqli_query($conn, $sql);


   // adding role into wordpress project
   add_role(
      'agent',
      __('Agent'),
      array(
         'read' => true,
         'edit_posts' => false,
         'delete_posts' => false,
         'agent_access' => true, // Custom capability
      )
   );
   // adding role into wordpress project
   add_role(
      'patient',
      __('Patient'),
      array(
         'read' => true,
      )
   );
}
register_activation_hook(__FILE__, 'vaccine_plugin_activate');


// add menu to admin panel page
function vaccine_plugin_options_page()
{
   add_menu_page(
      'Manage Your Vaccines At One Place',           // Page title
      'Vaccines',           // Menu title
      'manage_options',       // Capability
      'my_vaccines',           // Menu slug
      'my_options_page_html', // Function to display the content of the page
   );

   add_submenu_page(
      'my_vaccines',
      'All Patients',
      'All Patients',
      'manage_options',
      'show_all_patients',
      'show_all_patients_function'
   );

   add_submenu_page(
      'my_vaccines',
      'All Agents',
      'All Agents',
      'manage_options',
      'show_all_agents',
      'show_all_agents_function'
   );

   add_submenu_page(
      'my_vaccines',
      'Patient Vaccines List',
      'Patient Vaccines List',
      'manage_options',
      'show_all_patients_vaccine_bookings',
      'show_all_patients_vaccine_bookings_function'
   );

   // Add submenu under the 'Vaccines' menu
   add_submenu_page(
      'my_vaccines', // Parent slug
      'Patient Vaccine Form', // Page title
      'Patient Vaccine Form', // Submenu title
      'agent_access', // Capability
      'my_patient_vaccine_form', // Menu slug
      'my_patient_vaccine_form_function' // Function to display the content of the submenu page
   );

   add_submenu_page(
      'my_vaccines',
      'Next Vaccines',
      'Next Vaccines',
      'agent_access',
      'my_next_vaccines',
      'my_next_vaccines_function'
   );

   add_submenu_page(
      'my_vaccines',
      'All Patient Vaccines List',
      'All Patient Vaccines List',
      'agent_access',
      'my_all_patient_vaccines_list',
      'my_all_patient_vaccines_list_function'
   );

   add_submenu_page(
      'my_vaccines',
      'My Bookings',
      'My Bookings',
      'patient_access',
      'my_bookings',
      'my_bookings_function'
   );

}
add_action('admin_menu', 'vaccine_plugin_options_page');

// providing access to custom role user - agent, patient
function agent_capabilities()
{
   $role = get_role('agent');
   if ($role) {
      $role->add_cap('agent_access');
   }
}
function patient_capabilities()
{
   $role = get_role('patient');
   if ($role) {
      $role->add_cap('patient_access');
   }
}
add_action('admin_init', 'agent_capabilities');
add_action('admin_init', 'patient_capabilities');


// Search for person by phone number - vaccine registration form
add_action('wp_ajax_search_person', 'find_names');
add_action('wp_ajax_nopriv_search_person', 'find_names');
function find_names()
{

   $phone = sanitize_text_field($_POST['phone']);

   $args = array(
      'meta_key' => 'phone',
      'meta_value' => $phone
   );

   $user_query = new WP_User_Query($args);
   $users = $user_query->get_results();
   error_log("Number of users found: " . count($users));

   if (!empty($users)) {
      $user = $users[0]; // Assuming phone numbers are unique
      $fname = $user->first_name;
      $lname = $user->last_name;
      $fullname = $fname . " " . $lname;
      echo $fullname;
   } else {
      echo "failed";
   }

   wp_die();
}

// fetch person info
add_action('wp_ajax_fetch_person', 'find_info');
add_action('wp_ajax_nopriv_fetch_person', 'find_info');
function find_info()
{
   // $path = $_SERVER['DOCUMENT_ROOT'];
   // include_once $path . '/wp-config.php';

   global $wpdb;
   $host = $wpdb->dbhost;
   $user = $wpdb->dbuser;
   $pass = $wpdb->dbpassword;
   $db = $wpdb->dbname;
   $conn = mysqli_connect($host, $user, $pass, $db);
   $id = sanitize_text_field($_POST['id']);
   $table = $wpdb->prefix . "vaccinations";


   $sql = "SELECT * FROM $table WHERE id='$id'";
   $query = mysqli_query($conn, $sql);
   $result = mysqli_fetch_assoc($query);

   // getting interval year, month, day between 2 dates
   if ($result['last_vaccination_date']) {
      $date1 = new DateTime($result['vaccination_date']);
      $date2 = new DateTime($result['last_vaccination_date']);
      $interval = $date1->diff($date2);
      $interval_year = $interval->y;
      $interval_month = $interval->m;
      $interval_day = $interval->d;
      $result['interval_year'] = $interval_year;
      $result['interval_month'] = $interval_month;
      $result['interval_day'] = $interval_day;
   } else {
      $result['interval_year'] = 0;
      $result['interval_month'] = 0;
      $result['interval_day'] = 0;
   }

   // getting user email id
   $args = array(
      'meta_key' => 'phone',
      'meta_value' => $result['email']
   );
   $user_query = new WP_User_Query($args);
   $users = $user_query->get_results();
   if (!empty($users)) {
      $user = $users[0];
      $result['email'] = $user->user_email;
   } else {
      echo "failed";
   }

   if ($result) {
      wp_send_json_success($result);
   } else {
      wp_send_json_error('No data found');
   }

   wp_die();
}



// register new patient's vaccine
add_action('wp_ajax_book_vaccine', 'register_vaccine');
add_action('wp_ajax_nopriv_book_vaccine', 'register_vaccine');
function register_vaccine()
{
   // $path = $_SERVER['DOCUMENT_ROOT'];
   // include_once $path . '/wp-config.php';

   global $wpdb;
   $host = $wpdb->dbhost;
   $user = $wpdb->dbuser;
   $pass = $wpdb->dbpassword;
   $db = $wpdb->dbname;

   $phone = sanitize_text_field($_POST['phone']);
   $email = sanitize_text_field($_POST['email']);
   $vcname = sanitize_text_field($_POST['vaccine_name']);
   $price = sanitize_text_field($_POST['vaccine_price']);
   $fname = sanitize_text_field($_POST['fname']);
   $lname = sanitize_text_field($_POST['lname']);
   $doctor = sanitize_text_field($_POST['doctor']);
   $year = sanitize_text_field($_POST['interval_year']);
   $month = sanitize_text_field($_POST['interval_month']);
   $day = sanitize_text_field($_POST['interval_day']);
   $note = sanitize_text_field($_POST['notes']);
   $current_date = sanitize_text_field($_POST['vac_date']);

   // seach via phone nmber meta field if user exist 
   $args = array(
      'meta_key' => 'phone',
      'meta_value' => $phone
   );
   $user_query = new WP_User_Query($args);
   $user = $user_query->get_results();
   foreach ($user as $user) {
      $name = $user->display_name;
   }

   if ($name) {
      // if user exist then book the vaccine

      // uploading the file
      if ($_FILES["formFile"]["tmp_name"]) {
         // Ensure the uploads directory exists
         $target_dir = "../uploads/";
         if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
         }

         // Generate a unique hash for the file
         $file_temp = $_FILES["formFile"]["tmp_name"];
         $file_name = basename($_FILES["formFile"]["name"]);
         $unique_id = uniqid();
         $file_hash = hash('md5', file_get_contents($file_temp) . $unique_id);
         $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
         $hashfile_name = $file_hash . '.' . $file_ext;
         $target_file = $target_dir . $file_hash . '.' . $file_ext;
         $uploadOk = 1;

         // Check if file already exists
         if (file_exists($target_file)) {
            $uploadOk = 0;
         }
         if ($uploadOk == 0) {
            // echo "Sorry, your file was not uploaded.";
         } else {
            if (move_uploaded_file($_FILES["formFile"]["tmp_name"], $target_file)) {
               $file_store_path = "uploads/" . $hashfile_name;
            } else {
               // echo "Sorry, there was an error uploading your file.";
            }
         }
      }


      if ($year || $month || $day) {
         $date = new DateTime($current_date);
         $str = "P" . $year . "Y" . $month . "M" . $day . "D";
         $interval = new DateInterval($str);
         $date->add($interval);
         $new_date = $date->format('Y-m-d');
      }

      $id = get_current_user_id();
      $table = $wpdb->prefix . "vaccinations";
      $res = $wpdb->insert(
         $table,
         array(
            "phone" => $phone,
            "firstname" => $fname,
            "lastname" => $lname,
            "vaccine" => $vcname,
            "price" => $price,
            "doctor" => $doctor,
            "vaccination_date" => $current_date,
            "last_vaccination_date" => $new_date,
            "notes" => $note,
            "file" => $file_store_path,
            "userid" => $id
         )
      );


      if ($res === false) {
         error_log("Database update error: " . $wpdb->last_error);
         echo "failed";
      } else {
         echo "success";
      }

   } else {
      // if user not exist 

      // user's registration
      $username = $fname . $lname;
      $password = $phone;
      $user_id = wp_create_user($username, $password, $email);
      if (is_wp_error($user_id)) {
         echo 'Error: ' . $user_id->get_error_message();

         echo "failed";
      } else {

         // setting user's role
         $user_id_role = new WP_User($user_id);
         $user_id_role->set_role('patient');

         update_user_meta($user_id, 'first_name', $fname);
         update_user_meta($user_id, 'last_name', $lname);
         update_user_meta($user_id, 'age', $age);
         update_user_meta($user_id, 'phone', $phone);

         // uploading the file
         if ($_FILES["formFile"]["tmp_name"]) {
            // Ensure the uploads directory exists
            $target_dir = "../uploads/";
            if (!is_dir($target_dir)) {
               mkdir($target_dir, 0777, true);
            }

            // Generate a unique hash for the file
            $file_temp = $_FILES["formFile"]["tmp_name"];
            $file_name = basename($_FILES["formFile"]["name"]);
            $unique_id = uniqid();
            $file_hash = hash('md5', file_get_contents($file_temp) . $unique_id);
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $hashfile_name = $file_hash . '.' . $file_ext;
            $target_file = $target_dir . $file_hash . '.' . $file_ext;
            $uploadOk = 1;

            // Check if file already exists
            if (file_exists($target_file)) {
               $uploadOk = 0;
            }
            if ($uploadOk == 0) {
               // echo "Sorry, your file was not uploaded.";
            } else {
               if (move_uploaded_file($_FILES["formFile"]["tmp_name"], $target_file)) {
                  $file_store_path = "uploads/" . $hashfile_name;
               } else {
                  // echo "Sorry, there was an error uploading your file.";
               }
            }
         }



         if ($year || $month || $day) {
            $date = new DateTime($current_date);
            $str = "P" . $year . "Y" . $month . "M" . $day . "D";
            $interval = new DateInterval($str);
            $date->add($interval);
            $new_date = $date->format('Y-m-d');
         }

         $id = get_current_user_id();
         $table = $wpdb->prefix . "vaccinations";
         $res = $wpdb->insert(
            $table,
            array(
               "phone" => $phone,
               "firstname" => $fname,
               "lastname" => $lname,
               "vaccine" => $vcname,
               "price" => $price,
               "doctor" => $doctor,
               "vaccination_date" => $current_date,
               "last_vaccination_date" => $new_date,
               "notes" => $note,
               "file" => $file_store_path,
               "userid" => $id
            )
         );

         if ($res === false) {
            error_log("Database update error: " . $wpdb->last_error);
            echo "failed";
         } else {
            echo "success";
         }


      }
      // end of user registration


   }

   wp_die();
}

add_action('wp_ajax_update_vaccine', 'update_info');
add_action('wp_ajax_nopriv_update_vaccine', 'update_info');
function update_info()
{

   // $path = $_SERVER['DOCUMENT_ROOT'];
   // include_once $path . '/wp-config.php';

   global $wpdb;

   $record_id = sanitize_text_field($_POST['recordid']);
   $phone = sanitize_text_field($_POST['phone']);
   $vcname = sanitize_text_field($_POST['vaccine_name']);
   $price = sanitize_text_field($_POST['vaccine_price']);
   $fname = sanitize_text_field($_POST['fname']);
   $lname = sanitize_text_field($_POST['lname']);
   $doctor = sanitize_text_field($_POST['doctor']);
   $year = sanitize_text_field($_POST['interval_year']);
   $month = sanitize_text_field($_POST['interval_month']);
   $day = sanitize_text_field($_POST['interval_day']);
   $note = sanitize_text_field($_POST['notes']);
   $current_date = sanitize_text_field($_POST['vac_date']);

   // uploading the file
   if ($_FILES["formFile"]["tmp_name"]) {
      // Ensure the uploads directory exists
      $target_dir = "../uploads/";
      if (!is_dir($target_dir)) {
         mkdir($target_dir, 0777, true);
      }

      $file_temp = $_FILES["formFile"]["tmp_name"];
      $file_name = basename($_FILES["formFile"]["name"]);
      $unique_id = uniqid();
      $file_hash = hash('md5', file_get_contents($file_temp) . $unique_id);
      $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
      $hashfile_name = $file_hash . '.' . $file_ext;
      $target_file = $target_dir . $file_hash . '.' . $file_ext;
      $uploadOk = 1;

      if (file_exists($target_file)) {
         $uploadOk = 0;
      }
      if ($uploadOk == 0) {
         // echo "Sorry, your file was not uploaded.";
      } else {
         if (move_uploaded_file($_FILES["formFile"]["tmp_name"], $target_file)) {
            $file_store_path = "uploads/" . $hashfile_name;
         }else{
            $file_store_path = "";
         } 
      }
   }
   // end of file upload

   $new_date = null;
   if ($year || $month || $day) {
      $date = new DateTime($current_date);
      $str = "P" . $year . "Y" . $month . "M" . $day . "D";
      $interval = new DateInterval($str);
      $date->add($interval);
      $new_date = $date->format('Y-m-d');
   }


   $agent_id = get_current_user_id();
   $table = $wpdb->prefix . "vaccinations";
   $res = $wpdb->update(
      $table,
      array(
         "vaccine" => $vcname,
         "price" => $price,
         "doctor" => $doctor,
         "vaccination_date" => $current_date,
         "last_vaccination_date" => $new_date,
         "notes" => $note,
         "file" => $file_store_path,
         "userid" => $agent_id,
      ),
      array('id' => $record_id)
   );


   if ($res === false) {
      error_log("Database update error: " . $wpdb->last_error);
      echo "failed";
   } else {
      echo "success";
   }

   wp_die();
}

// delete record on basics of id
add_action('wp_ajax_delete_record', 'delete_info');
add_action('wp_ajax_nopriv_delete_record', 'delete_info');
function delete_info()
{
   // $path = $_SERVER['DOCUMENT_ROOT'];
   // include_once $path . '/wp-config.php';

   global $wpdb;
   $record_id = sanitize_text_field($_POST['id']);
   $table = $wpdb->prefix . "vaccinations";

   $result = $wpdb->delete(
      $table,
      array('id' => $record_id)
   );


   if ($result) {
      echo "success";
   } else {
      echo "failed";
   }

   wp_die();
}





/**
 * page calling on menu page click
 */

// default page calling on plugin
function my_options_page_html()
{
   if (!current_user_can('manage_options')) {
      return;
   }
   ?>
   <div class="wrap">
      <h1 class="text-center">Instructions</h1>
      <br>
      <div class="row">
         <div class="col-1"></div>
         <div class="col-10">

            <p style="font-size:20px;background-color:yellow;color:black;">This plugin has generally 3 roles - <b>Super Admin</b>, <b>AGENT</b>
               and <b>PATIENT</b>
               <br><br>
               <b>Super Admin</b> ~ is website admin(administrator). He/She can login into their account via <?php echo get_site_url()."/login"; ?>. <br> He/She can view list of agents, patients. Also can view patient's vaccine information. <br> He/She has the access to edit or even remove vaccine's record. 
               <br>
               <br>
               <b>Agent</b> ~ is responsible for providing vaccine to patient. He/She can login into their account via <?php echo get_site_url()."/login"; ?>. <br> He/She can register patient along with their vaccines. 
               <br>
               He/she can edit patient's vaccine information. <br> Agents also view upcoming vaccine information. 
               <br><br>
               <b>Patient</b> ~ is responsible for accepting vaccine from agent. He/She can login into their account via <?php echo get_site_url()."/login"; ?>. <br> Inside My bookings section he/she can view vaccine and agent information. 
            </p>

            <p style="font-size:20px;background-color:green;color:white;">
               Admin can create agent manually and should have provide role select as "Agent"
               <br> Agent can register non-existing patient during vaccine booking
               <br> Agent can allot vaccine dates and in future he/she can update them too.
            </p>


         </div>
         <div class="col-1"></div>
      </div>
   </div>
   <?php
}

function my_patient_vaccine_form_function()
{
   require (plugin_dir_path(__FILE__) . 'agent/vaccine_form.php');
}

function my_all_patient_vaccines_list_function()
{
   require (plugin_dir_path(__FILE__) . 'agent/all_vaccines_list.php');
}

function my_next_vaccines_function()
{
   require (plugin_dir_path(__FILE__) . 'agent/next_vaccines.php');
}

function my_bookings_function()
{
   require (plugin_dir_path(__FILE__) . 'patient/bookings.php');
}

function show_all_patients_function(){
   require (plugin_dir_path(__FILE__) . 'admin/patients.php');
}

function show_all_agents_function(){
   require (plugin_dir_path(__FILE__) . 'admin/agents.php');
}

function show_all_patients_vaccine_bookings_function(){
   require (plugin_dir_path(__FILE__) . 'admin/vaccine_bookings.php');
}

?>