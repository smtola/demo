<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
   
        <main class="md:col-span-9">
            <x-filament-widgets::widgets
                :columns="$this->getColumn()"
                :widgets="$this->getWidgets()"
            />
        </main>
    </div>
</x-filament::page>