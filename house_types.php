
<?php
require_once 'auth.php';
require_once 'db.php';
require_once 'functions.php';

$landlord_id = $_SESSION['landlord_id'];

$message = '';
$type_name = '';
$rent_amount = '';

if(isset($_POST['add_type']))
{
    $type_name = clean($_POST['type_name']);
    $rent_amount = clean($_POST['rent_amount']);

    if(empty($type_name) || empty($rent_amount))
    {
        $message = "All fields are required.";
    }
    else
    {
        $stmt = $pdo->prepare(
            "INSERT INTO house_types
            (
                landlord_id,
                type_name,
                rent_amount
            )
            VALUES
            (?,?,?)"
        );

        $stmt->execute([
            $landlord_id,
            $type_name,
            $rent_amount
        ]);

        $message = "House type added successfully.";

        $type_name = '';
        $rent_amount = '';
    }
}

$stmt = $pdo->prepare(
    "SELECT *
     FROM house_types
     WHERE landlord_id=?
     ORDER BY id DESC"
);

$stmt->execute([$landlord_id]);

$houseTypes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>House Types</title>

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
padding:14px;
margin-bottom:10px;
text-decoration:none;
color:white;
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

.card{
background:rgba(255,255,255,.08);
backdrop-filter:blur(12px);
border:1px solid rgba(255,255,255,.08);
border-radius:18px;
padding:25px;
margin-bottom:25px;
}

.card h2{
margin-bottom:20px;
}

.form-group{
margin-bottom:15px;
}

label{
display:block;
margin-bottom:8px;
}

input{
width:100%;
padding:12px;
border:none;
outline:none;
border-radius:10px;
background:rgba(255,255,255,.08);
color:white;
}

button{
padding:12px 25px;
border:none;
border-radius:10px;
background:#38bdf8;
color:white;
font-weight:600;
cursor:pointer;
}

button:hover{
background:#0ea5e9;
}

.success{
background:#16a34a;
padding:12px;
border-radius:10px;
margin-bottom:20px;
}

table{
width:100%;
border-collapse:collapse;
}

table th{
background:#1e293b;
padding:15px;
text-align:left;
}

table td{
padding:15px;
border-bottom:1px solid rgba(255,255,255,.08);
}

.badge{
background:#22c55e;
padding:6px 12px;
border-radius:20px;
font-size:12px;
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

<div class="card">

<h2>Add House Type</h2>

<?php if(!empty($message)): ?>

<div class="success">
<?php echo $message; ?>
</div>

<?php endif; ?>

<form method="POST">

<div class="form-group">

<label>House Type</label>

<input
type="text"
name="type_name"
placeholder="Bedsitter, Single Room, 1 Bedroom..."
value="<?php echo $type_name; ?>"
required>

</div>

<div class="form-group">

<label>Rent Amount (KES)</label>

<input
type="number"
step="0.01"
name="rent_amount"
placeholder="5000"
value="<?php echo $rent_amount; ?>"
required>

</div>

<button
type="submit"
name="add_type">

Add House Type

</button>

</form>

</div>

<div class="card">

<h2>Your House Types</h2>

<table>

<tr>
<th>ID</th>
<th>Type</th>
<th>Rent</th>
<th>Status</th>
</tr>

<?php foreach($houseTypes as $type): ?>

<tr>

<td>
<?php echo $type['id']; ?>
</td>

<td>
<?php echo htmlspecialchars($type['type_name']); ?>
</td>

<td>
KES
<?php echo number_format(
$type['rent_amount'],
2
); ?>
</td>

<td>
<span class="badge">
Active
</span>
</td>

</tr>

<?php endforeach; ?>

<?php if(count($houseTypes) == 0): ?>

<tr>
<td colspan="4">
No house types added yet.
</td>
</tr>

<?php endif; ?>

</table>

</div>

</div>

</body>
</html>
