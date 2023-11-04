<h4 class="fw-700 font-xss mb-4">
    {{ __('texts.texts.profile.' . auth()->user()->lang) }}
</h4>

<small class="text-info">
    {{ '@' . auth()->user()->last_name . ' ' . auth()->user()->first_name }}
</small>

<form action="/logout" method="POST" class="d-none" id="logoutForm">
    @csrf
</form>

<div class="card bg-transparent-card w-100 border-0">
    <ul>
        <li>
            <a href="/my-profile" class="text-black">
                {{ __('texts.texts.personal_information.' . auth()->user()->lang) }}
            </a>
        </li>
        <li>
            <a href="#" class="text-black" id="logoutButton">
                {{ __('texts.texts.logout.' . auth()->user()->lang) }}
            </a>
        </li>
    </ul>
</div>

<script>
    let logoutButton = document.getElementById("logoutButton");

    logoutButton.addEventListener("click", e => {
        e.preventDefault();

        let logoutConfirm = confirm('{{ __('texts.texts.logout_confirm.' . auth()->user()->lang) }}');
        if (logoutConfirm) {
            localStorage.removeItem("jwtToken");
            document.getElementById("logoutForm").submit();
        }
    });
</script>
