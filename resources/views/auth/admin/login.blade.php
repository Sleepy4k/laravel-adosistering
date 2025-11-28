<x-auth-card
    title="Login Akun"
    :action="route('admin.login')"
    :background-image="asset('assets/images/auth/bg-login-admin.jpg')"
    button-color="bg-blue-600"
    footer-link-text="Atau login sebagai"
    footer-link-role="superadmin"
    :footer-link-url="route('superadmin.login')"
/>
