<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Labuan Cermin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e5799, #7db9e8);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Poppins', sans-serif;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
        .login-header {
            background: linear-gradient(45deg, #1e5799, #2989d8);
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }
        .login-form {
            padding: 30px;
        }
        .form-control {
            border-radius: 50px;
            padding: 12px 20px;
            border: 1px solid #ced4da;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(41, 137, 216, 0.25);
            border-color: #2989d8;
        }
        .btn-login {
            border-radius: 50px;
            padding: 12px 20px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            background: linear-gradient(45deg, #1e5799, #2989d8);
            border: none;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background: linear-gradient(45deg, #2989d8, #1e5799);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(41, 137, 216, 0.4);
        }
        .input-group-text {
            border-radius: 50px 0 0 50px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-right: none;
        }
        .social-login {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .social-icon {
            font-size: 24px;
            margin: 0 10px;
            color: #1e5799;
            transition: all 0.3s ease;
        }
        .social-icon:hover {
            transform: scale(1.2);
            color: #2989d8;
        }
        .waves {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background: url('https://raw.githubusercontent.com/youruser/yourrepo/main/waves.svg') repeat-x;
            background-size: 1000px 100px;
            animation: wave 10s linear infinite;
            z-index: -1;
        }
        .google-btn {
            width: 100%;
            height: 50px;
            background-color: #ffffff;
            border-radius: 25px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border: 1px solid #dddddd;
            overflow: hidden;
        }

        .google-btn:hover {
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .google-icon-wrapper {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .google-icon {
            font-size: 22px;
            color: #4285F4;
        }

        .btn-text {
            color: #757575;
            font-size: 16px;
            font-weight: 500;
            letter-spacing: 0.5px;
            font-family: 'Roboto', sans-serif;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #dddddd;
        }

        .divider span {
            padding: 0 10px;
            color: #757575;
            font-size: 14px;
        }
        @keyframes wave {
            0% {
                background-position-x: 0;
            }
            100% {
                background-position-x: 1000px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-container">
                    <div class="login-header">
                        Selamat Datang di Labuan Cermin
                    </div>
                    <div class="login-form">
                        <form action="login_proces.php" method="post">
                            <div class="mb-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                </div>
                            </div>
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-login">Masuk</button>
                            </div>
                        </form>
                        <div class="divider">
                            <span>atau</span>
                        </div>
                        
                        <div class="google-btn" id="googleSignIn">
                            <div class="google-icon-wrapper">
                            <i class="fab fa-google google-icon"></i>
                            </div>
                            <span class="btn-text">Masuk dengan Google</span>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="#" class="text-muted">Lupa password?</a>
                        </div>
                        <div class="social-login">
                            <a href="#" class="social-icon"><i class="fab fa-facebook"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-google"></i></a>
                            <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="waves"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>