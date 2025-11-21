<x-main-layout> 
    <div class="container mx-auto p-6" x-data="btsTable()">
    
        <!-- Filter Dropdown -->
        <div class="flex justify-left space-x-4 mb-8 mt-4">
            <select x-model="selectedKecamatan" class="border p-2 rounded-lg bg-gray-600 font-semibold"> 
                <option value="">Pilih Kecamatan</option>
                @foreach($kecamatan as $k)
                    <option value="{{ $k }}" class="bg-[#001F3F] font-semibold">{{ $k }}</option>
                @endforeach
            </select>
    
            <select x-model="selectedOperator" class="border p-2 rounded-lg bg-gray-600 font-semibold">
                <option value="">Pilih Operator</option>
                @foreach($operator as $o)
                    <option value="{{ $o }}" class="bg-[#001F3F] font-semibold">{{ $o }}</option>
                @endforeach
            </select>
    
            <select x-model="selectedTahun" class="border p-2 rounded-lg bg-gray-600 font-semibold">
                <option value="">Pilih Tahun</option>
                @foreach($tahun as $t)
                    <option value="{{ $t }}" class="bg-[#001F3F] font-semibold">{{ $t }}</option>
                @endforeach
            </select>

            @if (Route::has('login'))
                @auth
                    @if ((auth()->user()->role === 'superuser'))
                    <div class=" text-white font-semibold bg-gray-600 p-2 rounded-lg">
                        <a href="{{ url('/kelola-bts') }}">Kelola data BTS</a>
                    </div>
                    @endif
                    @if ((auth()->user()->role === 'admin'))
                    <div class=" text-white font-semibold bg-gray-600 p-2 rounded-lg">
                        <a href="{{ url('/kelola-bts') }}">Kelola data BTS</a>
                    </div>
                    @endif
                    @else
                        <div></div>
                @endauth
            @endif
        </div>
    
        <!-- Tabel Data BTS -->
        <table class="table-auto border-collapse border">
            <thead>
                <tr class= "text-black text-sm font-normal">
                    <th class="border px-4 py-2">Nama BTS</th>
                    <th class="border px-4 py-2">Operator</th>
                    <th class="border px-4 py-2">Jangkauan Sinyal (KM)</th>
                    <th class="border px-4 py-2">Tahun Registrasi</th>
                    <th class="border px-4 py-2">Kecamatan</th>
                </tr>
            </thead>
            <tbody class=" text-black">
                <template x-for="(row, index) in paginatedData" :key="index">
                    <tr>
                        <td class="border px-4 py-2" x-text="row.nama_bts"></td>
                        <td class="border px-4 py-2" x-text="row.operator"></td>
                        <td class="border px-4 py-2" x-text="row.jangkauan_sinyal"></td>
                        <td class="border px-4 py-2" x-text="row.Tahun_registrasi.substring(0,10)"></td>
                        <td class="border px-4 py-2" x-text="row.kecamatan"></td>
                    </tr>
                </template>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-4">
            <button @click="prevPage" :disabled="currentPage === 1" class="px-4 py-2 bg-gray-600 font-semibold rounded disabled:opacity-50 ">Prev</button>

            <span class="text-black">Halaman <span x-text="currentPage"></span> dari <span x-text="totalPages"></span></span>

            <button @click="nextPage" :disabled="currentPage === totalPages" class="px-4 py-2 bg-gray-600 font-semibold rounded disabled:opacity-50">Next</button>
        </div>
    </div>
    
    <script>
        function btsTable() {
        return {
            selectedKecamatan: '',
            selectedOperator: '',
            selectedTahun: '',
            data: @json($bts),
            currentPage: 1,
            perPage: 10,

            get filteredData() {
                return this.data.filter(row => {
                    if (this.selectedKecamatan && row.kecamatan !== this.selectedKecamatan) return false;
                    if (this.selectedOperator && row.operator !== this.selectedOperator) return false;
                    if (this.selectedTahun && row.Tahun_registrasi.substring(0, 4) !== this.selectedTahun) return false;
                    return true;
                });
            },

            get totalPages() {
                return Math.ceil(this.filteredData.length / this.perPage) || 1;
            },

            get paginatedData() {
                const start = (this.currentPage - 1) * this.perPage;
                return this.filteredData.slice(start, start + this.perPage);
            },

            nextPage() {
                if (this.currentPage < this.totalPages) {
                    this.currentPage++;
                }
            },

            prevPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                }
            },

            resetFilter() {
                this.selectedKecamatan = '';
                this.selectedOperator = '';
                this.selectedTahun = '';
                this.currentPage = 1;
            }
        }
        }
    </script>
    

</x-main-layout>