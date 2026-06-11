
<?php
require_once 'db.php';
require_once 'functions.php';

$message = '';

if(isset($_SESSION['landlord_id']))
{
    header("Location: landlord_dashboard.php");
    exit();
}

if(isset($_POST['login']))
{
    $email = clean($_POST['email']);
    $password = $_POST['password'];

    if(empty($email) || empty($password))
    {
        $message = "Please fill all fields.";
    }
    else
    {
        $stmt = $pdo->prepare(
            "SELECT * FROM landlords WHERE email = ? LIMIT 1"
        );

        $stmt->execute([$email]);

        $landlord = $stmt->fetch();

        if($landlord)
        {
            if($landlord['status'] == 'suspended')
            {
                $message = "Your account has been suspended.";
            }
            elseif(password_verify(
                $password,
                $landlord['password']
            ))
            {
                $_SESSION['landlord_id']
                = $landlord['id'];

                $_SESSION['landlord_name']
                = $landlord['full_name'];

                $_SESSION['landlord_code']
                = $landlord['landlord_code'];

                header("Location: landlord_dashboard.php");
                exit();
            }
            else
            {
                $message = "Invalid email or password.";
            }
        }
        else
        {
            $message = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Landlord Login</title>

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
padding:20px;

background:
linear-gradient(
135deg,
#0f172a,
#1e293b,
#2563eb
);
}

.card{
width:100%;
max-width:500px;

background:
rgba(255,255,255,0.08);

backdrop-filter:blur(20px);

border:1px solid
rgba(255,255,255,0.1);

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

.alert{
background:#dc2626;
padding:12px;
border-radius:10px;
margin-bottom:20px;
font-size:14px;
}

.form-group{
margin-bottom:18px;
}

label{
display:block;
margin-bottom:8px;
font-size:14px;
}

input{
width:100%;
padding:14px;
border:none;
outline:none;

background:
rgba(255,255,255,0.08);

border-radius:12px;

color:white;
}

input::placeholder{
color:#cbd5e1;
}

.btn{
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

.btn:hover{
background:#0ea5e9;
}

.links{
margin-top:20px;
text-align:center;
}

.links a{
color:#38bdf8;
text-decoration:none;
}

.links a:hover{
text-decoration:underline;
}

</style>

</head>

<body>

<div class="card">

<div class="logo">
Rental<span>Hub</span>
</div>

<p class="subtitle">
Landlord Account Login
</p>

<?php if(!empty($message)): ?>

<div class="alert">
<?php echo $message; ?>
</div>

<?php endif; ?>

<form method="POST">

<div class="form-group">

<label>Email Address</label>

<input
type="email"
name="email"
placeholder="Enter email"
required>

</div>

<div class="form-group">

<label>Password</label>

<input
type="password"
name="password"
placeholder="Enter password"
required>

</div>

<button
type="submit"
name="login"
class="btn">

Login

</button>

</form>

<div class="links">

Don't have an account?

<a href="landlord_register.php">
Register Here
</a>

</div>

</div>

</body>

</html>

