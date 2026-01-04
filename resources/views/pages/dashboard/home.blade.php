@extends('layouts.user')

@section('title', 'Dashboard User')

@section('content')
    <div class="max-w-7xl mx-auto" x-data="iotDashboard()">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-6 px-4 mb-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-[#4F4F4F]">Beranda</h1>
                <p class="text-sm text-gray-500" x-data="{ currentDate: '' }" x-init="const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                const now = new Date();
                currentDate = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear();" x-text="currentDate"></p>
            </div>
        </div>

        <!-- Info Section -->
        <div class="bg-white rounded-2xl border border-[#C2C2C2] py-4 px-4 mb-6">
            <p class="text-sm text-gray-600 italic">
                Irigasi Otomatis berarti menyalakan pompa berdasarkan kelembaban tanah secara otomatis. Batas kelembaban
                tanah dapat diatur pada menu <a href="#" class="underline">pengaturan</a>. Ketika irigasi otomatis
                diaktifkan, maka irigasi manual akan
                nonaktif.
            </p>
        </div>

        {{-- IoT Irrigation Map Section --}}
        <div class="bg-white p-5 rounded-2xl border border-[#C2C2C2] overflow-hidden my-6">
            <h2 class="text-xl font-semibold text-[#4F4F4F] mb-4">Peta Area Irigasi IoT</h2>
            <x-user.iot-irrigation-map-editable />
        </div>

        <!-- Block Cards with Accordion (Dynamic from config) -->
        @foreach($blocks as $block)
            <x-user.block-accordion-card 
                :blockId="$block['blockId']" 
                :blockName="$block['blockName']" 
                :avgHumidity="$block['avgHumidity']"
                :avgFlowRate="$block['avgFlowRate']" 
                :totalVolume="$block['totalVolume']" 
                :sprayers="$block['sprayers']" 
            />
        @endforeach

    </div>

    <script>
        function iotDashboard() {
            return {
                init() {
                    console.log('IoT Dashboard initialized');
                }
            }
        }
    </script>
@endsection
