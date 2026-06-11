
<?php
require_once 'auth.php';
require_once 'db.php';

$landlord_id = $_SESSION['landlord_id'];

try {

    $stmt = $pdo->prepare(
        "SELECT COUNT(*) total
         FROM houses
         WHERE landlord_id=?"
    );
    $stmt->execute([$landlord_id]);
    $totalUnits = $stmt->fetch()['total'];

    $stmt = $pdo->prepare(
        "SELECT COUNT(*) total
         FROM houses
         WHERE landlord_id=?
         AND status='occupied'"
    );
    $stmt->execute([$landlord_id]);
    $occupiedUnits = $stmt->fetch()['total'];

    $stmt = $pdo->prepare(
        "SELECT COUNT(*) total
         FROM houses
         WHERE landlord_id=?
         AND status='vacant'"
    );
    $stmt->execute([$landlord_id]);
    $vacantUnits = $stmt->fetch()['total'];

    $stmt = $pdo->prepare(
        "SELECT COUNT(*) total
         FROM tenants
         WHERE landlord_id=?"
    );
    $stmt->execute([$landlord_id]);
    $totalTenants = $stmt->fetch()['total'];

    $month = date('n');
    $year = date('Y');

    $stmt = $pdo->prepare(
        "SELECT COUNT(*) total
         FROM rent_records rr
         JOIN tenants t ON rr.tenant_id=t.id
         WHERE t.landlord_id=?
         AND rr.rent_month=?
         AND rr.rent_year=?
         AND rr.status='paid'"
    );
    $stmt->execute([$landlord_id,$month,$year]);
    $paidTenants = $stmt->fetch()['total'];

    $stmt = $pdo->prepare(
        "SELECT COUNT(*) total
         FROM rent_records rr
         JOIN tenants t ON rr.tenant_id=t.id
         WHERE t.landlord_id=?
         AND rr.rent_month=?
         AND rr.rent_year=?
         AND rr.status!='paid'"
    );
    $stmt->execute([$landlord_id,$month,$year]);
    $unpaidTenants = $stmt->fetch()['total'];

    $stmt = $pdo->prepare(
        "SELECT IFNULL(SUM(rr.amount_paid),0) total
         FROM rent_records rr
         JOIN tenants t ON rr.tenant_id=t.id
         WHERE t.landlord_id=?
         AND rr.rent_month=?
         AND rr.rent_year=?"
    );
    $stmt->execute([$landlord_id,$month,$year]);
    $rentCollected = $stmt->fetch()['total'];

    $stmt = $pdo->prepare(
        "SELECT IFNULL(SUM(rr.balance),0) total
         FROM rent_records rr
         JOIN tenants t ON rr.tenant_id=t.id
         WHERE t.landlord_id=?
         AND rr.rent_month=?
         AND rr.rent_year=?"
    );
    $stmt->execute([$landlord_id,$month,$year]);
    $outstandingRent = $stmt->fetch()['total'];

} catch(Exception $e){

    die($e->getMessage());

}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Landlord Dashboard</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
background:#0f172a;
color:white;
}

.sidebar{
position:fixed;
left:0;
top:0;
width:250px;
height:100%;
background:#111827;
padding:25px;
}

.logo{
font-size:28px;
font-weight:700;
margin-bottom:40px;
}

.logo span{
color:#38bdf8;
}

.sidebar a{
display:block;
color:white;
text-decoration:none;
padding:14px;
margin-bottom:10px;
border-radius:10px;
background:rgba(255,255,255,.05);
}

.sidebar a:hover{
background:#38bdf8;
}

.main{
margin-left:250px;
padding:30px;
}

.header{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:30px;
}

.grid{
display:grid;
grid-template-columns:
repeat(auto-fit,minmax(250px,1fr));
gap:20px;
}

.card{
padding:25px;
border-radius:18px;

background:
rgba(255,255,255,.08);

backdrop-filter:blur(12px);

border:
1px solid rgba(255,255,255,.08);
}

.card h4{
color:#cbd5e1;
margin-bottom:10px;
font-size:14px;
}

.card h2{
font-size:32px;
}

.money{
color:#22c55e;
}

.danger{
color:#ef4444;
}

</style>

</head>

<body>

<div class="sidebar">

<div class="logo">
Rental<span>Hub</span>
</div>

<a href="landlord_dashboard.php">
🏠 Dashboard
</a>

<a href="house_types.php">
🏘 House Types
</a>

<a href="houses.php">
🏢 Houses
</a>

<a href="tenants.php">
👥 Tenants
</a>

<a href="payments.php">
💰 Payments
</a>

<a href="logout.php">
🚪 Logout
</a>

</div>

<div class="main">

<div class="header">

<div>
<h1>Welcome,
<?php echo htmlspecialchars($_SESSION['landlord_name']); ?>
</h1>

<p>
Landlord Code:
<strong>
<?php echo $_SESSION['landlord_code']; ?>
</strong>
</p>
</div>

</div>

<div class="grid">

<div class="card">
<h4>Total Units</h4>
<h2><?php echo $totalUnits; ?></h2>
</div>

<div class="card">
<h4>Occupied Units</h4>
<h2><?php echo $occupiedUnits; ?></h2>
</div>

<div class="card">
<h4>Vacant Units</h4>
<h2><?php echo $vacantUnits; ?></h2>
</div>

<div class="card">
<h4>Total Tenants</h4>
<h2><?php echo $totalTenants; ?></h2>
</div>

<div class="card">
<h4>Paid Tenants</h4>
<h2><?php echo $paidTenants; ?></h2>
</div>

<div class="card">
<h4>Unpaid Tenants</h4>
<h2><?php echo $unpaidTenants; ?></h2>
</div>

<div class="card">
<h4>Rent Collected</h4>
<h2 class="money">
KES <?php echo number_format($rentCollected,2); ?>
</h2>
</div>

<div class="card">
<h4>Outstanding Rent</h4>
<h2 class="danger">
KES <?php echo number_format($outstandingRent,2); ?>
</h2>
</div>

</div>

</div>

</body>
</html>

