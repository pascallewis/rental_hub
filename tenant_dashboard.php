
<?php
require_once 'db.php';
require_once 'functions.php';

if(!isset($_SESSION['tenant_id']))
{
    header("Location: tenant_login.php");
    exit();
}

$tenant_id = $_SESSION['tenant_id'];
$house_id = $_SESSION['house_id'];

/* Get tenant info */
$stmt = $pdo->prepare(
    "SELECT t.*, h.room_number, ht.type_name, ht.rent_amount
     FROM tenants t
     JOIN houses h ON t.house_id = h.id
     JOIN house_types ht ON h.house_type_id = ht.id
     WHERE t.id = ?"
);

$stmt->execute([$tenant_id]);
$data = $stmt->fetch();

/* Get payments */
$stmt = $pdo->prepare(
    "SELECT IFNULL(SUM(amount),0) as total_paid
     FROM payments
     WHERE tenant_id = ?"
);

$stmt->execute([$tenant_id]);
$total_paid = $stmt->fetch()['total_paid'];

$rent = $data['rent_amount'];
$balance = $rent - $total_paid;
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tenant Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

<style>

body{
margin:0;
font-family:Poppins;
background:#0f172a;
color:white;
}

.container{
padding:30px;
}

.card{
background:rgba(255,255,255,0.08);
backdrop-filter:blur(15px);
padding:25px;
border-radius:20px;
margin-bottom:20px;
}

.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
gap:15px;
}

.box{
padding:20px;
background:rgba(255,255,255,0.05);
border-radius:15px;
text-align:center;
}

h1,h2,h3{
margin:0;
}

.green{color:#22c55e;}
.red{color:#ef4444;}

a{
color:#38bdf8;
text-decoration:none;
}

</style>

</head>

<body>

<div class="container">

<h2>Welcome, <?php echo $_SESSION['tenant_name']; ?></h2>

<div class="card">

<h3>Your Room Details</h3>

<p>Room: <b><?php echo $data['room_number']; ?></b></p>
<p>Type: <b><?php echo $data['type_name']; ?></b></p>
<p>Monthly Rent: <b>KES <?php echo number_format($rent,2); ?></b></p>

</div>

<div class="grid">

<div class="box">
<h3>Total Paid</h3>
<p class="green">KES <?php echo number_format($total_paid,2); ?></p>
</div>

<div class="box">
<h3>Balance</h3>
<p class="red">KES <?php echo number_format($balance,2); ?></p>
</div>

<div class="box">
<h3>Status</h3>
<p>
<?php echo ($balance <= 0) ? "PAID" : "PENDING"; ?>
</p>
</div>

</div>

<div class="card">
<a href="tenant_login.php">Logout</a>
</div>

</div>

</body>
</html>

