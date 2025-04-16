<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="{
        state: $wire.$entangle('{{ $getStatePath() }}'),
        isOpen: false,
        currentDecade: Math.floor(new Date().getFullYear() / 10) * 10,
        isOpen: false,
        maxYear: new Date().getFullYear(),
        minDecade: 1900,
        get startYear() {
            return this.currentDecade;
        },
        get endYear() {
            return Math.min(this.currentDecade + 11, this.maxYear);
        },
        get years() {
            const years = [];
            for (let i = 0; i < 12; i++) {
                const year = this.startYear + i;
                if (year <= this.maxYear) {
                    years.push(year);
                }
            }
            return years;
        },
        selectYear(year) {
            if (year <= this.maxYear) {
                this.state = year;
                this.isOpen = false;
            }
        },
        changeDecade(delta) {
            let newDecade = this.currentDecade + delta;
            if (newDecade + 11 >= this.maxYear) {
                newDecade = Math.max(this.maxYear - 11, this.minDecade);
            } else if (newDecade < this.minDecade) {
                newDecade = this.minDecade
            }
            this.currentDecade = newDecade;
        }
    }" class="relative">
        <x-filament::input.wrapper>
            <x-filament::input type="text" x-model="state" readonly @click="isOpen = !isOpen" />
        </x-filament::input.wrapper>
        <x-filament::section x-show="isOpen" @click.outside="isOpen = false"
            x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            class="absolute top-full left-0 border border-gray-300 rounded shadow-lg z-10 mt-1 w-full">
            <div class="flex justify-between items-center mb-2">
                <x-filament::icon-button icon="heroicon-m-chevron-left" @click="changeDecade(-10)" color="gray" />
                <span x-text="startYear + ' - ' + (startYear + 11)"></span>
                <x-filament::icon-button icon="heroicon-m-chevron-right" @click="changeDecade(10)" color="gray" />
            </div>
            <div class="grid grid-cols-3 gap-2">
                <template x-for="year in years" :key="year">
                    <x-filament::button @click="selectYear(year)" :class="{ 'bg-gray-400/10 ': year === state }" class="" color="gray"
                        x-text="year" outlined></x-filament::button>
                </template>
            </div>
        </x-filament::section>
    </div>
</x-dynamic-component>