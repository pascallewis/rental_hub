
<?php
require_once 'db.php';
require_once 'functions.php';

$message = '';
$messageType = '';

if(isset($_POST['register']))
{
    $full_name = clean($_POST['full_name']);
    $phone = clean($_POST['phone']);
    $email = clean($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if(
        empty($full_name) ||
        empty($phone) ||
        empty($email) ||
        empty($password) ||
        empty($confirm_password)
    ){
        $message = "All fields are required.";
        $messageType = "error";
    }
    elseif($password !== $confirm_password){
        $message = "Passwords do not match.";
        $messageType = "error";
    }
    else{

        $check = $pdo->prepare(
            "SELECT id FROM landlords WHERE email=?"
        );

        $check->execute([$email]);

        if($check->rowCount() > 0){

            $message = "Email already exists.";
            $messageType = "error";

        }else{

            $landlord_code = generateLandlordCode();

            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $insert = $pdo->prepare(
                "INSERT INTO landlords
                (
                    landlord_code,
                    full_name,
                    phone,
                    email,
                    password
                )
                VALUES
                (?,?,?,?,?)"
            );

            $insert->execute([
                $landlord_code,
                $full_name,
                $phone,
                $email,
                $hashed_password
            ]);

            $message =
            "Registration successful. Your landlord code is: "
            . $landlord_code;

            $messageType = "success";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Landlord Registration</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
min-height:100vh;
display:flex;
justify-content:center;
align-items:center;
background:
linear-gradient(
135deg,
#0f172a,
#1e293b,
#2563eb
);
padding:20px;
}

.card{
width:100%;
max-width:550px;
background:rgba(255,255,255,0.08);
backdrop-filter:blur(20px);
border:1px solid rgba(255,255,255,0.15);
border-radius:25px;
padding:35px;
color:white;
}

.logo{
text-align:center;
font-size:32px;
font-weight:700;
margin-bottom:10px;
}

.logo span{
color:#38bdf8;
}

.subtitle{
text-align:center;
margin-bottom:25px;
color:#cbd5e1;
}

.form-group{
margin-bottom:18px;
}

label{
display:block;
margin-bottom:6px;
font-size:14px;
}

input{
width:100%;
padding:14px;
border:none;
outline:none;
border-radius:12px;
background:rgba(255,255,255,0.08);
color:white;
}

input::placeholder{
color:#cbd5e1;
}

button{
width:100%;
padding:14px;
border:none;
border-radius:12px;
background:#38bdf8;
color:white;
font-size:16px;
font-weight:600;
cursor:pointer;
transition:.3s;
}

button:hover{
background:#0ea5e9;
}

.success{
background:#16a34a;
padding:12px;
border-radius:10px;
margin-bottom:15px;
}

.error{
background:#dc2626;
padding:12px;
border-radius:10px;
margin-bottom:15px;
}

.links{
text-align:center;
margin-top:20px;
}

.links a{
color:#38bdf8;
text-decoration:none;
}

</style>

</head>
<body>

<div class="card">

<div class="logo">
Rental<span>Hub</span>
</div>

<p class="subtitle">
Create your landlord account
</p>

<?php if($message != ''): ?>

<div class="<?php echo $messageType; ?>">
<?php echo $message; ?>
</div>

<?php endif; ?>

<form method="POST">

<div class="form-group">
<label>Full Name</label>
<input
type="text"
name="full_name"
required>
</div>

<div class="form-group">
<label>Phone Number</label>
<input
type="text"
name="phone"
required>
</div>

<div class="form-group">
<label>Email Address</label>
<input
type="email"
name="email"
required>
</div>

<div class="form-group">
<label>Password</label>
<input
type="password"
name="password"
required>
</div>

<div class="form-group">
<label>Confirm Password</label>
<input
type="password"
name="confirm_password"
required>
</div>

<button type="submit" name="register">
Create Account
</button>

</form>

<div class="links">

Already have an account?

<a href="landlord_login.php">
Login Here
</a>

</div>

</div>

</body>
</html>

