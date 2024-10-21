<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$args1 = array(
    'role' => 'agent',
    'order' => 'ASC'
   );
    $agent = get_users($args1);
?>

<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


<div class="wrap">
    <h1 class="text-center">All Agents List</h1>

    <br>

    <table class="table" id="datatable">
  <thead>
    <tr>
      <th scope="col">Enroll No.</th>
      <th scope="col">First Name</th>
      <th scope="col">Last Name</th>
      <th scope="col">Username</th>
      <th scope="col">Email ID</th>
    </tr>
  </thead>
  <tbody>
  <?php 
  $i=1;
  foreach($agent as $user){ 
    $field = get_post_meta($user->ID);
    ?> 
  <tr>
      <th scope="row"><?php echo $i; ?></th>
      <td><?php echo $user->first_name; ?></td>
      <td><?php echo $user->last_name; ?></td>
      <td><?php echo $user->nickname; ?></td>
      <td><?php echo $user->user_email; ?></td>
    </tr>
    <?php $i++; } ?>
  </tbody>
</table>

</div>


<script>
    jQuery('#datatable').dataTable({
    });
</script>