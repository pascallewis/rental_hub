
<?php
require_once 'auth.php';
require_once 'db.php';
require_once 'functions.php';

$landlord_id = $_SESSION['landlord_id'];
$message = '';

function generatePrefix($type)
{
    $words = explode(' ', strtoupper($type));

    if(count($words) == 1){
        return substr($words[0], 0, 2);
    }

    $prefix = '';
    foreach($words as $word){
        $prefix .= substr($word, 0, 1);
    }

    return substr($prefix, 0, 3);
}

if(isset($_POST['add_units']))
{
    $house_type_id = (int)$_POST['house_type_id'];
    $units = (int)$_POST['units'];

    if($house_type_id <= 0 || $units <= 0)
    {
        $message = "Please select a house type and enter valid units.";
    }
    else
    {
        $stmt = $pdo->prepare(
            "SELECT *
             FROM house_types
             WHERE id=?
             AND landlord_id=?"
        );

        $stmt->execute([
            $house_type_id,
            $landlord_id
        ]);

        $houseType = $stmt->fetch();

        if($houseType)
        {
            $prefix = generatePrefix(
                $houseType['type_name']
            );

            $stmt = $pdo->prepare(
                "SELECT COUNT(*) total
                 FROM houses
                 WHERE landlord_id=?
                 AND house_type_id=?"
            );

            $stmt->execute([
                $landlord_id,
                $house_type_id
            ]);

            $existing = $stmt->fetch()['total'];

            for($i = 1; $i <= $units; $i++)
            {
                $number = $existing + $i;

                $room_number =
                    $prefix .
                    str_pad(
                        $number,
                        3,
                        '0',
                        STR_PAD_LEFT
                    );

                $insert = $pdo->prepare(
                    "INSERT INTO houses
                    (
                        landlord_id,
                        house_type_id,
                        room_number,
                        status
                    )
                    VALUES
                    (?,?,?,?)"
                );

                $insert->execute([
                    $landlord_id,
                    $house_type_id,
                    $room_number,
                    'vacant'
                ]);
            }

            $message =
            $units .
            " unit(s) created successfully.";
        }
    }
}

$types = $pdo->prepare(
    "SELECT *
     FROM house_types
     WHERE landlord_id=?
     ORDER BY type_name ASC"
);

$types->execute([$landlord_id]);
$houseTypes = $types->fetchAll();

$houses = $pdo->prepare(
    "SELECT
        h.*,
        ht.type_name,
        ht.rent_amount
     FROM houses h
     JOIN house_types ht
     ON h.house_type_id = ht.id
     WHERE h.landlord_id=?
     ORDER BY h.id DESC"
);

$houses->execute([$landlord_id]);
$allHouses = $houses->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Manage Houses</title>

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
backdrop-filter:blur(15px);
border:1px solid rgba(255,255,255,.08);
border-radius:18px;
padding:25px;
margin-bottom:25px;
}

h2{
margin-bottom:20px;
}

.form-group{
margin-bottom:15px;
}

label{
display:block;
margin-bottom:8px;
}

select,
input{
width:100%;
padding:12px;
border:none;
outline:none;
border-radius:10px;
background:rgba(255,255,255,.08);
color:white;
}

option{
color:black;
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

th{
background:#1e293b;
padding:15px;
text-align:left;
}

td{
padding:15px;
border-bottom:1px solid rgba(255,255,255,.08);
}

.badge-vacant{
background:#22c55e;
padding:6px 12px;
border-radius:20px;
font-size:12px;
}

.badge-occupied{
background:#ef4444;
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

<h2>Add Houses</h2>

<?php if(!empty($message)): ?>
<div class="success">
<?php echo $message; ?>
</div>
<?php endif; ?>

<form method="POST">

<div class="form-group">

<label>House Type</label>

<select
name="house_type_id"
required>

<option value="">
Select Type
</option>

<?php foreach($houseTypes as $type): ?>

<option
value="<?php echo $type['id']; ?>">

<?php echo htmlspecialchars(
$type['type_name']
); ?>

(KES <?php echo number_format(
$type['rent_amount']
); ?>)

</option>

<?php endforeach; ?>

</select>

</div>

<div class="form-group">

<label>Number of Units</label>

<input
type="number"
name="units"
min="1"
required>

</div>

<button
type="submit"
name="add_units">

Generate Units

</button>

</form>

</div>

<div class="card">

<h2>All Houses</h2>

<table>

<tr>
<th>Room</th>
<th>Type</th>
<th>Rent</th>
<th>Status</th>
</tr>

<?php foreach($allHouses as $house): ?>

<tr>

<td>
<?php echo $house['room_number']; ?>
</td>

<td>
<?php echo htmlspecialchars(
$house['type_name']
); ?>
</td>

<td>
KES
<?php echo number_format(
$house['rent_amount'],
2
); ?>
</td>

<td>

<?php if(
$house['status']
== 'vacant'
): ?>

<span class="badge-vacant">
Vacant
</span>

<?php else: ?>

<span class="badge-occupied">
Occupied
</span>

<?php endif; ?>

</td>

</tr>

<?php endforeach; ?>

<?php if(count($allHouses)==0): ?>

<tr>
<td colspan="4">
No houses found.
</td>
</tr>

<?php endif; ?>

</table>

</div>

</div>

</body>
</html>

