<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();
//Udpate Staff
if (isset($_POST['UpdateRecharge'])) {
  //Prevent Posting Blank Values
  if (
    empty($_POST["phone_number"]) || empty($_POST["date"]) || empty($_POST['customer_name']) || empty($_POST['recharge_id'])
    || empty($_POST["reload"]) || empty($_POST["amount"]) || empty($_POST["statuss"])
  ) {
    $err = "Blank Values Not Accepted";
  } else {
    $phone_number = $_POST['phone_number'];
    $date  = $_POST['date'];
    $customer_name = $_POST['customer_name'];
    $recharge_id = $_POST['recharge_id'];
    $reload = $_POST['reload'];
    $amount = $_POST['amount'];
    $statuss = $_POST['statuss'];

    //Insert Captured information to a database table
    $postQuery = "UPDATE rpos_recharge SET  phone_number =?, date =?, customer_name =?, reload =?, amount =?, statuss=?  WHERE recharge_id =?";
    $postStmt = $mysqli->prepare($postQuery);
    //bind paramaters
    $rc = $postStmt->bind_param('sssssss', $phone_number, $date, $customer_name, $reload, $amount, $statuss, $recharge_id);
    $postStmt->execute();
    //declare a varible which will be passed to alert function
    if ($postStmt) {
      $success = "Staff Updated" && header("refresh:1; url=recharge.php");
    } else {
      $err = "Please Try Again Or Try Later";
    }
  }
}
require_once('partials/_head.php');
?>

<body>
  <!-- Sidenav -->
  <?php
  require_once('partials/_sidebar.php');
  ?>
  <!-- Main content -->
  <div class="main-content">
    <!-- Top navbar -->
    <?php
    require_once('partials/_topnav.php');
    $update = $_GET['update'];
    $ret = "SELECT * FROM  rpos_recharge WHERE recharge_id = '$update' ";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($recharge = $res->fetch_object()) {
    ?>
      <!-- Header -->
      <div style="background-image: url(assets/img/theme/restro00.jpg); background-size: cover;" class="header  pb-8 pt-5 pt-md-8">
        <span class="mask bg-gradient-dark opacity-8"></span>
        <div class="container-fluid">
          <div class="header-body">
          </div>
        </div>
      </div>
      <!-- Page content -->
      <div class="container-fluid mt--8">
        <!-- Table -->
        <div class="row">
          <div class="col">
            <div class="card shadow">
              <div class="card-header border-0">
                <h3>Please Fill All Fields</h3>
              </div>
              <div class="card-body">
                <form method="POST">
                  <div class="form-row">
                    <div class="col-md-4">
                      <input type="text" name="phone_number" placeholder="Phone Number" class="form-control" value="<?php echo $recharge->phone_number; ?>">
                    </div>
                    <div class="col-md-2">
                      <input type="submit" name="search" value="Search" class="btn btn-secondary" value="">
                    </div>

                    <div class="col-md-6">
                      <input type="text" name="date" id="current_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly>
                    </div>
                  </div>
                  <hr>
                  <div class="form-row">

                    <div class="col-md-6">
                      <label>Customer Name</label>
                      <input type="text" name="customer_name" class="form-control" value="<?php echo $recharge->customer_name; ?>">
                    </div>
                    <div class="col-md-6">
                      <label>Recharge ID</label>
                      <input type="text" name="recharge_id" class="form-control" value="<?php echo $recharge->recharge_id; ?>">
                    </div>
                  </div>
                  <br>
                  <div class="form-row">
                    <div class="col-md-6">
                      <label>Reload Type</label> <br>
                      <select name="reload" id="reload" class="form-control" value="<?php echo $recharge->reload; ?>">
                        <option value="mobile">Mobile</option>
                        <option value="dishtv">Dish TV</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label>Amount</label>
                      <input type="text" name="amount" class="form-control" value="<?php echo $recharge->amount; ?>">
                    </div>
                  </div>
                  <br>
                  <div class="form-row">

                    <div class="col-md-6">
                      <label>Status</label> <br>
                      <select name="statuss" id="status" class="form-control" value="<?php echo $recharge->statuss; ?>">
                        <option value="success">Success</option>
                        <option value="pending">Pending</option>
                        <option value="fail">Fail</option>
                      </select>
                    </div>
                  </div>
                  <br>
                  <div class="form-row">
                    <div class="col-md-6">
                      <input type="submit" name="UpdateRecharge" value="Update Recharge" class="btn btn-success" value="">
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- Footer -->
      <?php
      require_once('partials/_footer.php');
    }
      ?>
      </div>
  </div>
  <!-- Argon Scripts -->
  <?php
  require_once('partials/_scripts.php');
  ?>
</body>

</html>