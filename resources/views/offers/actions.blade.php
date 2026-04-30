<div class="flex justify-end gap-2">
    <a href="{{ route('offers.show', $offer) }}" class="w-9 h-9 rounded-lg border border-primary/20 text-primary inline-flex items-center justify-center hover:bg-primary/10" title="View">
        <span class="material-symbols-outlined text-[18px]">visibility</span>
    </a>
    <a href="{{ route('offers.edit', $offer) }}" class="w-9 h-9 rounded-lg border border-amber-200 text-amber-600 inline-flex items-center justify-center hover:bg-amber-50" title="Edit">
        <span class="material-symbols-outlined text-[18px]">edit</span>
    </a>
    <form action="{{ route('offers.destroy', $offer) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="w-9 h-9 rounded-lg border border-red-200 text-red-600 inline-flex items-center justify-center hover:bg-red-50" onclick="return confirm('Delete this offer?')">
            <span class="material-symbols-outlined text-[18px]">delete</span>
        </button>
    </form>
</div>
