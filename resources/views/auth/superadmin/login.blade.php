<x-auth-card
    title="Login Akun"
    :action="route('superadmin.login')"
    :background-image="asset('assets/images/auth/bg-login-superadmin.jpg')"
    button-color="bg-gray-800"
    footer-link-text="Atau login sebagai"
    footer-link-role="user"
    :footer-link-url="route('login')"
/>
