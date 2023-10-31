<h4 class="fw-700 font-xss mb-4">
    {{ __('texts.texts.languages.' . auth()->user()->lang) }}
</h4>

<div class="card bg-transparent-card w-100 border-0">
    <ul>
        <li>
            <a href="#" class="text-black languages-choices" data-lang="vi" id="vi_lang_choice">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('assets/imgs/vietnam_flag.png') }}" alt="vietnam_flag" class="languages-choices"
                        data-lang="vi" style="height: 32px; width: 32px; object-fit: cover;">
                    <div class="p-2 languages-choices" data-lang="vi">
                        Viêt Nam
                    </div>
                </div>
            </a>
        </li>
        <li>
            <a href="#" class="text-black languages-choices" data-lang="en" id="en_lang_choice">
                <div class="d-flex align-items-center mt-3">
                    <img src="{{ asset('assets/imgs/england_flag.png') }}" alt="vietnam_flag" class="languages-choices"
                        data-lang="en" style="height: 32px; width: 32px; object-fit: cover;">
                    <div class="p-2 languages-choices" data-lang="en">
                        English
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

            let formData = "lang=" + e.target.dataset.lang;
            $.ajax({
                url: "/update-lang",
                type: "POST",
                data: formData,
                success: function(result) {
                    if (result.meta.success) {
                        window.location.reload();
                    }
                },
            });
        });
    });
</script>
