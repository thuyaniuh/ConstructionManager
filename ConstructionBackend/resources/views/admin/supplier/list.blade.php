@extends('admin.layout.master')
@section('title','Danh sách nhà cung cấp')
@section('content')

<!-- Vùng hiển thị thông báo thành công -->
<div id="alertSuccess" class="alert alert-success d-none" role="alert">
    <strong>Thành công!</strong> Nhà cung cấp đã được xử lý thành công.
</div>

<h2>Thông tin nhà cung cấp</h2>

<!-- Nút mở modal thêm nhà cung cấp -->
<button class="btn btn-primary btn-sm mb-2" onclick="showCreateSupplierModal()">
    <i class="fa-solid fa-plus" style="color: #ffffff;"></i> Thêm nhà cung cấp
</button>

<!-- Modal để thêm và sửa nhà cung cấp -->
<div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="supplierModalLabel">Thêm/Sửa Nhà Cung Cấp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="supplierForm">
                    <input type="hidden" id="supplierId"> <!-- Ẩn id nhà cung cấp để phục vụ cho việc sửa -->
                    <div class="mb-3">
                        <label for="supplierName" class="form-label">Tên nhà cung cấp</label>
                        <input type="text" class="form-control" id="supplierName" required>
                    </div>
                    <div class="mb-3">
                        <label for="supplierAddress" class="form-label">Địa chỉ</label>
                        <input type="text" class="form-control" id="supplierAddress" required>
                    </div>
                    <div class="mb-3">
                        <label for="supplierPhone" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" id="supplierPhone" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="saveSupplier()">Lưu nhà cung cấp</button>
            </div>
        </div>
    </div>
</div>

<!-- Bảng danh sách nhà cung cấp -->
<table class="table table-bordered" id="suppliers-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên nhà cung cấp</th>
            <th>Địa chỉ</th>
            <th>Số điện thoại</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody id="suppliers-body">
        <!-- Dữ liệu sẽ được hiển thị tại đây -->
    </tbody>
</table>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const API_BASE_URL = 'http://127.0.0.1:8000/api/suppliers';

    // Gọi API để lấy dữ liệu nhà cung cấp và hiển thị lên bảng
    function loadSuppliers() {
        axios.get(API_BASE_URL)
            .then(function(response) {
                let suppliers = response.data;
                let tableBody = document.getElementById('suppliers-body');
                tableBody.innerHTML = '';

                suppliers.forEach(supplier => {
                    let row = `<tr>
                                    <td>${supplier.supplier_id}</td>
                                    <td>${supplier.supplier_name}</td>
                                    <td>${supplier.address}</td>
                                    <td>${supplier.phone}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" onclick="editSupplier(${supplier.supplier_id})">
                                            <i class="fas fa-edit"></i> Sửa
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteSupplier(${supplier.supplier_id})">
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

    // Hiển thị modal thêm nhà cung cấp
    function showCreateSupplierModal() {
        document.getElementById('supplierForm').reset(); // Reset lại form
        document.getElementById('supplierId').value = ''; // Xóa id khi tạo mới
        var myModal = new bootstrap.Modal(document.getElementById('supplierModal'));
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

    // Hàm lưu nhà cung cấp (dùng cho cả thêm mới và sửa)
    function saveSupplier() {
        let supplierId = document.getElementById('supplierId').value; // Lấy ID nhà cung cấp (nếu có)
        let data = {
            supplier_name: document.getElementById('supplierName').value,
            address: document.getElementById('supplierAddress').value,
            phone: document.getElementById('supplierPhone').value
        };

        if (supplierId) {
            // Nếu có ID thì gọi API cập nhật (PUT)
            axios.put(`${API_BASE_URL}/update/${supplierId}`, data)
                .then(function(response) {
                    showSuccessAlert(); // Hiển thị thông báo thành công
                    loadSuppliers(); // Tải lại dữ liệu sau khi cập nhật thành công
                    var myModal = bootstrap.Modal.getInstance(document.getElementById('supplierModal'));
                    myModal.hide(); // Đóng modal sau khi lưu
                })
                .catch(function(error) {
                    console.error('Có lỗi khi cập nhật nhà cung cấp: ', error);
                    alert('Đã xảy ra lỗi, vui lòng thử lại.');
                });
        } else {
            // Nếu không có ID thì gọi API tạo mới (POST)
            axios.post(`${API_BASE_URL}/store`, data)
                .then(function(response) {
                    showSuccessAlert(); // Hiển thị thông báo thành công
                    loadSuppliers(); // Tải lại dữ liệu sau khi thêm thành công
                    var myModal = bootstrap.Modal.getInstance(document.getElementById('supplierModal'));
                    myModal.hide(); // Đóng modal sau khi lưu
                })
                .catch(function(error) {
                    console.error('Có lỗi khi thêm nhà cung cấp: ', error);
                    alert('Đã xảy ra lỗi, vui lòng thử lại.');
                });
        }
    }

    // Hàm chỉnh sửa nhà cung cấp (lấy dữ liệu từ API và hiển thị trong modal)
    function editSupplier(id) {
        axios.get(`${API_BASE_URL}/${id}`)
            .then(function(response) {
                let supplier = response.data;
                document.getElementById('supplierId').value = supplier.supplier_id;
                document.getElementById('supplierName').value = supplier.supplier_name;
                document.getElementById('supplierAddress').value = supplier.address;
                document.getElementById('supplierPhone').value = supplier.phone;

                var myModal = new bootstrap.Modal(document.getElementById('supplierModal'));
                myModal.show();
            })
            .catch(function(error) {
                console.error('Có lỗi khi lấy dữ liệu nhà cung cấp: ', error);
                alert('Không thể lấy thông tin nhà cung cấp.');
            });
    }

    // Hàm xóa nhà cung cấp
    function deleteSupplier(id) {
        if (confirm('Bạn có chắc chắn muốn xóa nhà cung cấp này?')) {
            axios.delete(`${API_BASE_URL}/${id}`)
                .then(function(response) {
                    showSuccessAlert(); // Hiển thị thông báo thành công
                    loadSuppliers(); // Tải lại dữ liệu sau khi xóa thành công
                })
                .catch(function(error) {
                    console.error('Có lỗi khi xóa nhà cung cấp: ', error);
                    alert('Đã xảy ra lỗi, vui lòng thử lại.');
                });
        }
    }

    // Load danh sách nhà cung cấp khi trang được tải
    window.onload = function() {
        loadSuppliers();
    };
</script>
@endsection