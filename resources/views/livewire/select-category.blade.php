<div class="mb-5">

    <label for="category_id" class="form-label">Select Category</label>
    <select name="category_id" id="category_id" wire:model="category_id" class="form-select">
        <option value="">Select a category</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    @error('category_id')
        <div class="alert alert-danger mt-2">{{ $message }}</div>
    @enderror
</div>
