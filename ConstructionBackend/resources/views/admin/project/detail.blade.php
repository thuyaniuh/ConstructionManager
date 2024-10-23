@extends('admin.layout.master')
@section('title', 'Chi Tiết Dự Án')

@section('content')
<h2>Chi Tiết Dự Án: <span id="projectName"></span></h2>

<!-- Hiển thị thông tin dự án -->
<div class="mb-3">
    <strong>Ngày Bắt Đầu:</strong> <span id="startDay"></span><br>
    <strong>Ngày Kết Thúc:</strong> <span id="endDay"></span><br>
    <strong>Ngân Sách:</strong> <span id="budget"></span><br>
    <strong>Trạng Thái:</strong> <span id="status"></span>
</div>

<!-- Danh sách người dùng đã được phân công -->
<h4>Danh Sách Người Dùng</h4>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tên Người Dùng</th>
            <th>Hành Động</th>
        </tr>
    </thead>
    <tbody id="assignedUsers">
        <!-- Người dùng sẽ được tải ở đây -->
    </tbody>
</table>

<!-- Nút mở modal thêm người dùng -->
<button class="btn btn-primary mb-4" onclick="openAssignUserModal()">Thêm Người Dùng</button>

<!-- Modal phân công người dùng -->
<div class="modal fade" id="assignUserModal" tabindex="-1" aria-labelledby="assignUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignUserModalLabel">Phân Công Người Dùng vào Dự Án</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="assignUserForm">
                    <input type="hidden" id="projectId" value="">
                    <div id="userList" class="mb-3">
                        <!-- Danh sách người dùng sẽ được tạo động -->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="assignUsers()">Phân Công</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    const projectId = window.location.pathname.split('/')[2];

    // Hàm để tải thông tin chi tiết dự án
    function loadProjectDetails() {
        axios.get(`/api/projects/${projectId}`)
            .then(response => {
                const project = response.data;
                document.getElementById('projectName').innerText = project.name;
                document.getElementById('startDay').innerText = project.start_day;
                document.getElementById('endDay').innerText = project.end_day;
                document.getElementById('budget').innerText = project.budget;
                document.getElementById('status').innerText = project.status;

                loadAssignedUsers();
            })
            .catch(error => {
                console.error('Error loading project details:', error);
            });
    }

    // Hàm để tải danh sách người dùng đã được phân công
    function loadAssignedUsers() {
        axios.get(`/api/assignments/${projectId}`)
            .then(response => {
                const users = response.data;
                const assignedUsers = document.getElementById('assignedUsers');
                assignedUsers.innerHTML = '';

                users.forEach(user => {
                    assignedUsers.innerHTML += `
                        <tr>
                            <td>${user.user.name}</td>
                            <td><button class="btn btn-danger btn-sm" onclick="removeUser(${user.assignment_id})">Xóa</button></td>
                        </tr>
                    `;
                });
            })
            .catch(error => {
                console.error('Error loading assigned users:', error);
            });
    }

    // Hàm mở modal để thêm người dùng
    function openAssignUserModal() {
        axios.get('/api/users')
            .then(response => {
                const users = response.data;
                const userList = document.getElementById('userList');
                userList.innerHTML = '';

                users.forEach(user => {
                    userList.innerHTML += `
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="${user.id}" id="user${user.id}">
                            <label class="form-check-label" for="user${user.id}">${user.name}</label>
                        </div>
                    `;
                });

                var myModal = new bootstrap.Modal(document.getElementById('assignUserModal'));
                myModal.show();
            })
            .catch(error => {
                console.error('Error loading users:', error);
            });
    }

    // Hàm để phân công người dùng
    function assignUsers() {
        const selectedUserIds = [];

        document.querySelectorAll('#userList input[type="checkbox"]:checked').forEach(checkbox => {
            selectedUserIds.push(checkbox.value);
        });

        axios.post(`/api/assignments/assign/${projectId}`, {
                user_ids: selectedUserIds
            })
            .then(response => {
                alert('Phân công người dùng thành công!');
                var myModal = bootstrap.Modal.getInstance(document.getElementById('assignUserModal'));
                myModal.hide();
                loadAssignedUsers(); // Tải lại danh sách người dùng sau khi phân công
            })
            .catch(error => {
                console.error('Error assigning users:', error);
                alert('Có lỗi khi phân công người dùng!');
            });
    }

    // Hàm để xóa người dùng khỏi dự án
    function removeUser(assignmentId) {
        if (confirm('Bạn có chắc chắn muốn xóa người dùng này khỏi dự án?')) {
            axios.delete(`/api/assignments/remove/${assignmentId}`)
                .then(response => {
                    loadAssignedUsers(); // Tải lại danh sách sau khi xóa
                })
                .catch(error => {
                    console.error('Error removing user:', error);
                });
        }
    }

    // Khi trang được tải, load thông tin dự án
    window.onload = function() {
        loadProjectDetails();
    };
</script>
@endsection