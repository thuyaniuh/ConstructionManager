@extends('admin.layout.master')
@section('title','Danh sách vật tư')
@section('content')

<!-- Vùng hiển thị thông báo thành công -->
<div id="alertSuccess" class="alert alert-success d-none" role="alert">
    <strong>Thành công!</strong> Vật tư đã được xử lý thành công.
</div>

<h2>Thông tin vật tư</h2>

<!-- Nút mở modal thêm vật tư -->
<button class="btn btn-primary btn-sm mb-2" onclick="showCreateMaterialModal()">
    <i class="fa-solid fa-plus" style="color: #ffffff;"></i> Thêm vật tư
</button>

<!-- Modal để thêm và sửa vật tư -->
<div class="modal fade" id="materialModal" tabindex="-1" aria-labelledby="materialModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="materialModalLabel">Thêm/Sửa Vật tư</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="materialForm">
                    <input type="hidden" id="materialId"> <!-- Ẩn id vật tư để phục vụ cho việc sửa -->
                    <div class="mb-3">
                        <label for="materialName" class="form-label">Tên vật tư</label>
                        <input type="text" class="form-control" id="materialName" required>
                    </div>
                    <div class="mb-3">
                        <label for="materialPrice" class="form-label">Giá tham chiếu</label>
                        <input type="number" class="form-control" id="materialPrice" required>
                    </div>
                    <div class="mb-3">
                        <label for="materialUnit" class="form-label">Đơn vị</label>
                        <input type="text" class="form-control" id="materialUnit" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="saveMaterial()">Lưu vật tư</button>
            </div>
        </div>
    </div>
</div>

<!-- Bảng danh sách vật tư -->
<table class="table table-bordered" id="materials-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên vật tư</th>
            <th>Giá tham chiếu</th>
            <th>Đơn vị</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody id="materials-body">
        <!-- Dữ liệu sẽ được hiển thị tại đây -->
    </tbody>
</table>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const API_BASE_URL = 'http://127.0.0.1:8000/api/materials';

    // Gọi API để lấy dữ liệu vật tư và hiển thị lên bảng
    function loadMaterials() {
        axios.get(API_BASE_URL)
            .then(function(response) {
                let materials = response.data;
                let tableBody = document.getElementById('materials-body');
                tableBody.innerHTML = '';

                materials.forEach(material => {
                    let row = `<tr>
                                    <td>${material.material_id}</td>
                                    <td>${material.material_name}</td>
                                    <td>${parseFloat(material.price).toLocaleString()}</td>
                                    <td>${material.unit}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" onclick="editMaterial(${material.material_id})">
                                            <i class="fas fa-edit"></i> Sửa
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteMaterial(${material.material_id})">
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
    }

    // Hiển thị modal thêm vật tư
    function showCreateMaterialModal() {
        document.getElementById('materialForm').reset(); // Reset lại form
        document.getElementById('materialId').value = ''; // Xóa id khi tạo mới
        var myModal = new bootstrap.Modal(document.getElementById('materialModal'));
        myModal.show();
    }

    // Hàm hiển thị thông báo thành công
    function showSuccessAlert() {
        const alertDiv = document.getElementById('alertSuccess');
        alertDiv.classList.remove('d-none');
        setTimeout(function() {
            alertDiv.classList.add('d-none');
        }, 3000); // Ẩn sau 3 giây
    }

    // Hàm lưu vật tư (dùng cho cả thêm mới và sửa)
    function saveMaterial() {
        let materialId = document.getElementById('materialId').value; // Lấy ID vật tư (nếu có)
        let data = {
            material_name: document.getElementById('materialName').value,
            price: document.getElementById('materialPrice').value,
            unit: document.getElementById('materialUnit').value
        };

        if (materialId) {
            // Nếu có ID thì gọi API cập nhật (PUT)
            axios.put(`${API_BASE_URL}/update/${materialId}`, data)
                .then(function(response) {
                    showSuccessAlert(); // Hiển thị thông báo thành công
                    loadMaterials(); // Tải lại dữ liệu sau khi cập nhật thành công
                    var myModal = bootstrap.Modal.getInstance(document.getElementById('materialModal'));
                    myModal.hide(); // Đóng modal sau khi lưu
                })
                .catch(function(error) {
                    console.error('Có lỗi khi cập nhật vật tư: ', error);
                    alert('Đã xảy ra lỗi, vui lòng thử lại.');
                });
        } else {
            // Nếu không có ID thì gọi API tạo mới (POST)
            axios.post(`${API_BASE_URL}/store`, data)
                .then(function(response) {
                    showSuccessAlert(); // Hiển thị thông báo thành công
                    loadMaterials(); // Tải lại dữ liệu sau khi thêm thành công
                    var myModal = bootstrap.Modal.getInstance(document.getElementById('materialModal'));
                    myModal.hide(); // Đóng modal sau khi lưu
                })
                .catch(function(error) {
                    console.error('Có lỗi khi thêm vật tư: ', error);
                    alert('Đã xảy ra lỗi, vui lòng thử lại.');
                });
        }
    }

    // Hàm chỉnh sửa vật tư (lấy dữ liệu từ API và hiển thị trong modal)
    function editMaterial(id) {
        axios.get(`${API_BASE_URL}/${id}`)
            .then(function(response) {
                let material = response.data;
                document.getElementById('materialId').value = material.material_id;
                document.getElementById('materialName').value = material.material_name;
                document.getElementById('materialPrice').value = material.price;
                document.getElementById('materialUnit').value = material.unit;

                var myModal = new bootstrap.Modal(document.getElementById('materialModal'));
                myModal.show();
            })
            .catch(function(error) {
                console.error('Có lỗi khi lấy dữ liệu vật tư: ', error);
                alert('Không thể lấy thông tin vật tư.');
            });
    }

    // Hàm xóa vật tư
    function deleteMaterial(id) {
        if (confirm('Bạn có chắc chắn muốn xóa vật tư này?')) {
            axios.delete(`${API_BASE_URL}/${id}`)
                .then(function(response) {
                    showSuccessAlert(); // Hiển thị thông báo thành công
                    loadMaterials(); // Tải lại dữ liệu sau khi xóa thành công
                })
                .catch(function(error) {
                    console.error('Có lỗi khi xóa vật tư: ', error);
                    alert('Đã xảy ra lỗi, vui lòng thử lại.');
                });
        }
    }

    // Load danh sách vật tư khi trang được tải
    window.onload = function() {
        loadMaterials();
    };
</script>
@endsection