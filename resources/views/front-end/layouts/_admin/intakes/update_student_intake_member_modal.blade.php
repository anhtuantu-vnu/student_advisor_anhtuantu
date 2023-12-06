<div data-bs-toggle="modal" data-bs-target="#updateStudentIntakeModal" class="d-none" id="updateStudentIntakeModalOpener">
</div>
<div class="modal fade" id="updateStudentIntakeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-lg-down">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title" id="updateIntakeModalTitle">
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 560px; overflow-y: scroll;">
                <form action="">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="">
                                {{ __('texts.texts.attendance_points.' . auth()->user()->lang) }}
                            </label>
                            <input type="number" min="0" step="0.001" class="form-control"
                                id="attendance_points" autocomplete="off">
                        </div>
                        <div class="col-md-4">
                            <label for="">
                                {{ __('texts.texts.mid_term_points.' . auth()->user()->lang) }}
                            </label>
                            <input type="number" min="0" step="0.001" class="form-control"
                                id="mid_term_points" autocomplete="off">
                        </div>
                        <div class="col-md-4">
                            <label for="">
                                {{ __('texts.texts.last_term_points.' . auth()->user()->lang) }}
                            </label>
                            <input type="number" min="0" step="0.001" class="form-control"
                                id="last_term_points" autocomplete="off">
                        </div>
                        <div class="col-md-12 mt-2">
                            <button type="button" class="btn btn-success text-white" id="updateStudentIntake">
                                {{ __('texts.texts.save.' . auth()->user()->lang) }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let searchStudentInput = document.getElementById("searchStudentInput");
    let foundStudentsContainer = document.getElementById("foundStudentsContainer");
    let chosenStudentsContainer = document.getElementById("chosenStudentsContainer");
    let addStudentSubmitButton = document.getElementById("addStudentSubmitButton");
    let searchStudentTimeout;
    let chosenStudents = [];

    searchStudentInput.addEventListener("keyup", e => {
        e.preventDefault();

        if (!e.target.value) {
            foundStudentsContainer.classList.add("d-none");
            return;
        }

        if (searchStudentTimeout) {
            clearTimeout(searchStudentTimeout);
        }
        searchStudentTimeout = setTimeout(() => {
            $.ajax({
                type: "GET",
                url: `/search/users?page=1&limit=10&search=${e.target.value.toLowerCase()}`,
                success: function(data) {
                    if (data.meta.success) {
                        foundSearchUsers = [...data.data.foundUsers];
                        foundSearchUsers = foundSearchUsers.filter(item => {
                            return item.role == 'student';
                        });

                        if (!foundSearchUsers.length) {
                            foundStudentsContainer.classList.add("d-none");
                            return;
                        }

                        let foundStudentsLi = "";
                        foundSearchUsers.forEach(item => {
                            foundStudentsLi +=
                                `<li class="add-student-li hover-li cursor-pointer" data-user-name="${item.last_name + ' ' + item.first_name}" data-user-uuid="${item.uuid}">${item.last_name + " " + item.first_name}</li>`;
                        });

                        foundStudentsContainer.classList.remove("d-none");
                        foundStudentsContainer.innerHTML = `<ul>${foundStudentsLi}</ul>`;
                        addStudentLiEvent();
                    } else {
                        foundStudentsContainer.classList.add("d-none");
                    }
                },
            });
        }, 500);
    });

    function addRemoveStudentEvent() {
        let chosenStudentBadges = document.querySelectorAll(".chosen-student-badge");
        Array.from(chosenStudentBadges).forEach(item => {
            item.addEventListener("click", e => {
                let removeConfirm = confirm(
                    "{{ __('texts.texts.remove_confirm.' . auth()->user()->lang) }}");
                if (removeConfirm) {
                    let foundStudent = chosenStudents.find(item => {
                        return item.uuid == e.target.dataset.uuid;
                    });

                    if (foundStudent) {
                        chosenStudents = chosenStudents.filter(teacher => {
                            return teacher.uuid != e.target.dataset.uuid;
                        });

                        populateStudents();
                    }
                }
            });
        });
    }

    function populateStudents() {
        chosenStudentsContainer.innerHTML = "";
        if (chosenStudents.length) {
            chosenStudents.forEach(student => {
                chosenStudentsContainer.classList.remove("d-none");
                chosenStudentsContainer.innerHTML +=
                    `<span style="margin-right: 8px;" class="cursor-pointer badge badge-primary chosen-student-badge" data-uuid="${student.uuid}">${student.name}</span>`;
            });

            addRemoveStudentEvent();
        } else {
            chosenStudentsContainer.classList.add("d-none");
        }
    }

    function addStudentLiEvent() {
        let studentLis = document.querySelectorAll(".add-student-li");
        Array.from(studentLis).forEach(li => {
            li.addEventListener("click", e => {
                foundStudentsContainer.classList.add("d-none");

                let foundStudent = chosenStudents.find(item => {
                    return item.uuid == e.target.dataset.userUuid;
                });
                if (!foundStudent) {
                    chosenStudents.push({
                        uuid: e.target.dataset.userUuid,
                        name: e.target.dataset.userName,
                    });
                }

                populateStudents();
            });
        });
    }

    addStudentSubmitButton.addEventListener("click", e => {
        e.preventDefault();

        if (!chosenStudents.length) {
            return;
        }

        let formData = "uuids=" + chosenStudents.map(item => item.uuid).join(",");
        $.ajax({
            url: "/admin/intakes/{{ $intake->uuid }}/add-students",
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
