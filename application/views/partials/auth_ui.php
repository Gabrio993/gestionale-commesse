<link rel="icon" type="image/png" href="<?= base_url('assets/images/auth-bg.png') ?>">
<style>
    body.auth-page {
        font-family: Arial, sans-serif;
        margin: 0;
        min-height: 100vh;
        color: #1f2937;
        background:
            radial-gradient(circle at 20% 20%, rgba(59, 130, 246, 0.28), transparent 22%),
            radial-gradient(circle at 80% 30%, rgba(17, 24, 39, 0.24), transparent 24%),
            linear-gradient(135deg, #0f172a 0%, #111827 42%, #1f2937 100%);
        position: relative;
    }

    .auth-page::before {
        content: "";
        position: fixed;
        inset: 0;
        background:
            radial-gradient(circle at top left, rgba(255, 255, 255, 0.10), transparent 28%),
            radial-gradient(circle at bottom right, rgba(255, 255, 255, 0.06), transparent 24%);
        pointer-events: none;
    }

    .auth-shell {
        position: relative;
        min-height: 100vh;
        display: grid;
        place-items: center;
        padding: 32px 20px;
    }

    @keyframes authSlideInLeft {
        from {
            opacity: 0;
            transform: translateX(-400px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes authSlideInRight {
        from {
            opacity: 0;
            transform: translateX(1000px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .auth-corner-brand {
        position: fixed;
        top: 24px;
        left: 24px;
        z-index: 3;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        
    }

    .auth-landing .auth-corner-brand {
        animation: authSlideInLeft 2s ease-out both;
    }

    .auth-landing .auth-card {
        animation: authSlideInRight 2s ease-out both;
    }

    .auth-corner-brand strong {
        display: block;
        font-size: 18px;
        line-height: 1.1;
    }


    .auth-card {
        width: min(92vw, 520px);
        background: rgba(255, 255, 255, 0.94);
        border: 1px solid rgba(229, 231, 235, 0.85);
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.24);
        backdrop-filter: blur(14px);
    }

    .auth-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .auth-logo {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: block;
        object-fit: cover;
        box-shadow: 0 10px 24px rgba(17, 24, 39, 0.18);
        background: #fff;
    }

    .auth-brand strong {
        display: block;
        font-size: 18px;
    }

    .auth-brand span {
        display: block;
        color: #6b7280;
        font-size: 13px;
    }

    .auth-title {
        margin: 0 0 8px;
        font-size: 30px;
        line-height: 1.1;
    }

    .auth-text {
        margin: 0 0 22px;
        color: #6b7280;
    }

    .auth-grid {
        display: grid;
        gap: 12px;
    }

    .auth-actions {
        display: grid;
        gap: 12px;
        margin-top: 20px;
    }

    .auth-button,
    .auth-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 16px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        border: 1px solid transparent;
        cursor: pointer;
    }

    .auth-button.primary {
        background: #111827;
        color: #fff;
    }

    .auth-button.secondary,
    .auth-link.secondary {
        background: #fff;
        color: #111827;
        border-color: #d1d5db;
    }

    .auth-field label {
        display: block;
        margin: 14px 0 6px;
        font-weight: 700;
    }

    .auth-field input {
        width: 100%;
        box-sizing: border-box;
        padding: 12px 14px;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.96);
        font: inherit;
    }

    .auth-msg {
        margin: 12px 0 0;
        color: #065f46;
    }

    .auth-err {
        margin: 12px 0 0;
        color: #b91c1c;
    }

    @media (max-width: 560px) {
        .auth-landing .auth-corner-brand,
        .auth-landing .auth-card {
            animation-duration: 0.01ms;
            animation-delay: 0s;
        }

        .auth-corner-brand {
            top: 14px;
            left: 14px;
            padding: 8px 10px;
            gap: 8px;
        }

        .auth-corner-logo {
            width: 38px;
            height: 38px;
        }

        .auth-corner-brand strong {
            font-size: 15px;
        }

        .auth-corner-brand span {
            font-size: 11px;
        }

        .auth-card {
            width: 100%;
            padding: 22px;
            border-radius: 20px;
        }

        .auth-title {
            font-size: 26px;
        }
    }
</style>
