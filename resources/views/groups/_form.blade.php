<div>
    <label for="name" class="block text-xs font-medium text-gray-500 uppercase mb-1">
        Nom du groupe
    </label>
    <input type="text" name="name" id="name"
           class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror"
           value="{{ old('name', $group->name ?? '') }}" required>
    @error('name')
        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="description" class="block text-xs font-medium text-gray-500 uppercase mb-1">
        Description
    </label>
    <textarea name="description" id="description" rows="3"
              class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-500 @enderror">{{ old('description', $group->description ?? '') }}</textarea>
    @error('description')
        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="leader_id" class="block text-xs font-medium text-gray-500 uppercase mb-1">
        Responsable
    </label>
    <select name="leader_id" id="leader_id"
            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('leader_id') border-red-500 @enderror">
        <option value="">— Aucun —</option>
        @foreach ($believers as $believer)
            <option value="{{ $believer->id }}"
                @selected(old('leader_id', $group->leader_id ?? null) == $believer->id)>
                {{ $believer->full_name ?? $believer->name }}
            </option>
        @endforeach
    </select>
    @error('leader_id')
        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>