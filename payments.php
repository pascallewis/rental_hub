
<?php
require_once 'auth.php';
require_once 'db.php';
require_once 'functions.php';

$landlord_id = $_SESSION['landlord_id'];

$message = "";

/* Get tenants */
$stmt = $pdo->prepare(
    "SELECT t.id, t.full_name, h.room_number, ht.rent_amount
     FROM tenants t
     JOIN houses h ON t.house_id = h.id
     JOIN house_types ht ON h.house_type_id = ht.id
     WHERE t.landlord_id = ?
     ORDER BY t.full_name ASC"
);

$stmt->execute([$landlord_id]);
$tenants = $stmt->fetchAll();

if(isset($_POST['pay']))
{
    $tenant_id = (int)$_POST['tenant_id'];
    $amount = (float)$_POST['amount'];
    $method = clean($_POST['method']);
    $txn = clean($_POST['txn_code']);

    if($tenant_id <= 0 || $amount <= 0)
    {
        $message = "Invalid payment details.";
    }
    else
    {
        /* Insert payment */
        $stmt = $pdo->prepare(
            "INSERT INTO payments
            (tenant_id, amount, method, txn_code, created_at)
            VALUES (?,?,?,?,NOW())"
        );

        $stmt->execute([
            $tenant_id,
            $amount,
            $method,
            $txn
        ]);

        /* Optional: update house status logic can be added later */

        $message = "Payment recorded successfully.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payments</title>

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

input, select{
width:100%;
padding:12px;
margin-bottom:15px;
border:none;
border-radius:10px;
background:rgba(255,255,255,0.08);
color:white;
}

button{
padding:12px;
width:100%;
border:none;
border-radius:10px;
background:#38bdf8;
color:white;
font-weight:bold;
cursor:pointer;
}

button:hover{
background:#0ea5e9;
}

.alert{
background:#16a34a;
padding:10px;
margin-bottom:15px;
border-radius:10px;
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

<h2>Record Payment</h2>

<?php if($message): ?>
<div class="alert"><?php echo $message; ?></div>
<?php endif; ?>

<form method="POST">

<select name="tenant_id" required>
<option value="">Select Tenant</option>
<?php foreach($tenants as $t): ?>
<option value="<?php echo $t['id']; ?>">
<?php echo $t['full_name']; ?> (Room <?php echo $t['room_number']; ?>)
</option>
<?php endforeach; ?>
</select>

<input type="number" name="amount" placeholder="Amount Paid" required>

<select name="method" required>
<option value="cash">Cash</option>
<option value="mpesa">M-Pesa</option>
<option value="bank">Bank</option>
</select>

<input type="text" name="txn_code" placeholder="Transaction Code (optional)">

<button type="submit" name="pay">Record Payment</button>

</form>

</div>

<div class="card">

<h2>Recent Payments</h2>

<table>

<tr>
<th>Tenant</th>
<th>Amount</th>
<th>Method</th>
<th>Transaction</th>
<th>Date</th>
</tr>

<?php
$stmt = $pdo->prepare(
    "SELECT p.*, t.full_name
     FROM payments p
     JOIN tenants t ON p.tenant_id = t.id
     WHERE t.landlord_id = ?
     ORDER BY p.id DESC
     LIMIT 10"
);

$stmt->execute([$landlord_id]);

$payments = $stmt->fetchAll();
?>

<?php foreach($payments as $p): ?>
<tr>
<td><?php echo $p['full_name']; ?></td>
<td>KES <?php echo number_format($p['amount'],2); ?></td>
<td><?php echo $p['method']; ?></td>
<td><?php echo $p['txn_code']; ?></td>
<td><?php echo $p['created_at']; ?></td>
</tr>
<?php endforeach; ?>

</table>

</div>

</div>

</body>
</html>

