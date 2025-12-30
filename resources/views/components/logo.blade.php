@if($logoPath ?? null)
    <img src="{{ asset('storage/'.$logoPath) }}" alt="Fortress Lenders" class="h-9 sm:h-10 w-auto object-contain">
@else
    <div class="w-9 h-9 sm:w-10 sm:h-10 bg-gradient-to-br from-teal-700 to-teal-800 rounded-lg flex items-center justify-center shadow-lg">
        <span class="text-amber-400 font-bold text-lg sm:text-xl">F</span>
    </div>
    <span class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 hidden sm:inline">Fortress Lenders</span>
    <span class="text-base font-bold text-gray-900 sm:hidden">Fortress</span>
@endif



