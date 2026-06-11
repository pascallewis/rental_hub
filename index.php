
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>RentalHub - Smart Rental Management System</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{
    background: radial-gradient(circle at top, #0f172a, #020617);
    color:white;
    min-height:100vh;
    overflow-x:hidden;
}

/* NAV */
nav{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:25px 60px;
}

.logo{
    font-size:28px;
    font-weight:700;
}

.logo span{
    color:#38bdf8;
}

.nav-actions a{
    margin-left:15px;
    padding:10px 18px;
    border-radius:10px;
    text-decoration:none;
    font-size:14px;
}

.btn-outline{
    border:1px solid rgba(255,255,255,0.2);
    color:white;
}

.btn-primary{
    background:#38bdf8;
    color:white;
}

.btn-primary:hover{
    background:#0ea5e9;
}

/* HERO */
.hero{
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:85vh;
    padding:40px;
}

.hero-container{
    text-align:center;
    max-width:900px;
}

.hero h1{
    font-size:58px;
    line-height:1.1;
    margin-bottom:20px;
}

.hero p{
    color:#cbd5e1;
    font-size:18px;
    margin-bottom:40px;
}

/* GET STARTED SELECTION */
.selection{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:25px;
    margin-top:30px;
}

.card{
    background:rgba(255,255,255,0.06);
    border:1px solid rgba(255,255,255,0.1);
    backdrop-filter:blur(20px);
    border-radius:20px;
    padding:40px;
    transition:.3s;
}

.card:hover{
    transform:translateY(-5px);
    border-color:#38bdf8;
}

.icon{
    font-size:40px;
    margin-bottom:15px;
}

.card h2{
    margin-bottom:10px;
}

.card p{
    color:#cbd5e1;
    font-size:14px;
    margin-bottom:20px;
}

.card a{
    display:inline-block;
    padding:12px 20px;
    border-radius:10px;
    text-decoration:none;
    font-weight:500;
}

.landlord-btn{
    background:#38bdf8;
    color:white;
}

.tenant-btn{
    background:#22c55e;
    color:white;
}

/* FEATURES */
.features{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    padding:80px 60px;
}

.feature{
    background:rgba(255,255,255,0.04);
    border-radius:15px;
    padding:25px;
}

.feature h3{
    margin-bottom:10px;
}

.feature p{
    color:#cbd5e1;
    font-size:14px;
}

/* FOOTER */
footer{
    text-align:center;
    padding:30px;
    color:#64748b;
    border-top:1px solid rgba(255,255,255,0.1);
}

/* MOBILE */
@media(max-width:900px){
    .selection{
        grid-template-columns:1fr;
    }

    .hero h1{
        font-size:40px;
    }

    nav{
        padding:20px;
    }
}

</style>
</head>

<body>

<nav>
    <div class="logo">Rental<span>Hub</span></div>

    <div class="nav-actions">
        <a href="landlord_login.php" class="btn-outline">Landlord Login</a>
        <a href="tenant_login.php" class="btn-outline">Tenant Login</a>
        <a href="#get-started" class="btn-primary">Get Started</a>
    </div>
</nav>

<section class="hero">

<div class="hero-container">

    <h1>Manage Rent, Tenants & Vacancies Easily</h1>

    <p>
        A modern rental system for landlords and tenants.
        Track payments, manage houses, and automate rent collection.
    </p>

    <div id="get-started" class="selection">

        <!-- LANDLORD -->
        <div class="card">
            <div class="icon">🏠</div>
            <h2>I am a Landlord</h2>
            <p>Manage houses, tenants, payments and monitor rent collection.</p>
            <a href="landlord_register.php" class="landlord-btn">
                Continue as Landlord →
            </a>
        </div>

        <!-- TENANT -->
        <div class="card">
            <div class="icon">👤</div>
            <h2>I am a Tenant</h2>
            <p>View rent, make payments, and track your rental history.</p>
            <a href="tenant_register.php" class="tenant-btn">
                Continue as Tenant →
            </a>
        </div>

    </div>

</div>

</section>

<section class="features">

<div class="feature">
<h3>💰 Rent Tracking</h3>
<p>Monitor all payments in real time.</p>
</div>

<div class="feature">
<h3>🏘 Property Control</h3>
<p>Add and manage multiple houses easily.</p>
</div>

<div class="feature">
<h3>🔔 Notifications</h3>
<p>Get alerts for payments and tenants.</p>
</div>

<div class="feature">
<h3>📊 Reports</h3>
<p>Understand income and occupancy instantly.</p>
</div>

</section>

<footer>
© <?php echo date('Y'); ?> RentalHub. All Rights Reserved.
</footer>

</body>
</html>

