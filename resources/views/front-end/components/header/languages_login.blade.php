<h4 class="fw-700 font-xss">
    {{ __('texts.texts.languages.' . $lang) }}
</h4>

<form action="" method="GET" id="loginLangForm">
    <input type="hidden" name="lang" id="loginLangInput">
</form>

<div class="card bg-transparent-card w-100 border-0">
    <ul>
        <li>
            <a href="#" class="text-black languages-choices" data-lang="vi" id="vi_lang_choice">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('assets/imgs/vietnam_flag.png') }}" alt="vietnam_flag" class="languages-choices"
                        data-lang="vi" style="height: 32px; width: 32px; border-radius: 100%; object-fit: cover;">
                    <div class="pl-2 languages-choices" data-lang="vi">
                        &nbsp;Viêt Nam
                    </div>
                </div>
            </a>
        </li>
        <li>
            <a href="#" class="text-black languages-choices" data-lang="en" id="en_lang_choice">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('assets/imgs/england_flag.png') }}" alt="vietnam_flag" class="languages-choices"
                        data-lang="en" style="height: 32px; width: 32px; border-radius: 100%; object-fit: cover;">
                    <div class="pl-2 languages-choices" data-lang="en">
                        &nbsp;English
                    </div>
                </div>
            </a>
        </li>
    </ul>
</div>

<script>
    let langChoices = document.querySelectorAll(".languages-choices");
    Array.from(langChoices).forEach(item => {
        item.addEventListener("click", e => {
            e.preventDefault();

            document.getElementById("loginLangInput").value = e.target.dataset.lang;
            document.getElementById("loginLangForm").submit();
        });
    });
</script>
