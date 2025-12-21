<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <style>
        body {
            margin: 0;
            font-family: "Poppins", sans-serif;
            display: flex;
            height: 100vh;
            background: #f3f4f6;
        }

        /* LEFT SIDE */
        .left-side {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: white;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .logo {
            font-size: 46px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .subtitle {
            margin-top: 12px;
            font-size: 18px;
            opacity: 0.85;
        }

        /* RIGHT SIDE */
        .right-side {
            position: relative;
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('/images/book.jpg') center/cover no-repeat;
        }

        .right-side::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.5);
            z-index: 0;
        }

        .right-side > * {
            position: relative;
            z-index: 1;
        }

        /* FORM BOX */
        .form-box {
            width: 80%;
            max-width: 420px;
            background: white;
            padding: 40px;
            border-radius: 14px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
            animation: fadeIn 0.4s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            font-size: 32px;
            margin-bottom: 25px;
            font-weight: 600;
            color: #1e293b;
            text-align: center;
        }

        /* FORM GROUP */
        .form-group {
            margin-bottom: 18px;
            display: flex;
            flex-direction: column; /* label above input */
        }

        .form-group label {
            font-weight: 700;
            color: #000000;
            margin-bottom: 6px;
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 12px 14px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            font-size: 16px;
            box-sizing: border-box;
            transition: 0.2s;
        }

        input:focus, select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
            outline: none;
        }

        button {
            width: 100%;
            padding: 14px;
            background-color: #2563eb;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: 0.25s;
            font-weight: 600;
        }

        button:hover {
            background-color: #1d4ed8;
            box-shadow: 0 6px 15px rgba(37,99,235,0.35);
        }

        .login-link {
            text-align: center;
            margin-top: 18px;
            color: #475569;
        }

        a {
            text-decoration: none;
            color: #2563eb;
            font-weight: 600;
        }

        /* ERROR MESSAGES */
        .error {
            color: red;
            font-size: 0.85rem;
            font-style: italic;
            margin-top: 4px;
        }

        /* PASSWORD WRAPPER */
        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            width: 100%;
            padding-right: 40px; /* space for eye */
            height: 40px;        /* fixed height */
            font-size: 16px;
            line-height: 1.4;
            box-sizing: border-box;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            height: 100%; /* same height as input */
        }

        .toggle-password svg {
            display: block;
            width: 22px;
            height: 22px;
        }

        .input-with-icon {
            display: flex;
            align-items: center; /* vertically center icon and text */
            position: relative;
        }

        .input-with-icon input {
            flex: 1;
            padding: 12px 14px;
            padding-right: 40px; /* leave space for eye */
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            box-sizing: border-box;
        }
        /* PASSWORD TOOLTIP ! */
        .tooltip {
            display: inline-block;
            margin-left: 6px;
            width: 16px;
            height: 16px;
            line-height: 16px;
            text-align: center;
            border-radius: 50%;
            background-color: #555;
            color: #fff;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            position: relative;
        }

        .tooltip-text {
            visibility: hidden;
            opacity: 0;
            width: 260px;
            background-color: #333;
            color: #fff;
            text-align: left;
            padding: 8px;
            border-radius: 6px;
            position: absolute;
            top: 22px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
            font-size: 0.75rem;
            transition: opacity 0.3s ease;
        }

        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        /* FLOATING PASSWORD DIALOG */
        #password-dialog {
            position: absolute;
            top: 50%;
            right: 105%;
            width: 260px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 0.8rem;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-50%);
            transition: opacity 0.3s ease, transform 0.3s ease;
            z-index: 20;
        }

        #password-dialog.show {
            opacity: 1;
            visibility: visible;
        }

        #password-dialog ul {
            list-style: none;
            padding-left: 0;
            margin-top: 6px;
        }

        #password-dialog li {
            margin-bottom: 4px;
            color: red;
        }

        #password-dialog::before {
            content: "";
            position: absolute;
            right: -8px;
            top: 50%;
            transform: translateY(-50%);
            border-width: 8px;
            border-style: solid;
            border-color: transparent #ddd transparent transparent;
        }

        /* CONFIRM PASSWORD MESSAGE */
        #confirm-message {
            font-size: 0.85rem;
            font-style: italic;
            margin-top: 4px;
            display: block;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            body {
                flex-direction: column;
            }

            .left-side {
                height: 40vh;
            }

            .form-box {
                width: 90%;
            }
        }
    </style>
</head>
<body>

    <!-- LEFT SIDE -->
    <div class="left-side">
        <div class="logo">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" 
                 viewBox="0 0 24 24" stroke="white" stroke-width="2" width="45" height="45">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14v7"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 17l-6 4-6-4"/>
            </svg>
            LearnHub
        </div>

        <div class="subtitle">
            Smart Learning for a Smarter Future
        </div>
    </div>

    <!-- RIGHT SIDE -->
    <div class="right-side">
        @yield('content')
    </div>

</body>
</html>
