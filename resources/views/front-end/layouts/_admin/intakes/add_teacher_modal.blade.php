<div class="modal fade" id="addTeacherintakeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-lg-down">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    {{ __('texts.texts.add.' . auth()->user()->lang) }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 560px; overflow-y: scroll;">
                <form action="">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">
                                <i class="feather-user"></i>
                                {{ __('texts.texts.teacher.' . auth()->user()->lang) }}
                            </label>
                            <input type="text" class="form-control" id="searchTeacherInput" autocomplete="off">
                            <div class="mt-2 p-3 bg-white rounded border d-none"
                                style="z-index: 2; max-height: 120px; overflow-y: scroll;" id="foundTeachersContainer">
                            </div>
                            <div class="d-flex-flex-wrap mt-2 d-none" id="chosenTeachersContainer"></div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <button type="button" class="btn btn-success text-white" id="addTeacherSubmitButton">
                                {{ __('texts.texts.add.' . auth()->user()->lang) }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let searchTeacherInput = document.getElementById("searchTeacherInput");
    let foundTeachersContainer = document.getElementById("foundTeachersContainer");
    let chosenTeachersContainer = document.getElementById("chosenTeachersContainer");
    let addTeacherSubmitButton = document.getElementById("addTeacherSubmitButton");
    let searchTeacherTimeout;
    let chosenTeachers = [];

    searchTeacherInput.addEventListener("keyup", e => {
        e.preventDefault();

        if (!e.target.value) {
            foundTeachersContainer.classList.add("d-none");
            return;
        }

        if (searchTeacherTimeout) {
            clearTimeout(searchTeacherTimeout);
        }
        searchTeacherTimeout = setTimeout(() => {
            $.ajax({
                type: "GET",
                url: `/search/users?page=1&limit=10&search=${e.target.value.toLowerCase()}`,
                success: function(data) {
                    if (data.meta.success) {
                        foundSearchUsers = [...data.data.foundUsers];
                        foundSearchUsers = foundSearchUsers.filter(item => {
                            return item.role == 'teacher';
                        });

                        if (!foundSearchUsers.length) {
                            foundTeachersContainer.classList.add("d-none");
                            return;
                        }

                        let foundTeachersLi = "";
                        foundSearchUsers.forEach(item => {
                            foundTeachersLi +=
                                `<li class="add-teacher-li hover-li cursor-pointer" data-user-name="${item.last_name + ' ' + item.first_name}" data-user-uuid="${item.uuid}">${item.last_name + " " + item.first_name}</li>`;
                        });

                        foundTeachersContainer.classList.remove("d-none");
                        foundTeachersContainer.innerHTML = `<ul>${foundTeachersLi}</ul>`;
                        addTeacherLiEvent();
                    } else {
                        foundTeachersContainer.classList.add("d-none");
                    }
                },
            });
        }, 500);
    });

    function addRemoveTeacherEvent() {
        let chosenTeacherBadges = document.querySelectorAll(".chosen-teacher-badge");
        Array.from(chosenTeacherBadges).forEach(item => {
            item.addEventListener("click", e => {
                let removeConfirm = confirm(
                    "{{ __('texts.texts.remove_confirm.' . auth()->user()->lang) }}");
                if (removeConfirm) {
                    let foundTeacher = chosenTeachers.find(item => {
                        return item.uuid == e.target.dataset.uuid;
                    });

                    if (foundTeacher) {
                        chosenTeachers = chosenTeachers.filter(teacher => {
                            return teacher.uuid != e.target.dataset.uuid;
                        });

                        populateTeachers();
                    }
                }
            });
        });
    }

    function populateTeachers() {
        chosenTeachersContainer.innerHTML = "";
        if (chosenTeachers.length) {
            chosenTeachers.forEach(teacher => {
                chosenTeachersContainer.classList.remove("d-none");
                chosenTeachersContainer.innerHTML +=
                    `<span style="margin-right: 8px;" class="cursor-pointer badge badge-primary chosen-teacher-badge" data-uuid="${teacher.uuid}">${teacher.name}</span>`;
            });

            addRemoveTeacherEvent();
        } else {
            chosenTeachersContainer.classList.add("d-none");
        }
    }

    function addTeacherLiEvent() {
        let teacherLis = document.querySelectorAll(".add-teacher-li");
        Array.from(teacherLis).forEach(li => {
            li.addEventListener("click", e => {
                foundTeachersContainer.classList.add("d-none");

                let foundTeacher = chosenTeachers.find(item => {
                    return item.uuid == e.target.dataset.userUuid;
                });
                if (!foundTeacher) {
                    chosenTeachers.push({
                        uuid: e.target.dataset.userUuid,
                        name: e.target.dataset.userName,
                    });
                }

                populateTeachers();
            });
        });
    }

    addTeacherSubmitButton.addEventListener("click", e => {
        e.preventDefault();

        if (!chosenTeachers.length) {
            return;
        }

        let formData = "uuids=" + chosenTeachers.map(item => item.uuid).join(",");
        $.ajax({
            url: "/admin/intakes/{{ $intake->uuid }}/add-teachers",
            type: "POST",
            data: formData,
            success: function(result) {
                if (result.meta.success) {
                    alert("{{ __('texts.texts.update_success.' . auth()->user()->lang) }}");
                    window.location.reload();
                } else {
                    alert(JSON.stringify(result));
                    return;
                }
            },
            error: function(error) {
                alert(error.responseJSON.message);
                return;
            },
        });
    });
</script>
