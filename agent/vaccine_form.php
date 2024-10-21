<?php
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}
?>

<div class="wrap">
  <h1 class="text-center">Patient Vaccine Form</h1>

  <br>

  <!-- book new vaccine area -->
  <div>
    <div class="row">
      <div class="col-2"></div>
      <div class="col-8">

        <form id="form1">
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label required">Phone Number</label>
            <input type="number" class="form-control" name="phone" id="phone" placeholder="enter patient phone number"
              required>
            <div id="phone_input">
              <font class="alertfield">Please enter phone number</font>
            </div>
          </div>
          <div class="mb-3" id="email_sec">
            <label for="exampleInputEmail1" class="form-label required">Email</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="enter patient email id"
              required>
            <div id="email_input">
              <font class="alertfield">Please enter email id</font>
            </div>
          </div>
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label required">Vaccine Name</label>
            <input type="text" class="form-control" name="vaccine_name" id="vaccine_name"
              placeholder="enter patient vaccine name" required>
            <div id="vaccine_input">
              <font class="alertfield">Please enter vaccine name</font>
            </div>
          </div>
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label required">Vaccine Price</label>
            <input type="number" class="form-control" name="vaccine_price" id="vaccine_price"
              placeholder="enter patient vaccine price" required>
            <div id="price_input">
              <font class="alertfield">Please enter vaccine price</font>
            </div>
          </div>
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label required ">First Name</label>
            <input type="email" class="form-control" name="fname" id="fname" placeholder="enter patient first name"
              required>
            <div id="fname_input">
              <font class="alertfield">Please enter patient first name</font>
            </div>
          </div>
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label required">Last Name</label>
            <input type="email" class="form-control" name="lname" id="lname" placeholder="enter patient last name"
              required>
            <div id="lname_input">
              <font class="alertfield">Please enter patient last name</font>
            </div>
          </div>
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label required">Doctor Name</label>
            <input type="text" class="form-control" name="doctor" id="doctor" placeholder="enter patient doctor name"
              required>
            <div id="doctor_input">
              <font class="alertfield">Please enter doctor name</font>
            </div>
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
                <select class="form-select" name="interval_year" id="interval_year" aria-label="Default select example">
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
                <select class="form-select" name="interval_day" id="interval_day" aria-label="Default select example">
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
            <div id="vaccine_date_input">
              <font class="alertfield">Please enter vaccination date</font>
            </div>
          </div>

          <div class="mb-3">
            <label for="formFile" class="form-label">Select Prescription (supports only PDF, JPG, JPEG, PNG)</label>
            <input class="form-control" type="file" name="formFile" id="formFile">
          </div>

          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Raction Notes</label>
            <input type="text" class="form-control" name="notes" id="notes">
          </div>


          <button type="submit" id="submit_btn" class="btn btn-primary">Submit</button>
        </form>

      </div>
      <div class="col-2"></div>
    </div>
  </div>
  <!-- end of book vaccine area -->



  <script>
    jQuery('#document').ready(function () {

      jQuery('#phone').blur(function () {
        if (jQuery(this).val() == null || jQuery(this).val() == '') {
          jQuery('#phone_input').show();
        } else {
          jQuery('#phone_input').hide();
        }
      });
      jQuery('#vaccine_name').blur(function () {
        if (jQuery(this).val() == null || jQuery(this).val() == '') {
          jQuery('#vaccine_input').show();
        } else {
          jQuery('#vaccine_input').hide();
        }
      });
      jQuery('#vaccine_price').blur(function () {
        if (jQuery(this).val() == null || jQuery(this).val() == '') {
          jQuery('#price_input').show();
        } else {
          jQuery('#price_input').hide();
        }
      });
      jQuery('#fname').blur(function () {
        if (jQuery(this).val() == null || jQuery(this).val() == '') {
          jQuery('#fname_input').show();
        } else {
          jQuery('#fname_input').hide();
        }
      });
      jQuery('#lname').blur(function () {
        if (jQuery(this).val() == null || jQuery(this).val() == '') {
          jQuery('#lname_input').show();
        } else {
          jQuery('#lname_input').hide();
        }
      });
      jQuery('#doctor').blur(function () {
        if (jQuery(this).val() == null || jQuery(this).val() == '') {
          jQuery('#doctor_input').show();
        } else {
          jQuery('#doctor_input').hide();
        }
      });
      jQuery('#vac_date').blur(function () {
        if (jQuery(this).val() == null || jQuery(this).val() == '') {
          jQuery('#vaccine_date_input').show();
        } else {
          jQuery('#vaccine_date_input').hide();
        }
      });
      jQuery('#email').blur(function () {
        if (jQuery(this).is(":visible")) {
          if (jQuery(this).val() == null || jQuery(this).val() == '') {
            alert("Please enter email id");
            jQuery('#email_input').show();
          } else {
            jQuery('#email_input').hide();
          }
        }
      });


      var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";

      jQuery("#last_vac_date_sec").show();
      jQuery("#email_sec").hide();

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

      // file extension check using js
      jQuery("#formFile").change(function () {
        var fileExtension = ['pdf', 'jpeg', 'jpg', 'png'];
        if (jQuery.inArray(jQuery(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
          alert("Only formats are allowed : " + fileExtension.join(', '));
          jQuery("#formFile").val("");
        }
      });

      jQuery("#phone").blur(function () {
        let phone = jQuery('#phone').val();
        if (phone.length == 0) {
          alert("Please enter phone no.")
        } else {
          jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
              action: 'search_person',
              phone: phone
            },
            success: function (response) {
              if (response == "failed") {
                jQuery("#email_sec").show();
              } else {
                let name = response;
                let parts = name.split(' ')
                let firstName = parts.shift();
                let lastName = parts.join(' ');

                jQuery('#fname').val(firstName);
                jQuery('#fname_input').hide();
                jQuery('#lname').val(lastName);
                jQuery('#lname_input').hide();
              }
            },
            error: function(response){
                alert("Error Occured! Try again!");
            },
          });
        }

      });


      jQuery('#submit_btn').click(function (e) {
        e.preventDefault();

        let count = 0;
        // field input validation
        if (jQuery('#phone').val() != null && jQuery('#phone').val() != '') {
          count++;
        }
        if (jQuery('#vaccine_name').val() != null && jQuery('#vaccine_name').val() != '') {
          count++;
        }
        if (jQuery('#vaccine_price').val() != null && jQuery('#vaccine_price').val() != '') {
          count++;
        }
        if (jQuery('#fname').val() != null && jQuery('#fname').val() != '') {
          count++;
        }
        if (jQuery('#lname').val() != null && jQuery('#lname').val() != '') {
          count++;
        }
        if (jQuery('#doctor').val() != null && jQuery('#doctor').val() != '') {
          count++;
        }
        if (jQuery('#vac_date').val() != null && jQuery('#vac_date').val() != '') {
          count++;
        }
       if(jQuery('#email').is(":visible")){
        if (jQuery('#email').val() != null && jQuery('#email').val() != '') {
          count++;
        }else{
          count--;
        }
       }

        if (count >= 7) {
          var formData = new FormData(document.getElementById('form1'));
          formData.append('action', 'book_vaccine');
          jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
              console.warn(response);
              if (response == "success") {
                alert("booking successful!");
                window.location = "<?php echo get_site_url(); ?>/dashboard";
              } else if (response == "failed") {
                alert("booking failed");
              }
            },
            error: function (response) {
              alert("booking failed!");
            }
          });
        } else {
          alert("Enter all required inputs");
        }

      });

    });
  </script>


</div>