<!DOCTYPE html>
<html>
<head>
    <title>Dev Users - Neo Faraid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-dark">
    <div class="container py-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">👥 Development Users</h3>
                <p class="mb-0">Quick password reset links for testing</p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'info' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td>
                                    @if($user->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('dev.reset', $user->email) }}" 
                                       class="btn btn-warning btn-sm" title="Reset Password">
                                        <i class="fas fa-key"></i> Reset
                                    </a>
                                    <a href="{{ route('login') }}" 
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-sign-in-alt"></i> Login
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 text-center">
                    <a href="{{ route('home') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-home me-2"></i> Homepage
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-success me-2">
                        <i class="fas fa-user-plus me-2"></i> Register New User
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>