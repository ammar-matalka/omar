<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ config('app.direction', 'ltr') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Email Verification Code') }} - {{ config('app.name') }}</title>
    
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        /* Base styles */
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f8fafc;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        /* Header */
        .email-header {
            background: linear-gradient(135deg, #0ea5e9, #d946ef);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .email-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="white" opacity="0.1"><circle cx="20" cy="20" r="15"/><circle cx="80" cy="80" r="20"/><circle cx="60" cy="30" r="10"/></svg>');
        }
        
        .logo {
            font-size: 28px;
            font-weight: 900;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }
        
        .header-text {
            font-size: 18px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
        
        /* Body */
        .email-body {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1f2937;
        }
        
        .content {
            font-size: 16px;
            line-height: 1.7;
            margin-bottom: 30px;
            color: #4b5563;
        }
        
        .verification-section {
            background: linear-gradient(135deg, #f0f9ff, #fdf4ff);
            border: 2px dashed #0ea5e9;
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        
        .verification-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #1f2937;
        }
        
        .verification-code {
            font-size: 36px;
            font-weight: 900;
            font-family: 'Courier New', monospace;
            background: white;
            color: #0ea5e9;
            padding: 20px 30px;
            border-radius: 12px;
            letter-spacing: 8px;
            margin: 20px 0;
            border: 3px solid #0ea5e9;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.2);
        }
        
        .verification-note {
            font-size: 14px;
            color: #6b7280;
            margin-top: 15px;
        }
        
        .expiry-warning {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        
        .expiry-icon {
            font-size: 20px;
            margin-bottom: 5px;
            color: #d97706;
        }
        
        .expiry-text {
            font-size: 14px;
            font-weight: 600;
            color: #92400e;
        }
        
        .instructions {
            background: #f9fafb;
            border-left: 4px solid #0ea5e9;
            padding: 20px;
            margin: 30px 0;
        }
        
        .instructions-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .instructions-list {
            list-style: none;
            padding: 0;
        }
        
        .instructions-list li {
            font-size: 14px;
            margin-bottom: 8px;
            color: #4b5563;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        
        .step-number {
            background: #0ea5e9;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            flex-shrink: 0;
            margin-top: 2px;
        }
        
        .cta-section {
            text-align: center;
            margin: 30px 0;
        }
        
        .cta-button {
            background: linear-gradient(135deg, #0ea5e9, #3b82f6);
            color: white;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
            transition: all 0.2s ease;
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(14, 165, 233, 0.4);
            color: white;
        }
        
        /* Footer */
        .email-footer {
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            padding: 30px;
            text-align: center;
        }
        
        .footer-text {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 15px;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .footer-link {
            color: #0ea5e9;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }
        
        .footer-link:hover {
            text-decoration: underline;
        }
        
        .company-info {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        
        .security-notice {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        
        .security-icon {
            color: #dc2626;
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        .security-text {
            font-size: 13px;
            color: #7f1d1d;
            line-height: 1.5;
        }
        
        /* Responsive */
        @media (max-width: 600px) {
            .email-container {
                margin: 0;
                box-shadow: none;
            }
            
            .email-header,
            .email-body,
            .email-footer {
                padding: 20px;
            }
            
            .verification-code {
                font-size: 28px;
                padding: 15px 20px;
                letter-spacing: 4px;
            }
            
            .footer-links {
                flex-direction: column;
                gap: 10px;
            }
        }
        
        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .email-container {
                background-color: #1f2937;
            }
            
            .email-body {
                color: #e5e7eb;
            }
            
            .greeting {
                color: #f9fafb;
            }
            
            .content {
                color: #d1d5db;
            }
            
            .instructions {
                background: #374151;
                border-left-color: #60a5fa;
            }
            
            .instructions-title {
                color: #f9fafb;
            }
            
            .instructions-list li {
                color: #d1d5db;
            }
            
            .email-footer {
                background: #374151;
                border-top-color: #4b5563;
            }
            
            .footer-text {
                color: #9ca3af;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="logo">{{ config('app.name') }}</div>
            <div class="header-text">{{ __('Email Verification Required') }}</div>
        </div>
        
        <!-- Body -->
        <div class="email-body">
            <div class="greeting">{{ __('Hello!') }}</div>
            
            <div class="content">
                {{ __('Thank you for registering with') }} {{ config('app.name') }}! {{ __('To complete your registration, please use the verification code below:') }}
            </div>
            
            <!-- Verification Section -->
            <div class="verification-section">
                <div class="verification-title">{{ __('Your Verification Code') }}</div>
                <div class="verification-code">{{ $code }}</div>
                <div class="verification-note">{{ __('Enter this code on the registration page to verify your email address.') }}</div>
            </div>
            
            <!-- Expiry Warning -->
            <div class="expiry-warning">
                <div class="expiry-icon">⏰</div>
                <div class="expiry-text">{{ __('This code will expire in 10 minutes for security reasons.') }}</div>
            </div>
            
            <!-- Instructions -->
            <div class="instructions">
                <div class="instructions-title">
                    <span>📋</span>
                    {{ __('How to use this code:') }}
                </div>
                <ul class="instructions-list">
                    <li>
                        <span class="step-number">1</span>
                        {{ __('Return to the registration page on') }} {{ config('app.name') }}
                    </li>
                    <li>
                        <span class="step-number">2</span>
                        {{ __('Enter the verification code exactly as shown above') }}
                    </li>
                    <li>
                        <span class="step-number">3</span>
                        {{ __('Complete your registration details') }}
                    </li>
                    <li>
                        <span class="step-number">4</span>
                        {{ __('Start exploring our platform!') }}
                    </li>
                </ul>
            </div>
            
            <!-- CTA Section -->
            <div class="cta-section">
                <a href="{{ route('register.step2') }}" class="cta-button">
                    {{ __('Complete Registration') }} →
                </a>
            </div>
            
            <!-- Security Notice -->
            <div class="security-notice">
                <div class="security-icon">🔒</div>
                <div class="security-text">
                    {{ __('For security reasons, if you did not request this verification code, please ignore this email. The code will expire automatically.') }}
                </div>
            </div>
            
            <div class="content">
                {{ __('If you\'re having trouble with the verification process, please don\'t hesitate to contact our support team.') }}
            </div>
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <div class="footer-text">
                {{ __('Best regards,') }}<br>
                {{ __('The') }} {{ config('app.name') }} {{ __('Team') }}
            </div>
            
            <div class="footer-links">
                <a href="{{ route('home') }}" class="footer-link">{{ __('Visit Website') }}</a>
                <a href="#" class="footer-link">{{ __('Contact Support') }}</a>
                <a href="#" class="footer-link">{{ __('Privacy Policy') }}</a>
            </div>
            
            <div class="company-info">
                © {{ date('Y') }} {{ config('app.name') }}. {{ __('All rights reserved.') }}<br>
                {{ __('This email was sent to') }} {{ $email ?? 'your email address' }}
            </div>
        </div>
    </div>
</body>
</html>