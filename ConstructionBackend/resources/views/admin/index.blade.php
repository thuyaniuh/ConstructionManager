@extends('admin.layout.master')
@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3 id="count-project">
                    <div class="spinner-border text-light"></div>
                </h3>
                <p>Project</p>
            </div>
            <div class="icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>53<sup style="font-size: 20px">%</sup></h3>
                <p>Bounce Rate</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3 id="count-user">
                    <div class="spinner-border text-light"></div>
                </h3>
                <p>User</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3 id='count-supplier'>
                    <div class="spinner-border text-light"></div>
                </h3>
                <p>Supplier</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-pie"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<!-- Thêm thư viện Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // Hàm để gọi API và cập nhật số lượng người dùng
    var URL = 'http://127.0.0.1:8000/api'

    function loadAllData() {
        // Gọi tất cả các API đồng thời
        Promise.all([
            axios.get(URL + '/count'),
            axios.get(URL + '/count-project'),
            axios.get(URL + '/count-supplier')
        ]).then(function(responses) {
            // Dữ liệu người dùng
            let countUser = responses[0].data.count;
            document.getElementById('count-user').innerHTML = countUser;

            // Dữ liệu dự án
            let countProject = responses[1].data.count;
            document.getElementById('count-project').innerHTML = countProject;

            // Dữ liệu nhà cung cấp
            let countSupplier = responses[2].data.count;
            document.getElementById('count-supplier').innerHTML = countSupplier;
        }).catch(function(error) {
            console.log(error); // Xử lý lỗi nếu có
        });
    }

    // Gọi hàm loadAllData khi trang web đã sẵn sàng
    document.addEventListener('DOMContentLoaded', function() {
        loadAllData(); // Gọi một lần khi DOM đã tải xong
    });
</script>
@endsection