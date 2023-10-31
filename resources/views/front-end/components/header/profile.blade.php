<h4 class="fw-700 font-xss mb-4">
    {{ __('texts.texts.profile') }}
</h4>

<form action="/logout" method="POST" class="d-none" id="logoutForm">
  @csrf
</form>

<div class="card bg-transparent-card w-100 border-0">
    <ul>
        <li>
            <a href="/my-profile" class="text-black">
              {{ __('texts.texts.personal_information') }}
            </a>
        </li>
        <li>
          <a href="#" class="text-black" id="logoutButton">
            {{ __('texts.texts.logout') }}
          </a>
        </li>
    </ul>
</div>

<script>
  let logoutButton = document.getElementById("logoutButton");

  logoutButton.addEventListener("click", e => {
    e.preventDefault();
    
    let logoutConfirm = confirm('{{ __('texts.texts.logout_confirm') }}');
    if(logoutConfirm) {
      localStorage.removeItem("jwtToken");
      document.getElementById("logoutForm").submit();
    }
  });
</script>
