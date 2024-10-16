@extends('admin.layout.master')
@section('title','Quản lý người dùng')
@section('content')
<h2>Thông tin người dùng</h2>

<!-- Nút mở modal thêm người dùng -->
<button class="btn btn-primary btn-sm mb-2" onclick="showCreateUserModal()">
    <i class="fa-solid fa-plus" style="color: #ffffff;"></i> Thêm người dùng
</button>

<!-- Modal để thêm và sửa người dùng -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Thêm/Sửa Người dùng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="userForm" enctype="multipart/form-data">
                    <input type="hidden" id="userId"> <!-- Ẩn id người dùng để phục vụ cho việc sửa -->
                    <div class="mb-3">
                        <label for="userName" class="form-label">Tên người dùng</label>
                        <input type="text" class="form-control" id="userName" required>
                    </div>
                    <div class="mb-3">
                        <label for="userBirth" class="form-label">Ngày sinh</label>
                        <input type="date" class="form-control" id="userBirth" required>
                    </div>
                    <div class="mb-3">
                        <label for="userPhone" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" id="userPhone" required>
                    </div>
                    <div class="mb-3">
                        <label for="userEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="userEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="userRole" class="form-label">Vai trò</label>
                        <select class="form-control" id="userRole" required>
                            <option value="admin">Admin</option>
                            <option value="worker">Worker</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="userStatus" class="form-label">Trạng thái</label>
                        <select class="form-control" id="userStatus" required>
                            <option value="active">Active</option>
                            <option value="locked">Locked</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="userAvatar" class="form-label">Avatar</label>
                        <input type="file" class="form-control" id="userAvatar" accept="image/*">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="saveUser()">Lưu người dùng</button>
            </div>
        </div>
    </div>
</div>

<!-- Bảng danh sách người dùng -->
<table class="table table-bordered" id="users-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên người dùng</th>
            <th>Email</th>
            <th>Vai trò</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody id="users-body">
        <!-- Dữ liệu sẽ được hiển thị tại đây -->
    </tbody>
</table>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Gọi API để lấy dữ liệu người dùng và hiển thị lên bảng
    axios.get('http://127.0.0.1:8000/api/users/')
        .then(function(response) {
            let users = response.data;
            let tableBody = document.getElementById('users-body');

            users.forEach(user => {
                let row = `<tr>
                                <td>${user.user_id}</td>
                                <td>${user.name}</td>
                                <td>${user.email}</td>
                                <td>${user.role}</td>
                                <td>${user.active_status}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editUser(${user.user_id})">
                                        <i class="fas fa-edit"></i> Sửa
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.user_id})">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </td>
                            </tr>`;
                tableBody.innerHTML += row;
            });
        })
        .catch(function(error) {
            console.error('Có lỗi khi lấy dữ liệu: ', error);
        });

    // Hiển thị modal thêm người dùng
    function showCreateUserModal() {
        document.getElementById('userForm').reset(); // Reset lại form
        document.getElementById('userId').value = ''; // Xóa id khi tạo mới
        var myModal = new bootstrap.Modal(document.getElementById('userModal'));
        myModal.show();
    }

    // Hàm lưu người dùng (dùng cho cả thêm mới và sửa)
    function saveUser() {
        let userId = document.getElementById('userId').value; // Lấy ID người dùng (nếu có)
        let formData = new FormData();
        formData.append('name', document.getElementById('userName').value);
        formData.append('birth', document.getElementById('userBirth').value);
        formData.append('phone', document.getElementById('userPhone').value);
        formData.append('email', document.getElementById('userEmail').value);
        formData.append('role', document.getElementById('userRole').value);
        formData.append('active_status', document.getElementById('userStatus').value);
        formData.append('avatar', document.getElementById('userAvatar').files[0]);

        if (userId) {
            // Nếu có ID thì gọi API cập nhật (PUT)
            axios.post(`http://127.0.0.1:8000/api/users/update/${userId}`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(function(response) {
                    showAlert('Cập nhật thành công', 'success');
                    location.reload(); // Tải lại trang sau khi cập nhật thành công
                })
                .catch(function(error) {
                    console.error('Có lỗi khi cập nhật người dùng: ', error);
                    showAlert('Đã xảy ra lỗi, vui lòng thử lại', 'danger');
                });
        } else {
            // Nếu không có ID thì gọi API tạo mới (POST)
            axios.post('http://127.0.0.1:8000/api/users/store', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(function(response) {
                    showAlert('Thêm người dùng thành công', 'success');
                    location.reload(); // Tải lại trang sau khi tạo thành công
                })
                .catch(function(error) {
                    console.error('Có lỗi khi thêm người dùng: ', error);
                    showAlert('Đã xảy ra lỗi, vui lòng thử lại', 'danger');
                });
        }
    }

    // Hàm chỉnh sửa người dùng (lấy dữ liệu từ API và hiển thị trong modal)
    function editUser(id) {
        axios.get(`http://127.0.0.1:8000/api/users/${id}`)
            .then(function(response) {
                let user = response.data;
                document.getElementById('userId').value = user.user_id;
                document.getElementById('userName').value = user.name;
                document.getElementById('userBirth').value = user.birth;
                document.getElementById('userPhone').value = user.phone;
                document.getElementById('userEmail').value = user.email;
                document.getElementById('userRole').value = user.role;
                document.getElementById('userStatus').value = user.active_status;

                var myModal = new bootstrap.Modal(document.getElementById('userModal'));
                myModal.show();
            })
            .catch(function(error) {
                console.error('Có lỗi khi lấy dữ liệu người dùng: ', error);
                showAlert('Không thể lấy thông tin người dùng', 'danger');
            });
    }

    // Hàm xóa người dùng
    function deleteUser(id) {
        if (confirm('Bạn có chắc chắn muốn xóa người dùng này?')) {
            axios.delete(`http://127.0.0.1:8000/api/users/${id}`)
                .then(function(response) {
                    showAlert('Xóa người dùng thành công', 'success');
                    location.reload(); // Tải lại trang sau khi xóa thành công
                })
                .catch(function(error) {
                    console.error('Có lỗi khi xóa người dùng: ', error);
                    showAlert('Đã xảy ra lỗi, vui lòng thử lại', 'danger');
                });
        }
    }

    // Hàm hiển thị thông báo thành công hoặc lỗi
    function showAlert(message, type) {
        let alertBox = document.createElement('div');
        alertBox.className = `alert alert-${type}`;
        alertBox.innerText = message;
        document.body.prepend(alertBox);

        setTimeout(function() {
            alertBox.remove();
        }, 3000);
    }
</script>
@endsection