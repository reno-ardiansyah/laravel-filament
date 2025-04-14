<x-filament::page>
    <form method="POST" wire:submit="save">
      {{ $this->form }}
      <button type="submit" class="mt-4 bg-green-500 w-40 hover:bg-blue-400 text-white font-bold py-2 px-4">
        Save
      </button>
    </form>
</x-filament::page>
