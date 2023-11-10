<div>
    <h3 class="fw-500 mb-0 mt-0 text-grey-900 mb-4" style="font-size: 24px !important;">
        {{ __('texts.texts.users.' . auth()->user()->lang) }}
    </h3>
    <div class="row" id="user-results-container">
    </div>
    <div class="mt-2">
        <span class="text-primary text-decoration-underline" id="loadMoreSearchUsers">
            <small class="cursor-pointer">
                {{ __('texts.texts.load_more.' . auth()->user()->lang) }}
            </small>
        </span>
    </div>
</div>

<script>
    let loadMoreSearchUsers = document.getElementById("loadMoreSearchUsers");
    let userResultsContainer = document.getElementById("user-results-container");

    let userSearchCurrentPage = 1;
    let userSearchLimit = 6;
    let foundSearchUsers = [];

    loadMoreSearchUsers.addEventListener("click", e => {
        userSearchCurrentPage++;
        searchUsers();
    });

    function searchUsers() {
        let params = new URL(window.location.href);
        let search = params.searchParams.get("search");

        $.ajax({
            type: "GET",
            url: `/search/users?page=${userSearchCurrentPage}&limit=${userSearchLimit}&search=${search}`,
            success: function(data) {
                if (data.meta.success) {
                    foundSearchUsers = foundSearchUsers.concat(data.data.foundUsers);
                    if (data.data.foundUsers.length < userSearchLimit) {
                        loadMoreSearchUsers.classList.add("d-none");
                    } else {
                        loadMoreSearchUsers.classList.remove("d-none");
                    }

                    if (!foundSearchUsers.length) {
                        loadMoreSearchUsers.classList.add("d-none");
                        userResultsContainer.innerHTML = `
                        <div class="col-12 bg-light rounded">
                            <div class="card-body">
                              {{ __('texts.texts.no_users_found.' . auth()->user()->lang) }}
                            </div>
                        </div>
                        `;
                    } else {
                        populateFoundUsers();
                    }
                }
            },
        });
    }

    searchUsers();

    function populateFoundUsers() {
        foundSearchUsers.forEach(item => {
            if (!item.shown) {
                item.shown = true;
                let thisClass = item.class_roles.length ? item.class_roles[0].class_.name : '';
                userResultsContainer.innerHTML += `
                <div class="col-md-4">
                    <div class="card-body d-flex flex-wrap pt-0 ps-4 pe-4 pb-3">
                        <div>
                            <img src="${item.avatar}" alt="${item.last_name}_logo" style="width: 80px; height: 80px; aspect-ratio: 1; object-fit: cover; margin-right: 8px; border-radius: 100%;" class="border" />
                        </div>
                        <div>
                          <a href="/users/${item.uuid}">
                            <div class="cursor-pointer">
                              <h4 class="fw-700 text-grey-900 font-xssss mt-2">
                                ${item.last_name + ' ' + item.first_name}
                                <span class="d-block font-xsssss fw-500 mt-1 lh-4 text-grey-500">
                                    ${thisClass}
                                </span>
                              </h4>
                            </div>
                          </a>
                          <div>
                            <small>#${item.role}</small>
                          </div>
                        </div>
                    </div>
                </div>
                `;
            }
        });
    }
</script>
