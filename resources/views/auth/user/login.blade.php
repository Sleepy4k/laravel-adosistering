<x-auth-card
    title="Login Akun"
    :action="route('login')"
    :background-image="asset('assets/images/auth/bg-login-user.jpg')"
    button-color="bg-gray-800"
    footer-link-text="Atau login sebagai"
    footer-link-role="admin"
    :footer-link-url="route('admin.login')"
/>
