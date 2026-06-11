
<?php
require_once 'auth.php';
require_once 'db.php';
require_once 'functions.php';

$landlord_id = $_SESSION['landlord_id'];

/* Get tenants with house + rent info */
$stmt = $pdo->prepare(
    "SELECT 
        t.id,
        t.full_name,
        t.phone,
        t.email,
        h.room_number,
        ht.type_name,
        ht.rent_amount
     FROM tenants t
     JOIN houses h ON t.house_id = h.id
     JOIN house_types ht ON h.house_type_id = ht.id
     WHERE t.landlord_id = ?
     ORDER BY t.id DESC"
);

$stmt->execute([$landlord_id]);
$tenants = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tenants</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

<style>

body{
margin:0;
font-family:Poppins;
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

.logo span{color:#38bdf8;}

.sidebar a{
display:block;
padding:14px;
margin-bottom:10px;
text-decoration:none;
color:white;
border-radius:10px;
background:rgba(255,255,255,0.05);
}

.sidebar a:hover{
background:#38bdf8;
}

.main{
margin-left:250px;
padding:30px;
}

.card{
background:rgba(255,255,255,0.08);
backdrop-filter:blur(15px);
padding:25px;
border-radius:20px;
margin-bottom:20px;
}

table{
width:100%;
border-collapse:collapse;
}

th{
background:#1e293b;
padding:15px;
text-align:left;
}

td{
padding:15px;
border-bottom:1px solid rgba(255,255,255,0.1);
}

.badge{
padding:6px 12px;
border-radius:20px;
font-size:12px;
background:#22c55e;
}

</style>

</head>

<body>

<div class="sidebar">

<div class="logo">
Rental<span>Hub</span>
</div>

<a href="landlord_dashboard.php">🏠 Dashboard</a>
<a href="house_types.php">🏘 House Types</a>
<a href="houses.php">🏢 Houses</a>
<a href="tenants.php">👥 Tenants</a>
<a href="payments.php">💰 Payments</a>
<a href="logout.php">🚪 Logout</a>

</div>

<div class="main">

<div class="card">

<h2>All Tenants</h2>

<table>

<tr>
<th>Name</th>
<th>Phone</th>
<th>Email</th>
<th>Room</th>
<th>Type</th>
<th>Rent</th>
</tr>

<?php foreach($tenants as $t): ?>

<tr>
<td><?php echo htmlspecialchars($t['full_name']); ?></td>
<td><?php echo $t['phone']; ?></td>
<td><?php echo $t['email']; ?></td>
<td><?php echo $t['room_number']; ?></td>
<td><?php echo $t['type_name']; ?></td>
<td>KES <?php echo number_format($t['rent_amount'],2); ?></td>
</tr>

<?php endforeach; ?>

<?php if(count($tenants) == 0): ?>
<tr>
<td colspan="6">No tenants found</td>
</tr>
<?php endif; ?>

</table>

</div>

</div>

</body>
</html>

