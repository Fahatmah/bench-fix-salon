<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

    if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
  } else{
if(isset($_POST['submit']))
  {
    
    $cid=$_GET['viewid'];
      $remark=$_POST['remark'];
      $status=$_POST['status'];
   $query=mysqli_query($con, "update  tblbook set Remark='$remark',Status='$status' where ID='$cid'");
    
    if ($query) {
      $qry = mysqli_query($con, "SELECT tbluser.email, tbluser.Firstname, tbluser.Lastname, tblbook.AptNumber 
      FROM tbluser 
      JOIN tblbook ON tbluser.ID = tblbook.UserID 
      WHERE tblbook.ID = '$cid'");    
      
    if (!$qry) {
      echo "<script>alert('Error executing query: " . mysqli_error($con) . "');</script>";
      exit;
    }

    $results = mysqli_fetch_array($qry);

    if (!$results) {
        echo "<script>alert('No data found for appointment ID: $cid');</script>";
        exit;
    }

    $UserEmail = $results['email'];
    $UserName = $results['Firstname'] . ' ' . $results['Lastname'];
    $aptId = $results['AptNumber'];
    
    require __DIR__ . "/../../vendor/autoload.php";

        $mail = new PHPMailer(true);

            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "leonardr009@gmail.com";
            $mail->Password = "llgp swji majk hqwh";
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port = 587;

            $mail->setFrom("leonardr009@gmail.com");
            $mail->addAddress($UserEmail, $UserName);

            $mail->isHTML(true);

            if ($status == "Confirmed") {
              $mail->Subject = "Win Salon Appointment Approved";
              $mail->Body = "Hello $UserName,<br><br>Your appointment request (Reference ID: $aptId) has been approved. Please check your account for details.<br><br>Thanks for choosing Win Salon!";
            } elseif ($status == "Rejected") {
                $mail->Subject = "Win Salon Appointment Rejected";
                $mail->Body = "Hello $UserName,<br><br>We regret to inform you that your appointment request : $aptId : has been rejected. Please check your account for further details.<br><br>Best regards,<br>Win Salon";
            }

            try {
              $mail->send();
              echo '<script>alert("All remark has been updated.");</script>';
              echo "<script type='text/javascript'> document.location ='all-appointment.php'; </script>";
          } catch (Exception $e) {
              echo "<script>alert('Email could not be sent. Mailer Error: {$mail->ErrorInfo}');</script>";
          }
      } else {
        echo '<script>alert("Something Went Wrong. Please try again.");</script>';
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Win Salon | View Appointment</title>

<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- Bootstrap Core CSS -->
<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
<!-- Custom CSS -->
<link href="css/style.css" rel='stylesheet' type='text/css' />
<!-- font CSS -->
<!-- font-awesome icons -->
<link href="css/font-awesome.css" rel="stylesheet"> 
<!-- //font-awesome icons -->
 <!-- js-->
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/modernizr.custom.js"></script>
<!--webfonts-->
<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic' rel='stylesheet' type='text/css'>
<!--//webfonts--> 
<!--animate-->
<link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
<script src="js/wow.min.js"></script>
	<script>
		 new WOW().init();
	</script>
<!--//end-animate-->
<!-- Metis Menu -->
<script src="js/metisMenu.min.js"></script>
<script src="js/custom.js"></script>
<link href="css/custom.css" rel="stylesheet">
<!--//Metis Menu -->
</head> 
<body class="cbp-spmenu-push">
	<div class="main-content">
		<!--left-fixed -navigation-->
		 <?php include_once('includes/sidebar.php');?>
		<!--left-fixed -navigation-->
		<!-- header-starts -->
		 <?php include_once('includes/header.php');?>
		<!-- //header-ends -->
		<!-- main content start-->
		<div id="page-wrapper">
			<div class="main-page">
				<div class="tables">
					<h3 class="title1">View Appointment</h3>
					<div class="table-responsive bs-example widget-shadow">

						<h4>View Appointment:</h4>
						<?php
$cid=$_GET['viewid'];
$ret=mysqli_query($con,"select tbluser.FirstName,tbluser.LastName,tbluser.Email,tbluser.MobileNumber,tblbook.ID as bid,tblbook.AptNumber,tblbook.AptDate,tblbook.AptTime,tblbook.Service, tblbook.Service_Total_Price, tblbook.Message,tblbook.BookingDate,tblbook.Remark,tblbook.Status,tblbook.RemarkDate from tblbook join tbluser on tbluser.ID=tblbook.UserID where tblbook.ID='$cid'");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>
						<table class="table table-bordered">
							<tr>
    <th>Appointment Number</th>
    <td><?php  echo $row['AptNumber'];?></td>
  </tr>
  <tr>
<th>Name</th>
    <td><?php  echo $row['FirstName'];?> <?php  echo $row['LastName'];?></td>
  </tr>

<tr>
    <th>Email</th>
    <td><?php  echo $row['Email'];?></td>
  </tr>
   <tr>
    <th>Mobile Number</th>
    <td><?php  echo $row['MobileNumber'];?></td>
  </tr>
   <tr>
    <th>Appointment Date</th>
    <td><?php  echo $row['AptDate'];?></td>
  </tr>
 
<tr>
    <th>Appointment Time</th>
    <!-- <td><?php  echo $row['AptTime'];?></td> -->
    <td><?php echo date("g:i A", strtotime($row['AptTime'])); ?></td>
  </tr>

  <tr>
    <th>Service</th>
    <td><?php  echo $row['Service'];?></td>
  </tr>

  <tr>
    <th>Total Price</th>
    <td><?php  echo $row['Service_Total_Price'];?></td>
  </tr>
  
  
  <tr>
    <th>Apply Date</th>
    <td><?php  echo $row['BookingDate'];?></td>
  </tr>
  

<tr>
    <th>Status</th>
    <td> <?php  
if($row['Status']=="")
{
  echo "Not Updated Yet";
}

if($row['Status']=="Approved")
{
  echo "Approved";
}

if($row['Status']=="Rejected")
{
  echo "Rejected";
}

     ;?></td>
  </tr>
						</table>
						<table class="table table-bordered">
							<?php if($row['Status']==""){ ?>


<form name="submit" method="post" enctype="multipart/form-data"> 

<tr>
    <th>Remark :</th>
    <td>
    <textarea name="remark" placeholder="" rows="12" cols="14" class="form-control wd-450" required="true"></textarea></td>
   </tr>

  <tr>
    <th>Status :</th>
    <td>
   <select name="status" class="form-control wd-450" required="true" >
     <option value="Confirmed" selected="true">Confirm</option>
     <option value="Rejected">Rejected</option>
   </select></td>
  </tr>

  <tr align="center">
    <td colspan="2"><button type="submit" name="submit" class="btn btn-primary">Submit</button></td>
  </tr>
  </form>
<?php } else { ?>
						</table>
						<table class="table table-bordered">
							<tr>
    <th>Remark</th>
    <td><?php echo $row['Remark']; ?></td>
  </tr>
<tr>
    <th>Status</th>
    <td><?php echo $row['Status']; ?></td>
  </tr>

<tr>
<th>Remark date</th>
<td><?php echo $row['RemarkDate']; ?>  </td></tr>

						</table>
						<?php } ?>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<!--footer-->
		
        <!--//footer-->
	</div>
	<!-- Classie -->
		<script src="js/classie.js"></script>
		<script>
			var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
				showLeftPush = document.getElementById( 'showLeftPush' ),
				body = document.body;
				
			showLeftPush.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( body, 'cbp-spmenu-push-toright' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
				disableOther( 'showLeftPush' );
			};
			
			function disableOther( button ) {
				if( button !== 'showLeftPush' ) {
					classie.toggle( showLeftPush, 'disabled' );
				}
			}
		</script>
	<!--scrolling js-->
	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>
	<!--//scrolling js-->
	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.js"> </script>
</body>
</html>
<?php }  ?>