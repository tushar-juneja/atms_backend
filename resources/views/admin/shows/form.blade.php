<form
    action="{{ $edit ? route('admin.shows.update', $show->id) : route('admin.shows.store') }}"
    method="POST">
    @csrf
    @if ($edit)
        @method('PUT')
    @endif

    <div class="mt-4 mb-3">
        <label for="name" class="form-label">Show Name</label>
        <input type="text" class="form-control" id="name" name="name"
            value="{{ old('name', $show->name ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label for="date_time" class="form-label">Date and Time</label>
        <input type="datetime-local" class="form-control" id="date_time" name="date_time"
            value="{{ old('date_time', $show->date_time ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label for="artist" class="form-label">Artist Performing</label>
        <input type="text" class="form-control" id="artist" name="artist"
            value="{{ old('artist', $show->artist ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label for="show_manager_id" class="form-label">Show Manager</label>
        <select class="form-control" id="show_manager_id" name="show_manager_id" required>
            <option value="">Select a Show Manager</option>
            @foreach ($showManagers as $manager)
                <option value="{{ $manager->id }}" 
                    {{ old('show_manager_id', $show->show_manager_id ?? '') == $manager->id ? 'selected' : '' }}>
                    {{ $manager->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="publish" name="publish"
        {{ old('publish', $show->publish ?? false) ? 'checked' : '' }} value="1"
        onclick="return confirm('Are you sure you want to ' + (this.checked ? 'publish' : 'unpublish') + ' this show?')">
    <label class="form-check-label" for="publish">Publish</label>
</div>


    <button type="submit" class="btn btn-success">
        {{ $edit ? 'Update Show' : 'Create Show' }}
    </button>
    <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
</form>
