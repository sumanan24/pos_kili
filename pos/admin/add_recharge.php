<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();
//Add Staff
if (isset($_POST['addrecharge'])) {
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
    $postQuery = "INSERT INTO rpos_recharge (recharge_id, phone_number, date, customer_name, reload,amount,statuss) VALUES(?,?,?,?,?,?,?)";
    $postStmt = $mysqli->prepare($postQuery);
    //bind paramaters
    $rc = $postStmt->bind_param('sssssss', $recharge_id, $phone_number, $date, $customer_name, $reload, $amount, $statuss);
    $postStmt->execute();
    //declare a varible which will be passed to alert function
    if ($postStmt) {
      $success = "Recharge Added";
      header("refresh:2; url=recharge.php");
    } else {
      $err = "Please Try Again Or Try Later";
    }
  }
}


$customer_namesea = null;
$phone_number = null;
if (isset($_POST['search'])) {
  $phone_number = $_POST['phone_number'];
  $query = "SELECT customer_name as cname
                  FROM  rpos_customers 
                  WHERE customer_phoneno = $phone_number";
  $query_run = mysqli_query($mysqli, $query);
  while ($row = mysqli_fetch_array($query_run)) {

    $customer_namesea = $row['cname'];
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
                    <input type="text" name="phone_number" placeholder="Phone Number" class="form-control" value="<?php echo $phone_number; ?>">
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
                    <input type="text" name="customer_name" class="form-control" value="<?php echo $customer_namesea; ?>">
                  </div>
                  <div class="col-md-6">
                    <label>Recharge ID</label>
                    <input type="text" name="recharge_id" class="form-control" value="<?php echo $alpha; ?>-<?php echo $beta; ?>">
                  </div>
                </div>
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Reload Type</label> <br>
                    <select name="reload" id="reload" class="form-control">
                      <option value="mobile">Mobile</option>
                      <option value="dishtv">Dish TV</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label>Amount</label>
                    <input type="text" name="amount" class="form-control" value="">
                  </div>
                </div>
                <br>
                <div class="form-row">

                  <div class="col-md-6">
                    <label>Status</label> <br>
                    <select name="statuss" id="status" class="form-control">
                      <option value="success">Success</option>
                      <option value="pending">Pending</option>
                      <option value="fail">Fail</option>
                    </select>
                  </div>
                </div>
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" name="addrecharge" value="Add Recharge" class="btn btn-success" value="">
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
      ?>
    </div>
  </div>
  <!-- Argon Scripts -->
  <?php
  require_once('partials/_scripts.php');
  ?>
</body>

</html>