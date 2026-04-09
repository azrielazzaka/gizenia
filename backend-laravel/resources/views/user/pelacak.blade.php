@extends('layouts.user')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h2 class="text-3xl font-bold text-gray-900">Pelacak Nutrisi</h2>
        <p class="text-gray-500 text-sm mt-1">Catat dan evaluasi asupan harian Anda bersama AI.</p>
    </div>
    <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-6 rounded-xl flex items-center text-sm shadow-md transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> 
        Catat Makanan
    </button>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 lg:col-span-1">
        <h3 class="font-bold text-gray-900 mb-6">Ringkasan Makro Hari Ini</h3>
        
        <div class="relative w-40 h-40 mx-auto mb-6">
            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="40" fill="none" stroke="#F3F4F6" stroke-width="12"></circle>
                <circle cx="50" cy="50" r="40" fill="none" stroke="#059669" stroke-width="12" stroke-dasharray="251.2" stroke-dashoffset="100" stroke-linecap="round"></circle>
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="text-2xl font-black text-gray-900">1250</span>
                <span class="text-[10px] font-bold text-gray-400 uppercase">/ 2100 Kcal</span>
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <div class="flex justify-between text-xs font-bold mb-1"><span class="text-gray-600">Protein</span><span class="text-blue-600">45g / 120g</span></div>
                <div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-blue-500 h-2 rounded-full" style="width: 35%"></div></div>
            </div>
            <div>
                <div class="flex justify-between text-xs font-bold mb-1"><span class="text-gray-600">Karbohidrat</span><span class="text-orange-600">150g / 250g</span></div>
                <div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-orange-500 h-2 rounded-full" style="width: 60%"></div></div>
            </div>
            <div>
                <div class="flex justify-between text-xs font-bold mb-1"><span class="text-gray-600">Lemak</span><span class="text-purple-600">30g / 65g</span></div>
                <div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-purple-500 h-2 rounded-full" style="width: 45%"></div></div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-50 lg:col-span-2">
        <h3 class="font-bold text-gray-900 mb-6">Jurnal Makanan</h3>
        
        <div class="space-y-4 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-emerald-100 text-emerald-600 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-4 rounded-2xl border border-slate-100 shadow-sm bg-white">
                    <div class="flex justify-between items-start mb-1">
                        <h4 class="font-bold text-gray-900 text-sm">Sarapan</h4>
                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded">350 Kcal</span>
                    </div>
                    <p class="text-xs text-gray-500">Oatmeal dengan pisang dan almond (200g).</p>
                </div>
            </div>
            
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-blue-100 text-blue-600 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                </div>
                <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-4 rounded-2xl border border-slate-100 shadow-sm bg-white">
                    <div class="flex justify-between items-start mb-1">
                        <h4 class="font-bold text-gray-900 text-sm">Makan Siang</h4>
                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded">900 Kcal</span>
                    </div>
                    <p class="text-xs text-gray-500">Nasi Goreng Merah & Dada Ayam Bakar (350g).</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection