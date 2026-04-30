@extends('clients.layout.app')

@section('title', 'Chính sách bảo mật')

@section('content')
<link rel="stylesheet" href="{{ asset('css/clients/policy.css') }}">
<div class="policy-page-wrapper">
    <div class="container-setting policy-container">
        <h1 class="policy-page-title">CHÍNH SÁCH BẢO MẬT</h1>
        
        <div class="policy-content">
            <div class="policy-section">
                <h2 class="policy-section-title">1. Giới thiệu</h2>
                <p>NestAway cam kết bảo vệ quyền riêng tư và thông tin cá nhân của người dùng khi sử dụng dịch vụ. Chính sách này giải thích cách chúng tôi thu thập, sử dụng và bảo vệ dữ liệu của bạn. Khi truy cập và sử dụng website, bạn đồng ý với các nội dung được quy định trong chính sách này.</p>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">2. Phạm vi áp dụng</h2>
                <p>Chúng tôi có thể thu thập thông tin cá nhân như họ tên, email, số điện thoại khi bạn đăng ký tài khoản hoặc đặt phòng. Ngoài ra, hệ thống cũng lưu trữ thông tin liên quan đến đặt phòng như thời gian lưu trú, số lượng khách, lịch sử giao dịch.</p>
                <p>Bên cạnh đó, một số dữ liệu kỹ thuật như địa chỉ IP, loại thiết bị, trình duyệt và cookies cũng được thu thập nhằm phục vụ việc vận hành và cải thiện trải nghiệm người dùng.</p>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">3. Mục đích sử dụng thông tin</h2>
                <p>Thông tin của bạn được sử dụng để xử lý và xác nhận đặt phòng, liên hệ hỗ trợ khi cần thiết, cũng như cải thiện chất lượng dịch vụ. Chúng tôi cũng sử dụng dữ liệu để đảm bảo an toàn hệ thống, phát hiện và ngăn chặn các hành vi gian lận hoặc truy cập trái phép.</p>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">4. Chia sẻ thông tin</h2>
                
                <p>Chúng tôi chỉ chia sẻ thông tin cá nhân trong các trường hợp cần thiết, bao gồm việc cung cấp thông tin cho đối tác thanh toán để xử lý giao dịch.</p>
                <p>Ngoài ra, thông tin có thể được cung cấp khi có yêu cầu từ cơ quan pháp luật. Chúng tôi cam kết không bán, trao đổi hoặc sử dụng thông tin cá nhân của người dùng cho mục đích thương mại.</p>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">5. Bảo mật thông tin</h2>
                <ul>
                    <li>Chúng tôi áp dụng nhiều biện pháp nhằm bảo vệ dữ liệu người dùng. Dữ liệu được mã hóa trong quá trình truyền tải thông qua giao thức bảo mật HTTPS. Mật khẩu người dùng được mã hóa và không lưu dưới dạng có thể đọc được.</li>
                    <li>Hệ thống được kiểm soát truy cập chặt chẽ, chỉ những người có thẩm quyền mới có thể tiếp cận dữ liệu. Ngoài ra, chúng tôi thường xuyên cập nhật hệ thống, giám sát hoạt động và thực hiện các biện pháp bảo mật để hạn chế tối đa rủi ro.</li>
                    <li>Tuy nhiên, do đặc thù của môi trường internet, chúng tôi không thể đảm bảo an toàn tuyệt đối trong mọi trường hợp.</li>
                </ul>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">6. Quyền của người dùng</h2>
                <p>Người dùng có quyền truy cập, chỉnh sửa hoặc cập nhật thông tin cá nhân của mình bất cứ lúc nào. Bạn cũng có thể yêu cầu xóa dữ liệu hoặc từ chối nhận các thông báo không cần thiết từ hệ thống.</p>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">7. Lưu trữ dữ liệu</h2>
                <p>Thông tin cá nhân sẽ được lưu trữ trong thời gian cần thiết để cung cấp dịch vụ hoặc theo yêu cầu của pháp luật. Khi không còn cần thiết, dữ liệu sẽ được xóa hoặc ẩn danh để đảm bảo an toàn.</p>
            </div>

            <div class="policy-section">
                <h2 class="policy-section-title">8. Liên hệ</h2>
                <p>Nếu bạn có bất kỳ thắc mắc nào liên quan đến chính sách bảo mật, vui lòng liên hệ:</p>
                <ul>
                    <li>📞 0967 798 825</li>
                    <li>📧 support@nestaway.vn</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
