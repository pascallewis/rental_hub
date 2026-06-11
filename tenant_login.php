
<?php
require_once 'db.php';
require_once 'functions.php';

$message = '';

if(isset($_SESSION['tenant_id']))
{
    header("Location: tenant_dashboard.php");
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
            "SELECT * FROM tenants WHERE email=? LIMIT 1"
        );

        $stmt->execute([$email]);
        $tenant = $stmt->fetch();

        if($tenant)
        {
            if(password_verify($password, $tenant['password']))
            {
                $_SESSION['tenant_id'] = $tenant['id'];
                $_SESSION['tenant_name'] = $tenant['full_name'];
                $_SESSION['landlord_id'] = $tenant['landlord_id'];
                $_SESSION['house_id'] = $tenant['house_id'];

                header("Location: tenant_dashboard.php");
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
<html>
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tenant Login</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

<style>

body{
margin:0;
font-family:Poppins;
background:#0f172a;
color:white;
display:flex;
justify-content:center;
align-items:center;
height:100vh;
}

.card{
width:100%;
max-width:450px;
background:rgba(255,255,255,0.08);
backdrop-filter:blur(15px);
padding:30px;
border-radius:20px;
}

input{
width:100%;
padding:12px;
margin-bottom:15px;
border:none;
border-radius:10px;
background:rgba(255,255,255,0.08);
color:white;
}

button{
width:100%;
padding:12px;
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
background:red;
padding:10px;
border-radius:10px;
margin-bottom:15px;
}

a{
color:#38bdf8;
text-decoration:none;
}

</style>

</head>

<body>

<div class="card">

<h2>Tenant Login</h2>

<?php if($message): ?>
<div class="alert"><?php echo $message; ?></div>
<?php endif; ?>

<form method="POST">

<input type="email" name="email" placeholder="Email" required>

<input type="password" name="password" placeholder="Password" required>

<button name="login">Login</button>

</form>

<p>
Don't have an account?
<a href="tenant_register.php">Register</a>
</p>

</div>

</body>
</html>

