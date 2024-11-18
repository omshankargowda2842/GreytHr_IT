<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class PostLoginMessage
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session('post_login')) {
            session()->forget('post_login');
            return $this->generatePostLoginResponse();
        }

        return $next($request);
    }

    protected function generatePostLoginResponse(): Response
    {
        try {
            $user = Auth::user();

            // Validate and format user names
            $firstName = $user->employee_name ?? 'User';

            if (empty($firstName)) {
                throw new \Exception('User name is missing');
            }

            $userName = ucwords(trim($firstName . ' '));
            $homeUrl = route('dashboard');

            $html = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Loading...</title>
                <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
                <style>
                    body, html {
                        height: 100%;
                        margin: 0;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        background-color: #f8f9fa;
                        font-family: \'Montserrat\', sans-serif;
                    }
                    #message {
                        font-size: 1.25em;
                        color: #3b4452;
                        font-weight: 500;
                        text-align: center;
                    }
                    .name {
                        font-weight: bold;
                        color: #02114f;
                    }
                    .dots {
                        display: inline-block;
                        vertical-align: middle;
                    }
                    .dots span {
                        display: inline-block;
                        width: 8px;
                        height: 8px;
                        margin-left: 2px;
                        background-color: #333;
                        border-radius: 50%;
                        opacity: 0;
                        animation: blink 1.4s infinite both;
                    }
                    .dots span:nth-child(1) {
                        animation-delay: 0.2s;
                    }
                    .dots span:nth-child(2) {
                        animation-delay: 0.4s;
                    }
                    .dots span:nth-child(3) {
                        animation-delay: 0.6s;
                    }
                    @keyframes blink {
                        0%, 80%, 100% { opacity: 0; }
                        40% { opacity: 1; }
                    }
                </style>
                <script>
                    setTimeout(function() {
                        window.location.href = "' . $homeUrl . '";
                    }, 3000);
                </script>
            </head>
            <body>
                <div id="message">
                    Hi <span class="name">' . $userName . '</span>, just a moment, we are getting things ready for you
                    <span class="dots">
                        <span>.</span><span>.</span><span>.</span>
                    </span>
                </div>
            </body>
            </html>';

            return new Response($html, 200);
        } catch (\Exception $e) {
            // Handle the exception (e.g., log the error and show a default message)
            $homeUrl = route('dashboard');
            $html = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error</title>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
        <style>
            body, html {
                height: 100%;
                margin: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                background-color: #f8f9fa;
                font-family: \'Montserrat\', sans-serif;
            }
            #message {
                font-size: 24px;
                color: #333;
                text-align: center;
            }
            .error {
                font-weight: bold;
                color: #dc3545; /* Red color */
            }
        </style>
        <script>
            setTimeout(function() {
                window.location.href = "' . $homeUrl . '";
            }, 3000);
        </script>
    </head>
    <body>
        <div id="message">
            <div class="error">An error occurred: ' . $e->getMessage() . '</div>
            <div>Please try again later.</div>
        </div>
    </body>
    </html>';



            return new Response($html, 500);
        }
    }
}
