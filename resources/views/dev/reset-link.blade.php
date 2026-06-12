<!DOCTYPE html>
<html>
<head>
    <title>Dev Password Reset - Neo Faraid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; padding: 20px; }
        .card { max-width: 800px; margin: 0 auto; }
        .token-box { background: #212529; color: #fff; padding: 15px; border-radius: 5px; font-family: monospace; }
        .btn-reset { font-size: 1.2rem; padding: 15px 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">🚀 Development Password Reset</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    <h5><i class="fas fa-user me-2"></i> User: {{ $user->name }}</h5>
                    <p class="mb-0"><i class="fas fa-envelope me-2"></i> Email: {{ $user->email }}</p>
                </div>
                
                <div class="text-center my-4">
                    <a href="{{ $resetUrl }}" class="btn btn-success btn-reset">
                        <i class="fas fa-key me-2"></i> Click to Reset Password
                    </a>
                </div>
                
                <div class="mt-4">
                    <h5>Reset URL:</h5>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" value="{{ $resetUrl }}" id="resetUrl" readonly>
                        <button class="btn btn-outline-secondary" onclick="copyToClipboard('resetUrl')">
                            <i class="fas fa-copy me-2"></i>Copy
                        </button>
                    </div>
                    
                    <h5>Token Details:</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Email:</label>
                            <input type="text" class="form-control" value="{{ $email }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Token:</label>
                            <div class="token-box">{{ $token }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 text-center">
                    <a href="{{ route('dev.users.html') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-users me-2"></i> All Users
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-sign-in-alt me-2"></i> Login
                    </a>
                    <a href="{{ route('password.request') }}" class="btn btn-outline-info">
                        <i class="fas fa-key me-2"></i> Normal Reset
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    function copyToClipboard(elementId) {
        const element = document.getElementById(elementId);
        element.select();
        element.setSelectionRange(0, 99999);
        document.execCommand('copy');
        
        // Show feedback
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check me-2"></i>Copied!';
        button.classList.remove('btn-outline-secondary');
        button.classList.add('btn-success');
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-secondary');
        }, 2000);
    }
    </script>
</body>
</html>