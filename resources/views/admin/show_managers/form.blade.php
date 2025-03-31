    <form
        action="{{ $edit ? route('admin.show_managers.update', ['id' => $showManager->id]) : route('admin.show_managers.store') }}"
        method="POST">
        @csrf
        @if ($edit)
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name"
                value="{{ old('name', $showManager->name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email"
                value="{{ old('email', $showManager->email ?? '') }}" required
                {{ isset($showManager) ? 'readonly' : '' }}>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password"
                {{ isset($showManager) ? '' : 'required' }}>
            @if (isset($showManager))
                <small class="text-muted">Leave blank if you don't want to change the password.</small>
            @endif
        </div>

        <button type="submit" class="btn btn-success">
            {{ $edit ? 'Update Show Manager' : 'Create Show Manager' }}
        </button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
    </form>
