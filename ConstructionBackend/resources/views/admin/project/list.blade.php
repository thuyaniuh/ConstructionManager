@extends('admin.layout.master')

@section('title', 'Quản lý Dự án')

@section('content')
<h2 class="mb-4">Quản lý Dự án</h2>

<!-- Nút mở modal thêm dự án -->
<button class="btn btn-primary btn-sm mb-4" onclick="showCreateProjectModal()">
    <i class="fa-solid fa-plus" style="color: #ffffff;"></i> Thêm Dự án
</button>

<!-- Tabs phân loại dự án -->
<ul class="nav nav-tabs mb-3" id="projectTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="construction-tab" data-bs-toggle="tab" data-bs-target="#construction" type="button" role="tab" aria-controls="construction" aria-selected="true">Dự án Xây dựng</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="design-tab" data-bs-toggle="tab" data-bs-target="#design" type="button" role="tab" aria-controls="design" aria-selected="false">Dự án Thiết kế</button>
    </li>
</ul>

<!-- Nội dung các tab -->
<div class="tab-content" id="projectTabsContent">
    <!-- Tab Dự án Xây dựng -->
    <div class="tab-pane fade show active" id="construction" role="tabpanel" aria-labelledby="construction-tab">
        <div id="constructionProjects" class="row"></div>
    </div>

    <!-- Tab Dự án Thiết kế -->
    <div class="tab-pane fade" id="design" role="tabpanel" aria-labelledby="design-tab">
        <div id="designProjects" class="row"></div>
    </div>
</div>

<!-- Modal thêm dự án -->
<div class="modal fade" id="createProjectModal" tabindex="-1" aria-labelledby="createProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createProjectModalLabel">Thêm Dự án</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="projectForm">
                    <div class="mb-3">
                        <label for="projectName" class="form-label">Tên dự án</label>
                        <input type="text" class="form-control" id="projectName" required>
                    </div>
                    <div class="mb-3">
                        <label for="projectType" class="form-label">Loại dự án</label>
                        <select class="form-select" id="projectType" required>
                            <option value="construction">Dự án Xây dựng</option>
                            <option value="design">Dự án Thiết kế</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="startDate" class="form-label">Ngày bắt đầu</label>
                        <input type="date" class="form-control" id="startDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="endDate" class="form-label">Ngày kết thúc</label>
                        <input type="date" class="form-control" id="endDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="budget" class="form-label">Ngân sách</label>
                        <input type="number" step="0.01" class="form-control" id="budget" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="status" required>
                            <option value="in-progress">Đang thực hiện</option>
                            <option value="completed">Đã hoàn thành</option>
                            <option value="on-hold">Tạm dừng</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="createProject()">Thêm Dự án</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    // Hiển thị modal tạo dự án
    function showCreateProjectModal() {
        var myModal = new bootstrap.Modal(document.getElementById('createProjectModal'));
        myModal.show();
    }

    // Gọi API để tạo dự án mới với kiểm tra ràng buộc ngày
    function createProject() {
        let projectName = document.getElementById('projectName').value;
        let projectType = document.getElementById('projectType').value;
        let startDate = document.getElementById('startDate').value;
        let endDate = document.getElementById('endDate').value;
        let budget = document.getElementById('budget').value;
        let status = document.getElementById('status').value;
        let description = document.getElementById('description').value; // Lưu mô tả dự án

        // Kiểm tra ngày kết thúc phải sau hoặc bằng ngày bắt đầu
        if (new Date(endDate) < new Date(startDate)) {
            alert('Ngày kết thúc không thể trước ngày bắt đầu!');
            return;
        }

        let data = {
            name: projectName,
            type: projectType,
            start_day: startDate,
            end_day: endDate,
            budget: budget,
            status: status,
            description: description // Gửi mô tả nhưng không cần hiển thị ra ngoài
        };

        let url = '/api/projects/store'; // API để tạo dự án

        axios.post(url, data)
            .then(response => {
                alert('Dự án đã được thêm thành công!');
                location.reload(); // Tải lại trang sau khi thêm thành công
            })
            .catch(error => {
                console.error('Có lỗi khi thêm dự án:', error);
                if (error.response && error.response.status === 422) {
                    alert('Dữ liệu không hợp lệ! Hãy kiểm tra lại các thông tin đã nhập.');
                } else {
                    alert('Đã xảy ra lỗi khi thêm dự án!');
                }
            });
    }

    // Gọi API để lấy các dự án Xây dựng
    axios.get('/api/projects')
        .then(response => {
            const projects = response.data.filter(project => project.type === "Xây dựng");
            const constructionContainer = document.getElementById('constructionProjects');
            constructionContainer.innerHTML = createProjectCards(projects);
        })
        .catch(error => {
            console.error('Error fetching construction projects:', error);
        });

    // Gọi API để lấy các dự án Thiết kế
    axios.get('/api/projects')
        .then(response => {
            const projects = response.data.filter(project => project.type === "Thiết kế");
            const designContainer = document.getElementById('designProjects');
            designContainer.innerHTML = createProjectCards(projects);
        })
        .catch(error => {
            console.error('Error fetching design projects:', error);
        });

    // Hàm tạo HTML cho các thẻ dự án
    function createProjectCards(projects) {
        let cards = '';
        projects.forEach(project => {
            const editUrl = `/projects/${project.project_id}/edit`;

            cards += `<div class="col-md-4 mb-4">
                <div class="card h-100">
                <div class="card-body">
                <h5 class="card-title">
                    <a href="/projects/${project.project_id}/details" style="cursor:pointer; text-decoration:none;">
                        ${project.name}
                    </a>
                </h5>
                <p class="card-text">
                    Ngày bắt đầu: ${project.start_day}<br>
                    Ngày kết thúc: ${project.end_day}<br>
                    Ngân sách: ${project.budget}<br> <!-- Hiển thị ngân sách -->
                    Trạng thái: <span class="badge ${getStatusClass(project.status)}">${project.status}</span>
                </p>
                <a href="${editUrl}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Sửa
                </a>
                <button class="btn btn-danger btn-sm" onclick="deleteProject(${project.project_id})">
                    <i class="fas fa-trash"></i> Xóa
                </button>
                </div>
                 </div>
    </div>
`;

        });
        return cards;
    }

    // Hàm xác định lớp trạng thái
    function getStatusClass(status) {
        switch (status) {
            case 'completed':
                return 'bg-success';
            case 'in-progress':
                return 'bg-warning';
            case 'on-hold':
                return 'bg-secondary';
            default:
                return 'bg-info';
        }
    }

    // Hàm xoá dự án
    function deleteProject(id) {
        if (confirm('Bạn có chắc chắn muốn xóa dự án này?')) {
            axios.delete(`/api/projects/${id}`)
                .then(response => {
                    location.reload(); // Tải lại trang sau khi xóa
                })
                .catch(error => {
                    console.error('Error deleting project:', error);
                });
        }
    }
</script>
@endsection