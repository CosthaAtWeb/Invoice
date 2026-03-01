<?php
include('header-login.php');
include('functions.php');
?>

<div class="login-wrapper">

    <div id="response" class="alert alert-success login-alert" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <div class="message"></div>
    </div>

    <div class="login-card">

        <!-- Logo / Header -->
        <div class="login-card-header">
            <h1 class="text-center">
                <img src="<?php echo COMPANY_LOGO ?>" class="img-responsive" style="max-height:60px; margin:0 auto;">
            </h1>
            <p class="login-subtitle">Sign in to your account</p>
        </div>

        <!-- Form Body -->
        <div class="login-card-body">
            <form accept-charset="UTF-8" role="form" method="post" id="login_form">
                <input type="hidden" name="action" value="login">
                <fieldset>

                    <div class="input-group form-group">
                        <div class="input-group-addon"><i class="glyphicon glyphicon-user"></i></div>
                        <input class="form-control required" name="username" id="username" type="text" placeholder="Enter Username">
                    </div>

                    <div class="input-group form-group">
                        <div class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></div>
                        <input class="form-control required" name="password" type="password" placeholder="Enter Password">
                    </div>

                    <div class="checkbox login-remember">
                        <label>
                            <input name="remember" type="checkbox" value="Remember Me"> Remember Me
                        </label>
                    </div>

                    <button type="button" id="btn-login" class="btn btn-primary btn-block login-btn">
                        <i class="glyphicon glyphicon-log-in"></i> &nbsp;Login
                    </button>

                </fieldset>
            </form>
        </div>

    </div>
</div>

<style>
/* ── Full-page centered layout ───────────────────────────────────────── */
html, body {
    height: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
}

body {
    background: linear-gradient(135deg, #eef1ff 0%, #f3eeff 100%) !important;
    font-family: 'DM Sans', 'Source Sans Pro', sans-serif !important;
}

/* Flex container that truly centres the card */
.login-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 30px 16px;
}

.login-alert {
    width: 100%;
    max-width: 420px;
    margin-bottom: 16px;
    border-radius: 10px;
}

/* ── Card ─────────────────────────────────────────────────────────────── */
.login-card {
    width: 100%;
    max-width: 420px;
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(79, 110, 247, 0.14), 0 4px 16px rgba(0,0,0,0.06);
    overflow: hidden;
    animation: cardIn 0.45s cubic-bezier(0.22,1,0.36,1) both;
}

@keyframes cardIn {
    from { opacity: 0; transform: translateY(24px) scale(0.97); }
    to   { opacity: 1; transform: translateY(0)    scale(1);    }
}

/* ── Card Header ─────────────────────────────────────────────────────── */
.login-card-header {
    background: linear-gradient(135deg, #4f6ef7 0%, #7c3aed 100%);
    padding: 32px 32px 24px;
    text-align: center;
}

.login-card-header img {
    max-height: 60px;
    margin: 0 auto 10px;
    /* If logo has no image yet, show placeholder text */
    display: block;
}

.login-subtitle {
    color: rgba(255,255,255,0.8);
    font-size: 13px;
    margin: 8px 0 0;
    letter-spacing: 0.3px;
}

/* ── Card Body ────────────────────────────────────────────────────────── */
.login-card-body {
    padding: 32px 32px 28px;
}

/* Input groups */
.login-card-body .form-group {
    margin-bottom: 16px;
}

.login-card-body .input-group-addon {
    background-color: #f4f6fb !important;
    border: 1px solid #e8ecf4 !important;
    border-right: none !important;
    color: #9ca3af !important;
    border-radius: 8px 0 0 8px !important;
    padding: 0 12px;
    font-size: 14px;
}

.login-card-body .form-control {
    border: 1px solid #e8ecf4 !important;
    border-left: none !important;
    border-radius: 0 8px 8px 0 !important;
    height: 44px !important;
    font-size: 13.5px !important;
    color: #1a1d2e !important;
    background: #fff !important;
    box-shadow: none !important;
    padding: 8px 14px !important;
    transition: border-color 0.2s, box-shadow 0.2s !important;
}

.login-card-body .form-control:focus {
    border-color: #4f6ef7 !important;
    box-shadow: 0 0 0 3px rgba(79,110,247,0.12) !important;
    outline: none !important;
}

/* Whole input-group border highlight on focus */
.login-card-body .input-group:focus-within .input-group-addon {
    border-color: #4f6ef7 !important;
}

/* Remember me */
.login-remember {
    margin: 4px 0 20px;
}
.login-remember label {
    font-size: 13px !important;
    color: #6b7280 !important;
    font-weight: 400 !important;
    cursor: pointer;
}

/* Login button */
.login-btn {
    background: linear-gradient(135deg, #4f6ef7, #7c3aed) !important;
    border: none !important;
    border-radius: 10px !important;
    height: 46px !important;
    font-size: 14px !important;
    font-weight: 700 !important;
    letter-spacing: 0.3px;
    color: #fff !important;
    box-shadow: 0 4px 16px rgba(79,110,247,0.35) !important;
    transition: all 0.2s !important;
}
.login-btn:hover {
    box-shadow: 0 8px 24px rgba(79,110,247,0.45) !important;
    transform: translateY(-1px) !important;
    opacity: 0.95;
}
.login-btn:active {
    transform: translateY(0) !important;
}
</style>

<?php include('footer.php'); ?>