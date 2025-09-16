#extends('layouts.main')

#section('content')
<div style="
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0;
">
    <div style="
        background-color: #fff;
        padding: 40px 60px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        text-align: center;
        max-width: 450px;
    ">
        <h1 style="margin:0 0 15px; font-size: 2rem; color: #333;">
            From About 👋
        </h1>
        <p style="margin:0 0 25px; color: #555;">
            Hello, You're ready to start building amazing PHP applications.
        </p>

        <div>
            <a href="/docs" style="
                display:inline-block;
                padding:10px 20px;
                margin:0 5px;
                background-color:#3b82f6;
                color:#fff;
                text-decoration:none;
                border-radius:6px;
                font-weight:bold;
            ">Documentation</a>

            <a href="/blog" style="
                display:inline-block;
                padding:10px 20px;
                margin:0 5px;
                background-color:#e5e7eb;
                color:#333;
                text-decoration:none;
                border-radius:6px;
                font-weight:bold;
            ">Blog</a>
        </div>

        <p style="margin-top:20px; font-size:0.85rem; color:#999;">
            Crafted with ❤️ by PHP Fame Team
        </p>
    </div>
</div>
#endsection
