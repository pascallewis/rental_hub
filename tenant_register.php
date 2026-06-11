
<?php
require_once 'db.php';
require_once 'functions.php';

$message = '';
$landlord = null;
$availableRooms = [];

$step = 1; // 1 = enter code, 2 = register

if(isset($_POST['check_landlord']))
{
    $code = clean($_POST['landlord_code']);

    $stmt = $pdo->prepare(
        "SELECT * FROM landlords WHERE landlord_code=?"
    );

    $stmt->execute([$code]);
    $landlord = $stmt->fetch();

    if($landlord)
    {
        $step = 2;

        $stmt = $pdo->prepare(
            "SELECT * FROM houses
             WHERE landlord_id=?
             AND status='vacant'"
        );

        $stmt->execute([$landlord['id']]);
        $availableRooms = $stmt->fetchAll();
    }
    else
    {
        $message = "Invalid landlord code.";
    }
}

if(isset($_POST['register_tenant']))
{
    $landlord_id = (int)$_POST['landlord_id'];
    $house_id = (int)$_POST['house_id'];

    $full_name = clean($_POST['full_name']);
    $phone = clean($_POST['phone']);
    $email = clean($_POST['email']);
    $password = $_POST['password'];

    if(
        empty($full_name) ||
        empty($phone) ||
        empty($email) ||
        empty($password) ||
        $house_id <= 0
    ){
        $message = "All fields are required.";
        $step = 2;
    }
    else
    {
        $stmt = $pdo->prepare(
            "SELECT id FROM tenants WHERE email=?"
        );

        $stmt->execute([$email]);

        if($stmt->rowCount() > 0)
        {
            $message = "Email already exists.";
            $step = 2;
        }
        else
        {
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                "INSERT INTO tenants
                (landlord_id, house_id, full_name, phone, email, password)
                VALUES (?,?,?,?,?,?)"
            );

            $stmt->execute([
                $landlord_id,
                $house_id,
                $full_name,
                $phone,
                $email,
                $hashed
            ]);

            $stmt = $pdo->prepare(
                "UPDATE houses SET status='occupied' WHERE id=?"
            );

            $stmt->execute([$house_id]);

            $message = "Registration successful. You can now login.";
            $step = 1;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tenant Registration</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
body{
margin:0;
font-family:Poppins;
background:#0f172a;
color:white;
display:flex;
justify-content:center;
align-items:center;
min-height:100vh;
}

.card{
width:100%;
max-width:600px;
background:rgba(255,255,255,0.08);
backdrop-filter:blur(15px);
padding:30px;
border-radius:20px;
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
padding:10px;
background:red;
margin-bottom:15px;
border-radius:10px;
}

.success{
background:green;
padding:10px;
margin-bottom:15px;
border-radius:10px;
}
</style>
</head>

<body>

<div class="card">

<h2>Tenant Registration</h2>

<?php if($message): ?>
<div class="<?php echo (strpos($message,'success')!==false)?'success':'alert'; ?>">
<?php echo $message; ?>
</div>
<?php endif; ?>

<?php if($step == 1): ?>

<form method="POST">

<input type="text" name="landlord_code" placeholder="Enter Landlord Code" required>

<button name="check_landlord">Continue</button>

</form>

<?php else: ?>

<form method="POST">

<input type="hidden" name="landlord_id" value="<?php echo $landlord['id']; ?>">

<input type="text" name="full_name" placeholder="Full Name" required>
<input type="text" name="phone" placeholder="Phone" required>
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>

<select name="house_id" required>
<option value="">Select Available Room</option>

<?php foreach($availableRooms as $room): ?>
<option value="<?php echo $room['id']; ?>">
<?php echo $room['room_number']; ?>
</option>
<?php endforeach; ?>

</select>

<button name="register_tenant">Register</button>

</form>

<?php endif; ?>

</div>

</body>
</html>

