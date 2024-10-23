@extends('admin.layout.master')
@section('title', 'Chấm công dự án')

@section('content')

<h2>Chấm công cho dự án</h2>

<!-- Chọn dự án -->
<div class="mb-3">
    <label for="projectId" class="form-label">Chọn dự án</label>
    <select class="form-control" id="projectId" onchange="loadUsersForProject()">
        <!-- Options của dự án sẽ được load từ API -->
    </select>
</div>

<!-- Nút để mở modal chấm công -->
<button class="btn btn-primary" onclick="showCreateEntryModal()">Tạo chấm công</button>

<!-- Modal để tạo chấm công -->
<div class="modal fade" id="entryModal" tabindex="-1" aria-labelledby="entryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="entryModalLabel">Chấm công cho người dùng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="entryForm">
                    <!-- Danh sách người dùng thuộc dự án -->
                    <div id="users-container">
                        <!-- Các ô chọn người dùng sẽ được tạo động -->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="createEntries()">Lưu chấm công</button>
            </div>
        </div>
    </div>
</div>

<!-- Bảng danh sách chấm công -->
<table class="table table-bordered" id="entries-table">
    <thead>
        <tr>
            <th>Người dùng</th>
            <th>Trạng thái</th>
            <th>Thời gian bắt đầu</th>
            <th>Thời gian kết thúc</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody id="entries-body">
        <!-- Dữ liệu sẽ được hiển thị tại đây -->
    </tbody>
</table>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    const API_ENTRIES_URL = 'http://127.0.0.1:8000/api/entries';
    const API_PROJECTS_URL = 'http://127.0.0.1:8000/api/projects';
    const API_USERS_URL = 'http://127.0.0.1:8000/api/users';

    // Tải danh sách dự án
    function loadProjects() {
        axios.get(API_PROJECTS_URL)
            .then(function(response) {
                let projectSelect = document.getElementById('projectId');
                projectSelect.innerHTML = '<option value="">Chọn dự án</option>';
                response.data.forEach(project => {
                    let option = document.createElement('option');
                    option.value = project.id;
                    option.textContent = project.name;
                    projectSelect.appendChild(option);
                });
            })
            .catch(function(error) {
                console.error('Lỗi khi tải danh sách dự án: ', error);
            });
    }

    // Tải danh sách người dùng thuộc dự án
    function loadUsersForProject() {
        let projectId = document.getElementById('projectId').value;

        if (projectId) {
            axios.get(`${API_USERS_URL}/project/${projectId}`)
                .then(function(response) {
                    let usersContainer = document.getElementById('users-container');
                    usersContainer.innerHTML = ''; // Xóa danh sách người dùng trước đó

                    response.data.forEach(user => {
                        let userDiv = `
                            <div class="mb-3">
                                <label>${user.name}</label>
                                <select class="form-control" data-user-id="${user.id}">
                                    <option value="present">Có mặt</option>
                                    <option value="late">Trễ</option>
                                    <option value="absent">Vắng</option>
                                </select>
                            </div>
                        `;
                        usersContainer.innerHTML += userDiv;
                    });

                    // Hiển thị modal sau khi tải xong người dùng
                    var myModal = new bootstrap.Modal(document.getElementById('entryModal'));
                    myModal.show();
                })
                .catch(function(error) {
                    console.error('Lỗi khi tải danh sách người dùng: ', error);
                });
        }
    }

    // Tạo bản ghi chấm công cho người dùng thuộc dự án
    function createEntries() {
        let projectId = document.getElementById('projectId').value;
        let users = [];

        // Lấy danh sách người dùng và trạng thái từ modal
        document.querySelectorAll('#users-container select').forEach(function(select) {
            users.push({
                user_id: select.getAttribute('data-user-id'),
                note: select.value
            });
        });

        axios.post(`${API_ENTRIES_URL}/create/${projectId}`, {
                users: users
            })
            .then(function(response) {
                loadEntries(projectId);
                var myModal = bootstrap.Modal.getInstance(document.getElementById('entryModal'));
                myModal.hide(); // Đóng modal sau khi lưu
            })
            .catch(function(error) {
                console.error('Lỗi khi tạo chấm công: ', error);
            });
    }

    // Tải danh sách chấm công cho dự án
    function loadEntries(projectId) {
        axios.get(`${API_ENTRIES_URL}/${projectId}`)
            .then(function(response) {
                let entries = response.data;
                let tableBody = document.getElementById('entries-body');
                tableBody.innerHTML = '';

                entries.forEach(entry => {
                    let row = `<tr>
                        <td>${entry.user.name}</td>
                        <td>${entry.note}</td>
                        <td>${entry.start_time}</td>
                        <td>${entry.end_time ? entry.end_time : 'Chưa check out'}</td>
                        <td>
                            ${entry.end_time ? '' : `<button class="btn btn-success" onclick="checkout(${entry.entry_id})">Check out</button>`}
                        </td>
                    </tr>`;
                    tableBody.innerHTML += row;
                });
            })
            .catch(function(error) {
                console.error('Lỗi khi tải danh sách chấm công: ', error);
            });
    }

    // Check-out cho người dùng
    function checkout(entryId) {
        axios.post(`${API_ENTRIES_URL}/${entryId}/checkout`)
            .then(function(response) {
                let projectId = document.getElementById('projectId').value;
                loadEntries(projectId); // Tải lại danh sách sau khi check-out
            })
            .catch(function(error) {
                console.error('Lỗi khi check out: ', error);
            });
    }

    // Khi trang tải, load danh sách dự án
    window.onload = function() {
        loadProjects();
    };
</script>

@endsection