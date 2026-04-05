<div class="flex justify-end gap-2 pr-4">
    <a href="{{ route('products.edit', $product) }}" 
        class="w-10 h-10 text-primary hover:bg-primary/10 rounded-xl transition-all transform hover:-translate-y-0.5 flex items-center justify-center border border-primary/5 bg-primary/[0.02] shadow-sm"
        title="Edit">
        <span class="material-symbols-outlined text-[20px]">edit</span>
    </a>
    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
        @csrf @method('DELETE')
        <button type="submit" onclick="return confirm('ખાતરી છે?')"
                class="w-10 h-10 text-red-600 hover:bg-red-50 rounded-xl transition-all transform hover:-translate-y-0.5 flex items-center justify-center border border-red-100 bg-red-50/30 shadow-sm"
                title="Delete">
            <span class="material-symbols-outlined text-[20px]">delete</span>
        </button>
    </form>
</div>
